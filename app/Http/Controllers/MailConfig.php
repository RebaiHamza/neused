<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class MailConfig extends Controller
{

    public function getset()
    {
        $config = \App\Config::first();

        $env_files = ['MAIL_FROM_NAME' => env('MAIL_FROM_NAME') , 'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS') , 'MAIL_DRIVER' => env('MAIL_DRIVER') , 'MAIL_HOST' => env('MAIL_HOST') , 'MAIL_PORT' => env('MAIL_PORT') , 'MAIL_USERNAME' => env('MAIL_USERNAME') , 'MAIL_PASSWORD' => env('MAIL_PASSWORD') , 'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION')

        ];
        return view('admin.mailsetting.mailset', compact('config', 'env_files'));

    }

    public function changeMailEnvKeys(Request $request)
    {
        $input = $request->all();

        $env_update = $this->changeEnv(['MAIL_FROM_NAME' => $request->MAIL_FROM_NAME, 'MAIL_DRIVER' => $request->MAIL_DRIVER, 'MAIL_HOST' => $request->MAIL_HOST, 'MAIL_PORT' => $request->MAIL_PORT, 'MAIL_USERNAME' => $request->MAIL_USERNAME, 'MAIL_FROM_ADDRESS' => $string = preg_replace('/\s+/', '', $request->MAIL_USERNAME) , 'MAIL_PASSWORD' => $request->MAIL_PASSWORD, 'MAIL_ENCRYPTION' => $request->MAIL_ENCRYPTION, ]);

        $config = \App\Config::first();

        $config->update($input);

        if ($env_update)
        {
            return back()->with('updated', 'Mail settings has been saved');
        }
        else
        {
            return back()
                ->with('deleted', 'Mail settings could not be saved');
        }

    }

}

