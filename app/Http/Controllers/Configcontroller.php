<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Config;
use App\Button;
use DB;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class Configcontroller extends Controller
{
    public function getset()
    {

        $env_files = ['MAIL_FROM_NAME' => env('MAIL_FROM_NAME') , 'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS') , 'MAIL_DRIVER' => env('MAIL_DRIVER') , 'MAIL_HOST' => env('MAIL_HOST') , 'MAIL_PORT' => env('MAIL_PORT') , 'MAIL_USERNAME' => env('MAIL_USERNAME') , 'MAIL_PASSWORD' => env('MAIL_PASSWORD') , 'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION') ,

        ];
        return view('admin.mailsetting.mailset', compact('env_files'));

    }

    public function gitlabupdate(Request $request)
    {

        $input = $request->all();

        $env_update = $this->changeEnv(['GITLAB_CLIENT_ID' => $request->GITLAB_CLIENT_ID, 'GITLAB_CLIENT_SECRET' => $request->GITLAB_CLIENT_SECRET, 'GITLAB_CALLBACK_URL' => $request->GITLAB_CALLBACK_URL, ]);

        if (isset($request->ENABLE_GITLAB))
        {
            $env_update = $this->changeEnv(['ENABLE_GITLAB' => 1]);
        }
        else
        {
            $env_update = $this->changeEnv(['ENABLE_GITLAB' => 0]);
        }

        if ($env_update)
        {
            return back()->with('updated', 'Settings has been saved');
        }
        else
        {
            return back()
                ->with('deleted', 'Settings could not be saved');
        }
    }

    public function changeMailEnvKeys(Request $request)
    {
        $input = $request->all();
        // some code
        

        $env_update = $this->changeEnv(['MAIL_FROM_NAME' => $request->MAIL_FROM_NAME, 'MAIL_DRIVER' => $request->MAIL_DRIVER, 'MAIL_HOST' => $request->MAIL_HOST, 'MAIL_PORT' => $request->MAIL_PORT, 'MAIL_USERNAME' => $request->MAIL_USERNAME, 'MAIL_FROM_ADDRESS' => $string = preg_replace('/\s+/', '', $request->MAIL_FROM_ADDRESS) , 'MAIL_PASSWORD' => $request->MAIL_PASSWORD, 'MAIL_ENCRYPTION' => $request->MAIL_ENCRYPTION, ]);

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

    public function socialget()
    {
        $setting = Config::first();
        $env_files = ['FACEBOOK_CLIENT_ID' => env('FACEBOOK_CLIENT_ID') , 'FACEBOOK_CLIENT_SECRET' => env('FACEBOOK_CLIENT_SECRET') , 'FB_CALLBACK_URL' => env('FB_CALLBACK_URL') , 'GOOGLE_CLIENT_ID' => env('GOOGLE_CLIENT_ID') , 'GOOGLE_CLIENT_SECRET' => env('GOOGLE_CLIENT_SECRET') , 'GOOGLE_CALLBACK_URL' => env('GOOGLE_CALLBACK_URL') , 'GITLAB_CLIENT_ID' => env('GITLAB_CLIENT_ID') , 'GITLAB_CLIENT_SECRET' => env('GITLAB_CLIENT_SECRET') , 'GITLAB_CALLBACK_URL' => env('GITLAB_CALLBACK_URL')

        ];
        return view('admin.mailsetting.social', compact('env_files', 'setting'));
    }

    public function slfb(Request $request)
    {
        $setting = Config::first();

        $setting->fb_login_enable = $request->fb_enable;

        $env_update = $this->changeEnv(['FACEBOOK_CLIENT_ID' => $request->FACEBOOK_CLIENT_ID, 'FACEBOOK_CLIENT_SECRET' => $request->FACEBOOK_CLIENT_SECRET, 'FB_CALLBACK_URL' => $request->FB_CALLBACK_URL

        ]);

        $setting->save();

        return redirect()
            ->route('gen.set')
            ->with('updated', 'Facebook Login Setting Updated !');
    }

    public function slgl(Request $request)
    {
        $setting = Config::first();

        $setting->google_login_enable = $request->google_enable;

        $env_update = $this->changeEnv(['GOOGLE_CLIENT_ID' => $request->GOOGLE_CLIENT_ID, 'GOOGLE_CLIENT_SECRET' => $request->GOOGLE_CLIENT_SECRET, 'GOOGLE_CALLBACK_URL' => $request->GOOGLE_CALLBACK_URL

        ]);

        $setting->save();

        return redirect()
            ->route('gen.set')
            ->with('updated', 'Google Login Setting Updated !');
    }

    protected function changeEnv($data = array())
    {
        if (count($data) > 0)
        {

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            // Loop through given data
            foreach ((array)$data as $key => $value)
            {

                // Loop through .env-data
                foreach ($env as $env_key => $env_value)
                {

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if ($entry[0] == $key)
                    {
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    }
                    else
                    {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        }
        else
        {
            return false;
        }
    }

}

