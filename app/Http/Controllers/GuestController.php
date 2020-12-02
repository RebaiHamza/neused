<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Allcountry;
use App\Allstate;
use App\Allcity;
use Session;
use Crypt;
use Response;
use App\User;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;
use App\Faq;
use App\Wishlist;
use Redirect;
use Illuminate\Support\MessageBag;
use App\Cart;
use App\Product;
use App\AddSubVariant;
use Hash;
use App\Page;
use App\Country;
use App\Store;
use Image;

class GuestController extends Controller
{

    public function offlineview()
    {
        return view('offline');
    }

    public function adminLoginAs($id){
        
        $userid = Crypt::decrypt($id);
        $user = User::find($userid);

        if(isset($user)){
            Auth::login($user);
            notify()->success('Logged in as '.Auth::user()->name);
            return redirect('/');
        }else{
            return back()->with('warning','404 User Not found !');
        }
    }

    public function changelang(Request $request)
    {
        Session::put('changed_language', $request->lang);
    }

    public function sellerloginview()
    {
        return view('seller.login');
    }

    public function sellerregisterview()
    {
        require_once 'price.php';
        $country = Country::all();
        return view('seller.register', compact('country', 'conversion_rate'));
    }

    public function dosellerlogin(Request $request)
    {
        if (Auth::attempt(['email' => $request->get('email') , 'password' => $request->get('password') ,

        ], $request->remember))
        {
            if (Auth::user()->role_id == 'v')
            {
                return redirect()
                    ->intended(route('seller.dboard'));
            }
            else if(Auth::user()->role_id == 'om'){
                return redirect()
                    ->intended(route('seller.orders'));
            }
            else if(Auth::user()->role_id == 'pm'){
                return redirect()
                    ->intended(route('seller.orders'));
            }
            else
            {
                Auth::logout();
                notify()
                    ->error('Access Denied !');
                return Redirect::back();
            }
        }
        else
        {
            $errors = new MessageBag(['email' => ['Email or password is invalid.']]);
            return Redirect::back()->withErrors($errors)->withInput($request->except('password'));
            return Redirect::back();
        }
    }

    public function dosellerregister(Request $request)
    {
        $data = $this->validate($request,[

            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'mobile' => 'numeric|unique:users,mobile',
            'storename'=>"required",
            'address'=>"required",
            'country_id' => 'required|not_in:0',
            'state_id' => 'required|not_in:0',
            'city_id' => 'required|not_in:0',
            'store_logo' => 'required | max:1000',


        ], [
            'mobile.unique' => 'Mobile number is already taken !',
            'mobile.numeric' => 'Mobile number should be numeric !',
            "storename.required"=>"Store Name is Required",
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'mobile' => $request['mobile'],
            'role_id' => 'v',
            'password' => Hash::make($request['password']),
            'is_verified' => 1,
        ]);


        $input = $request->all();
        
    
         $image = $request->file('store_logo');
         $optimizeImage = Image::make($image);
         $optimizePath = public_path().'/images/store/';
         $store_logo = time().$image->getClientOriginalName();
         $optimizeImage->resize(300, 300, function ($constraint) {
           $constraint->aspectRatio();
         });
         $optimizeImage->save($optimizePath.$store_logo, 72);

         $input['store_logo'] = $store_logo;
    

            $register = $request->file('register');
            $path = 'files/register/';
            $filename = time().'.'.$register->getClientOriginalExtension();
            $register->move($path, $filename);
            $input['register'] = $filename;
   

            $patent = $request->file('patent');
            $path = 'files/patent/';
            $filename = time().'.'.$patent->getClientOriginalExtension();
            $patent->move($path, $filename);
            $input['patent'] = $filename;

            if($request['new_type'] == 1){
                $new_type = 1;
            }else{
                $new_type = 0;
            }

            if($request['ticket_type'] == 1){
                $ticket_type = 1; 
            }else{
                $ticket_type = 0;
            }

            if($request['bid_type'] == 1){
                $bid_type = 1;
            }else{
                $bid_type = 0;
            }

       $inputstore = ([
        'user_id' => $user->id,
        'name' => $request['storename'],
        'address' => $request['address'],
        'mobile' => $request['mobile'],
        'email' => $request['email'],
        'city_id' => $request['city_id'],
        'country_id' => $request['country_id'],
        'state_id' => $request['state_id'],
        'pin_code' => $request['pin_code'],
        'status' => '0',
        'new_seller' => $new_type,
        'ticket_seller' => $ticket_type,
        'bid_seller' => $bid_type,
        'verified_store' => $request['verified_store'],
        'store_logo' => $request['store_logo'],
        'branch' => $request['branch'],
        'ifsc' => $request['ifsc'],
        'account' => $request['account'],
        'account_name' => $request['account_name'],
        'bank_name' => $request['bank_name'],
        'preferd' => $request['preferd'],
        'name1' => $request['name1'],
        'otherStores' => $request['otherStores'],
        'register' => $input['register'],
        'patent' => $input['patent'],
        'verified_store' => '0',
        ]);

        $data = Store::create($inputstore);
       
        $data->save();
        Auth::logout($user);
        return redirect('/');
    }

