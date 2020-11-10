<?php

namespace App\Http\Controllers\used;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AddSubVariant;
use App\Adv;
use App\AutoDetectGeo;
use App\BankDetail;
use App\Blog;
use App\Brand;
use App\Cart;
use App\Category;
use App\Commission;
use App\CommissionSetting;
use App\Country;
use App\Coupan;
use App\DetailAds;
use App\FaqProduct;
use App\Genral;
use App\Mail\SendReviewMail;
use App\multiCurrency;
use App\Notifications\SendReviewNotification;
use App\Order;
use App\Product;
use App\ProductAttributes;
use App\Slider;
use App\Store;
use App\used_coupan;
use App\User;
use App\UserReview;
use App\Vender_category;
use App\Widgetsetting;
use App\Wishlist;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Session;
use Image;
use Mail;
use Response;
//use Session;
use Share;
use View;

class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        Session::get('changed_language');

        require_once str_replace('\used','',__DIR__).'\price.php';

        $sellerSystem = Genral::first()->vendor_enable;

        $slider = Slider::where('status', '1')->get();

        $advquery = Adv::query();

        $advs = $advquery->where('status', '1')->get();

        $blogquery = Blog::query();
        $blogs = $blogquery->where('status', '1')->get();

        $home_slider = Widgetsetting::where('name', 'slider')->first();

        $productsquery = Product::query();
        if ($sellerSystem == 1) {
            $products = $productsquery->orderBy('id', 'DESC')->where('status', '1')->where('is_used','1')
                ->take(20)
                ->get();
        } else {

            $products = [];

            $x = $productsquery->orderBy('id', 'DESC')->where('status', '1')
                ->take(20)
                ->get();

            foreach ($x as $key => $value) {
                if ($value->vender['role_id'] == 'a') {
                    $products[] = $value;
                }
            }
        }

        /*$old_product = $productsquery->where('status', '1')->take(7)
            ->orderBy('id', 'DESC')
            ->get();*/

        if ($sellerSystem == 1) {
            $featureds = $productsquery->take(20)->orderBy('id', 'DESC')
                ->where([['status', '1'], ['featured', '1']])
                ->get();
        } else {
            $x = $productsquery->take(20)->orderBy('id', 'DESC')
                ->where([['status', '1'], ['featured', '1']])
                ->get();

            $featureds = [];

            foreach ($x as $key => $f) {
                if ($f->vender['role_id'] == 'a') {
                    $featureds[] = $f;
                }
            }

        }

        Session::put('currencyChanged', 'no');

        return view('front.index', compact('slider', 'advs', 'products', 'featureds', 'home_slider', 'blogs', 'conversion_rate', 'conversion_rate'));

    }

    public function share(Request $request)
    {

        $currentUrl = $_SERVER['QUERY_STRING'];

        $currentUrl = str_replace('url=', '', $currentUrl);

        return response()->json(['cururl' => View::make('front.share', compact('currentUrl'))->render()]);

    }

    public function user_review(Request $request, $id)
    {

        $this->validate($request, [

            "quality" => "required", "Price" => "required", "Value" => "required",

        ]);

        $user = $request->name;
        $status = 0;
        $cusers = UserReview::where('pro_id', $id)->where('user', Auth::user()
                ->id)
                ->first();
        $purchased = Order::where('user_id', Auth::user()->id)
            ->get();

        foreach ($purchased as $value) {

            foreach ($value->invoices as $singleorder) {
                $av = AddSubVariant::findorfail($singleorder->variant_id);

                if ($av->products->id == $id && $singleorder->status == 'delivered') {
                    $status = 1;
                }

            }

        }

        if (empty($purchased)) {
            notify()->error('Please purchase this product to rate & review !');
            return back();
        }

        $orders = UserReview::where('pro_id', $id)->first();

        if (empty($request->name)) {
            return back()
                ->with('failure', 'Please Login');
        }

        if (isset($cusers)) {
            notify()->error('You Have Already Rated This Product !');
            return back();
        }

        if ($status == 1) {

            $obj = new UserReview;
            $obj->pro_id = $id;
            $obj->qty = $request->quality;
            $obj->price = $request->Price;
            $obj->value = $request->Value;
            $obj->user = $request->name;
            $obj->summary = $request->summary;
            $obj->review = $request->review;
            $obj->save();

            $findprovendor = Product::find($id);

            if ($request->review != '') {
                if ($findprovendor->vender['role_id'] != 'a') {
                    $msg = 'A New pending review has been received on ' . $findprovendor->vender->name . ' product';
                } else {
                    $msg = 'A New pending review has been received on your product';
                }
            } else {
                if ($findprovendor->vender['role_id'] != 'a') {
                    $msg = 'A New pending rating has been received on ' . $findprovendor->vender->name . ' product';
                } else {
                    $msg = 'A New pending rating has been received on your product';
                }
            }

            $admins = User::where('role_id', '=', 'a')->where('status', '=', '1')->get();
            /*Send Notification*/
            \Notification::send($admins, new SendReviewNotification($findprovendor->name, $msg));

            notify()->success('Rated Successfully !');

            /*Send mail*/
            try {

                foreach ($admins as $key => $user) {
                    Mail::to($user->email)->send(new SendReviewMail(Auth::user()->name, $findprovendor->name, $msg));
                }

            } catch (\Swift_TransportException $e) {

            }

            return back();
        } else {
            notify()->error('Thank you for purchase this product but please wait till until product is delivered !');
            return back();
        }
    }

    public function search(Request $request)
    {

        $search = $request->keyword;

        if ($request->cat == 'all') {
            $query = Product::where('tags', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%')
                ->get();
        } else {
            $query = Product::where('tags', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%')->where('category_id', '=', $request->cat)
                ->with('subvariants')
                ->get();
        }

        if (count($query) < 1) {

            $url = url('shop?category=0&start=0&end=1.00&keyword=' . $request->keyword);

            return redirect($url);

        } else {

            require_once 'price.php';

            $price_array = array();
            $price_login = Genral::findOrFail(1)->login;

            foreach ($query as $searchresult) {

                foreach ($searchresult->category->products as $old) {

                    foreach ($old->subvariants as $orivar) {

                        $convert_price = 0;
                        $show_price = 0;

                        $commision_setting = CommissionSetting::first();

                        if ($commision_setting->type == "flat") {

                            $commission_amount = $commision_setting->rate;
                            if ($commision_setting->p_type == 'f') {

                                if ($old->tax_r != '') {
                                    $cit = $commission_amount * $old->tax_r / 100;
                                    $totalprice = $old->vender_price + $orivar->price + $commission_amount + $cit;
                                    $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount + $cit;
                                } else {
                                    $totalprice = $old->vender_price + $orivar->price + $commission_amount;
                                    $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount;
                                }

                                if ($old->vender_offer_price == 0) {
                                    $totalprice;
                                    array_push($price_array, $totalprice);
                                } else {
                                    $totalsaleprice;
                                    $convert_price = $totalsaleprice == '' ? $totalprice : $totalsaleprice;
                                    $show_price = $totalprice;
                                    array_push($price_array, $totalsaleprice);

                                }

                            } else {

                                $totalprice = ($old->vender_price + $orivar->price) * $commission_amount;

                                $totalsaleprice = ($old->vender_offer_price + $orivar->price) * $commission_amount;

                                $buyerprice = ($old->vender_price + $orivar->price) + ($totalprice / 100);

                                $buyersaleprice = ($old->vender_offer_price + $orivar->price) + ($totalsaleprice / 100);

                                if ($old->vender_offer_price == 0) {
                                    $bprice = round($buyerprice, 2);

                                    array_push($price_array, $bprice);
                                } else {
                                    $bsprice = round($buyersaleprice, 2);
                                    $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                    $show_price = $buyerprice;
                                    array_push($price_array, $bsprice);

                                }

                            }
                        } else {

                            $comm = Commission::where('category_id', $old->category_id)->first();
                            if (isset($comm)) {
                                if ($comm->type == 'f') {

                                    if ($old->tax_r != '') {
                                        $cit = $comm->rate * $old->tax_r / 100;
                                        $price = $old->vender_price + $comm->rate + $orivar->price + $cit;
                                        $offer = $old->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                    } else {
                                        $price = $old->vender_price + $comm->rate + $orivar->price;
                                        $offer = $old->vender_offer_price + $comm->rate + $orivar->price;
                                    }

                                    $convert_price = $offer == '' ? $price : $offer;
                                    $show_price = $price;

                                    if ($old->vender_offer_price == 0) {

                                        array_push($price_array, $price);
                                    } else {
                                        array_push($price_array, $offer);
                                    }

                                } else {

                                    $commission_amount = $comm->rate;

                                    $totalprice = ($old->vender_price + $orivar->price) * $commission_amount;

                                    $totalsaleprice = ($old->vender_offer_price + $orivar->price) * $commission_amount;

                                    $buyerprice = ($old->vender_price + $orivar->price) + ($totalprice / 100);

                                    $buyersaleprice = ($old->vender_offer_price + $orivar->price) + ($totalsaleprice / 100);

                                    if ($old->vender_offer_price == 0) {
                                        $bprice = round($buyerprice, 2);
                                        array_push($price_array, $bprice);
                                    } else {
                                        $bsprice = round($buyersaleprice, 2);
                                        $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                        $show_price = round($buyerprice, 2);
                                        array_push($price_array, $bsprice);
                                    }

                                }
                            } else {
                                $commission_amount = 0;

                                $totalprice = ($old->vender_price + $orivar->price) * $commission_amount;

                                $totalsaleprice = ($old->vender_offer_price + $orivar->price) * $commission_amount;

                                $buyerprice = ($old->vender_price + $orivar->price) + ($totalprice / 100);

                                $buyersaleprice = ($old->vender_offer_price + $orivar->price) + ($totalsaleprice / 100);

                                if ($old->vender_offer_price == 0) {
                                    $bprice = round($buyerprice, 2);
                                    array_push($price_array, $bprice);
                                } else {
                                    $bsprice = round($buyersaleprice, 2);
                                    $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                    $show_price = round($buyerprice, 2);
                                    array_push($price_array, $bsprice);
                                }
                            }
                        }

                    }

                }

                if ($price_array != null) {
                    $firstsub = min($price_array);
                    $startp = round($firstsub);
                    if ($startp >= $firstsub) {
                        $startp = $startp - 1;
                    } else {
                        $startp = $startp;
                    }

                    $lastsub = max($price_array);
                    $endp = round($lastsub);

                    if ($endp <= $lastsub) {
                        $endp = $endp + 1;
                    } else {
                        $endp = $endp;
                    }

                } else {
                    $startp = 0.00;
                    $endp = 0.00;
                }

                if (isset($firstsub)) {
                    if ($firstsub == $lastsub) {
                        $startp = 0.00;
                    }
                }

                unset($price_array);

                $price_array = array();

                $url = url('shop?category=' . $searchresult
                        ->category->id . '&start=' . $startp * $conversion_rate . '&end=' . $endp * $conversion_rate . '&keyword=' . $request->keyword);

                    return redirect($url);
            }

        }

    }

    public function details_product($id)
    {

        require_once 'price.php';

        $mainproreviews = UserReview::where('pro_id', $id)->where('status', '1')
            ->get();
        $pro = Product::find($id);

        if (isset($pro)) {

            $qualityprogress = 0;
            $quality = 0;
            $tq = 0;

            $priceprogress = 0;
            $price = 0;
            $tp = 0;

            $valueprogress = 0;
            $value = 0;
            $vp = 0;

            if (!empty($mainproreviews[0])) {

                $count = count($mainproreviews);

                foreach ($mainproreviews as $key => $r) {
                    $quality = $tq + $r->qty * 5;
                }

                $countq = ($count * 1) * 5;
                $ratq = $quality / $countq;
                $qualityprogress = ($ratq * 100) / 5;

                foreach ($mainproreviews as $key => $r) {
                    $price = $tp + $r->price * 5;
                }

                $countp = ($count * 1) * 5;
                $ratp = $price / $countp;
                $priceprogress = ($ratp * 100) / 5;

                foreach ($mainproreviews as $key => $r) {
                    $value = $vp + $r->value * 5;
                }

                $countv = ($count * 1) * 5;
                $ratv = $value / $countv;
                $valueprogress = ($ratv * 100) / 5;

            }

            $faqs = FaqProduct::where('pro_id', $id)->get();

            return view("front.detail", compact("pro", "mainproreviews", 'conversion_rate', 'qualityprogress', 'valueprogress', 'priceprogress', 'faqs'));

        } else {
            notify()
                ->error('404 Product not found !');
            return redirect('/');
        }

    }

    public function AddToWishList($id)
    {

        if (isset(Auth::user()->id)) {
            $wish = DB::table('wishlists')->where('user_id', Auth::user()
                    ->id)
                    ->where('pro_id', $id)->first();
            if (!empty($wish)) {
                return 'error';
            } else {
                $wishlist = new Wishlist;

                $wishlist->user_id = Auth::user()->id;
                $wishlist->pro_id = $id;
                $wishlist->save();
                return 'success';
            }
        } else {
            return back()
                ->with("failure", "Please Log in to use this feature !");
        }
    }

    public function wishlist_show()
    {
        require_once 'price.php';
        if (Auth::user()) {
            $data = Wishlist::where('user_id', Auth::user()->id)->get();
            $count = [];

            foreach ($data as $key => $var) {

                if ($var->variant->products->status == '1') {
                    $count[] = $var->id;
                }

            }

            $wishcount = count($count);

            return view('front.wishlist', compact('conversion_rate', 'data', 'wishcount'));
        } else {
            return back()
                ->with("failure", "Please Log in to use this feature !");
        }
    }

    public function removeWishList($id)
    {
        $user = Auth::user()->id;
        DB::table('wishlists')
            ->where('user_id', $user)->where('pro_id', $id)->delete();
        return 'deleted';
    }

    public function addtTocartfromWishList($id)
    {
        $user = Auth::user()->id;
        DB::table('wishlists')
            ->where('user_id', $user)->where('pro_id', $id)->delete();
        return redirect('addtocart/' . $id);
        return back()->with('failure', 'Item Removed From Wish List');
    }

    public function check()
    {
        if (Auth::check()) {

            $newuser = Auth::user();

            $carts = Session::get('item');

            if (!empty($carts[0])) {
                foreach ($carts as $cart) {

                    $cart_table = Cart::where('pro_id', $cart['id'])->where('user_id', $newuser->id)
                        ->first();
                    if (empty($cart_table)) {
                        Cart::create(array(
                            'pro_id' => $cart['id'],
                            'qty' => $cart['qty'],
                            'user_id' => $newuser->id,
                            'semi_total' => $cart['total_price'],

                        ));
                    } else {
                        Cart::where('pro_id', $cart['id'])->where('user_id', $newuser->id)
                            ->update(array(
                                'pro_id' => $cart['id'],
                                'qty' => $cart['qty'],
                                'user_id' => $newuser->id,
                                'semi_total' => $cart['total_price'],

                            ));
                    }
                }

            }

            Session::forget('item');

        }

        if ($newuser->role_id == 'a') {
            return redirect('admin');
        } elseif ($newuser->role_id == 'v') {
            return redirect('vender');
        } else {
            return redirect('home');
        }

    }

    public function process_to_guest(Request $request)
    {

        if ($request->checkValue == "guest") {
            return redirect()
                ->route('guest.checkout');
        } else {
            return redirect()
                ->route('referfromcheckoutwindow');
        }

    }

    public function coupan_apply(Request $request)
    {
        $auth = Auth::id();
        $date = date('Y-m-d');
        $total = Session('total');
        if (!empty($auth)) {
            $cart = Cart::where('user_id', $auth)->get();
        } else {
            return back()
                ->with("failure", "You are not logged in.");
        }

        $coupan = Coupan::where('code', $request->code)
            ->first();

        foreach ($cart as $carts) {

            if (!empty($coupan['pro_id'])) {
                if ($carts->product['id'] != $coupan['pro_id']) {

                    return back()->with("failure", "Invalid Coupan Code... This Product.");
                }
                $cdate = date($coupan->expirey_dt);
                if (!$coupan) {
                    return back()->with("failure", "Invalid Coupan Code. Please Try Again.");
                } elseif ($coupan->status == 0) {
                    return back()
                        ->with("failure", "Invalid Coupan Code. Please Try Again.");
                } elseif ($date > $cdate) {
                    return back()->with("failure", "Coupan Code Is Expire. Please Try Again.");
                } elseif ($total < $coupan->minimum) {

                    return back()
                        ->with("failure", "Minimum Cart Quantity.." . $coupan->minimum . "Then Coupan apply");
                }
                if (!Auth::check()) {
                    return back()
                        ->with("failure", "You are not logged in.");
                }
                $coupan_used = DB::table('used_coupans')->where('user_id', $auth)->first();
                if (empty($coupan_used)) {

                    $remaining = $coupan->max_use_coupan;

                    if ($coupan->Type == 'percentage') {

                        $per = ($carts
                                ->product->price / 100) * $coupan->amount;

                            if ($remaining < $carts->qty) {
                            $discount_amount = $remaining * $per;
                        } else {
                            $discount_amount = $carts->qty * $per;
                        }

                    } else {

                        if ($remaining < $carts->qty) {
                            $discount_amount = $remaining * $coupan->amount;
                        } else {
                            $discount_amount = $carts->qty * $coupan->amount;
                        }
                    }

                    session()
                        ->put('coupan', ['id' => $coupan->id, 'name' => $coupan->code, 'discount' => $discount_amount, 'total' => $coupan->item($total, $carts->product['id'], $discount_amount)]);

                    return back()->with("success", "Couapn Has Been Applied.");

                } else {
                    if ($coupan_used->used_coupan >= $coupan->max_use_coupan) {

                        $remaining = $coupan->max_use_coupan - $coupan_used->used_coupan;

                        if ($coupan->Type == 'percentage') {

                            $per = ($carts
                                    ->product->price / 100) * $coupan->amount;

                                if ($remaining < $carts->qty) {
                                $discount_amount = $remaining * $per;
                            } else {
                                $discount_amount = $carts->qty * $per;
                            }

                        } else {

                            if ($remaining < $carts->qty) {
                                $discount_amount = $remaining * $coupan->amount;
                            } else {
                                $discount_amount = $carts->qty * $coupan->amount;
                            }
                        }

                        session()
                            ->put('coupan', ['id' => $coupan->id, 'name' => $coupan->code, 'discount' => $discount_amount, 'total' => $coupan->item($total, $carts->product['id'], $discount_amount)]);

                        return back()->with("success", "Couapn Has Been Applied.");

                    }

                }

            }
            if (!empty($coupan['category'])) {
                if ($carts->product['category_id'] != $coupan['category']) {

                    return back()->with("failure", "Invalid Coupan Code... This Category.");
                }

                if ($carts->product['category_id'] == $coupan['category']) {
                    $cdate = date($coupan->expirey_dt);
                    if (!$coupan) {
                        return back()->with("failure", "Invalid Coupan Code. Please Try Again.");
                    } elseif ($coupan->status == 0) {
                        return back()
                            ->with("failure", "Invalid Coupan Code. Please Try Again.");
                    } elseif ($date > $cdate) {
                        return back()->with("failure", "Coupan Code Is Expire. Please Try Again.");
                    } elseif ($total < $coupan->minimum) {

                        return back()
                            ->with("failure", "Minimum Cart Quantity.." . $coupan->minimum . "Then Coupan apply");
                    }
                    if (!Auth::check()) {
                        return back()
                            ->with("failure", "You are not logged in.");
                    }
                    $coupan_used = DB::table('used_coupans')->where('user_id', $auth)->first();
                    if (empty($coupan_used)) {

                        $remaining = $coupan->max_use_coupan;

                        if ($coupan->Type == 'percentage') {

                            $per = ($carts->price / 100) * $coupan->amount;

                            if ($remaining < $carts->qty) {
                                $discount_amount = $remaining * $per;
                            } else {
                                $discount_amount = $carts->qty * $per;
                            }

                        } else {

                            if ($remaining < $carts->qty) {
                                $discount_amount = $remaining * $coupan->amount;
                            } else {
                                $discount_amount = $carts->qty * $coupan->amount;
                            }
                        }

                        session()
                            ->put('coupan', ['id' => $coupan->id, 'name' => $coupan->code, 'discount' => $discount_amount, 'total' => $coupan->cat($total, $carts->product['category_id'], $discount_amount)]);

                        return back()->with("success", "Couapn Has Been Applied.");

                    } else {
                        if ($coupan_used->used_coupan >= $coupan->max_use_coupan) {

                            $remaining = $coupan->max_use_coupan - $coupan_used->used_coupan;

                            if ($coupan->Type == 'percentage') {

                                $per = ($carts->price / 100) * $coupan->amount;

                                if ($remaining < $carts->qty) {
                                    $discount_amount = $remaining * $per;
                                } else {
                                    $discount_amount = $carts->qty * $per;
                                }

                            } else {

                                if ($remaining < $carts->qty) {
                                    $discount_amount = $remaining * $coupan->amount;
                                } else {
                                    $discount_amount = $carts->qty * $coupan->amount;
                                }
                            }

                            session()
                                ->put('coupan', ['id' => $coupan->id, 'name' => $coupan->code, 'discount' => $discount_amount, 'total' => $coupan->cat($total, $carts->product['category_id'], $discount_amount)]);

                            return back()->with("success", "Couapn Has Been Applied.");

                        }

                    }
                }
            }

        }

        if (!empty($coupan)) {

            $cdate = date($coupan->expirey_dt);
        }
        if (!$coupan) {
            return back()->with("failure", "Invalid Coupan Code. Please Try Again.");
        } elseif ($coupan->status == 0) {
            return back()
                ->with("failure", "Invalid Coupan Code. Please Try Again.");
        } elseif ($date > $cdate) {
            return back()->with("failure", "Coupan Code Is Expire. Please Try Again.");
        } elseif ($total < $coupan->minimum) {

            return back()
                ->with("failure", "Minimum Cart Quantity.." . $coupan->minimum . "Then Coupan apply");
        } else {

            $coupan_used = DB::table('used_coupans')->where('user_id', '1')
                ->get();
            $conversion_rate = json_decode($coupan_used, true);
            $cdate = date($coupan->expirey_dt);

            if (!$coupan) {
                return back()->with("failure", "Invalid Coupan Code. Please Try Again.");
            } elseif ($coupan->status == 0) {
                return back()
                    ->with("failure", "Invalid Coupan Code. Please Try Again.");
            } elseif ($date > $cdate) {
                return back()->with("failure", "Coupan Code Is Expire. Please Try Again.");
            } elseif ($total < $coupan->minimum) {
                return back()
                    ->with("failure", "Minimum Cart Quantity.." . $coupan->minimum . "Then Coupan apply");
            }
            if (!empty($conversion_rate)) {
                if ($conversion_rate['0']['used_coupan'] >= $coupan->max_use_coupan) {
                    return back()
                        ->with("failure", "This Coupan Code Not For You. Please Try Again.");
                }
            }
            session()
                ->put('coupan', ['id' => $coupan->id, 'name' => $coupan->code, 'discount' => $coupan->amount, 'total' => $coupan->discount($total)]);
            return back()->with("success", "Couapn Has Been Applied.");
        }
    }

    public function coupan_destroy()
    {
        session()
            ->forget('coupan');
        return back()
            ->with("failure", "Couapn Has Been Removed.");
    }

    public function comparisonList()
    {
        require_once 'price.php';

        return view('front.comparison', compact('conversion_rate'));
    }

    public function docomparison($id)
    {

        //create a session and put products on it //
        if (!empty(Session::get('comparison'))) {

            $countComparison = count(Session::get('comparison'));

            if ($countComparison < 4) {

                $comproducts = Session::get('comparison');
                $countLength = count(Session::get('comparison'));
                $avbl = 0;

                $fpro = 0;

                foreach ($comproducts as $key => $value) {
                    $fpro = $comproducts[$key]['proid'];
                }

                $firstProduct = Product::find($fpro);
                $currentpro = Product::find($id);

                if ($firstProduct->child != $currentpro->child) {
                    notify()
                        ->success('Only similar product can be compared');
                    return back();
                    exit;
                }

                foreach ($comproducts as $key => $pro) {

                    if ($pro['proid'] == $id) {

                        $avbl = 1;
                        break;

                    } else {

                        $avbl = 0;

                    }
                }

                if ($avbl == 0) {

                    Session::push('comparison', ['proid' => $id]);
                    notify()->success('Product added to your compare list !');
                    return back();
                } else {
                    notify()
                        ->error('Product is already added to your comparison list !');
                    return back();
                }

            } else {
                notify()
                    ->error('You can compare only 4 product at a time !');
                return back();
            }

        } else {
            Session::push('comparison', ['proid' => $id]);
            notify()->success('Product added to your compare list !');
            return back();
        }

        return view("front.comparison");
    }

    public function removeFromComparsion($id)
    {
        $comp = Session::get('comparison');

        foreach ($comp as $key => $value) {
            if ($value['proid'] == $id) {
                unset($comp[$key]);
            }
        }

        Session::put('comparison', $comp);
        notify()->success('Item removed from comparison list !');
        return back();

    }

    public function bankdetail()
    {

        $value = BankDetail::all();
        return view("front.bankdetail", compact("value"));
    }

    public function edit_blog($id)
    {

        $value = Blog::where('id', '1')->first();
        return view("front.blog", compact("value"));
    }

    public function currency($id)
    {

        $pre = Session::get('currency')['id'];

        Session::put('previous_cur', $pre);

        $currency = multiCurrency::where('currency_id', $id)->first();

        session()
            ->put('currency', ['id' => $currency
                    ->currency->code, 'mainid' => $currency
                    ->currency->id, 'value' => $currency->currency_symbol, 'position' => $currency->position]);

            Session::put('current_cur', $currency
                ->currency
                ->code);

            $status = 'yes';

            Session::put('currencyChanged', $status);

        return "Success";

    }

    public function applyforseller()
    {
        require_once 'price.php';
        $country = Country::all();
        $id = Auth::user()->id;
        $user = User::where('id', $id)->first();
        return view('user.applysellerform', compact('user', 'country', 'conversion_rate'));
    }

    public function store_vender(Request $request)
    {
        $input = $request->all();

        if ($file = $request->file('store_logo')) {

            $optimizeImage = Image::make($file);
            $optimizePath = public_path() . '/images/store/';
            $store_logo = time() . $file->getClientOriginalName();
            $optimizeImage->save($optimizePath . $store_logo, 72);

            $input['store_logo'] = $store_logo;

            $data = Store::create($input);

            $data->save();
        }

        $user_id = $request->user_id;

        User::where('id', $user_id)->update(array(
            'role_id' => 'v',
        ));

        $arrays = $request->vehicle;

        if (isset($arrays)) {

            foreach ($arrays as $arr) {

                if ($arr != null) {
                    $createC = new Vender_category;

                    $createC->title = $arr;

                    $createC->save();
                }

            }

        }
        notify()->success('Store Has Been Created ! Once its approved you can start selling your product !');
        return redirect('/');

    }

    public function guestCheckout()
    {
        require_once 'price.php';

        return view('front.guestCheckout', compact('conversion_rate'));
    }

    public function categoryfilter(Request $request)
    {

        $venderSystem = Genral::first()->vendor_enable;

        if ($request->brandNames[0] == null) {

            $brand_names = '';

        } else {

            $brand_names = $request->brandNames;

        }

        require_once 'price.php';

        $start_price = $request->start_price;

        $tags_pro = $request->tag;
        $starts = $request->start;
        $ends = $request->end;
        $filter = $request->filter;
        $display = $request->display;
        $catid = $request->catID;
        $sid = $request->sid;
        $chid = $request->chid;
        $outofstock = $request->oot;
        $slider = $request->slider;
        $tag_check = $request->tag_check;
        $products = Product::query();
        $all_brands_products = array();
        $tags_new = array();
        $testingarr = array();
        $sidebarbrands = array();
        $vararray = $request->variantArray;
        $attrarray = $request->attrArray;
        $emarray = array();
        $uniqarray = array();
        $filledpro = array();
        $ratings = $request->ratings;
        $start_rat = $request->start_rat;
        $featured = $request->featured;
        $variantProduct = array();
        $variantProValues = array();

        $a = array();

        if ($request->catID != "") {

            if ($request->keyword != '' && $request->tag == '') {
                $search = $request->keyword;

                $search = str_replace("+", " ", $search);

                //with keyword and witout tag
                if ($request->chid != '') {
                    if ($brand_names != '') {

                        if (is_array($brand_names)) {

                            if ($featured == 1) {

                                $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                    ->where('grand_id', $chid)->get();
                            } else {
                                $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('grand_id', $chid)->get();
                            }

                            if ($vararray != null) {

                                foreach ($all_brands_products as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($all_brands_products as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $all_brands_products = $filledpro;

                            } else {
                                $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('grand_id', $chid)->get();
                            }

                            foreach ($all_brands_products as $pro) {

                                if (count($pro->subvariants) > 0) {
                                    $pro_all_tags = explode(',', $pro->tags);
                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }
                                }

                            }

                            $tagsunique = array_unique($tags_new);

                            $testingarr = $all_brands_products;

                        }
                    } else {

                        if ($vararray != null) {

                            if ($featured == 1) {
                                $tag_products = $products
                                    ->where('tags', 'LIKE', '%' . $search . '%')
                                    ->where('featured', '=', '1')
                                    ->where('grand_id', $chid)->get();
                            } else {
                                $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('grand_id', $chid)->get();
                            }

                            foreach ($tag_products as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($attrarray as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($vararray as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($attrarray) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($tag_products as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                        } else {

                            if ($featured == 1) {
                                $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('featured', '=', '1')
                                    ->where('grand_id', $chid)->get();
                                $featured_pros = $tag_products;
                            } else {
                                $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('grand_id', $chid)->get();
                            }

                        }

                        $allbrands = Brand::all();

                        foreach ($allbrands as $brands) {
                            if (is_array($brands->category_id)) {
                                foreach ($brands->category_id as $brandcategory) {
                                    if ($brandcategory == $catid) {

                                        $sidebarbrands[$brands
                                                ->id] = $brands->name;

                                    }
                                }
                            }
                        }

                        foreach ($tag_products as $pro) {

                            if (count($pro->subvariants) > 0) {

                                $pro_all_tags = explode(',', $pro->tags);
                                foreach ($pro_all_tags as $t) {
                                    array_push($tags_new, $t);
                                }
                            }

                        }

                        $tagsunique = array_unique($tags_new);

                        $getattr = ProductAttributes::all();

                        foreach ($getattr as $attr) {

                            $res = in_array($catid, $attr->cats_id);

                            if ($res == $attr->id) {

                                array_push($variantProduct, $attr);

                            }

                            foreach ($attr->provalues as $item) {
                                array_push($variantProValues, $item);
                            }

                        }

                    }
                } else {
                    if ($request->sid != '') {
                        if ($brand_names != '') {
                            if (is_array($brand_names)) {

                                if ($featured == 1) {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                        ->where('child', $sid)->get();
                                } else {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('child', $sid)->get();

                                }

                                if ($vararray != null) {

                                    if ($featured == 1) {
                                        $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                            ->where('child', $sid)->get();
                                    } else {
                                        $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('child', $sid)->get();
                                    }

                                    foreach ($all_brands_products as $pro) {
                                        if ($pro
                                            ->subvariants
                                            ->count() > 0) {
                                            foreach ($pro->subvariants as $sub) {

                                                foreach ($sub->main_attr_value as $key => $main) {
                                                    foreach ($attrarray as $attr) {
                                                        if ($attr == $key) {
                                                            foreach ($vararray as $var) {
                                                                if ($main == $var) {

                                                                    array_push($emarray, $sub);

                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    if (count($attrarray) > 1) {

                                        $array_temp = array();

                                        foreach ($emarray as $val) {
                                            if (!in_array($val, $array_temp)) {
                                                $array_temp[] = $val;
                                            } else {
                                                array_push($a, $val);
                                            }
                                        }
                                    } else {
                                        $a = $emarray;
                                    }

                                    foreach ($a as $b) {
                                        foreach ($all_brands_products as $p) {
                                            foreach ($p->subvariants as $s) {
                                                if ($s->id == $b->id) {
                                                    array_push($filledpro, $p);
                                                }
                                            }
                                        }
                                    }

                                    $all_brands_products = $filledpro;

                                } else {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('child', $sid)->get();
                                }

                                foreach ($all_brands_products as $pro) {

                                    if (count($pro->subvariants) > 0) {

                                        $pro_all_tags = explode(',', $pro->tags);
                                        foreach ($pro_all_tags as $t) {
                                            array_push($tags_new, $t);
                                        }
                                    }

                                }

                                $tagsunique = array_unique($tags_new);
                                $testingarr = $all_brands_products;

                            }
                        } else {

                            if ($vararray != null) {

                                if ($featured == 1) {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('featured', '=', '1')
                                        ->where('child', $sid)->get();
                                } else {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('child', $sid)->get();
                                }

                                foreach ($tag_products as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($tag_products as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                            } else {

                                if ($featured == 1) {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('featured', '=', '1')
                                        ->where('child', $sid)->get();

                                } else {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('child', $sid)->get();
                                }
                            }

                            $allbrands = Brand::all();

                            foreach ($allbrands as $brands) {
                                if (is_array($brands->category_id)) {
                                    foreach ($brands->category_id as $brandcategory) {
                                        if ($brandcategory == $catid) {

                                            $sidebarbrands[$brands
                                                    ->id] = $brands->name;

                                        }
                                    }
                                }
                            }

                            foreach ($tag_products as $pro) {

                                if (count($pro->subvariants) > 0) {

                                    $pro_all_tags = explode(',', $pro->tags);
                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }
                                }

                            }

                            $tagsunique = array_unique($tags_new);

                            $getattr = ProductAttributes::all();

                            foreach ($getattr as $attr) {

                                $res = in_array($catid, $attr->cats_id);

                                if ($res == $attr->id) {

                                    array_push($variantProduct, $attr);

                                }

                                foreach ($attr->provalues as $item) {
                                    array_push($variantProValues, $item);
                                }

                            }

                        }
                    } else {
                        if ($brand_names != '') {
                            if (is_array($brand_names)) {

                                if ($featured == 1) {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                        ->where('category_id', $catid)->get();
                                    $featured_pros = $all_brands_products;
                                } else {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();
                                }

                                if ($vararray != null) {

                                    if ($featured == 1) {

                                        $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                            ->where('category_id', $catid)->get();

                                    } else {

                                        $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();

                                    }

                                    foreach ($all_brands_products as $pro) {
                                        if ($pro
                                            ->subvariants
                                            ->count() > 0) {
                                            foreach ($pro->subvariants as $sub) {

                                                foreach ($sub->main_attr_value as $key => $main) {
                                                    foreach ($attrarray as $attr) {
                                                        if ($attr == $key) {
                                                            foreach ($vararray as $var) {
                                                                if ($main == $var) {

                                                                    array_push($emarray, $sub);

                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    if (count($attrarray) > 1) {

                                        $array_temp = array();

                                        foreach ($emarray as $val) {
                                            if (!in_array($val, $array_temp)) {
                                                $array_temp[] = $val;
                                            } else {
                                                array_push($a, $val);
                                            }
                                        }
                                    } else {
                                        $a = $emarray;
                                    }

                                    foreach ($a as $b) {
                                        foreach ($all_brands_products as $p) {
                                            foreach ($p->subvariants as $s) {
                                                if ($s->id == $b->id) {
                                                    array_push($filledpro, $p);
                                                }
                                            }
                                        }
                                    }

                                    $all_brands_products = $filledpro;

                                } else {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();
                                }

                                foreach ($all_brands_products as $pro) {

                                    if (count($pro->subvariants) > 0) {

                                        $pro_all_tags = explode(',', $pro->tags);

                                        foreach ($pro_all_tags as $t) {
                                            array_push($tags_new, $t);
                                        }
                                    }

                                }

                                $tagsunique = array_unique($tags_new);
                                $testingarr = $all_brands_products;
                            }
                        } else {

                            if ($vararray != null) {

                                if ($featured == 1) {

                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('featured', '=', '1')
                                        ->where('category_id', $catid)->get();

                                } else {

                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('category_id', $catid)->get();

                                }

                                foreach ($tag_products as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {

                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($tag_products as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                            } else {

                                if ($featured == 1) {

                                    $featured_pros = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('featured', '=', '1')
                                        ->where('category_id', $catid)->get();
                                    $tag_products = $featured_pros;
                                } else {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('category_id', $catid)->get();
                                }

                            }

                            $getattr = ProductAttributes::all();

                            foreach ($getattr as $attr) {
                                $res = in_array($catid, $attr->cats_id);

                                if ($res == $attr->id) {

                                    array_push($variantProduct, $attr);

                                }

                                foreach ($attr->provalues as $item) {
                                    array_push($variantProValues, $item);
                                }

                            }

                            $allbrands = Brand::all();

                            foreach ($allbrands as $brands) {
                                if (is_array($brands->category_id)) {
                                    foreach ($brands->category_id as $brandcategory) {
                                        if ($brandcategory == $catid) {

                                            $sidebarbrands[$brands
                                                    ->id] = $brands->name;

                                        }
                                    }
                                }
                            }

                            foreach ($tag_products as $pro) {

                                if (count($pro->subvariants) > 0) {

                                    $pro_all_tags = explode(',', $pro->tags);

                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }

                                }

                            }

                            $tagsunique = array_unique($tags_new);

                        }
                    }
                }
                //end

            } elseif ($request->keyword != '' && $request->tag != '') {

                $search = $request->keyword;

                $search = str_replace("+", " ", $search);

                //with keyword and with tag
                if ($request->chid != '') {
                    if ($brand_names != '') {
                        if (is_array($brand_names)) {
                            unset($testingarr);
                            $testingarr = array();

                            if ($featured == 1) {

                                $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                    ->where('grand_id', $chid)->get();

                            } else {

                                $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('grand_id', $chid)->get();

                            }

                            foreach ($request->tag as $url) {

                                foreach ($all_brands_products as $string) {
                                    $ex_tags = explode(',', $string->tags);

                                    foreach ($ex_tags as $ext) {
                                        if (strpos($ext, $url) !== false) {
                                            array_push($testingarr, $string);
                                        } else {

                                        }
                                    }
                                }
                            }

                            $testingarr = array_unique($testingarr);

                            foreach ($testingarr as $pro) {

                                if (count($pro->subvariants) > 0) {

                                    $pro_all_tags = explode(',', $pro->tags);
                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }
                                }

                            }

                            if ($vararray != null) {
                                foreach ($testingarr as $pro) {

                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                            $tagsunique = array_unique($tags_new);
                        }
                    } else {
                        unset($testingarr);
                        $testingarr = array();

                        if ($featured == 1) {
                            $strings = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('featured', '=', '1')
                                ->where('grand_id', $request->chid)
                                ->get();
                        } else {
                            $strings = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('grand_id', $request->chid)
                                ->get();
                        }

                        foreach ($request->tag as $url) {

                            foreach ($strings as $string) {
                                $ex_tags = explode(',', $string->tags);

                                foreach ($ex_tags as $ext) {
                                    if (strpos($ext, $url) !== false) {
                                        array_push($testingarr, $string);
                                    } else {
                                        //code

                                    }
                                }
                            }
                        }

                        $testingarr = array_unique($testingarr);

                        if ($vararray != null) {
                            foreach ($testingarr as $pro) {

                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($attrarray as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($vararray as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($attrarray) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $val = array_unique($val);
                                        $array_temp[] = $val;
                                    } else {
                                        $val = array_unique($val);
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = array_unique($emarray);
                            }

                            foreach ($a as $b) {
                                foreach ($testingarr as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                            $testingarr = $filledpro;

                        } else {
                            $testingarr;
                        }

                        foreach ($testingarr as $pro) {

                            if (count($pro->subvariants) > 0) {

                                $pro_all_tags = explode(',', $pro->tags);
                                foreach ($pro_all_tags as $t) {
                                    array_push($tags_new, $t);
                                }
                            }

                        }

                        $tagsunique = array_unique($tags_new);

                    }

                } else {
                    if ($request->sid != '') {
                        if ($brand_names != '') {
                            if (is_array($brand_names)) {
                                unset($testingarr);
                                $testingarr = array();

                                if ($featured == 1) {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                        ->where('child', $sid)->get();
                                } else {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('child', $sid)->get();
                                }

                                foreach ($request->tag as $url) {

                                    foreach ($all_brands_products as $string) {
                                        $ex_tags = explode(',', $string->tags);

                                        foreach ($ex_tags as $ext) {
                                            if (strpos($ext, $url) !== false) {
                                                array_push($testingarr, $string);
                                            } else {
                                                //code

                                            }
                                        }
                                    }
                                }

                                $testingarr = array_unique($testingarr);

                                if ($vararray != null) {
                                    foreach ($testingarr as $pro) {

                                        if ($pro
                                            ->subvariants
                                            ->count() > 0) {
                                            foreach ($pro->subvariants as $sub) {

                                                foreach ($sub->main_attr_value as $key => $main) {
                                                    foreach ($attrarray as $attr) {
                                                        if ($attr == $key) {
                                                            foreach ($vararray as $var) {
                                                                if ($main == $var) {

                                                                    array_push($emarray, $sub);

                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    if (count($attrarray) > 1) {

                                        $array_temp = array();

                                        foreach ($emarray as $val) {
                                            if (!in_array($val, $array_temp)) {
                                                $array_temp[] = $val;
                                            } else {
                                                array_push($a, $val);
                                            }
                                        }
                                    } else {
                                        $a = $emarray;
                                    }

                                    foreach ($a as $b) {
                                        foreach ($testingarr as $p) {
                                            foreach ($p->subvariants as $s) {
                                                if ($s->id == $b->id) {
                                                    array_push($filledpro, $p);
                                                }
                                            }
                                        }
                                    }

                                    $testingarr = $filledpro;

                                } else {
                                    $testingarr;
                                }

                                foreach ($testingarr as $pro) {

                                    if (count($pro->subvariants) > 0) {

                                        $pro_all_tags = explode(',', $pro->tags);
                                        foreach ($pro_all_tags as $t) {
                                            array_push($tags_new, $t);
                                        }
                                    }

                                }

                                $tagsunique = array_unique($tags_new);

                            }
                        } else {

                            unset($testingarr);
                            $testingarr = array();

                            if ($featured == 1) {
                                $strings = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('featured', '=', '1')
                                    ->where('child', $sid)->get();
                            } else {
                                $strings = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('child', $sid)->get();
                            }

                            foreach ($request->tag as $url) {

                                foreach ($strings as $string) {
                                    $ex_tags = explode(',', $string->tags);

                                    foreach ($ex_tags as $ext) {
                                        if (strpos($ext, $url) !== false) {
                                            array_push($testingarr, $string);
                                        } else {
                                            //code

                                        }
                                    }
                                }
                            }

                            $testingarr = array_unique($testingarr);

                            if ($vararray != null) {
                                foreach ($testingarr as $pro) {

                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                            foreach ($testingarr as $pro) {

                                if (count($pro->subvariants) > 0) {

                                    $pro_all_tags = explode(',', $pro->tags);
                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }
                                }

                            }

                            $tagsunique = array_unique($tags_new);

                        }

                    } else {
                        if ($brand_names != '') {
                            if (is_array($brand_names)) {

                                unset($testingarr);
                                $testingarr = array();

                                if ($featured == 1) {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                        ->where('category_id', $catid)->get();
                                } else {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();
                                }

                                foreach ($request->tag as $url) {

                                    foreach ($all_brands_products as $string) {
                                        $ex_tags = explode(',', $string->tags);

                                        foreach ($ex_tags as $ext) {
                                            if (strpos($ext, $url) !== false) {
                                                array_push($testingarr, $string);
                                            } else {
                                                //code

                                            }
                                        }
                                    }
                                }

                                $testingarr = array_unique($testingarr);

                                if ($vararray != null) {
                                    foreach ($testingarr as $pro) {

                                        if ($pro
                                            ->subvariants
                                            ->count() > 0) {
                                            foreach ($pro->subvariants as $sub) {

                                                foreach ($sub->main_attr_value as $key => $main) {
                                                    foreach ($attrarray as $attr) {
                                                        if ($attr == $key) {
                                                            foreach ($vararray as $var) {
                                                                if ($main == $var) {

                                                                    array_push($emarray, $sub);

                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    if (count($attrarray) > 1) {

                                        $array_temp = array();

                                        foreach ($emarray as $val) {
                                            if (!in_array($val, $array_temp)) {
                                                $array_temp[] = $val;
                                            } else {
                                                array_push($a, $val);
                                            }
                                        }
                                    } else {
                                        $a = $emarray;
                                    }

                                    foreach ($a as $b) {
                                        foreach ($testingarr as $p) {
                                            foreach ($p->subvariants as $s) {
                                                if ($s->id == $b->id) {
                                                    array_push($filledpro, $p);
                                                }
                                            }
                                        }
                                    }

                                    $testingarr = $filledpro;

                                } else {
                                    $testingarr;
                                }

                                foreach ($testingarr as $pro) {

                                    if (count($pro->subvariants) > 0) {

                                        $pro_all_tags = explode(',', $pro->tags);
                                        foreach ($pro_all_tags as $t) {
                                            array_push($tags_new, $t);
                                        }
                                    }

                                }

                                $tagsunique = array_unique($tags_new);

                            }
                        } else {

                            unset($testingarr);
                            $testingarr = array();

                            if ($featured == 1) {
                                $strings = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('featured', '=', '1')
                                    ->where('category_id', $catid)->get();
                            } else {
                                $strings = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('category_id', $catid)->get();
                            }

                            foreach ($request->tag as $url) {

                                foreach ($strings as $string) {
                                    $ex_tags = explode(',', $string->tags);

                                    foreach ($ex_tags as $ext) {
                                        if (strpos($ext, $url) !== false) {
                                            array_push($testingarr, $string);
                                        } else {
                                            //code

                                        }
                                    }
                                }
                            }

                            if ($vararray != null) {
                                foreach ($testingarr as $pro) {

                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                            foreach ($testingarr as $pro) {

                                if (count($pro->subvariants) > 0) {
                                    $pro_all_tags = explode(',', $pro->tags);
                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }
                                }

                            }

                            $tagsunique = array_unique($tags_new);
                        }

                    }
                }
                //end

            } elseif ($request->tag != '') {

                if ($request->chid != '') {
                    if ($brand_names != '') {
                        if (is_array($brand_names)) {
                            unset($testingarr);
                            $testingarr = array();

                            if ($featured == 1) {

                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                    ->where('grand_id', $chid)->get();

                            } else {

                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('grand_id', $chid)->get();

                            }

                            foreach ($request->tag as $url) {

                                foreach ($all_brands_products as $string) {
                                    $ex_tags = explode(',', $string->tags);

                                    foreach ($ex_tags as $ext) {
                                        if (strpos($ext, $url) !== false) {
                                            array_push($testingarr, $string);
                                        } else {
                                            //code

                                        }
                                    }
                                }
                            }

                            $testingarr = array_unique($testingarr);

                            foreach ($testingarr as $pro) {

                                if (count($pro->subvariants) > 0) {

                                    $pro_all_tags = explode(',', $pro->tags);
                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }
                                }

                            }

                            if ($vararray != null) {
                                foreach ($testingarr as $pro) {

                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                            $tagsunique = array_unique($tags_new);
                        }
                    } else {
                        unset($testingarr);
                        $testingarr = array();

                        if ($featured == 1) {
                            $strings = $products->where('featured', '=', '1')
                                ->where('grand_id', $request->chid)
                                ->get();
                        } else {
                            $strings = $products->where('grand_id', $request->chid)
                                ->get();
                        }

                        foreach ($request->tag as $url) {

                            foreach ($strings as $string) {
                                $ex_tags = explode(',', $string->tags);

                                foreach ($ex_tags as $ext) {
                                    if (strpos($ext, $url) !== false) {
                                        array_push($testingarr, $string);
                                    } else {
                                        //code

                                    }
                                }
                            }
                        }

                        $testingarr = array_unique($testingarr);

                        if ($vararray != null) {
                            foreach ($testingarr as $pro) {

                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($attrarray as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($vararray as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($attrarray) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $val = array_unique($val);
                                        $array_temp[] = $val;
                                    } else {
                                        $val = array_unique($val);
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = array_unique($emarray);
                            }

                            foreach ($a as $b) {
                                foreach ($testingarr as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                            $testingarr = $filledpro;

                        } else {
                            $testingarr;
                        }

                        foreach ($testingarr as $pro) {

                            if (count($pro->subvariants) > 0) {

                                $pro_all_tags = explode(',', $pro->tags);
                                foreach ($pro_all_tags as $t) {
                                    array_push($tags_new, $t);
                                }
                            }

                        }

                        $tagsunique = array_unique($tags_new);

                    }

                } else {
                    if ($request->sid != '') {
                        if ($brand_names != '') {
                            if (is_array($brand_names)) {
                                unset($testingarr);
                                $testingarr = array();

                                if ($featured == 1) {
                                    $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                        ->where('child', $sid)->get();
                                } else {
                                    $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('child', $sid)->get();
                                }

                                foreach ($request->tag as $url) {

                                    foreach ($all_brands_products as $string) {
                                        $ex_tags = explode(',', $string->tags);

                                        foreach ($ex_tags as $ext) {
                                            if (strpos($ext, $url) !== false) {
                                                array_push($testingarr, $string);
                                            } else {
                                                //code

                                            }
                                        }
                                    }
                                }

                                $testingarr = array_unique($testingarr);

                                if ($vararray != null) {
                                    foreach ($testingarr as $pro) {

                                        if ($pro
                                            ->subvariants
                                            ->count() > 0) {
                                            foreach ($pro->subvariants as $sub) {

                                                foreach ($sub->main_attr_value as $key => $main) {
                                                    foreach ($attrarray as $attr) {
                                                        if ($attr == $key) {
                                                            foreach ($vararray as $var) {
                                                                if ($main == $var) {

                                                                    array_push($emarray, $sub);

                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    if (count($attrarray) > 1) {

                                        $array_temp = array();

                                        foreach ($emarray as $val) {
                                            if (!in_array($val, $array_temp)) {
                                                $array_temp[] = $val;
                                            } else {
                                                array_push($a, $val);
                                            }
                                        }
                                    } else {
                                        $a = $emarray;
                                    }

                                    foreach ($a as $b) {
                                        foreach ($testingarr as $p) {
                                            foreach ($p->subvariants as $s) {
                                                if ($s->id == $b->id) {
                                                    array_push($filledpro, $p);
                                                }
                                            }
                                        }
                                    }

                                    $testingarr = $filledpro;

                                } else {
                                    $testingarr;
                                }

                                foreach ($testingarr as $pro) {

                                    if (count($pro->subvariants) > 0) {

                                        $pro_all_tags = explode(',', $pro->tags);
                                        foreach ($pro_all_tags as $t) {
                                            array_push($tags_new, $t);
                                        }
                                    }

                                }

                                $tagsunique = array_unique($tags_new);

                            }
                        } else {

                            unset($testingarr);
                            $testingarr = array();

                            if ($featured == 1) {
                                $strings = $products->where('featured', '=', '1')
                                    ->where('child', $sid)->get();
                            } else {
                                $strings = $products->where('child', $sid)->get();
                            }

                            foreach ($request->tag as $url) {

                                foreach ($strings as $string) {
                                    $ex_tags = explode(',', $string->tags);

                                    foreach ($ex_tags as $ext) {
                                        if (strpos($ext, $url) !== false) {
                                            array_push($testingarr, $string);
                                        } else {
                                            //code

                                        }
                                    }
                                }
                            }

                            $testingarr = array_unique($testingarr);

                            if ($vararray != null) {
                                foreach ($testingarr as $pro) {

                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                            foreach ($testingarr as $pro) {

                                if (count($pro->subvariants) > 0) {

                                    $pro_all_tags = explode(',', $pro->tags);
                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }
                                }

                            }

                            $tagsunique = array_unique($tags_new);

                        }

                    } else {
                        if ($brand_names != '') {
                            if (is_array($brand_names)) {

                                unset($testingarr);
                                $testingarr = array();

                                if ($featured == 1) {
                                    $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                        ->where('category_id', $catid)->get();
                                } else {
                                    $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();
                                }

                                foreach ($request->tag as $url) {

                                    foreach ($all_brands_products as $string) {
                                        $ex_tags = explode(',', $string->tags);

                                        foreach ($ex_tags as $ext) {
                                            if (strpos($ext, $url) !== false) {
                                                array_push($testingarr, $string);
                                            } else {
                                                //code

                                            }
                                        }
                                    }
                                }

                                $testingarr = array_unique($testingarr);

                                if ($vararray != null) {
                                    foreach ($testingarr as $pro) {

                                        if ($pro
                                            ->subvariants
                                            ->count() > 0) {
                                            foreach ($pro->subvariants as $sub) {

                                                foreach ($sub->main_attr_value as $key => $main) {
                                                    foreach ($attrarray as $attr) {
                                                        if ($attr == $key) {
                                                            foreach ($vararray as $var) {
                                                                if ($main == $var) {

                                                                    array_push($emarray, $sub);

                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    if (count($attrarray) > 1) {

                                        $array_temp = array();

                                        foreach ($emarray as $val) {
                                            if (!in_array($val, $array_temp)) {
                                                $array_temp[] = $val;
                                            } else {
                                                array_push($a, $val);
                                            }
                                        }
                                    } else {
                                        $a = $emarray;
                                    }

                                    foreach ($a as $b) {
                                        foreach ($testingarr as $p) {
                                            foreach ($p->subvariants as $s) {
                                                if ($s->id == $b->id) {
                                                    array_push($filledpro, $p);
                                                }
                                            }
                                        }
                                    }

                                    $testingarr = $filledpro;

                                } else {
                                    $testingarr;
                                }

                                foreach ($testingarr as $pro) {

                                    if (count($pro->subvariants) > 0) {

                                        $pro_all_tags = explode(',', $pro->tags);
                                        foreach ($pro_all_tags as $t) {
                                            array_push($tags_new, $t);
                                        }
                                    }

                                }

                                $tagsunique = array_unique($tags_new);

                            }
                        } else {

                            unset($testingarr);
                            $testingarr = array();

                            if ($featured == 1) {
                                $strings = $products->where('featured', '=', '1')
                                    ->where('category_id', $catid)->get();
                            } else {
                                $strings = $products->where('category_id', $catid)->get();
                            }

                            foreach ($request->tag as $url) {

                                foreach ($strings as $string) {
                                    $ex_tags = explode(',', $string->tags);

                                    foreach ($ex_tags as $ext) {
                                        if (strpos($ext, $url) !== false) {
                                            array_push($testingarr, $string);
                                        } else {
                                            //code

                                        }
                                    }
                                }
                            }

                            if ($vararray != null) {
                                foreach ($testingarr as $pro) {

                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                            foreach ($testingarr as $pro) {

                                if (count($pro->subvariants) > 0) {
                                    $pro_all_tags = explode(',', $pro->tags);
                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }
                                }

                            }

                            $tagsunique = array_unique($tags_new);
                        }

                    }
                }
            } else if ($starts >= 0 || $ends >= 0 && $starts != null && $ends != null && $starts != '' && $ends != '') {

                if ($request->chid != '') {
                    if ($brand_names != '') {

                        if (is_array($brand_names)) {

                            if ($featured == 1) {
                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                    ->where('grand_id', $chid)->get();
                            } else {
                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('grand_id', $chid)->get();
                            }

                            if ($vararray != null) {

                                foreach ($all_brands_products as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($all_brands_products as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $all_brands_products = $filledpro;

                            } else {
                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('grand_id', $chid)->get();
                            }

                            foreach ($all_brands_products as $pro) {

                                if (count($pro->subvariants) > 0) {
                                    $pro_all_tags = explode(',', $pro->tags);
                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }
                                }

                            }

                            $tagsunique = array_unique($tags_new);

                            $testingarr = $all_brands_products;

                        }
                    } else {

                        if ($vararray != null) {

                            if ($featured == 1) {
                                $tag_products = $products->where('featured', '=', '1')
                                    ->where('grand_id', $chid)->get();
                            } else {
                                $tag_products = $products->where('grand_id', $chid)->get();
                            }

                            foreach ($tag_products as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($attrarray as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($vararray as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($attrarray) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($tag_products as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                        } else {

                            if ($featured == 1) {
                                $tag_products = $products->where('featured', '=', '1')
                                    ->where('grand_id', $chid)->get();
                                $featured_pros = $tag_products;
                            } else {
                                $tag_products = $products->where('grand_id', $chid)->get();
                            }

                        }

                        $allbrands = Brand::all();

                        foreach ($allbrands as $brands) {
                            if (is_array($brands->category_id)) {
                                foreach ($brands->category_id as $brandcategory) {
                                    if ($brandcategory == $catid) {

                                        $sidebarbrands[$brands
                                                ->id] = $brands->name;

                                    }
                                }
                            }
                        }

                        foreach ($tag_products as $pro) {

                            if (count($pro->subvariants) > 0) {

                                $pro_all_tags = explode(',', $pro->tags);
                                foreach ($pro_all_tags as $t) {
                                    array_push($tags_new, $t);
                                }
                            }

                        }

                        $tagsunique = array_unique($tags_new);

                        $getattr = ProductAttributes::all();

                        foreach ($getattr as $attr) {

                            $res = in_array($catid, $attr->cats_id);

                            if ($res == $attr->id) {

                                array_push($variantProduct, $attr);

                            }

                            foreach ($attr->provalues as $item) {
                                array_push($variantProValues, $item);
                            }

                        }

                    }
                } else {
                    if ($request->sid != '') {
                        if ($brand_names != '') {
                            if (is_array($brand_names)) {

                                if ($featured == 1) {
                                    $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                        ->where('child', $sid)->get();
                                } else {
                                    $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('child', $sid)->get();

                                }

                                if ($vararray != null) {

                                    if ($featured == 1) {
                                        $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                            ->where('child', $sid)->get();
                                    } else {
                                        $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('child', $sid)->get();
                                    }

                                    foreach ($all_brands_products as $pro) {
                                        if ($pro
                                            ->subvariants
                                            ->count() > 0) {
                                            foreach ($pro->subvariants as $sub) {

                                                foreach ($sub->main_attr_value as $key => $main) {
                                                    foreach ($attrarray as $attr) {
                                                        if ($attr == $key) {
                                                            foreach ($vararray as $var) {
                                                                if ($main == $var) {

                                                                    array_push($emarray, $sub);

                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    if (count($attrarray) > 1) {

                                        $array_temp = array();

                                        foreach ($emarray as $val) {
                                            if (!in_array($val, $array_temp)) {
                                                $array_temp[] = $val;
                                            } else {
                                                array_push($a, $val);
                                            }
                                        }
                                    } else {
                                        $a = $emarray;
                                    }

                                    foreach ($a as $b) {
                                        foreach ($all_brands_products as $p) {
                                            foreach ($p->subvariants as $s) {
                                                if ($s->id == $b->id) {
                                                    array_push($filledpro, $p);
                                                }
                                            }
                                        }
                                    }

                                    $all_brands_products = $filledpro;

                                } else {
                                    $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('child', $sid)->get();
                                }

                                foreach ($all_brands_products as $pro) {

                                    if (count($pro->subvariants) > 0) {

                                        $pro_all_tags = explode(',', $pro->tags);
                                        foreach ($pro_all_tags as $t) {
                                            array_push($tags_new, $t);
                                        }
                                    }

                                }

                                $tagsunique = array_unique($tags_new);
                                $testingarr = $all_brands_products;

                            }
                        } else {

                            if ($vararray != null) {

                                if ($featured == 1) {
                                    $tag_products = $products->where('featured', '=', '1')
                                        ->where('child', $sid)->get();
                                } else {
                                    $tag_products = $products->where('child', $sid)->get();
                                }

                                foreach ($tag_products as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($tag_products as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                            } else {

                                if ($featured == 1) {
                                    $tag_products = $products->where('featured', '=', '1')
                                        ->where('child', $sid)->get();
                                    $featured_pros = $tag_products;
                                } else {
                                    $tag_products = $products->where('child', $sid)->get();
                                }
                            }

                            $allbrands = Brand::all();

                            foreach ($allbrands as $brands) {
                                if (is_array($brands->category_id)) {
                                    foreach ($brands->category_id as $brandcategory) {
                                        if ($brandcategory == $catid) {

                                            $sidebarbrands[$brands
                                                    ->id] = $brands->name;

                                        }
                                    }
                                }
                            }

                            foreach ($tag_products as $pro) {

                                if (count($pro->subvariants) > 0) {

                                    $pro_all_tags = explode(',', $pro->tags);
                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }
                                }

                            }

                            $tagsunique = array_unique($tags_new);

                            $getattr = ProductAttributes::all();

                            foreach ($getattr as $attr) {

                                $res = in_array($catid, $attr->cats_id);

                                if ($res == $attr->id) {

                                    array_push($variantProduct, $attr);

                                }

                                foreach ($attr->provalues as $item) {
                                    array_push($variantProValues, $item);
                                }

                            }

                        }
                    } else {

                        if ($brand_names != '') {
                            if (is_array($brand_names)) {

                                if ($featured == 1) {
                                    $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                        ->where('category_id', $catid)->get();
                                    $featured_pros = $all_brands_products;
                                } else {
                                    $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();
                                }

                                if ($vararray != null) {

                                    if ($featured == 1) {

                                        $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                            ->where('category_id', $catid)->get();

                                    } else {

                                        $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();

                                    }

                                    foreach ($all_brands_products as $pro) {
                                        if ($pro
                                            ->subvariants
                                            ->count() > 0) {
                                            foreach ($pro->subvariants as $sub) {

                                                foreach ($sub->main_attr_value as $key => $main) {
                                                    foreach ($attrarray as $attr) {
                                                        if ($attr == $key) {
                                                            foreach ($vararray as $var) {
                                                                if ($main == $var) {

                                                                    array_push($emarray, $sub);

                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    if (count($attrarray) > 1) {

                                        $array_temp = array();

                                        foreach ($emarray as $val) {
                                            if (!in_array($val, $array_temp)) {
                                                $array_temp[] = $val;
                                            } else {
                                                array_push($a, $val);
                                            }
                                        }
                                    } else {
                                        $a = $emarray;
                                    }

                                    foreach ($a as $b) {
                                        foreach ($all_brands_products as $p) {
                                            foreach ($p->subvariants as $s) {
                                                if ($s->id == $b->id) {
                                                    array_push($filledpro, $p);
                                                }
                                            }
                                        }
                                    }

                                    $all_brands_products = $filledpro;

                                } else {
                                    $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();
                                }

                                foreach ($all_brands_products as $pro) {

                                    if (count($pro->subvariants) > 0) {

                                        $pro_all_tags = explode(',', $pro->tags);

                                        foreach ($pro_all_tags as $t) {
                                            array_push($tags_new, $t);
                                        }
                                    }

                                }

                                $tagsunique = array_unique($tags_new);
                                $testingarr = $all_brands_products;
                            }
                        } else {

                            if ($vararray != null) {

                                if ($featured == 1) {

                                    $tag_products = $products->where('featured', '=', '1')
                                        ->where('category_id', $catid)->get();

                                } else {

                                    $tag_products = $products->where('category_id', $catid)->get();

                                }

                                foreach ($tag_products as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($attrarray as $attr) {
                                                    if ($attr == $key) {

                                                        foreach ($vararray as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($attrarray) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($tag_products as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                            } else {
                                if ($featured == 1) {

                                    $featured_pros = $products->where('featured', '=', '1')
                                        ->where('category_id', $catid)->get();
                                    $tag_products = $featured_pros;
                                } else {
                                    $tag_products = $products->where('category_id', $catid)->get();
                                }

                            }

                            $getattr = ProductAttributes::all();

                            foreach ($getattr as $attr) {
                                $res = in_array($catid, $attr->cats_id);

                                if ($res == $attr->id) {

                                    array_push($variantProduct, $attr);

                                }

                                foreach ($attr->provalues as $item) {
                                    array_push($variantProValues, $item);
                                }

                            }

                            $allbrands = Brand::all();

                            foreach ($allbrands as $brands) {
                                if (is_array($brands->category_id)) {
                                    foreach ($brands->category_id as $brandcategory) {
                                        if ($brandcategory == $catid) {

                                            $sidebarbrands[$brands
                                                    ->id] = $brands->name;

                                        }
                                    }
                                }
                            }

                            foreach ($tag_products as $pro) {

                                if (count($pro->subvariants) > 0) {

                                    $pro_all_tags = explode(',', $pro->tags);

                                    foreach ($pro_all_tags as $t) {
                                        array_push($tags_new, $t);
                                    }

                                }

                            }

                            $tagsunique = array_unique($tags_new);

                        }
                    }
                }
            } else {
                return "Wrong URL";
            }

            if ($brand_names != '') {

                $products = $testingarr;
                response()->json(array(
                    'product' => $products,
                ));
            } elseif ($testingarr != null) {

                $products = $testingarr;
                response()->json(array(
                    'product' => $products,
                ));
            } elseif ($vararray != null) {

                $products = $filledpro;
                response()->json(array(
                    'product' => $products,
                ));
            } else {

                $products = $tag_products;
                response()->json(array(
                    'product' => $products,
                ));
            }

            $pricing = array();

            if ($products != null && count($products) > 0) {
                foreach ($products as $product) {
                    if ($venderSystem != 1) {

                        if ($product->vender['role_id'] == 'a') {
                            foreach ($product->subvariants as $key => $sub) {
                                $customer_price;
                                $customeroffer_price;
                                $convert_price = 0;
                                $show_price = 0;

                                $commision_setting = CommissionSetting::first();

                                if ($commision_setting->type == "flat") {

                                    $commission_amount = $commision_setting->rate;
                                    if ($commision_setting->p_type == 'f') {

                                        if ($product->tax_r != '') {

                                            $cit = $commission_amount * $product->tax_r / 100;
                                            $totalprice = $product->vender_price + $sub->price + $commission_amount + $cit;
                                            $totalsaleprice = $product->vender_offer_price + $sub->price + $commission_amount + $cit;
                                        } else {
                                            $totalprice = $product->vender_price + $sub->price + $commission_amount;
                                            $totalsaleprice = $product->vender_offer_price + $sub->price + $commission_amount;
                                        }

                                        if (empty($product->vender_offer_price)) {
                                            $customer_price = $totalprice;
                                            $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                            $show_price = $customer_price;

                                        } else {
                                            $customer_price = $totalsaleprice;
                                            $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                            $convert_price = $totalsaleprice == '' ? $totalprice : $totalsaleprice;
                                            $show_price = $totalprice;
                                        }

                                    } else {

                                        $totalprice = ($product->vender_price + $sub->price) * $commission_amount;

                                        $totalsaleprice = ($product->vender_offer_price + $sub->price) * $commission_amount;

                                        $buyerprice = ($product->vender_price + $sub->price) + ($totalprice / 100);

                                        $buyersaleprice = ($product->vender_offer_price + $sub->price) + ($totalsaleprice / 100);

                                        if ($product->vender_offer_price == 0) {
                                            $customer_price = round($buyerprice, 2);
                                            $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                        } else {
                                            $customer_price = round($buyersaleprice, 2);
                                            $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                            $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                            $show_price = $buyerprice;
                                        }

                                    }
                                } else {

                                    $comm = Commission::where('category_id', $product->category_id)
                                        ->first();
                                    if (isset($comm)) {
                                        if ($comm->type == 'f') {

                                            if ($product->tax_r != '') {
                                                $cit = $comm->rate * $product->tax_r / 100;
                                                $totalprice = $product->vender_price + $comm->rate + $sub->price + $cit;
                                                $totalsaleprice = $product->vender_offer_price + $comm->rate + $sub->price + $cit;
                                            } else {
                                                $totalprice = $product->vender_price + $comm->rate + $sub->price;
                                                $totalsaleprice = $product->vender_offer_price + $comm->rate + $sub->price;
                                            }

                                            if (empty($product->vender_offer_price)) {
                                                $customer_price = $totalprice;
                                                $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                                $show_price = $customer_price;

                                            } else {
                                                $customer_price = $totalsaleprice;
                                                $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                                '<strike>' . $totalprice . '</strike>';
                                                $convert_price = $totalsaleprice == '' ? $totalprice : $totalsaleprice;
                                                $show_price = $totalprice;
                                            }

                                        } else {

                                            $commission_amount = $comm->rate;

                                            $totalprice = ($product->vender_price + $sub->price) * $commission_amount;

                                            $totalsaleprice = ($product->vender_offer_price + $sub->price) * $commission_amount;

                                            $buyerprice = ($product->vender_price + $sub->price) + ($totalprice / 100);

                                            $buyersaleprice = ($product->vender_offer_price + $sub->price) + ($totalsaleprice / 100);

                                            if ($product->vender_offer_price == 0) {
                                                $customer_price = round($buyerprice, 2);
                                                $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                            } else {
                                                $customer_price = round($buyersaleprice, 2);
                                                $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                                '<strike>' . round($buyerprice, 2) . '</strike>';
                                                $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                                $show_price = $buyerprice;
                                            }
                                        }
                                    } else {
                                        $commission_amount = 0;

                                        $totalprice = ($product->vender_price + $sub->price) * $commission_amount;

                                        $totalsaleprice = ($product->vender_offer_price + $sub->price) * $commission_amount;

                                        $buyerprice = ($product->vender_price + $sub->price) + ($totalprice / 100);

                                        $buyersaleprice = ($product->vender_offer_price + $sub->price) + ($totalsaleprice / 100);

                                        if ($product->vender_offer_price == 0) {
                                            $customer_price = round($buyerprice, 2);
                                            $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                        } else {
                                            $customer_price = round($buyersaleprice, 2);
                                            $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                            $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                            $show_price = $buyerprice;
                                        }
                                    }
                                }
                                array_push($pricing, $customer_price);
                            }
                        }

                    } else {
                        foreach ($product->subvariants as $key => $sub) {
                            $customer_price;
                            $customeroffer_price;
                            $convert_price = 0;
                            $show_price = 0;

                            $commision_setting = CommissionSetting::first();

                            if ($commision_setting->type == "flat") {

                                $commission_amount = $commision_setting->rate;
                                if ($commision_setting->p_type == 'f') {

                                    if ($product->tax_r != '') {

                                        $cit = $commission_amount * $product->tax_r / 100;
                                        $totalprice = $product->vender_price + $sub->price + $commission_amount + $cit;
                                        $totalsaleprice = $product->vender_offer_price + $sub->price + $commission_amount + $cit;
                                    } else {
                                        $totalprice = $product->vender_price + $sub->price + $commission_amount;
                                        $totalsaleprice = $product->vender_offer_price + $sub->price + $commission_amount;
                                    }

                                    if (empty($product->vender_offer_price)) {
                                        $customer_price = $totalprice;
                                        $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                        $show_price = $customer_price;

                                    } else {
                                        $customer_price = $totalsaleprice;
                                        $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                        $convert_price = $totalsaleprice == '' ? $totalprice : $totalsaleprice;
                                        $show_price = $totalprice;
                                    }

                                } else {

                                    $totalprice = ($product->vender_price + $sub->price) * $commission_amount;

                                    $totalsaleprice = ($product->vender_offer_price + $sub->price) * $commission_amount;

                                    $buyerprice = ($product->vender_price + $sub->price) + ($totalprice / 100);

                                    $buyersaleprice = ($product->vender_offer_price + $sub->price) + ($totalsaleprice / 100);

                                    if ($product->vender_offer_price == 0) {
                                        $customer_price = round($buyerprice, 2);
                                        $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                    } else {
                                        $customer_price = round($buyersaleprice, 2);
                                        $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                        $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                        $show_price = $buyerprice;
                                    }

                                }
                            } else {

                                $comm = Commission::where('category_id', $product->category_id)
                                    ->first();
                                if (isset($comm)) {
                                    if ($comm->type == 'f') {

                                        if ($product->tax_r != '') {
                                            $cit = $comm->rate * $product->tax_r / 100;
                                            $price = $product->vender_price + $comm->rate + $sub->price + $cit;
                                            $offer = $product->vender_offer_price + $comm->rate + $sub->price + $cit;
                                        } else {
                                            $price = $product->vender_price + $comm->rate + $sub->price;
                                            $offer = $product->vender_offer_price + $comm->rate + $sub->price;
                                        }

                                        if (empty($product->vender_offer_price)) {
                                            $customer_price = $price;
                                            $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                            $show_price = $customer_price;

                                        } else {
                                            $customer_price = $totalsaleprice;
                                            $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                            $convert_price = $totalsaleprice == '' ? $totalprice : $totalsaleprice;
                                            $show_price = $totalprice;
                                        }

                                    } else {

                                        $commission_amount = $comm->rate;

                                        $totalprice = ($product->vender_price + $sub->price) * $commission_amount;

                                        $totalsaleprice = ($product->vender_offer_price + $sub->price) * $commission_amount;

                                        $buyerprice = ($product->vender_price + $sub->price) + ($totalprice / 100);

                                        $buyersaleprice = ($product->vender_offer_price + $sub->price) + ($totalsaleprice / 100);

                                        if ($product->vender_offer_price == 0) {
                                            $customer_price = round($buyerprice, 2);
                                            $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                        } else {
                                            $customer_price = round($buyersaleprice, 2);
                                            $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                            '<strike>' . round($buyerprice, 2) . '</strike>';
                                            $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                            $show_price = $buyerprice;
                                        }
                                    }
                                } else {
                                    $commission_amount = 0;

                                    $totalprice = ($product->vender_price + $sub->price) * $commission_amount;

                                    $totalsaleprice = ($product->vender_offer_price + $sub->price) * $commission_amount;

                                    $buyerprice = ($product->vender_price + $sub->price) + ($totalprice / 100);

                                    $buyersaleprice = ($product->vender_offer_price + $sub->price) + ($totalsaleprice / 100);

                                    if ($product->vender_offer_price == 0) {
                                        $customer_price = round($buyerprice, 2);
                                        $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                    } else {
                                        $customer_price = round($buyersaleprice, 2);
                                        $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                        $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                        $show_price = $buyerprice;
                                    }
                                }
                            }
                            array_push($pricing, $customer_price);
                        }
                    }
                }
            }

            if ($pricing != null) {
                $start = min($pricing);
                $end = max($pricing);
            } else {
                $start = $starts;
                $end = $ends;
            }

            $x = array();

            foreach ($products as $key => $p) {
                if ($venderSystem != 1) {

                    if ($p
                        ->vender['role_id'] == 'a') {

                        array_push($x, $p);

                    }

                } else {

                    array_push($x, $p);

                }
            }

            $products = $x;

            $isad = DetailAds::where('position', '=', 'category')->where('linked_id', $catid)->where('status', '=', '1')
                ->first();

            require_once 'price.php';

            $start_price = 1;

            // $products = $this->paginate($products);

            return response()
                ->json(['product' => view('front.cat.product', compact('outofstock', 'ratings', 'start_rat', 'a', 'start_price', 'tag_check', 'brand_names', 'conversion_rate', 'products', 'tags_pro', 'catid', 'sid', 'chid', 'start', 'end', 'starts', 'ends', 'slider'))
                        ->render(), 'variantProValues' => $variantProValues, 'variantProduct' => $variantProduct, 'sidebarbrands' => $sidebarbrands, 'tagsunique' => $tagsunique, 'ad' => View::make('front.filters.ads', compact('isad', 'conversion_rate'))->render()]);

        } else {
            return "Error ! Something went wrong from our side";
        }

    }

    //on load get data
    public function categoryf(Request $request)
    {

        require_once 'price.php';

        $a = array();
        $emarray = array();
        $filledpro = array();

        $start_price = 1;

        $tag_check = $request->tag_check;

        $from = Session::get('previous_cur');
        $to = Session::get('current_cur');
        $cur_change = Session::get('currencyChanged');
        $genral = Genral::first();
        $cur_setting = AutoDetectGeo::first()->enabel_multicurrency;

        if ($cur_change == 'yes') {
            
            $defcurrate = currency(1.00, $from = $from, $to = $to, $format = false);

            $defcurrate = round($defcurrate, 2);

            $starts = $request->start * $defcurrate;
            $ends = $request->end * $defcurrate;

        } else {
            $starts = $request->start;
            $ends = $request->end;
        }

        $catid = $request->category;
        $sid = $request->sid;
        $chid = $request->chid;
        $tag = $request->tag;
        $tags_pro = $request->tag;
        $slider = $request->slider;
        $ratings = $request->ratings;
        $start_rat = $request->start_rat;
        $featured = $request->featured;
        $outofstock = $request->oot;

        if (empty($request->ratings)) {
            $ratings = 0;
            $start_rat = 0;
        }

        if ($request->brands == '') {
            $brand_names = '';
        } else {
            $brand_names = explode(",", $request->brands);
        }

        if ($request->varType == '') {
            $varType = '';
        } else {
            $varType = explode(",", $request->varType);
        }

        if ($request->varValue == '') {
            $varValue = '';
        } else {
            $varValue = explode(",", $request->varValue);
        }

        $products = Product::query();
        $all_brands_products = array();
        $testingarr = array();

        if ($request->keyword != '' && $request->tag == '') {

            $search = $request->keyword;

            if ($starts >= 0 || $ends >= 0 && $starts != null && $ends != null && $starts != '' && $ends != '') {
                //keyword without tag

                if ($request->chid != '') {
                    if ($brand_names != '') {
                        if (is_array($brand_names)) {

                            if ($featured == 1) {
                                $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                    ->where('grand_id', $chid)->get();
                                $testingarr = $all_brands_products;
                            } else {
                                $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('grand_id', $chid)->get();
                                $testingarr = $all_brands_products;
                            }

                            if ($varValue != null) {

                                foreach ($testingarr as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($varType as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($varValue as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($varType) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                        }
                    } else {

                        if ($varValue != null) {

                            if ($featured == 1) {
                                $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('grand_id', $chid)->where('featured', '=', '1')
                                    ->get();
                            } else {
                                $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('grand_id', $chid)->get();
                            }

                            foreach ($tag_products as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($varType as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($varValue as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($varType) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($tag_products as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                        } else {
                            if ($featured == 1) {

                                $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('grand_id', $chid)->where('featured', '1')
                                    ->get();

                            } else {
                                $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('grand_id', $chid)->get();
                            }
                        }

                    }
                } else {
                    if ($request->sid != '') {
                        if ($brand_names != '') {
                            if (is_array($brand_names)) {

                                if ($featured == 1) {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                        ->where('child', $sid)->get();

                                    $testingarr = $all_brands_products;
                                } else {
                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('child', $sid)->get();

                                    $testingarr = $all_brands_products;
                                }

                                if ($varValue != null) {

                                    foreach ($testingarr as $pro) {
                                        if ($pro
                                            ->subvariants
                                            ->count() > 0) {
                                            foreach ($pro->subvariants as $sub) {

                                                foreach ($sub->main_attr_value as $key => $main) {
                                                    foreach ($varType as $attr) {
                                                        if ($attr == $key) {
                                                            foreach ($varValue as $var) {
                                                                if ($main == $var) {

                                                                    array_push($emarray, $sub);

                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    if (count($varType) > 1) {

                                        $array_temp = array();

                                        foreach ($emarray as $val) {
                                            if (!in_array($val, $array_temp)) {
                                                $array_temp[] = $val;
                                            } else {
                                                array_push($a, $val);
                                            }
                                        }
                                    } else {
                                        $a = $emarray;
                                    }

                                    foreach ($a as $b) {
                                        foreach ($testingarr as $p) {
                                            foreach ($p->subvariants as $s) {
                                                if ($s->id == $b->id) {
                                                    array_push($filledpro, $p);
                                                }
                                            }
                                        }
                                    }

                                    $testingarr = $filledpro;

                                } else {
                                    $testingarr;
                                }

                            }
                        } else {

                            if ($varValue != null) {

                                if ($featured == 1) {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('child', $sid)->where('featured', '=', '1')
                                        ->get();
                                } else {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('child', $sid)->get();
                                }

                                foreach ($tag_products as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($varType as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($varValue as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($varType) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($tag_products as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                            } else {

                                if ($featured == 1) {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('child', $sid)->where('featured', '=', "1")
                                        ->get();
                                    $featured_pros = $tag_products;
                                } else {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('child', $sid)->get();
                                }
                            }

                        }
                    } else {

                        if ($brand_names != '') {
                            if (is_array($brand_names)) {

                                if ($featured == 1) {

                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('category_id', $catid)->where('featured', '=', '1')
                                        ->get();
                                    $testingarr = $all_brands_products;

                                } else {

                                    $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();
                                    $testingarr = $all_brands_products;
                                }

                                if ($varValue != null) {

                                    foreach ($testingarr as $pro) {
                                        if ($pro
                                            ->subvariants
                                            ->count() > 0) {
                                            foreach ($pro->subvariants as $sub) {

                                                foreach ($sub->main_attr_value as $key => $main) {
                                                    foreach ($varType as $attr) {
                                                        if ($attr == $key) {
                                                            foreach ($varValue as $var) {
                                                                if ($main == $var) {

                                                                    array_push($emarray, $sub);

                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    if (count($varType) > 1) {

                                        $array_temp = array();

                                        foreach ($emarray as $val) {
                                            if (!in_array($val, $array_temp)) {
                                                $array_temp[] = $val;
                                            } else {
                                                array_push($a, $val);
                                            }
                                        }
                                    } else {
                                        $a = $emarray;
                                    }

                                    foreach ($a as $b) {
                                        foreach ($testingarr as $p) {
                                            foreach ($p->subvariants as $s) {
                                                if ($s->id == $b->id) {
                                                    array_push($filledpro, $p);
                                                }
                                            }
                                        }
                                    }

                                    $testingarr = $filledpro;

                                } else {
                                    $testingarr;
                                }

                            }
                        } else {

                            if ($varValue != null) {

                                if ($featured == 1) {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('featured', '=', '1')
                                        ->where('category_id', $catid)->get();
                                } else {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('category_id', $catid)->get();
                                }

                                foreach ($tag_products as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($varType as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($varValue as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($varType) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($tag_products as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                            } else {

                                if ($featured == 1) {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('category_id', $catid)->where('featured', '=', '1')
                                        ->get();
                                    $featured_pros = $tag_products;
                                } else {
                                    $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('category_id', $catid)->get();
                                }

                            }

                        }

                    }
                }
                //end

            }

        } elseif ($request->keyword != '' && $request->tag != '') {

            $search = $request->keyword;

            if ($request->chid != '') {
                if ($brand_names != '') {

                    unset($testingarr);
                    $testingarr = array();

                    if (is_array($brand_names)) {

                        if ($featured == 1) {

                            $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                ->where('grand_id', $chid)->get();

                        } else {

                            $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('grand_id', $chid)->get();

                        }

                        $all_tags = explode(',', $request->tag);

                        foreach ($all_tags as $url) {

                            foreach ($all_brands_products as $string) {
                                $ex_tags = explode(',', $string->tags);

                                foreach ($ex_tags as $ext) {
                                    if (strpos($ext, $url) !== false) {
                                        array_push($testingarr, $string);
                                    } else {
                                        //code

                                    }
                                }
                            }
                        }

                        $testingarr = array_unique($testingarr);

                        if ($varValue != null) {

                            foreach ($testingarr as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($varType as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($varValue as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($varType) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($testingarr as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                            $testingarr = $filledpro;

                        } else {
                            $testingarr;
                        }

                    }
                } else {
                    unset($testingarr);
                    $testingarr = array();

                    if ($featured == 1) {
                        $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('featured', '=', '1')
                            ->where('grand_id', $request->chid)
                            ->get();
                    } else {
                        $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('grand_id', $request->chid)
                            ->get();
                    }

                    $all_tags = explode(',', $request->tag);

                    foreach ($all_tags as $url) {

                        foreach ($tag_products as $string) {
                            $ex_tags = explode(',', $string->tags);

                            foreach ($ex_tags as $ext) {
                                if (strpos($ext, $url) !== false) {
                                    array_push($testingarr, $string);
                                } else {
                                    //code

                                }
                            }
                        }
                    }

                    $testingarr = array_unique($testingarr);

                    if ($varValue != null) {

                        foreach ($testingarr as $pro) {
                            if ($pro
                                ->subvariants
                                ->count() > 0) {
                                foreach ($pro->subvariants as $sub) {

                                    foreach ($sub->main_attr_value as $key => $main) {
                                        foreach ($varType as $attr) {
                                            if ($attr == $key) {
                                                foreach ($varValue as $var) {
                                                    if ($main == $var) {

                                                        array_push($emarray, $sub);

                                                    }
                                                }
                                            }
                                        }
                                    }

                                }
                            }
                        }

                        if (count($varType) > 1) {

                            $array_temp = array();

                            foreach ($emarray as $val) {
                                if (!in_array($val, $array_temp)) {
                                    $array_temp[] = $val;
                                } else {
                                    array_push($a, $val);
                                }
                            }
                        } else {
                            $a = $emarray;
                        }

                        foreach ($a as $b) {
                            foreach ($testingarr as $p) {
                                foreach ($p->subvariants as $s) {
                                    if ($s->id == $b->id) {
                                        array_push($filledpro, $p);
                                    }
                                }
                            }
                        }

                        $testingarr = $filledpro;

                    } else {
                        $testingarr;
                    }

                }

            } else {
                if ($request->sid != '') {
                    if ($brand_names != '') {
                        if (is_array($brand_names)) {
                            unset($testingarr);
                            $testingarr = array();

                            if ($featured == 1) {
                                $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                    ->where('child', $sid)->get();
                            } else {
                                $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('child', $sid)->get();
                            }

                            $all_tags = explode(',', $request->tag);

                            foreach ($all_tags as $url) {

                                foreach ($all_brands_products as $string) {
                                    $ex_tags = explode(',', $string->tags);

                                    foreach ($ex_tags as $ext) {
                                        if (strpos($ext, $url) !== false) {
                                            array_push($testingarr, $string);
                                        } else {
                                            //code

                                        }
                                    }
                                }
                            }

                            $testingarr = array_unique($testingarr);

                            if ($varValue != null) {

                                foreach ($testingarr as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($varType as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($varValue as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($varType) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                        }
                    } else {
                        unset($testingarr);
                        $testingarr = array();

                        if ($featured == 1) {
                            $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('child', $sid)->where('featured', '=', '1')
                                ->get();
                        } else {
                            $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('child', $sid)->get();
                        }

                        $all_tags = explode(',', $request->tag);

                        foreach ($all_tags as $url) {

                            foreach ($tag_products as $string) {
                                $ex_tags = explode(',', $string->tags);

                                foreach ($ex_tags as $ext) {
                                    if (strpos($ext, $url) !== false) {
                                        array_push($testingarr, $string);
                                    } else {
                                        //code

                                    }
                                }
                            }
                        }

                        $testingarr = array_unique($testingarr);

                        if ($varValue != null) {

                            foreach ($testingarr as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($varType as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($varValue as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($varType) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($testingarr as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                            $testingarr = $filledpro;

                        } else {
                            $testingarr;
                        }

                    }

                } else {
                    if ($brand_names != '') {

                        unset($testingarr);
                        $testingarr = array();

                        if (is_array($brand_names)) {

                            if ($featured == 1) {
                                $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('category_id', $catid)->where('featured', '=', '1')
                                    ->get();
                            } else {
                                $all_brands_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();
                            }

                            $all_tags = explode(',', $request->tag);
                            foreach ($all_tags as $url) {

                                foreach ($all_brands_products as $string) {
                                    $ex_tags = explode(',', $string->tags);

                                    foreach ($ex_tags as $ext) {
                                        if (strpos($ext, $url) !== false) {
                                            array_push($testingarr, $string);
                                        } else {
                                            //code

                                        }
                                    }
                                }
                            }

                            $testingarr = array_unique($testingarr);

                            if ($varValue != null) {

                                foreach ($testingarr as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($varType as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($varValue as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($varType) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                        }
                    } else {

                        unset($testingarr);
                        $testingarr = array();

                        if ($featured == 1) {
                            $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('featured', '=', "1")
                                ->where('category_id', '=', $catid)->get();
                        } else {
                            $tag_products = $products->where('tags', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%')->where('category_id', $catid)->get();
                        }

                        $all_tags = explode(',', $request->tag);

                        foreach ($all_tags as $url) {

                            foreach ($tag_products as $string) {
                                $ex_tags = explode(',', $string->tags);

                                foreach ($ex_tags as $ext) {
                                    if (strpos($ext, $url) !== false) {
                                        array_push($testingarr, $string);
                                    } else {
                                        //code

                                    }
                                }
                            }
                        }

                        $testingarr = array_unique($testingarr);

                        if ($varValue != null) {

                            foreach ($testingarr as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($varType as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($varValue as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($varType) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($testingarr as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                            $testingarr = $filledpro;

                        } else {
                            $testingarr;
                        }

                    }

                }
            }

            //keyword with tag
            //end

        } elseif ($request->tag != '') {

            if ($request->chid != '') {
                if ($brand_names != '') {

                    unset($testingarr);
                    $testingarr = array();

                    if (is_array($brand_names)) {

                        if ($featured == 1) {

                            $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                ->where('grand_id', $chid)->get();

                        } else {

                            $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('grand_id', $chid)->get();

                        }

                        $all_tags = explode(',', $request->tag);

                        foreach ($all_tags as $url) {

                            foreach ($all_brands_products as $string) {
                                $ex_tags = explode(',', $string->tags);

                                foreach ($ex_tags as $ext) {
                                    if (strpos($ext, $url) !== false) { // Yoshi version
                                        array_push($testingarr, $string);
                                    } else {
                                        //code

                                    }
                                }
                            }
                        }

                        $testingarr = array_unique($testingarr);

                        if ($varValue != null) {

                            foreach ($testingarr as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($varType as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($varValue as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($varType) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($testingarr as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                            $testingarr = $filledpro;

                        } else {
                            $testingarr;
                        }

                    }
                } else {
                    unset($testingarr);
                    $testingarr = array();

                    if ($featured == 1) {
                        $tag_products = $products->where('featured', '=', '1')
                            ->where('grand_id', $request->chid)
                            ->get();
                    } else {
                        $tag_products = $products->where('grand_id', $request->chid)
                            ->get();
                    }

                    $all_tags = explode(',', $request->tag);

                    foreach ($all_tags as $url) {

                        foreach ($tag_products as $string) {
                            $ex_tags = explode(',', $string->tags);

                            foreach ($ex_tags as $ext) {
                                if (strpos($ext, $url) !== false) {
                                    array_push($testingarr, $string);
                                } else {
                                    //code

                                }
                            }
                        }
                    }

                    $testingarr = array_unique($testingarr);

                    if ($varValue != null) {

                        foreach ($testingarr as $pro) {
                            if ($pro
                                ->subvariants
                                ->count() > 0) {
                                foreach ($pro->subvariants as $sub) {

                                    foreach ($sub->main_attr_value as $key => $main) {
                                        foreach ($varType as $attr) {
                                            if ($attr == $key) {
                                                foreach ($varValue as $var) {
                                                    if ($main == $var) {

                                                        array_push($emarray, $sub);

                                                    }
                                                }
                                            }
                                        }
                                    }

                                }
                            }
                        }

                        if (count($varType) > 1) {

                            $array_temp = array();

                            foreach ($emarray as $val) {
                                if (!in_array($val, $array_temp)) {
                                    $array_temp[] = $val;
                                } else {
                                    array_push($a, $val);
                                }
                            }
                        } else {
                            $a = $emarray;
                        }

                        foreach ($a as $b) {
                            foreach ($testingarr as $p) {
                                foreach ($p->subvariants as $s) {
                                    if ($s->id == $b->id) {
                                        array_push($filledpro, $p);
                                    }
                                }
                            }
                        }

                        $testingarr = $filledpro;

                    } else {
                        $testingarr;
                    }

                }

            } else {
                if ($request->sid != '') {
                    if ($brand_names != '') {
                        if (is_array($brand_names)) {
                            unset($testingarr);
                            $testingarr = array();

                            if ($featured == 1) {
                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                    ->where('child', $sid)->get();
                            } else {
                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('child', $sid)->get();
                            }

                            $all_tags = explode(',', $request->tag);

                            foreach ($all_tags as $url) {

                                foreach ($all_brands_products as $string) {
                                    $ex_tags = explode(',', $string->tags);

                                    foreach ($ex_tags as $ext) {
                                        if (strpos($ext, $url) !== false) {
                                            array_push($testingarr, $string);
                                        } else {
                                            //code

                                        }
                                    }
                                }
                            }

                            $testingarr = array_unique($testingarr);

                            if ($varValue != null) {

                                foreach ($testingarr as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($varType as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($varValue as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($varType) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                        }
                    } else {
                        unset($testingarr);
                        $testingarr = array();

                        if ($featured == 1) {
                            $tag_products = $products->where('child', $sid)->where('featured', '=', '1')
                                ->get();
                        } else {
                            $tag_products = $products->where('child', $sid)->get();
                        }

                        $all_tags = explode(',', $request->tag);

                        foreach ($all_tags as $url) {

                            foreach ($tag_products as $string) {
                                $ex_tags = explode(',', $string->tags);

                                foreach ($ex_tags as $ext) {
                                    if (strpos($ext, $url) !== false) {
                                        array_push($testingarr, $string);
                                    } else {
                                        //code

                                    }
                                }
                            }
                        }

                        $testingarr = array_unique($testingarr);

                        if ($varValue != null) {

                            foreach ($testingarr as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($varType as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($varValue as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($varType) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($testingarr as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                            $testingarr = $filledpro;

                        } else {
                            $testingarr;
                        }

                    }

                } else {
                    if ($brand_names != '') {

                        unset($testingarr);
                        $testingarr = array();

                        if (is_array($brand_names)) {

                            if ($featured == 1) {
                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('category_id', $catid)->where('featured', '=', '1')
                                    ->get();
                            } else {
                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();
                            }

                            $all_tags = explode(',', $request->tag);
                            foreach ($all_tags as $url) {

                                foreach ($all_brands_products as $string) {
                                    $ex_tags = explode(',', $string->tags);

                                    foreach ($ex_tags as $ext) {
                                        if (strpos($ext, $url) !== false) {
                                            array_push($testingarr, $string);
                                        } else {
                                            //code

                                        }
                                    }
                                }
                            }

                            $testingarr = array_unique($testingarr);

                            if ($varValue != null) {

                                foreach ($testingarr as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($varType as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($varValue as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($varType) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                        }
                    } else {

                        unset($testingarr);
                        $testingarr = array();

                        if ($featured == 1) {
                            $tag_products = $products->where('featured', '=', "1")
                                ->where('category_id', '=', $catid)->get();
                        } else {
                            $tag_products = $products->where('category_id', $catid)->get();
                        }

                        $all_tags = explode(',', $request->tag);

                        foreach ($all_tags as $url) {

                            foreach ($tag_products as $string) {
                                $ex_tags = explode(',', $string->tags);

                                foreach ($ex_tags as $ext) {
                                    if (strpos($ext, $url) !== false) {
                                        array_push($testingarr, $string);
                                    } else {
                                        //code

                                    }
                                }
                            }
                        }

                        $testingarr = array_unique($testingarr);

                        if ($varValue != null) {

                            foreach ($testingarr as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($varType as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($varValue as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($varType) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($testingarr as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                            $testingarr = $filledpro;

                        } else {
                            $testingarr;
                        }

                    }

                }
            }
        } else if ($starts >= 0 || $ends >= 0 && $starts != null && $ends != null && $starts != '' && $ends != '') {

            if ($request->chid != '') {
                if ($brand_names != '') {
                    if (is_array($brand_names)) {

                        if ($featured == 1) {
                            $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                ->where('grand_id', $chid)->get();
                            $testingarr = $all_brands_products;
                        } else {
                            $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('grand_id', $chid)->get();
                            $testingarr = $all_brands_products;
                        }

                        if ($varValue != null) {

                            foreach ($testingarr as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($varType as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($varValue as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($varType) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($testingarr as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                            $testingarr = $filledpro;

                        } else {
                            $testingarr;
                        }

                    }
                } else {

                    if ($varValue != null) {

                        if ($featured == 1) {
                            $tag_products = $products->where('grand_id', $chid)->where('featured', '=', '1')
                                ->get();
                        } else {
                            $tag_products = $products->where('grand_id', $chid)->get();
                        }

                        foreach ($tag_products as $pro) {
                            if ($pro
                                ->subvariants
                                ->count() > 0) {
                                foreach ($pro->subvariants as $sub) {

                                    foreach ($sub->main_attr_value as $key => $main) {
                                        foreach ($varType as $attr) {
                                            if ($attr == $key) {
                                                foreach ($varValue as $var) {
                                                    if ($main == $var) {

                                                        array_push($emarray, $sub);

                                                    }
                                                }
                                            }
                                        }
                                    }

                                }
                            }
                        }

                        if (count($varType) > 1) {

                            $array_temp = array();

                            foreach ($emarray as $val) {
                                if (!in_array($val, $array_temp)) {
                                    $array_temp[] = $val;
                                } else {
                                    array_push($a, $val);
                                }
                            }
                        } else {
                            $a = $emarray;
                        }

                        foreach ($a as $b) {
                            foreach ($tag_products as $p) {
                                foreach ($p->subvariants as $s) {
                                    if ($s->id == $b->id) {
                                        array_push($filledpro, $p);
                                    }
                                }
                            }
                        }

                    } else {
                        if ($featured == 1) {
                            $tag_products = $products->where('grand_id', $chid)->where('featured', '1')
                                ->get();
                            $featured_pros = $tag_products;
                        } else {
                            $tag_products = $products->where('grand_id', $chid)->get();
                        }
                    }

                }
            } else {
                if ($request->sid != '') {
                    if ($brand_names != '') {
                        if (is_array($brand_names)) {

                            if ($featured == 1) {
                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('featured', '=', '1')
                                    ->where('child', $sid)->get();

                                $testingarr = $all_brands_products;
                            } else {
                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('child', $sid)->get();

                                $testingarr = $all_brands_products;
                            }

                            if ($varValue != null) {

                                foreach ($testingarr as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($varType as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($varValue as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($varType) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                        }
                    } else {

                        if ($varValue != null) {

                            if ($featured == 1) {
                                $tag_products = $products->where('child', $sid)->where('featured', '=', '1')
                                    ->get();
                            } else {
                                $tag_products = $products->where('child', $sid)->get();
                            }

                            foreach ($tag_products as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($varType as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($varValue as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($varType) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($tag_products as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                        } else {

                            if ($featured == 1) {
                                $tag_products = $products->where('child', $sid)->where('featured', '=', "1")
                                    ->get();
                                $featured_pros = $tag_products;
                            } else {
                                $tag_products = $products->where('child', $sid)->get();
                            }
                        }

                    }
                } else {

                    if ($brand_names != '') {
                        if (is_array($brand_names)) {

                            if ($featured == 1) {

                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('category_id', $catid)->where('featured', '=', '1')
                                    ->get();
                                $testingarr = $all_brands_products;

                            } else {

                                $all_brands_products = $products->whereIn('brand_id', $brand_names)->where('category_id', $catid)->get();
                                $testingarr = $all_brands_products;
                            }

                            if ($varValue != null) {

                                foreach ($testingarr as $pro) {
                                    if ($pro
                                        ->subvariants
                                        ->count() > 0) {
                                        foreach ($pro->subvariants as $sub) {

                                            foreach ($sub->main_attr_value as $key => $main) {
                                                foreach ($varType as $attr) {
                                                    if ($attr == $key) {
                                                        foreach ($varValue as $var) {
                                                            if ($main == $var) {

                                                                array_push($emarray, $sub);

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }

                                if (count($varType) > 1) {

                                    $array_temp = array();

                                    foreach ($emarray as $val) {
                                        if (!in_array($val, $array_temp)) {
                                            $array_temp[] = $val;
                                        } else {
                                            array_push($a, $val);
                                        }
                                    }
                                } else {
                                    $a = $emarray;
                                }

                                foreach ($a as $b) {
                                    foreach ($testingarr as $p) {
                                        foreach ($p->subvariants as $s) {
                                            if ($s->id == $b->id) {
                                                array_push($filledpro, $p);
                                            }
                                        }
                                    }
                                }

                                $testingarr = $filledpro;

                            } else {
                                $testingarr;
                            }

                        }
                    } else {

                        if ($varValue != null) {

                            if ($featured == 1) {
                                $tag_products = $products->where('featured', '=', '1')
                                    ->where('category_id', $catid)->get();
                            } else {
                                $tag_products = $products->where('category_id', $catid)->get();
                            }

                            foreach ($tag_products as $pro) {
                                if ($pro
                                    ->subvariants
                                    ->count() > 0) {
                                    foreach ($pro->subvariants as $sub) {

                                        foreach ($sub->main_attr_value as $key => $main) {
                                            foreach ($varType as $attr) {
                                                if ($attr == $key) {
                                                    foreach ($varValue as $var) {
                                                        if ($main == $var) {

                                                            array_push($emarray, $sub);

                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }

                            if (count($varType) > 1) {

                                $array_temp = array();

                                foreach ($emarray as $val) {
                                    if (!in_array($val, $array_temp)) {
                                        $array_temp[] = $val;
                                    } else {
                                        array_push($a, $val);
                                    }
                                }
                            } else {
                                $a = $emarray;
                            }

                            foreach ($a as $b) {
                                foreach ($tag_products as $p) {
                                    foreach ($p->subvariants as $s) {
                                        if ($s->id == $b->id) {
                                            array_push($filledpro, $p);
                                        }
                                    }
                                }
                            }

                        } else {

                            if ($featured == 1) {
                                $tag_products = $products->where('category_id', $catid)->where('featured', '=', '1')
                                    ->get();
                                $featured_pros = $tag_products;
                            } else {
                                $tag_products = $products->where('category_id', $catid)->get();
                            }

                        }

                    }

                }
            }
        } else {

        }

        if ($brand_names != "") {
            $products = $testingarr;
            response()->json(array(
                'product' => $products,
            ));
        } elseif ($varValue != null) {
            $products = $filledpro;
            response()->json(array(
                'product' => $products,
            ));
        } elseif ($testingarr != null) {
            $products = $testingarr;
        } elseif ($featured != 0) {

            $products = $featured_pros;
        } else {
            $products = $products->get();
            response()
                ->json(array(
                    'product' => $products,
                ));
        }

        $pricing = array();

        if ($products != null && count($products) > 0) {
            foreach ($products as $product) {
                foreach ($product->subvariants as $key => $sub) {
                    $customer_price = 0;
                    $customeroffer_price = 0;
                    $convert_price = 0;
                    $show_price = 0;
                    $commission_amount = 0;

                    $commision_setting = CommissionSetting::first();

                    if ($commision_setting->type == "flat") {

                        $commission_amount = $commision_setting->rate;
                        if ($commision_setting->p_type == 'f') {

                            if ($product->tax_r != '') {
                                $cit = $commission_amount * $product->tax_r / 100;
                                $totalprice = $product->vender_price + $sub->price + $commission_amount + $cit;
                                $totalsaleprice = $product->vender_offer_price + $sub->price + $commission_amount + $cit;
                            } else {
                                $totalprice = $product->vender_price + $sub->price + $commission_amount;
                                $totalsaleprice = $product->vender_offer_price + $sub->price + $commission_amount;
                            }

                            if (empty($product->vender_offer_price)) {
                                $customer_price = $totalprice;
                                $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                $show_price = $customer_price;

                            } else {
                                $customer_price = $totalsaleprice;
                                $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                $convert_price = $totalsaleprice == '' ? $totalprice : $totalsaleprice;
                                $show_price = $totalprice;
                            }

                        } else {

                            $totalprice = ($product->vender_price + $sub->price) * $commission_amount;

                            $totalsaleprice = ($product->vender_offer_price + $sub->price) * $commission_amount;

                            $buyerprice = ($product->vender_price + $sub->price) + ($totalprice / 100);

                            $buyersaleprice = ($product->vender_offer_price + $sub->price) + ($totalsaleprice / 100);

                            if ($product->vender_offer_price == 0) {
                                $customer_price = round($buyerprice, 2);
                                $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                            } else {
                                $customer_price = round($buyersaleprice, 2);
                                $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                $show_price = $buyerprice;
                            }

                        }
                    } else {

                        $comm = Commission::where('category_id', $product->category_id)->first();

                        if (isset($comm)) {
                            if ($comm->type == 'f') {

                                if ($product->tax_r != '') {
                                    $cit = $comm->rate * $product->tax_r / 100;
                                    $totalprice = $product->vender_price + $comm->rate + $sub->price + $cit;
                                    $totalsaleprice = $product->vender_offer_price + $comm->rate + $sub->price + $cit;
                                } else {
                                    $totalprice = $product->vender_price + $comm->rate + $sub->price;
                                    $totalsaleprice = $product->vender_offer_price + $comm->rate + $sub->price;
                                }

                                if (empty($product->vender_offer_price)) {
                                    $customer_price = $totalprice;
                                    $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                    $show_price = $customer_price;

                                } else {
                                    $customer_price = $totalsaleprice;
                                    $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                    $convert_price = $totalsaleprice == '' ? $totalprice : $totalsaleprice;
                                    $show_price = $totalprice;
                                }

                            } else {

                                $commission_amount = $comm->rate;

                                $totalprice = ($product->vender_price + $sub->price) * $commission_amount;

                                $totalsaleprice = ($product->vender_offer_price + $sub->price) * $commission_amount;

                                $buyerprice = ($product->vender_price + $sub->price) + ($totalprice / 100);

                                $buyersaleprice = ($product->vender_offer_price + $sub->price) + ($totalsaleprice / 100);

                                if ($product->vender_offer_price == 0) {
                                    $customer_price = round($buyerprice, 2);
                                    $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                } else {
                                    $customer_price = round($buyersaleprice, 2);
                                    $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                    $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                    $show_price = $buyerprice;
                                }
                            }
                        } else {
                            $commission_amount = 0;

                            $totalprice = ($product->vender_price + $sub->price) * $commission_amount;

                            $totalsaleprice = ($product->vender_offer_price + $sub->price) * $commission_amount;

                            $buyerprice = ($product->vender_price + $sub->price) + ($totalprice / 100);

                            $buyersaleprice = ($product->vender_offer_price + $sub->price) + ($totalsaleprice / 100);

                            if ($product->vender_offer_price == 0) {
                                $customer_price = round($buyerprice, 2);
                                $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                            } else {
                                $customer_price = round($buyersaleprice, 2);
                                $customer_price = round($customer_price * round($conversion_rate, 4), 2);
                                $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                                $show_price = $buyerprice;
                            }
                        }
                    }
                    array_push($pricing, $customer_price);
                }
            }
        }

        if ($pricing != null) {
            $start = min($pricing);
            $end = max($pricing);
        } else {
            $start = $starts;
            $end = $ends;
        }

        return view('front.filters.category', compact('outofstock', 'ratings', 'start_rat', 'a', 'start_price', 'tag_check', 'brand_names', 'conversion_rate', 'products', 'catid', 'sid', 'chid', 'start', 'end', 'starts', 'ends', 'tag', 'tags_pro', 'slider'));
    }

    public function paginate($items, $perPage = 3, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function brandfilter(Request $request)
    {
        $allbrands = Brand::all();
        $catid = $request->categoryId;
        $brandname = $request->brand;
        $search_brands = array();
        $keywordbrands = Brand::where('name', 'LIKE', '%' . $brandname . '%')->select('id', 'name', 'category_id')
            ->get();
        if ($brandname == '') {
            foreach ($allbrands as $key => $brands) {
                if (is_array($brands->category_id)) {

                    foreach ($brands->category_id as $brandcategory) {

                        if ($brandcategory == $catid) {
                            array_push($search_brands, $brands);
                        }
                    }
                }
            }
        } else {
            foreach ($keywordbrands as $key => $brands) {
                if (is_array($brands->category_id)) {

                    foreach ($brands->category_id as $brandcategory) {
                        if ($brandcategory == $catid) {
                            array_push($search_brands, $brands);
                        }
                    }
                }
            }
        }

        return response()->json($search_brands);

    }

    public function variantfilter(Request $request)
    {
        $catid = $request->catID;
        $vararray = $request->variantArray;
        $attrarray = $request->attrArray;
        $emarray = array();
        $productArray = array();
        $uniqarray = array();

        $getpro = Product::where('category_id', $catid)->get();
        if (isset($vararray)) {
            foreach ($getpro as $pro) {
                if ($pro
                    ->subvariants
                    ->count() > 0) {
                    foreach ($pro->subvariants as $sub) {

                        foreach ($sub->main_attr_value as $key => $main) {
                            foreach ($attrarray as $attr) {
                                if ($attr == $key) {
                                    foreach ($vararray as $var) {
                                        if ($main == $var) {

                                            array_push($emarray, $pro);

                                        }
                                    }
                                }
                            }
                        }

                    }
                }
            }

            $a = array();
            if (count($attrarray) > 1) {

                $array_temp = array();

                foreach ($emarray as $val) {
                    if (!in_array($val, $array_temp)) {
                        $array_temp[] = $val;
                    } else {
                        array_push($a, $val);
                    }
                }
            } else {
                $a = $emarray;
            }

            return $a;
            return $productArray;
        } else {
            echo "Nothing Selected";
        }

    }

}
