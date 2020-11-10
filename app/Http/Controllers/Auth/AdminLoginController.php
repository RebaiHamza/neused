<?php
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class AdminLoginController extends Controller
{
    

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login( Request $request )
    {
        // Validate form data
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required|min:6'
        ]);
        // Attempt to authenticate user
        // If successful, redirect to their intended location
        if ( Auth::guard('auth')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember) ){
            return redirect()->intended( route('admin.main') );
        }
        // Authentication failed, redirect back to the login form
        return redirect()->back()->withInput( $request->only('email', 'remember') );
    }
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->guest(route( 'admin.login' ));
    }
}