    public function register($file){
        return response()->download('files/register/'.$file);
    }

    public function patent($file){
        return response()->download('files/patent/'.$file);
    }

    public function referfromcheckoutwindow(Request $request)
    {
        require_once ('price.php');
        return view('front.referfromchwindow', compact('conversion_rate'));
    }

    public function adminLogin(Request $request)
    {

        if (Auth::attempt(['email' => $request->get('email') , 'password' => $request->get('password') ,

        ], $request->remember))
        {
            if (Auth::user()->role_id == 'a')
            {
                return redirect()
                    ->intended(route('admin.main'));
            }
            else
            {
                Auth::logout();
                notify()
                    ->error('Access Denied !');
                return Redirect::back();
            }
        }
        else
        {
            $errors = new MessageBag(['email' => ['Email or password is invalid.']]);
            return Redirect::back()->withErrors($errors)->withInput($request->except('password'));
            return Redirect::back();
        }
    }

    public function storereferfromcheckoutwindow(Request $request)
    {

        $request->validate(['name' => 'required', 'email' => 'email|required', 'mobile' => 'required', 'password' => 'required|min:6|max:50|confirmed', 'country_id' => 'required', 'state_id' => 'required', 'city_id' => 'required']);

        $newGuest = new User;

        $newGuest->name = $request->name;
        $newGuest->email = $request->email;
        $newGuest->mobile = $request->mobile;
        $newGuest->password = Hash::make($request->password);
        $newGuest->status = 1;
        $newGuest->country_id = $request->country_id;
        $newGuest->state_id = $request->state_id;
        $newGuest->city_id = $request->city_id;
        $newGuest->save();

        Auth::login($newGuest);

        if (Session::has('cart'))
        {

            foreach (Session::get('cart') as $key => $c)
            {

                $venderid = Product::findorFail($c['pro_id']);

                $cart = new Cart;
                $cart->user_id = Auth::user()->id;
                $cart->qty = $c['qty'];
                $cart->pro_id = $c['pro_id'];
                $cart->variant_id = $c['variantid'];
                $cart->ori_price = $c['varprice'];
                $cart->ori_offer_price = $c['varofferprice'];
                $cart->semi_total = $c['qty'] * $c['varofferprice'];
                $cart->price_total = $c['qty'] * $c['varprice'];
                $cart->vender_id = $venderid->vender_id;
                $cart->disamount = $c['discount'];
                $cart->distype = $c['distype'];
                $cart->save();
            }

        }

        Session::forget('cart');

        return redirect('/checkout')
            ->with('success', 'Create address to continue !');
    }

    public function guestregister(Request $request)
    {

        $request->validate(['name' => 'required|min:1', 'email' => 'email|required']);

        $newGuest = new User;

        $newGuest->name = $request->name;
        $newGuest->email = $request->email;
        $newGuest->password = Hash::make(str_random(8));
        $newGuest->status = 1;
        $newGuest->save();

        Auth::login($newGuest);

        if (Session::has('cart'))
        {

            foreach (Session::get('cart') as $key => $c)
            {

                $venderid = Product::findorFail($c['pro_id']);

                $cart = new Cart;
                $cart->user_id = Auth::user()->id;
                $cart->qty = $c['qty'];
                $cart->pro_id = $c['pro_id'];
                $cart->variant_id = $c['variantid'];
                $cart->ori_price = $c['varprice'];
                $cart->ori_offer_price = $c['varofferprice'];
                $cart->semi_total = $c['qty'] * $c['varofferprice'];
                $cart->price_total = $c['qty'] * $c['varprice'];
                $cart->vender_id = $venderid->vender_id;
                $cart->disamount = $c['discount'];
                $cart->distype = $c['distype'];
                $cart->save();
            }

        }

        Session::forget('cart');

        return redirect('/checkout')
            ->with('success', 'Create address to continue !');
    }

