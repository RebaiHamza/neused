<?php

namespace App\Http\Controllers;

use App\Config;
use App\multiCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class OtaUpdateController extends Controller
{
    public function update(Request $request)
    {

        $output = Artisan::call('migrate --path=database/migrations/update1_3');

        $op2 = Artisan::call('migrate');

        $listofcurrency = multiCurrency::all();

        $currency = array();

        foreach ($listofcurrency as $cur) {
            array_push($currency, $cur->currency->code);
        }

        Artisan::call('currency:manage add USD');

        if (in_array('USD', $currency)) {
            
            foreach ($currency as $c) {
                Artisan::call('currency:manage add ' . $c);
            }

        } else {

            foreach ($currency as $c) {

                Artisan::call('currency:manage add ' . $c);
            }

        }

        $cur = Artisan::call('currency:update -o');

        if (strstr(env('OPEN_EXCHANGE_RATE_KEY'), '11f0cdf')) {
            $this->changeEnv(['OPEN_EXCHANGE_RATE_KEY' => '']);
        }

        $add_on_field = "\nNOCAPTCHA_SITEKEY=\nNOCAPTCHA_SECRET=\nPAYSTACK_PUBLIC_KEY=\nPAYSTACK_SECRET_KEY=\nPAYSTACK_PAYMENT_URL=\nMERCHANT_EMAIL=\nOPEN_EXCHANGE_RATE_KEY=\nMESSENGER_CHAT_BUBBLE_URL";

        @file_put_contents(base_path() . '/.env', $add_on_field . PHP_EOL, FILE_APPEND);

        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        
        alert()->success('Updated to version ' . config('app.version'), 'Your App Updated Successfully !')->persistent('Close')->autoclose(8000);
        
        return redirect('/');

    }

    public function getotaview()
    {
        return view('ota.update');
    }

    protected function changeEnv($data = array())
    {
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
                $env = implode("\n\n", $env);

                // And overwrite the .env with the new data
                file_put_contents(base_path() . '/.env', $env);

                return true;

            } else {

                return false;
            }
        }
    }
}
