<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendFeedback;
use App\Genral;

class SendFeedBackController extends Controller
{
    public function send(Request $request)
    {
    	$feedback = [
    	    'name' => $request->name,
    	    'email' => $request->email,
    	    'msg' => $request->msg,
    	    'rate' => $request->rate
    	];

    	

    	$defaultemail = Genral::findorfail(1)->email;

    	try{
            Mail::to($defaultemail)->send(new SendFeedback($feedback));
        }catch(\Swift_TransportException $e){

        }
        
    	return back()->with('success','Sent Succesfully, Thanks for feedback us!');
}

}