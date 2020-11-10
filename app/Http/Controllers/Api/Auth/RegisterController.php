<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class RegisterController extends Controller
{
    use IssueTokenTrait;

	private $client;

	public function __construct(){
		$this->client = Client::find(2);
	}

    public function register(Request $request){

    	$this->validate($request, [
    		'name' => 'required',
    		'email' => 'required|email|unique:users,email',
            'mobile' => 'required',
    		'password' => 'required|min:6'
    	]);

    	$user = User::create([
    		'name' => request('name'),
    		'email' => request('email'),
            'mobile' => request('mobile'),
    		'password' => bcrypt(request('password'))
    	]);

    	return $this->issueToken($request, 'password');

    }
}
