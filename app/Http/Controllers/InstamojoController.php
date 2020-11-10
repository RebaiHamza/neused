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
use Redirect;
use Session;
use URL;

/*==========================================
=            Author: Media City            =
Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class InstamojoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function payment(Request $request)
    {

        require_once 'price.php';

        $adrid = Session::get('address');

        $address = Address::findOrFail($adrid);

        $c = strlen($address->phone);

        if ($c < 10) {

            $sentfromlastpage = 0;
            notify()->error("Invalid Phone no. ");
            return view('front.checkout', compact('conversion_rate', 'sentfromlastpage'));
        }

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

       
       
        $total = sprintf("%.2f",$total * $conversion_rate);

        if (round($request->actualtotal, 2) != $total) {

            $sentfromlastpage = 0;
            Session::put('from-pay-page', 'yes');
            Session::put('page-reloaded', 'yes');
            return redirect()->action('CheckoutController@add');

        }

        $inv_cus = Invoice::first();

        $order_id = Session::get('order_id');

        $amount = round(Crypt::decrypt($request->amount), 2);

        $api = new \Instamojo\Instamojo(config('services.instamojo.api_key'), config('services.instamojo.auth_token'), config('services.instamojo.url'));

        try
        {

            $url = url('/paidsuccess');
            $response = $api->paymentRequestCreate(array(
                "purpose" => "Payment For Order $inv_cus->order_prefix $order_id",
                "amount" => $amount,
                "buyer_name" => Auth::user()->name,
                "send_email" => true,
                "send_sms" => true,
                "email" => $address->email,
                "phone" => $address->phone,
                "redirect_url" => $url,
            ));

            header('Location: ' . $response['longurl']);

            exit();
        } catch (\Exception $e) {
            print('Error: ' . $e->getMessage());
            $failedTranscations = new FailedTranscations;
            $failedTranscations->order_id = $order_id;
            $failedTranscations->txn_id = 'INSTAMOJO_FAILED_' . str_rand(5);
            $failedTranscations->user_id = Auth::user()->id;
            $failedTranscations->save();
        }

    }

    public function success(Request $request)
    {

        $hc = decrypt(Session::get('handlingcharge'));

        require_once 'price.php';

        try
        {

            $api = new \Instamojo\Instamojo(config('services.instamojo.api_key'), config('services.instamojo.auth_token'), config('services.instamojo.url'));

            $response = $api->paymentRequestStatus(request('payment_request_id'));

            if (!isset($response['payments'][0]['status'])) {
                dd('payment failed');
            } else if ($response['payments'][0]['status'] != 'Credit') {
                dd('payment failed');
            } else {
                $user = Auth::user();
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
                $txn_id = $response['payments'][0]['payment_id'];

                if (Session::has('coupanapplied')) {

                    $discount = Session::get('coupanapplied')['discount'];

                } else {

                    $discount = 0;
                }

                foreach ($cart_table as $key => $cart) {

                    array_push($venderarray, $cart->vender_id);

                    $qty_total = $qty_total + $cart->qty;

                    array_push($pro_id, $cart->variant_id);

                    array_push($mainproid, $cart->pro_id);

                    $total_tax = $total_tax + $cart->tax_amount;

                    $total_shipping = $total_shipping + $cart->shipping;

                }

                $total_shipping = sprintf("%.2f", $total_shipping * $conversion_rate);

                $venderarray = array_unique($venderarray);
                $hc = round($hc, 2);

                $neworder = new Order();
                $neworder->order_id = Session::get('order_id');
                $neworder->qty_total = $qty_total;
                $neworder->user_id = Auth::user()->id;
                $neworder->delivery_address = Session::get('address');
                $neworder->billing_address = Session::get('billing');
                $neworder->order_total = Session::get('payamount') - $hc;
                $neworder->tax_amount = round($total_tax, 2);
                $neworder->shipping = round($total_shipping, 2);
                $neworder->status = '1';
                $neworder->coupon = Session::get('coupanapplied')['code'];
                $neworder->paid_in = session()->get('currency')['value'];
                $neworder->vender_ids = $venderarray;
                $neworder->transaction_id = $txn_id;
                $neworder->payment_receive = 'yes';
                $neworder->payment_method = 'Instamojo';
                $neworder->paid_in_currency = Session::get('currency')['id'];
                $neworder->pro_id = $pro_id;
                $neworder->discount = round($discount * $conversion_rate, 2);
                $neworder->distype = Session::get('coupanapplied')['appliedOn'];
                $neworder->main_pro_id = $mainproid;
                $neworder->handlingcharge = $hc;
                $neworder->created_at = date('Y-m-d H:i:s');

                $neworder->save();

                #Getting Invoice Prefix
                $invStart = Invoice::first()->inv_start;
                #end
                #Count order
                $ifanyorder = count(Order::all());
                $cart_table2 = Cart::where('user_id', Auth::user()->id)
                    ->orderBy('vender_id', 'ASC')
                    ->get();

                #Creating Invoice
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

                    /*Handling charge per item count*/
                    $hcsetting = Genral::first();

                    if ($hcsetting->chargeterm == 'pi') {

                        $perhc = $hc / count($cart_table2);

                    } else {

                        $perhc = $hc / count($cart_table2);

                    }
                    /*END*/

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
                    $newInvoice->inv_no = $invno;
                    $newInvoice->qty = $invcart->qty;
                    $newInvoice->status = 'pending';
                    $newInvoice->local_pick = $invcart->ship_type;
                    $newInvoice->variant_id = $invcart->variant_id;
                    $newInvoice->vender_id = $invcart->vender_id;
                    $newInvoice->price = $price;
                    $newInvoice->tax_amount = $invcart->tax_amount;
                    $newInvoice->igst = session()->has('igst') ? session()->get('igst') : null;
                    $newInvoice->sgst = session()->has('indiantax') ? session()->get('indiantax')['sgst'] : null;
                    $newInvoice->cgst = session()->has('indiantax') ? session()->get('indiantax')['cgst'] : null;
                    $newInvoice->shipping = round($invcart->shipping * $conversion_rate, 2);
                    $newInvoice->discount = round($invcart->disamount * $conversion_rate, 2);
                    $newInvoice->handlingcharge = $perhc;

                    if ($invcart->product->vender->role_id == 'v') {
                        $newInvoice->paid_to_seller = 'NO';
                    }

                    $newInvoice->save();

                }
                #end
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
                $order_id = Session::get('order_id');
                $orderiddb = $inv_cus->order_prefix . $order_id;

                $user->notify(new UserOrderNotification($order_id, $orderiddb));
                $get_admins = User::where('role_id', '=', 'a')->get();

                /*Sending notification to all admin*/
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
                    
                    $paidcurrency = Session::get('currency')['id'];
                    $e = Address::findOrFail($neworder->delivery_address)->email;
                    Mail::to($e)->send(new OrderMail($neworder, $inv_cus, $paidcurrency));
                    Session::forget('cart');
                    Session::forget('coupan');
                    Session::forget('billing');
                    Session::forget('lastid');
                    Session::forget('address');
                    Session::forget('payout');
                    Session::forget('handlingcharge');
                    Session::forget('coupanapplied');

                    Cart::where('user_id', Auth::user()->id)->delete();

                    $status = "Order #$inv_cus->order_prefix $neworder->order_id placed successfully !";
                    notify()->success("$status");
                    return redirect()->route('order.done');

                } catch (\Swift_TransportException $e) {

                    Session::forget('cart');
                    Session::forget('coupan');
                    Session::forget('billing');
                    Session::forget('lastid');
                    Session::forget('address');
                    Session::forget('payout');
                    Session::forget('handlingcharge');
                    Session::forget('coupanapplied');

                    Cart::where('user_id', Auth::user()->id)->delete();

                    $status = "Order #$inv_cus->order_prefix $neworder->order_id placed successfully !";
                    notify()->success("$status");
                    return redirect()->route('order.done');
                }
                /*End*/

            }
        } catch (\Exception $e) {

            $sentfromlastpage = 0;
            notify()->error("Payment Failed ! Try Again");
            $failedTranscations = new FailedTranscations;
            $failedTranscations->txn_id = 'INSTAMOJO_FAILED_' . str_random(5);
            $failedTranscations->user_id = Auth::user()->id;
            $failedTranscations->save();
            return view('front.checkout', compact('conversion_rate', 'sentfromlastpage'));
        }

    }

    #endoflast

}
