<?php
namespace App\Http\Controllers;

use App\Genral;
use Illuminate\Http\Request;
use Image;

/*==========================================
=            Author: Media City            =
Author URI: https://mediacity.co.in
=            Developer: @nkit              =
=            Copyright (c) 2020            =
==========================================*/

class GenralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $row = Genral::first();

        $env_files = ['APP_NAME' => env('APP_NAME')];

        return view("admin.genral.edit", compact("row", "env_files"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();

        $active = @file_get_contents(public_path() . '/config.txt');
        $curdomain = @file_get_contents(public_path() . '/ddtl.txt');

        if (!$active) {
            $putS = 1;
            file_put_contents(public_path() . '/config.txt', $putS);
        }

        $d = \Request::getHost();
        $domain = str_replace("www.", "", $d);

        if ($domain == 'localhost' || strstr($domain, '.test') || strstr($domain, '192.168.0') || strstr($domain, 'mediacity.co.in')) {
            return $this->verifiedupdate($input, $request);
        } else {
            $token = (file_exists(public_path() . '/intialize.txt') && file_get_contents(public_path() . '/intialize.txt') != null) ? file_get_contents(public_path() . '/intialize.txt') : 0;
            $code = (file_exists(public_path() . '/code.txt') && file_get_contents(public_path() . '/code.txt') != null) ? file_get_contents(public_path() . '/code.txt') : 0;
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => "https://mediacity.co.in/purchase/public/api/check/{$domain}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    "Authorization: Bearer " . $token,
                ),
            );
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            if (curl_errno($ch) > 0) {
                $message = "Error connecting to API.";
                return back()->with('delete', $message);
            }
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($responseCode == 200) {
                $body = json_decode($response);
                if ($domain == $curdomain) {
                    return $this->verifiedupdate($input, $request);
                } else {
                    $putS = 0;
                    file_put_contents(public_path() . '/config.txt', $putS);
                }
            } else {
                $message = "Failed";
                $putS = 0;
                file_put_contents(public_path() . '/config.txt', $putS);
            }
        }

    }

    public function verifiedupdate($input, $request)
    {

        $cat = Genral::first();

        $env_update = $this->changeEnv([

            'APP_URL' => $request->APP_URL,
            'APP_NAME' => str_replace(' ', '-', $request->project_name),
            'NOCAPTCHA_SECRET' => $request->NOCAPTCHA_SECRET,
            'NOCAPTCHA_SITEKEY' => $request->NOCAPTCHA_SITEKEY,
            'OPEN_EXCHANGE_RATE_KEY' => $request->OPEN_EXCHANGE_RATE_KEY,
            'MESSENGER_CHAT_BUBBLE_URL' => $request->MESSENGER_CHAT_BUBBLE_URL
        ]);

        if ($request->logo) {

            $image = $request->file('logo');
            $input['logo'] = 'logo.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/genral');
            $img = Image::make($image->path());

            if (file_exists(public_path() . '/images/genral/' . $cat->logo)) {
                unlink(public_path() . '/images/genral/' . $cat->logo);
            }

            $img->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->save($destinationPath . '/' . $input['logo']);

        }

        if ($file = $request->file('fevicon')) {

            $image = $request->file('fevicon');
            $input['fevicon'] = 'fevicon.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/genral');
            $img = Image::make($image->path());

            if ($cat->fevicon != null) {

                if (file_exists(public_path() . '/images/genral/' . $cat->fevicon)) {
                    unlink(public_path() . '/images/genral/' . $cat->fevicon);
                }

            }

            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->save($destinationPath . '/' . $input['fevicon']);

        }

        if (isset($request->right_click)) {
            $input['right_click'] = '1';
        } else {
            $input['right_click'] = '0';
        }

        if (isset($request->captcha_enable)) {
            $input['captcha_enable'] = '1';
        } else {
            $input['captcha_enable'] = '0';
        }

        if (isset($request->inspect)) {
            $input['inspect'] = '1';
        } else {
            $input['inspect'] = '0';
        }

        if (isset($request->login)) {
            $input['login'] = '1';
        } else {
            $input['login'] = '0';
        }

        if (isset($request->guest_login)) {
            $input['guest_login'] = '1';
        } else {
            $input['guest_login'] = '0';
        }

        if (isset($request->vendor_enable)) {
            $input['vendor_enable'] = 1;
        } else {
            $input['vendor_enable'] = 0;
        }

        if (isset($request->APP_DEBUG)) {
            $env_update = $this->changeEnv(['APP_DEBUG' => 'true']);
        } else {
            $env_update = $this->changeEnv(['APP_DEBUG' => 'false']);
        }

        if ($request->file('preloader')) {
            $dir = 'images/preloader';
            $leave_files = array('index.php');

            foreach (glob("$dir/*") as $file2) {
                if (!in_array(basename($file2), $leave_files)) {
                    unlink($file2);
                }
            }

            $image = $request->file('preloader');
            $img = Image::make($image->path());
            $preloader = 'preloader.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/preloader');
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->save($destinationPath . '/' . $preloader);
        }

        $cat->update($input);

        return back()->with("updated", "Genral Setting Has Been Updated");

    }

    protected function changeEnv($data = array())
    {
        if (count($data) > 0) {

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);

            // Loop through given data
            foreach ((array) $data as $key => $value) {

                // Loop through .env-data
                foreach ($env as $env_key => $env_value) {

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if ($entry[0] == $key) {
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
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
        } else {
            return false;
        }
    }

}
