<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class PWAController extends Controller
{
    public function index(){
    	$setting = file_get_contents('manifest.json');
        $sw = file_get_contents('sw.js');
    	return view('admin.pwa.index',compact('setting','sw'));
    }

    public function updatesetting(Request $request){
    	$ms = file_put_contents('manifest.json', $request->app_setting);
        $sw = file_put_contents('sw.js', $request->sw_setting);
    	if(isset($update) || isset($sw)){
    		return back()->with('updated','App Setting Updated !');
    	}else{
    		return back()->with('warning','Oops ! Something went wrong');
    	}
    }

    public function updateicons(Request $request){
       if($file = $request->file('icon36'))
        {

            $name = 'icon36x36.png';
            $file->move('images/icons', $name);
        
        }

        if($file = $request->file('icon48'))
        {
            
            $name = 'icon48x48.png';
            $file->move('images/icons', $name);
        
        }

        if($file = $request->file('icon72'))
        {
            
            $name = 'icon72x72.png';
            $file->move('images/icons', $name);
        
        }

        if($file = $request->file('icon96'))
        {
            
            $name = 'icon96x96.png';
            $file->move('images/icons', $name);
        
        }

        if($file = $request->file('icon144'))
        {
            
            $name = 'icon144x144.png';
            $file->move('images/icons', $name);
        
        }

        if($file = $request->file('icon168'))
        {
            
            $name = 'icon168x168.png';
            $file->move('images/icons', $name);
        
        }

        if($file = $request->file('icon192'))
        {
            
            $name = 'icon192x192.png';
            $file->move('images/icons', $name);
        
        }

        if($file = $request->file('icon256'))
        {
            
            $name = 'icon256x256.png';
            $file->move('images/icons', $name);
        
        }

        if($file = $request->file('icon512'))
        {
            
            $name = 'icon512x512.png';
            $file->move('images/icons', $name);
        
        }

        return back()->with('updated','Icons are updated !');
    }
}
