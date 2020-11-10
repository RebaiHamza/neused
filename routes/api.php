<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/** Login register and logout with refresh token api */
 Route::post('login', 'Api\Auth\LoginController@login');
 Route::post('register', 'Api\Auth\RegisterController@register');
 Route::post('refresh', 'Api\Auth\LoginController@refresh');
 Route::post('logout','Api\Auth\LoginController@logout');

 /** Three level Categories with product without products api */
 Route::get('categories','Api\MainController@categories');
 Route::get('subcategories','Api\MainController@subcategories');
 Route::get('childcategories','Api\MainController@childcategories');
 Route::get('category/{id}','Api\MainController@getcategoryproduct');
 Route::get('subcategory/{id}','Api\MainController@getsubcategoryproduct');
 Route::get('childcategory/{id}','Api\MainController@getchildcategoryproduct');

 /** Offer,Hotdeals,Sliders,brands,Page api */
 Route::get('/hotdeals','Api\MainController@hotdeals');
 Route::get('/specialOffer','Api\MainController@specialoffer');
 Route::get('/sliders','Api\MainController@sliders');
 Route::get('/brands','Api\MainController@brands');
 Route::get('/page/{slug}','Api\MainController@page');
 
 /** Menus API */
 Route::get('topmenus','Api\MainController@menus');
 Route::get('footermenus','Api\MainController@footermenus');

 /** Genreal FAQs Apis */
 Route::get('faqs','Api\MainController@faqs');

 /* Blogs APIs*/
 Route::get('/blogs','Api\MainController@listallblog');
 Route::get('/blog/post/{slug}','Api\MainController@blogdetail');

 /** User Profile APIs */
Route::middleware(['auth:api'])->group(function () {
    Route::get('myprofile','Api\MainController@userprofile');
    Route::get('mywallet','Api\MainController@mywallet');
    Route::get('manageaddress','Api\MainController@getuseraddress');
    Route::get('mybanks','Api\MainController@getuserbanks');
});



