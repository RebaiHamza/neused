<?php

namespace App\Http\Controllers\Auth;

use App\Cart;
use App\Genral;
use App\Http\Controllers\Controller;
use App\Product;
use App\User;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;

/*==========================================
Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class RegisterController extends Controller
{

    private $setting;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->setting = Genral::first();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function register(Request $request)
    {

        if ($this->setting->captcha_enable == 1) {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'mobile' => 'numeric|unique:users,mobile',
                'g-recaptcha-response' => ['required', new CaptchaRule],
            ], [
                'g-recaptcha-response.required' => 'Please check the captcha !',
                'mobile.unique' => 'Mobile no is already taken !',
                'mobile.numeric' => 'Mobile no should be numeric !',
            ]
            );

        } else {

            $request->validate([

                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'mobile' => 'numeric|unique:users,mobile',

            ], [
                'mobile.unique' => 'Mobile no is already taken !',
                'mobile.numeric' => 'Mobile no should be numeric !',
            ]);

        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'mobile' => $request['mobile'],
            'password' => Hash::make($request['password']),
            'is_verified' => 1,
        ]);

        if (Session::has('cart')) {

            foreach (Session::get('cart') as $key => $c) {

                $venderid = Product::findorFail($c['pro_id']);

                $cart = new Cart;
                $cart->user_id = $user->id;
                $cart->qty = $c['qty'];
                $cart->pro_id = $c['pro_id'];
                $cart->variant_id = $c['variantid'];
                $cart->ori_price = $c['varprice'];
                $cart->ori_offer_price = $c['varofferprice'];
                $cart->semi_total = $c['qty'] * $c['varofferprice'];
                $cart->price_total = $c['qty'] * $c['varprice'];
                $cart->vender_id = $venderid->vender_id;
                $cart->save();
            }

        }

        Session::forget('cart');

        Auth::login($user);

        return redirect('/');

    }

}