    public function cartlogin(Request $request)
    {

        //do login and send cart item to db cart
        if (Auth::attempt(array(
            'email' => $request->get('email') ,
            'password' => $request->get('password')
        )))
        {

            session(['email' => $request->get('email') ]);

            if (!empty(Session::get('cart')))
            {

                $Incart = Auth::user()->cart;
                $SessionCart = Session::get('cart');

                foreach (Session::get('cart') as $key => $c)
                {

                    $venderid = Product::findorFail($c['pro_id']);

                    if (count(Auth::user()->cart) > 0)
                    {

                        $x = Cart::where('variant_id', $SessionCart[$key]['variantid'])->first();

                        if (isset($x))
                        {

                            $findvar = AddSubVariant::find($c['variantid']);

                            if ($findvar->max_order_qty == '')
                            {

                                if ($findvar->stock > 0)
                                {

                                    $newqty = $x->qty + $c['qty'];
                                    $newofferprice = $c['qty'] * $c['varofferprice'];
                                    $newprice = $c['qty'] * $c['varprice'];

                                    Cart::where('user_id', Auth::user()->id)
                                        ->where('variant_id', $c['variantid'])->update(['qty' => $newqty, 'semi_total' => $newofferprice, 'price_total' => $newprice]);

                                }

                            }

                        }
                        else
                        {

                            $cart = new Cart;
                            $cart->user_id = Auth::user()->id;
                            $cart->qty = $c['qty'];
                            $cart->pro_id = $c['pro_id'];
                            $cart->variant_id = $c['variantid'];
                            $cart->ori_price = $c['varprice'];
                            $cart->ori_offer_price = $c['varofferprice'];
                            $cart->semi_total = $c['qty'] * $c['varofferprice'];
                            $cart->price_total = $c['qty'] * $c['varprice'];
                            $cart->vender_id = $venderid->vender_id;
                            $cart->disamount = $c['discount'];
                            $cart->distype = $c['distype'];
                            $cart->save();

                        }

                    }
                    else
                    {

                        $cart = new Cart;
                        $cart->user_id = Auth::user()->id;
                        $cart->qty = $c['qty'];
                        $cart->pro_id = $c['pro_id'];
                        $cart->variant_id = $c['variantid'];
                        $cart->ori_price = $c['varprice'];
                        $cart->ori_offer_price = $c['varofferprice'];
                        $cart->semi_total = $c['qty'] * $c['varofferprice'];
                        $cart->price_total = $c['qty'] * $c['varprice'];
                        $cart->vender_id = $venderid->vender_id;
                        $cart->disamount = $c['discount'];
                        $cart->distype = $c['distype'];
                        $cart->save();

                    }

                }

            }

            Session::forget('cart');

            return redirect()
                ->intended('/checkout');
        }
        else
        {
            $errors = new MessageBag(['email' => ['Email or password is invalid.']]);
            return Redirect::back()->withErrors($errors)->withInput($request->except('password'));
            return Redirect::back();
        }

    }

    public function checkInWish(Request $request)
    {

        $findinWishlist = Wishlist::where('pro_id', '=', $request->varid)
            ->first();

        if (isset($findinWishlist))
        {
            return 'InWish';
        }
        else
        {
            return 'NotInWish';
        }
    }

    public function showpage($slug)
    {
        require_once ('price.php');
        $page = Page::where('status', '=', '1')->where('slug', '=', $slug)->first();
        if($page){
            return view('front.singlepage', compact('conversion_rate', 'page'));
        }else{
            return abort(404,'Page Not found');
        }
        
    }

    public function faq()
    {
        require_once ('price.php');
        $faqs = Faq::where('status', '1')->orderBy('id', 'desc')
            ->paginate(10);
        return view('front.faq', compact('conversion_rate', 'faqs'));
    }

    public function getPinAddress(Request $request)
    {

        $term = $request->get('term');

        $result = array();

        if (Auth::check())
        {
            $queries = DB::table('addresses')->where('user_id', Auth::user()
                ->id)
                ->where('pin_code', 'LIKE', '%' . $term . '%')->get();
        }

        $queries2 = DB::table('allcities')->where('pincode', 'LIKE', '%' . $term . '%')->get();

        if (Auth::check())
        {
            foreach ($queries as $q)
            {

                $address = strlen($q->address) > 100 ? substr($q->address, 0, 100) . "..." : $q->address;

                $result[] = ['pincode' => $q->pin_code, 'value' => $q->pin_code . '(' . $address . ')'];

            }
        }

        foreach ($queries2 as $qq)
        {

            $state = Allstate::find($qq->state_id);
            $country = Allcountry::find($state->country_id)->nicename;

            $result[] = ['pincode' => $qq->pincode, 'value' => $qq->pincode . '(' . $qq->name . ',' . $state->name . ',' . $country . ')'];

        }

        if (strlen($term) > 12)
        {

            return ['Invalid Pincode'];

        }
        elseif (count($result) == 0)
        {
            return ['Delivery not available for this'];
        }
        else
        {
            return Response::json($result);
        }

    }

    public function choose_state(Request $request)
    {
       
        $id = $request['catId'];

        $country = Allcountry::find($id);
        $upload = Allstate::where('country_id', $id)->pluck('name', 'id')
            ->all();

        return response()->json($upload);
    }

    public function choose_city(Request $request)
    {

        $id = $request['catId'];

        $state = Allstate::find($id);
        $upload = Allcity::where('state_id', $id)->pluck('name', 'id')
            ->all();

        return response()
            ->json($upload);
    }

    public function changeCur(Request $request)
    {

        $start = $request->start;
        $end = $request->end;

        Session::put('prev_start', $start);
        Session::put('prev_end', $end);

    }
}

