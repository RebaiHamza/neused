<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use App\User;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class LoginController extends Controller
{

    use IssueTokenTrait;

	private $client;

	public function __construct(){
		$this->client = Client::find(2);
	}

    public function login(Request $request){

    	$this->validate($request, [
    		'email' => 'required',
    		'password' => 'required'
    	]);

        $user = User::where('email','=',$request->email)->first();

        if($user['status'] == 0){
            return response()->json(['msg' => 'User is not active !']);
        }

        return $this->issueToken($request, 'password');

    }

    public function refresh(Request $request){
        
    	$this->validate($request, [
    		'refresh_token' => 'required'
    	]);

    	return $this->issueToken($request, 'refresh_token');
    }

   public function logout()
    { 
        if (Auth::check()) {
           Auth::user()->AauthAcessToken()->delete();
        }
    }
}
