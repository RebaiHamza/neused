<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Newsletter;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class NewsletterController extends Controller
{
    public function store(Request $request)
    {

        if (env('MAILCHIMP_APIKEY') != '')
        {
            if (!Newsletter::isSubscribed($request->email))
            {
                Newsletter::subscribePending($request->email);
                notify()->success('Thanks For Subscribe !');
                return back();
            }
            else
            {
                notify()->error('You are already in our subscription list !');
                return back();
            }
        }
        else
        {
            notify()->error('Mailchimp API keys not updated !');
            return back();
        }

    }
}

