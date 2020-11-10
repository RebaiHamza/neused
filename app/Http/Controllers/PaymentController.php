<?php
namespace App\Http\Controllers;

use App\Address;
use App\AddSubVariant;
use App\Cart;
use App\Coupan;
use App\FailedTranscations;
use App\Genral;
use App\Invoice;
use App\InvoiceDownload;
use App\Mail\OrderMail;
use App\Notifications\OrderNotification;
use App\Notifications\SellerNotification;
use App\Notifications\UserOrderNotification;
use App\Order;
use App\User;
use Auth;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Mail;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;

/*==========================================
=            Author: Media City            =
Author URI: https://mediacity.co.in
=            Developer : @nkit             =
=            Copyright (c) 2020            =
==========================================*/

class PaymentController extends Controller
{
    private $_api_context;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this
            ->_api_context
            ->setConfig($paypal_conf['settings']);
    }

    public function payWithpaypal(Request $request)
    {
        $payout = Crypt::decrypt($request->amount);
        $payout = round($payout, 2);

        require_once 'price.php';

        $cart_table = Auth::user()->cart;
        $total = 0;

        foreach ($cart_table as $key => $val) {

            if ($val->product->tax_r != null && $val->product->tax == 0) {

                if ($val->ori_offer_price != 0) {
                    //get per product tax amount
                    $p = 100;
                    $taxrate_db = $val->product->tax_r;
                    $vp = $p + $taxrate_db;
                    $taxAmnt = $val->product->vender_offer_price / $vp * $taxrate_db;
                    $taxAmnt = sprintf("%.2f", $taxAmnt);
                    $price = ($val->ori_offer_price - $taxAmnt) * $val->qty;

                } else {

                    $p = 100;
                    $taxrate_db = $val->product->tax_r;
                    $vp = $p + $taxrate_db;
                    $taxAmnt = $val->product->vender_price / $vp * $taxrate_db;

                    $taxAmnt = sprintf("%.2f", $taxAmnt);

                    $price = ($val->ori_price - $taxAmnt) * $val->qty;
                }

            } else {

                if ($val->semi_total != 0) {

                    $price = $val->semi_total;

                } else {

                    $price = $val->price_total;

                }
            }

            $total = $total + $price;

        }

        $total = sprintf("%.2f",$total*$conversion_rate);

        if (round($request->actualtotal, 2) != $total) {

            require_once 'price.php';
            $sentfromlastpage = 0;
            Session::put('from-pay-page', 'yes');
            Session::put('page-reloaded', 'yes');
            return redirect()->action('CheckoutController@add');

        }

        $setcurrency = Session::get('currency')['id'];

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Item 1')
        /** item name **/
            ->setCurrency($setcurrency)->setQuantity(1)
            ->setPrice($payout);
        /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array(
            $item_1,
        ));
        $amount = new Amount();
        $amount->setCurrency($setcurrency)->setTotal($payout);
        $transaction = new Transaction();
        $transaction->setAmount($amount)->setItemList($item_list)->setDescription('Payment for order');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('status'))
            ->setCancelUrl(URL::to('/checkout'));
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions(array(
            $transaction,
        ));

        try
        {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {

                notify()->error('Connection timeout !');
                $failedTranscations = new FailedTranscations;
                $failedTranscations->order_id = $order_id;
                $failedTranscations->txn_id = 'PAYPAL_FAILED_' . str_rand(5);
                $failedTranscations->user_id = Auth::user()->id;
                $failedTranscations->save();

                $sentfromlastpage = 0;
                return view('front.checkout', compact('sentfromlastpage', 'conversion_rate'));
            } else {

                notify()->error('Some error occur, Sorry for inconvenient');
                $failedTranscations = new FailedTranscations;
                $failedTranscations->order_id = $order_id;
                $failedTranscations->txn_id = 'PAYPAL_FAILED_' . str_rand(5);
                $failedTranscations->user_id = Auth::user()->id;
                $failedTranscations->save();
                $sentfromlastpage = 0;
                return view('front.checkout', compact('sentfromlastpage', 'conversion_rate'));
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        notify()->error('Unknown error occurred !');
        $sentfromlastpage = 0;
        return view('front.checkout', compact('sentfromlastpage', 'conversion_rate'));
    }

    public function getPaymentStatus(Request $request)
    {

        require_once 'price.php';

        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $hc = decrypt(Session::get('handlingcharge'));
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');

        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
            Session::flash('failure', 'Payment failed !');

            $failedTranscations = new FailedTranscations;
            $failedTranscations->order_id = $order_id;
            $failedTranscations->txn_id = 'PAYPAL_FAILED_' . str_rand(5);
            $failedTranscations->user_id = Auth::user()->id;
            $failedTranscations->save();
            $sentfromlastpage = 0;

            return view('front.checkout', compact('sentfromlastpage', 'conversion_rate'));
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        /**Execute the payment **/
        $response = $payment->execute($execution, $this->_api_context);

        $order_id = uniqid();
        $user = Auth::user();

        if ($response->getState() == 'approved') {
            $transactions = $payment->getTransactions();
            $relatedResources = $transactions[0]->getRelatedResources();
            $sale = $relatedResources[0]->getSale();
            $saleId = $sale->getId();

            $cart = Session::get('item');
            $billing = Session::get('billing');
            $user_id = $user->id;
            $invno = 0;
            $venderarray = array();
            $qty_total = 0;
            $pro_id = array();
            $mainproid = array();
            $total_tax = 0;
            $total_shipping = 0;
            $cart_table = Cart::where('user_id', $user->id)
                ->get();

            $lastid = Session::get('lastid');

            $payble = 0;

            foreach ($cart_table as $key => $cart) {

                array_push($venderarray, $cart->vender_id);

                $qty_total = $qty_total + $cart->qty;

                array_push($pro_id, $cart->variant_id);

                array_push($mainproid, $cart->pro_id);

                $total_tax = $total_tax + $cart->tax_amount;

                $total_shipping = $total_shipping + $cart->shipping;

            }

            if (Session::has('coupanapplied')) {

                $discount = Session::get('coupanapplied')['discount'];

            } else {

                $discount = 0;
            }

            $venderarray = array_unique($venderarray);

            $neworder = new Order();
            $neworder->order_id = $order_id;
            $neworder->qty_total = $qty_total;
            $neworder->user_id = Auth::user()->id;
            $neworder->delivery_address = Session::get('address');
            $neworder->billing_address = Session::get('billing');
            $neworder->order_total = round($sale->amount->total - $hc, 2);
            $neworder->tax_amount = round($total_tax, 2);
            $neworder->shipping = round($total_shipping * $conversion_rate, 2);
            $neworder->status = '1';
            $neworder->paid_in = session()->get('currency')['value'];
            $neworder->vender_ids = $venderarray;
            $neworder->transaction_id = $payment_id;
            $neworder->payment_receive = 'yes';
            $neworder->payment_method = 'PayPal';
            $neworder->pro_id = $pro_id;
            $neworder->paid_in_currency = Session::get('currency')['id'];
            $neworder->main_pro_id = $mainproid;
            $neworder->sale_id = $saleId;
            $neworder->handlingcharge = $hc;
            $neworder->created_at = date('Y-m-d H:i:s');
            $neworder->coupon = Session::get('coupanapplied')['code'];
            $neworder->discount = round($discount * $conversion_rate, 2);
            $neworder->distype = Session::get('coupanapplied')['appliedOn'];
            $neworder->save();

            #Getting Invoice Prefix
            $invStart = Invoice::first()->inv_start;
            #end
            #Count order
            $ifanyorder = count(Order::all());
            $cart_table2 = Cart::where('user_id', Auth::user()->id)
                ->orderBy('vender_id', 'ASC')
                ->get();

            /*Handling charge per item count*/
            $hcsetting = Genral::first();

            if ($hcsetting->chargeterm == 'pi') {

                $perhc = $hc / count($cart_table2);

            } else {

                $perhc = $hc / count($cart_table2);

            }
            /*END*/

            #Making Invoice
            foreach ($cart_table2 as $key => $invcart) {

                $lastinvoices = InvoiceDownload::where('order_id', $neworder->id)
                    ->get();
                $lastinvoicerow = InvoiceDownload::orderBy('id', 'desc')->first();

                if (count($lastinvoices) > 0) {

                    foreach ($lastinvoices as $last) {
                        if ($invcart->vender_id == $last->vender_id) {
                            $invno = $last->inv_no;

                        } else {
                            $invno = $last->inv_no;
                            $invno = $invno + 1;
                        }
                    }

                } else {
                    if ($lastinvoicerow) {
                        $invno = $lastinvoicerow->inv_no + 1;
                    } else {
                        $invno = $invStart;
                    }

                }

                $findvariant = AddSubVariant::find($invcart->variant_id);
                $price = 0;

                if ($invcart->semi_total != 0) {

                    if ($findvariant->products->tax_r != '') {

                        $p = 100;
                        $taxrate_db = $findvariant->products->tax_r;
                        $vp = $p + $taxrate_db;
                        $tam = $findvariant->products->offer_price / $vp * $taxrate_db;
                        $tam = sprintf("%.2f", $tam);

                        $price = sprintf("%.2f", ($invcart->ori_offer_price - $tam) * $conversion_rate);
                    } else {
                        $price = $invcart->ori_offer_price * $conversion_rate;
                        $price = sprintf("%.2f", $price);
                    }

                } else {

                    if ($findvariant->products->tax_r != '') {

                        $p = 100;

                        $taxrate_db = $findvariant->products->tax_r;

                        $vp = $p + $taxrate_db;

                        $tam = $findvariant->products->vender_price / $vp * $taxrate_db;

                        $tam = sprintf("%.2f", $tam);

                        $price = sprintf("%.2f", ($invcart->ori_price - $tam) * $conversion_rate);

                    } else {
                        $price = $invcart->ori_price * $conversion_rate;
                        $price = sprintf("%.2f", $price);

                    }
                }

                $newInvoice = new InvoiceDownload();
                $newInvoice->order_id = $neworder->id;
                $newInvoice->local_pick = $invcart->ship_type;
                $newInvoice->inv_no = $invno;
                $newInvoice->qty = $invcart->qty;
                $newInvoice->status = 'pending';
                $newInvoice->variant_id = $invcart->variant_id;
                $newInvoice->vender_id = $invcart->vender_id;
                $newInvoice->price = $price;
                $newInvoice->tax_amount = round($invcart->tax_amount, 2);
                $newInvoice->igst = session()->has('igst') ? session()->get('igst') : null;
                $newInvoice->sgst = session()->has('indiantax') ? session()->get('indiantax')['sgst'] : null;
                $newInvoice->cgst = session()->has('indiantax') ? session()->get('indiantax')['cgst'] : null;
                $newInvoice->shipping = round($invcart->shipping * $conversion_rate, 2);
                $newInvoice->discount = round($invcart->disamount * $conversion_rate, 2);
                $newInvoice->handlingcharge = round($perhc, 2);
                if ($invcart->product->vender->role_id == 'v') {
                    $newInvoice->paid_to_seller = 'NO';
                }
                $newInvoice->save();

            }

            #End
            // Coupon applied //
            $c = Coupan::find(Session::get('coupanapplied')['cpnid']);

            if (isset($c)) {
                $c->maxusage = $c->maxusage - 1;
                $c->save();
            }

            //end //

            foreach ($cart_table as $carts) {

                $id = $carts->variant_id;
                $variant = AddSubVariant::findorfail($id);

                if (isset($variant)) {

                    $used = $variant->stock - $carts->qty;
                    DB::table('add_sub_variants')
                        ->where('id', $id)->update(['stock' => $used]);

                }

            }
            $inv_cus = Invoice::first();
            $orderiddb = $inv_cus->order_prefix . $order_id;
            $paidcurrency = Session::get('currency')['id'];
            $user->notify(new UserOrderNotification($order_id, $orderiddb));
            $get_admins = User::where('role_id', '=', 'a')->get();

            /*Sending notifcation to all admin*/
            \Notification::send($get_admins, new OrderNotification($order_id, $orderiddb));

            /*Send notifcation to vender*/
            $vender_system = Genral::first()->vendor_enable;

            /*if vender system enable and user role is not admin*/
            if ($vender_system == 1) {

                $msg = "New Order $orderiddb Received !";
                $url = route('seller.view.order', $order_id);

                foreach ($venderarray as $key => $vender) {
                    $v = User::find($vender);
                    if ($v->role_id == 'v') {
                        $v->notify(new SellerNotification($url, $msg));
                    }
                }

            }
            /*end*/
            Session::forget('page-reloaded');
            /*Send Mail to User*/
            try {

                $e = Address::findOrFail($neworder->delivery_address)->email;
                Mail::to($e)->send(new OrderMail($neworder, $inv_cus, $paidcurrency));
                Session::forget('cart');
                Session::forget('coupan');
                Session::forget('billing');
                Session::forget('coupanapplied');
                Session::forget('lastid');
                Session::forget('address');
                Session::forget('payout');

                Cart::where('user_id', $user->id)->delete();

                $newmsg = "Order #$inv_cus->order_prefix $neworder->order_id placed successfully !";
                notify()->success("$newmsg");
                return redirect()->route('order.done');

            } catch (\Swift_TransportException $e) {

                Session::forget('cart');
                Session::forget('coupan');
                Session::forget('billing');
                Session::forget('coupanapplied');
                Session::forget('lastid');
                Session::forget('address');
                Session::forget('payout');

                Cart::where('user_id', $user->id)
                    ->delete();

                $newmsg = "Order #$inv_cus->order_prefix $neworder->order_id placed successfully !";
                notify()->success("$newmsg");
                return redirect()->route('order.done');

            }
            /*End*/

        } else {
            notify()->error("Payment Failed !");
            $sentfromlastpage = 0;
            $failedTranscations = new FailedTranscations;
            $failedTranscations->order_id = $order_id;
            $failedTranscations->txn_id = 'PAYPAL_FAILED_' . str_random(5);
            $failedTranscations->user_id = Auth::user()->id;
            $failedTranscations->save();
            return view('front.checkout', compact('sentfromlastpage', 'conversion_rate'));
        }

    }

}
