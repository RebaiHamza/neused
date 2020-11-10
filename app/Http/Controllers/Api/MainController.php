<?php

namespace App\Http\Controllers\Api;

use App\Blog;
use App\Brand;
use App\Category;
use App\Faq;
use App\FooterMenu;
use App\Grandcategory;
use App\Hotdeal;
use App\Http\Controllers\Controller;
use App\Menu;
use App\Page;
use App\slider;
use App\SpecialOffer;
use App\Subcategory;
use App\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

/*==========================================
=            emart Rest APIs               =
=            Author: Media City            =
Author URI: https://mediacity.co.in
=            Developer : @nkit             =
=            Copyright (c) 2020            =
==========================================*/

class MainController extends Controller
{
    public function categories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $categories = Category::orderBy('position', 'ASC')->get();
        return response()->json(['categories' => $categories]);
    }

    public function subcategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $categories = Subcategory::orderBy('position', 'ASC')->get();
        return response()->json(['categories' => $categories]);
    }

    public function childcategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $categories = Grandcategory::orderBy('position', 'ASC')->get();
        return response()->json(['categories' => $categories]);
    }

    public function getcategoryproduct(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $cat = Category::find($id);

        if (!$cat) {
            return response()->json(['Category not found !']);
        }

        if ($cat->status != 1) {
            return response()->json(['Category is not active !']);
        }

        $pros = $cat->products;

        return response()->json($pros);

    }

    public function getsubcategoryproduct(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $subcat = Subcategory::find($id);

        if (!$subcat) {
            return response()->json(['Category not found !']);
        }

        if ($subcat->status != 1) {
            return response()->json(['Category is not active !']);
        }

        $pros = $subcat->products;

        return response()->json($pros);

    }

    public function sliders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $sliders = slider::where('status', '=', '1')->get();
        return response()->json($sliders);
    }

    public function hotdeals(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $sliders = Hotdeal::where('status', '=', '1')->get();
        return response()->json($sliders);
    }

    public function specialoffer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $sp = SpecialOffer::where('status', '=', '1')->get();
        return response()->json($sp);
    }

    public function brands(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $brand = Brand::where('status', '=', '1')->get();
        return response()->json($brand);
    }

    public function page(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $page = Page::where('slug', '=', $slug)->first();
        return response()->json($page);

    }

    public function menus(Request $request){
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $topmenu = Menu::orderBy('position','ASC')->get();

        return response()->json($topmenu);
    }

    public function footermenus(Request $request){
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $footermenus = FooterMenu::get();

        return response()->json($footermenus = FooterMenu::get());
    }

    public function userprofile(Request $request){

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        if (!Auth::check()) {
            return response()->json("You're not logged in !");
        } else {
            $user = Auth::user();
            return response()->json($user);
        }


    }

    public function mywallet(Request $request){
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        if (!Auth::check()) {
            return response()->json("You're not logged in !");
        }

        $wallet = UserWallet::firstWhere('user_id','=',Auth::user()->id);
        $wallethistory = $wallet->wallethistory;
        return response()->json(['wallet' => $wallet, 'wallethistory' => $wallethistory]);
    }

    public function getuseraddress(Request $request){
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        if (!Auth::check()) {
            return response()->json("You're not logged in !");
        }

        $address = Auth::user()->addresses;
        return response()->json($address);
    }

    public function getuserbanks(Request $request){
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        if (!Auth::check()) {
            return response()->json("You're not logged in !");
        }

        $userbanklist = Auth::user()->banks;
        return response()->json($userbanklist);
    }

    public function faqs(Request $request){
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $faqs = Faq::all();

        return response()->json($faqs);
    }

    public function listallblog(Request $request){

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $blogs = Blog::orderBy('id','DESC')->get();
        return response()->json($blogs);
    }

    public function blogdetail(Request $request,$slug){

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $blog = Blog::firstWhere('slug','=',$slug);

        if(!isset($blog)){
            return response()->json('404 Blog post not found !');
        }

        return response()->json($blog);
    }

}
