<?php
namespace App\Http\Controllers;

use App\Address;
use App\AddSubVariant;
use App\Allcity;
use App\Allstate;
use App\AutoDetectGeo;
use App\BillingAddress;
use App\Cart;
use App\City;
use App\Commission;
use App\CommissionSetting;
use App\Config;
use App\Country;
use App\CurrencyCheckout;
use App\FailedTranscations;
use App\Genral;
use App\Invoice;
use App\Order;
use App\Product;
use App\Shipping;
use App\ShippingWeight;
use App\State;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Image;
use Session;

/*==========================================
=            Author: Media City            =
Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class CheckoutController extends Controller
{

    public function getFailedTranscation()
    {
        require_once 'price.php';
        $user = Auth::user();
        $failedtranscations = FailedTranscations::orderby('id', 'DESC')->where('user_id', $user->id)->paginate(10);
        return view('user.failedtranscation', compact('conversion_rate', 'failedtranscations'));
    }

    public function chooseaddress(Request $request)
    {

        require_once 'price.php';

        $pincodesystem = Config::first()->pincode_system;

        $getaddress = $request->seladd;

        if (!$getaddress) {
            $getaddress = Session::get('address');
        }

        #if pincode validation enable !
        if ($pincodesystem == 1) {
            #PinCode validation
            $getpincode = Address::find($getaddress)->pin_code;

            if (strlen($getpincode) > 5) {

                $avbl_pincode = Allcity::where('pincode', $getpincode)->first();

                if (empty($avbl_pincode->pincode)) {
                    notify()->error('Delivery Not available on selected address Pincode !');
                    return redirect('/checkout');
                }

            } else {
                notify()->error('Delivery Not available on selected address Pincode !');
                return redirect('/checkout');
            }
        }

        Session::put('address', $getaddress);

        $total = 0;

        $user = Auth::user();

        if (Auth::check()) {

            $cart_table = Cart::where('user_id', $user->id)
                ->get();

            foreach ($cart_table as $carts) {
                $min = $carts->qty;
                $id = $carts->variant_id;
                $pros = AddSubVariant::where('id', $id)->first();
                $max = 0;

                if ($pros->max_order_qty == null) {
                    $max = $pros->stock;
                } else {
                    $max = $pros->max_order_qty;
                }

                if ($max >= $min) {

                } else {

                    notify()->error('Max Limit Reached !');
                    return back();
                }

            }
        }

        if (Auth::check()) {

            $user_id = Auth::user()->id;
            $user = User::find($user_id);

            $descript = $request->shipping;

            foreach ($cart_table as $key => $val) {
                if ($val->semi_total == 0) {
                    $price = $val->price_total;
                } else {
                    $price = $val->semi_total;
                }

                $total = $total + $price;
            }

            $shippingcharge = $descript;

            $grandtotal = $total + $shippingcharge;

            if (!empty(Session::get('from-order-step-3')) && Session::get('from-order-step-3') == 'yes') {

                notify()->success('Item removed from your cart !');
                return view('front.step03', compact('conversion_rate', 'grandtotal', 'user', 'total', 'shippingcharge'));
            }

            if (!empty(Session::get('page-reloaded')) && Session::get('page-reloaded') == 'yes') {
                return view('front.step03', compact('conversion_rate', 'grandtotal', 'user', 'total', 'shippingcharge'));
            }

            if (!empty(Session::get('re-verify')) && Session::get('re-verify') == 'yes') {
                return view('front.step03', compact('conversion_rate', 'grandtotal', 'user', 'total', 'shippingcharge'));
            }

            if ($request->has('step2')) {
                return view('front.step03', compact('conversion_rate', 'grandtotal', 'user', 'total', 'shippingcharge'));

            } elseif (!empty(Session::get('billing'))) {

                $sentfromlastpage = 0;
                notify()->success('Address Updated Successfully !');
                return view('front.checkout', compact('conversion_rate', 'sentfromlastpage'));

            } else {

                $sentfromlastpage = 0;
                notify()->success('Address Updated Successfully !');
                return view('front.checkout', compact('conversion_rate', 'sentfromlastpage'));

            }
        }
    }

    public function index(Request $request)
    {

        require_once 'price.php';

        $total = 0;

        $checkoutsetting_check = AutoDetectGeo::first();

        if ($checkoutsetting_check->enable_cart_page == 1) {
            $listcheckOutCurrency = CurrencyCheckout::get();
            $currentCurrency = Session::get('currency');

            foreach ($listcheckOutCurrency as $key => $all) {
                if ($currentCurrency['id'] == $all->currency) {
                    if ($all->checkout_currency == 1) {
                        Session::forget('validcurrency');
                    } else {
                        Session::put('validcurrency', 1);
                        return redirect('/cart');
                    }
                }
            }
        }

        $user = Auth::user();
        if (Auth::check()) {
            $cart_table = Cart::where('user_id', $user->id)
                ->get();

            foreach ($cart_table as $carts) {
                $min = $carts->qty;
                $id = $carts->variant_id;
                $pros = AddSubVariant::where('id', $id)->first();
                $max = 0;
                if ($pros->max_order_qty == null) {
                    $max = $pros->stock;
                } else {
                    $max = $pros->max_order_qty;
                }

                if ($max >= $min) {

                } else {
                    notify()->error('Sorry the product is out of stock !');
                    return back();
                }

            }
        }

        if (Auth::check()) {

            $user_id = Auth::user()->id;
            $user = User::find($user_id);

            $shipping = BillingAddress::where('user_id', $user->id)->first();

            if ($request->shipping != "") {
                $descript = $request->shipping;
            } else {
                $x = Session::get('shippingcharge');
                $descript = $x;
            }

            foreach ($cart_table as $key => $citem) {

                $convert_price = 0;
                $show_price = 0;

                $pro = Product::withTrashed()->find($citem->pro_id);

                if ($pro->trashed()) {
                    Cart::where('pro_id', $pro->id)->where('user_id', Auth::user()->id)->delete();
                }

                $orivar = AddSubVariant::withTrashed()->findorfail($citem->variant_id);

                if ($orivar->trashed()) {
                    Cart::where('variant_id', $citem->variant_id)->where('user_id', Auth::user()->id)->delete();
                }

                $oriofferprice = $pro->offer_price + $orivar->price;
                $oriprice = $pro->price_total + $orivar->price;

                $commision_setting = CommissionSetting::first();

                if ($commision_setting->type == "flat") {

                    $commission_amount = $commision_setting->rate;
                    if ($commision_setting->p_type == 'f') {

                        $totalprice = $pro->vender_price + $orivar->price + $commission_amount;
                        $totalsaleprice = $pro->vender_offer_price + $orivar->price + $commission_amount;

                        if ($pro->vender_offer_price == 0) {
                            $show_price = $totalprice;
                        } else {
                            $totalsaleprice;

                            $convert_price = $totalsaleprice == '' ? $totalprice : $totalsaleprice;
                            $show_price = $totalprice;
                        }

                    } else {

                        $totalprice = ($pro->vender_price + $orivar->price) * $commission_amount;

                        $totalsaleprice = ($pro->vender_offer_price + $orivar->price) * $commission_amount;

                        $buyerprice = ($pro->vender_price + $orivar->price) + ($totalprice / 100);

                        $buyersaleprice = ($pro->vender_offer_price + $orivar->price) + ($totalsaleprice / 100);

                        if ($pro->vender_offer_price == 0) {
                            $show_price = round($buyerprice, 2);
                        } else {
                            round($buyersaleprice, 2);
                            $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                            $show_price = $buyerprice;
                        }

                    }
                } else {

                    $comm = Commission::where('category_id', $pro->category_id)->first();
                    if (isset($comm)) {
                        if ($comm->type == 'f') {

                            $price = $pro->vender_price + $comm->rate + $orivar->price;

                            if ($pro->vender_offer_price != null) {
                                $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                            } else {
                                $offer = $pro->vender_offer_price;
                            }

                            if ($pro->vender_offer_price == 0 || $pro->vender_offer_price == null) {
                                $show_price = $price;
                            } else {

                                $convert_price = $offer;
                                $show_price = $price;
                            }

                        } else {

                            $commission_amount = $comm->rate;

                            $totalprice = ($pro->vender_price + $orivar->price) * $commission_amount;

                            $totalsaleprice = ($pro->vender_offer_price + $orivar->price) * $commission_amount;

                            $buyerprice = ($pro->vender_price + $orivar->price) + ($totalprice / 100);

                            $buyersaleprice = ($pro->vender_offer_price + $orivar->price) + ($totalsaleprice / 100);

                            if ($pro->vender_offer_price == 0) {
                                $show_price = round($buyerprice, 2);
                            } else {
                                round($buyersaleprice, 2);

                                $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                $show_price = round($buyerprice, 2);
                            }

                        }
                    } else {
                        $commission_amount = 0;

                        $totalprice = ($pro->vender_price + $orivar->price) * $commission_amount;

                        $totalsaleprice = ($pro->vender_offer_price + $orivar->price) * $commission_amount;

                        $buyerprice = ($pro->vender_price + $orivar->price) + ($totalprice / 100);

                        $buyersaleprice = ($pro->vender_offer_price + $orivar->price) + ($totalsaleprice / 100);

                        if ($pro->vender_offer_price == null) {
                            $show_price = round($buyerprice, 2);
                        } else {
                            $convert_price = round($buyersaleprice, 2);

                            $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                            $show_price = round($buyerprice, 2);
                        }
                    }
                }

                if ($pro->vender_offer_price != null || $pro->vender_offer_price != '' || $pro->vender_offer_price != 0) {

                    if ($convert_price != $citem->ori_offer_price || $show_price != $citem->ori_price) {

                        Cart::where('pro_id', '=', $pro->id)->where('id', '=', $citem->id)->update(['semi_total' => $convert_price * $citem->qty, 'ori_offer_price' => $convert_price, 'price_total' => $show_price * $citem->qty, 'ori_price' => $show_price]);

                    }

                } else {

                    if ($pro->vender_offer_price == null || $pro->vender_offer_price == '' || $pro->vender_offer_price == 0 && $show_price != $citem->ori_price) {

                        Cart::where('pro_id', '=', $pro->id)->where('id', '=', $citem->id)->update(['semi_total' => '0', 'ori_offer_price' => '0', 'price_total' => $show_price * $citem->qty, 'ori_price' => $show_price]);

                    }
                }
            }

            $newcart = Auth::user()->cart;

            foreach ($newcart as $key => $val) {
                if ($val->semi_total == 0) {
                    $price = $val->price_total;
                } else {
                    $price = $val->semi_total;
                }

                $total = $total + $price;
            }

            $shippingcharge = 0;

            foreach ($cart_table as $key => $cart) {

                if ($cart->product->free_shipping == 0) {

                    $free_shipping = Shipping::where('id', $cart
                            ->product
                            ->shipping_id)
                            ->first();

                    if (!empty($free_shipping)) {
                        if ($free_shipping->name == "Shipping Price") {

                            $weight = ShippingWeight::first();
                            $pro_weight = $cart
                                ->variant->weight;
                            if ($weight->weight_to_0 >= $pro_weight) {
                                if ($weight->per_oq_0 == 'po') {
                                    $x = $weight->weight_price_0;
                                    $shippingcharge = $shippingcharge + $weight->weight_price_0;
                                    Cart::where('id', $cart->id)->update(['shipping' => $x]);
                                } else {
                                    $x = $weight->weight_price_0 * $cart->qty;
                                    $shippingcharge = $shippingcharge + $weight->weight_price_0 * $cart->qty;
                                    Cart::where('id', $cart->id)->update(['shipping' => $x]);
                                }
                            } elseif ($weight->weight_to_1 >= $pro_weight) {
                                if ($weight->per_oq_1 == 'po') {
                                    $x = $weight->weight_price_1;
                                    $shippingcharge = $shippingcharge + $weight->weight_price_1;
                                    Cart::where('id', $cart->id)->update(['shipping' => $x]);
                                } else {
                                    $x = $weight->weight_price_1 * $cart->qty;
                                    $shippingcharge = $shippingcharge + $weight->weight_price_1 * $cart->qty;
                                    Cart::where('id', $cart->id)->update(['shipping' => $x]);
                                }
                            } elseif ($weight->weight_to_2 >= $pro_weight) {
                                if ($weight->per_oq_2 == 'po') {
                                    $x = $weight->weight_price_2;
                                    $shippingcharge = $shippingcharge + $weight->weight_price_2;
                                    Cart::where('id', $cart->id)->update(['shipping' => $x]);
                                } else {
                                    $x = $weight->weight_price_2 * $cart->qty;
                                    $shippingcharge = $shippingcharge + $weight->weight_price_2 * $cart->qty;
                                    Cart::where('id', $cart->id)->update(['shipping' => $x]);
                                }
                            } elseif ($weight->weight_to_3 >= $pro_weight) {
                                if ($weight->per_oq_3 == 'po') {
                                    $x = $weight->weight_price_3;
                                    $shippingcharge = $shippingcharge + $weight->weight_price_3;
                                    Cart::where('id', $cart->id)->update(['shipping' => $x]);
                                } else {
                                    $x = $weight->weight_price_3 * $cart->qty;
                                    $shippingcharge = $shippingcharge + $weight->weight_price_3 * $cart->qty;
                                    Cart::where('id', $cart->id)->update(['shipping' => $x]);
                                }
                            } else {
                                if ($weight->per_oq_4 == 'po') {
                                    $x = $weight->weight_price_4;
                                    $shippingcharge = $shippingcharge + $weight->weight_price_4;
                                    Cart::where('id', $cart->id)->update(['shipping' => $x]);
                                } else {
                                    $x = $weight->weight_price_4 * $cart->qty;
                                    $shippingcharge = $shippingcharge + $weight->weight_price_4 * $cart->qty;
                                    Cart::where('id', $cart->id)->update(['shipping' => $x]);
                                }

                            }

                        } else {
                            $x = $free_shipping->price;
                            $shippingcharge = $shippingcharge + $free_shipping->price;
                            Cart::where('id', $cart->id)->update(['shipping' => $x]);

                        }
                    }

                }

            }

            $cartamountsetting = Genral::first();

            if ($cartamountsetting->cart_amount != 0 || $cartamountsetting->cart_amount != '') {
                if ($cartamountsetting->cart_amount * $conversion_rate <= $total * $conversion_rate) {
                    $shippingcharge = 0;
                }
            }

            Session::put('shippingcharge', $shippingcharge);

            $grandtotal = $total + $shippingcharge;

            return view('front.step2', compact('conversion_rate', 'grandtotal', 'user', 'total', 'shipping', 'shippingcharge'));

        }

        return view('front.chkoutnotlogin', compact('conversion_rate'));

    }

    public function add(Request $request)
    {

        require_once 'price.php';

        if (!empty(Session::has('from-order-review-page')) && Session::get('from-order-review-page') == 'yes') {
            $sentfromlastpage = 0;
            notify()->success('Item removed from your cart !');
            return view('front.checkout', compact('conversion_rate', 'sentfromlastpage'));
        }

        if (!empty(Session::get('from-pay-page')) && Session::get('from-pay-page') == 'yes') {
            $sentfromlastpage = 0;
            notify()->error('Please verify again as Payment has been modified !');
            return view('front.checkout', compact('conversion_rate', 'sentfromlastpage'));
        }

        if (!empty(Session::get('page-reloaded')) && Session::get('page-reloaded') == 'yes') {
            $sentfromlastpage = 0;
            return view('front.checkout', compact('conversion_rate', 'sentfromlastpage'));
        }

        $user = Auth::user();
        $user_id = $user->id;
        $cart = Session::get('item');

        $countbillingaddress = BillingAddress::where('user_id', Auth::user()->id)
            ->count();

        $addrid = Session::get('address');
        $getaddress = Address::findOrFail($addrid);

        if ($countbillingaddress < 1) {

            if ($request->sameship == 1) {

                Session::put('ship_check', $addrid);

                $newbilling = new BillingAddress();

                $newbilling->total = $request->total;
                $newbilling->firstname = $getaddress->name;
                $newbilling->address = clean($getaddress->address);
                $newbilling->mobile = $getaddress->phone;
                $newbilling->pincode = $getaddress->pin_code;
                $newbilling->city = $getaddress->city_id;
                $newbilling->state = $getaddress->state_id;
                $newbilling->country_id = $getaddress->country_id;
                $newbilling->user_id = Auth::user()->id;
                $newbilling->email = $getaddress->email;

                $newbilling->save();

                session()->put('billing', ['firstname' => $getaddress->name, 'address' => $getaddress->address, 'email' => $getaddress->email, 'country_id' => $getaddress->country_id, 'city' => $getaddress->city_id, 'state' => $getaddress->state_id, 'total' => $request->total, 'mobile' => $getaddress->phone, 'pincode' => $getaddress->pin_code]);

            } else {

                Session::put('ship_check', '0');

                if ($request->billing_name != '' && $request->billing_address != '' && $request->billing_mobile != '' && $request->billing_state != "" && $request->billing_pincode != '' && $request->billing_city != '' && $request->billing_country != '' && $request->billing_email != '') {
                    $newbilling = new BillingAddress();

                    $newbilling->total = $request->total;
                    $newbilling->firstname = $request->billing_name;
                    $newbilling->address = $request->billing_address;
                    $newbilling->mobile = $request->billing_mobile;
                    $newbilling->pincode = $request->billing_pincode;
                    $newbilling->city = $request->billing_city;
                    $newbilling->state = $request->billing_state;
                    $newbilling->country_id = $request->billing_country;
                    $newbilling->user_id = Auth::user()->id;
                    $newbilling->email = $request->billing_email;

                    $newbilling->save();

                    $addflag = 0;
                    #validation here
                    $alladdress = Address::where('user_id', Auth::user()->id)
                        ->get();

                    foreach ($alladdress as $value) {

                        if ($value->name == $request->billing_name && $value->address == $request->billing_address && $value->city_id == $request->billing_city && $value->state_id == $request->billing_state && $value->country_id == $request->billing_country && $request->billing_pincode == $value->pin_code) {

                            $addflag = 1;
                        }
                    }
                    ##
                    if ($addflag != 1) {

                        $newaddress = new Address();

                        $newaddress->name = $request->billing_name;
                        $newaddress->address = clean($request->billing_address);
                        $newaddress->email = $request->billing_email;
                        $newaddress->phone = $request->billing_mobile;
                        $newaddress->pin_code = $request->billing_pincode;
                        $newaddress->city_id = $request->billing_city;
                        $newaddress->state_id = $request->billing_state;
                        $newaddress->country_id = $request->billing_country;
                        $newaddress->defaddress = "0";
                        $newaddress->user_id = Auth::user()->id;

                        $newaddress->save();
                    }

                    session()->put('billing', ['firstname' => $request->billing_name, 'address' => $request->billing_address, 'email' => $request->billing_email, 'country_id' => $request->billing_country, 'city' => $request->billing_city, 'state' => $request->billing_state, 'total' => $request->total, 'mobile' => $request->billing_mobile, 'pincode' => $request->billing_pincode]);
                } else {
                    notify()->error('Please fill all fields to continue !');
                    return back();
                }

            }

        } else {

            if ($request->sameship == 1) {

                Session::put('ship_check', $addrid);
                Session::forget('ship_from_choosen_address');

                $getaddress = Address::findOrFail($addrid);

                session()->put('billing', ['firstname' => $getaddress->name, 'address' => $getaddress->address, 'email' => $getaddress->email, 'country_id' => $getaddress->country_id, 'city' => $getaddress->city_id, 'state' => $getaddress->state_id, 'total' => $request->total, 'mobile' => $getaddress->phone, 'pincode' => $getaddress->pin_code]);

            } else {

                Session::put('ship_check', 0);

                $data = $request->all();

                $getalladdress = BillingAddress::where('user_id', Auth::user()->id)
                    ->get();
                $getuseraddress = Address::where('user_id', Auth::user()->id)
                    ->get();
                $flag = 0;
                $add_cus = 0;
                $add_flag = 0;
                foreach ($getalladdress as $value) {

                    if ($value->firstname == $data['billing_name'] && $value->address == $data['billing_address'] && $value->city == $data['billing_city'] && $value->state == $data['billing_state'] && $value->country_id == $data['billing_country']) {

                        #if match found putting flag = 1
                        foreach ($getuseraddress as $value2) {

                            if ($value2->name == $data['billing_name'] && $value2->address == $data['billing_address'] && $value2->city_id == $data['billing_city'] && $value2->state_id == $data['billing_state'] && $value2->country_id == $data['billing_country']) {

                                $add_cus = $value2->id;
                                $add_flag = 1;

                            }

                        }

                        $flag = 1;

                        break;

                    } else {

                        #if match not found putting flag = 0
                        $flag = 0;
                        #address if already there
                        foreach ($getuseraddress as $value2) {

                            if ($value2->name == $data['billing_name'] && $value2->address == $data['billing_address'] && $value2->city_id == $data['billing_city'] && $value2->state_id == $data['billing_state'] && $value2->country_id == $data['billing_country']) {

                                $add_cus = $value2->id;
                                $add_flag = 1;

                            }

                        }

                    }
                }

                $config = Config::first();

                if ($flag == 1) {

                    Session::put('ship_from_choosen_address', $add_cus);

                    if ($config->pincode_system == 0) {
                        session()->put('billing', ['firstname' => $data['billing_name'], 'address' => $data['billing_address'], 'email' => $data['billing_email'], 'country_id' => $data['billing_country'], 'city' => $data['billing_city'], 'state' => $data['billing_state'], 'total' => $request->total, 'mobile' => $data['billing_mobile']]);
                    } else {
                        session()->put('billing', ['firstname' => $data['billing_name'], 'address' => $data['billing_address'], 'email' => $data['billing_email'], 'country_id' => $data['billing_country'], 'city' => $data['billing_city'], 'state' => $data['billing_state'], 'total' => $request->total, 'mobile' => $data['billing_mobile'], 'pincode' => $data['billing_pincode']]);
                    }

                } else {

                    Session::forget('ship_from_choosen_address');
                    #Saving in billing table if address match not found
                    $newbilling = new BillingAddress();
                    $newbilling->total = $request->total;
                    $newbilling->firstname = $request->billing_name;
                    $newbilling->address = clean($request->billing_address);
                    $newbilling->mobile = $request->billing_mobile;
                    $newbilling->pincode = $request->billing_pincode;
                    $newbilling->city = $request->billing_city;
                    $newbilling->state = $request->billing_state;
                    $newbilling->country_id = $request->billing_country;
                    $newbilling->user_id = Auth::user()->id;
                    $newbilling->email = $request->billing_email;

                    $newbilling->save();

                    if ($add_flag != 1) {
                        #Saving as Shipping address for next-time
                        $newaddress = new Address();

                        $newaddress->name = $request->billing_name;
                        $newaddress->address = clean($request->billing_address);
                        $newaddress->email = $request->billing_email;
                        $newaddress->phone = $request->billing_mobile;
                        $newaddress->pin_code = $request->billing_pincode;
                        $newaddress->city_id = $request->billing_city;
                        $newaddress->state_id = $request->billing_state;
                        $newaddress->country_id = $request->billing_country;
                        $newaddress->defaddress = "0";
                        $newaddress->user_id = Auth::user()->id;

                        $newaddress->save();
                    }

                    if ($config->pincode_system == 1) {
                        session()->put('billing', ['firstname' => $data['billing_name'], 'address' => $data['billing_address'], 'email' => $data['billing_email'], 'country_id' => $data['billing_country'], 'city' => $data['billing_city'], 'state' => $data['billing_state'], 'total' => $request->total, 'mobile' => $data['billing_mobile'], 'pincode' => $data['billing_pincode']]);
                    } else {
                        session()->put('billing', ['firstname' => $data['billing_name'], 'address' => $data['billing_address'], 'email' => $data['billing_email'], 'country_id' => $data['billing_country'], 'city' => $data['billing_city'], 'state' => $data['billing_state'], 'total' => $request->total, 'mobile' => $data['billing_mobile']]);
                    }
                }

            }

        }

        $sentfromlastpage = 0;
        notify()->success('Address Updated Successfully !');
        return view('front.checkout', compact('conversion_rate', 'sentfromlastpage'));

    }

    public function show_profile()
    {
        require_once 'price.php';
        if (!Auth::check()) {
            return redirect()
                ->route('login');
        } else {
            $user = Auth::user();
            $country = Country::all();
            $citys = Allcity::all();
            $states = Allstate::where('country_id', $user->country_id)
                ->get();
            $citys = Allcity::where('state_id', $user->state_id)
                ->get();
            return view('user.profile', compact('conversion_rate', 'user', 'country', 'citys', 'states'));
        }

    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $input = $request->all();

        if ($request->name == '') {
            $input['name'] = $user->name;

        }
        if ($request->mobile == '') {
            $input['mobile'] = $user->mobile;
        }
        if ($request->country_id == '') {
            $input['country_id'] = $user->country_id;
        }
        if ($request->state_id == '') {
            $input['state_id'] = $user->state_id;
        }
        if ($request->city_id == '') {
            $input['city_id'] = $user->city_id;
        }

        if ($file = $request->file('image')) {

            if ($user->image != null) {
                
                if (file_exists(public_path() . '/images/user/' . $user->image)) {
                    unlink(public_path() . '/images/user/' . $user->image);
                }

            }

            $optimizeImage = Image::make($file);
            $optimizePath = public_path() . '/images/user/';
            $name = time() . $file->getClientOriginalName();
            $optimizeImage->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
            $optimizeImage->save($optimizePath . $name);

            $input['image'] = $name;

        } else {

            $input['password'] = $user->password;
            $input['image'] = $user->image;
            try
            {

                $user->update($input);

            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == '1062') {
                    return back()->with("success", "Email Alerdy Exists");
                }
            }

        }

        try
        {

            $user->update($input);

        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == '1062') {
                return back()->with("success", "Email Already Exists");
            }
        }

        return redirect('profile')
            ->with('success', 'Profile has been updated');

    }

    public function changepass(Request $request, $id)
    {

        $this->validate($request, ['old_password' => 'required', 'password' => 'required|between:6,50|confirmed', 'password_confirmation' => 'required']);
        $user = User::findOrFail($id);

        if (Hash::check($request->old_password, $user->password)) {

            $user->fill([
                'password' => Hash::make($request->password),
            ])->save();

            notify()->success('Password changed successfully !');
            return back();
        } else {
            notify()->error('Old password is incorrect !');
            return back();
        }

        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('success', 'Password Updated Successfully !');
    }

    public function order()
    {
        require_once 'price.php';

        $user = Auth::user();

        $orders = Order::orderBy('id', 'desc')->where('user_id', $user->id)->where('status', '=', 1)->paginate(5);

        $ord_postfix = Invoice::first()->order_prefix;

        return view('user.order', compact('ord_postfix', 'orders', 'user', 'conversion_rate'));
    }

}
