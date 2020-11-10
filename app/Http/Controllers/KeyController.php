<?php
namespace App\Http\Controllers;

use App\BankDetail;
use App\Config;
use Illuminate\Http\Request;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Developer : Ankit             =
=            Copyright (c) 2020            =
==========================================*/

class KeyController extends Controller
{

    protected $config;

    public function __construct()
    {
        $this->config = Config::first();
    }

    public function paymentsettings()
    {
        $bank = BankDetail::first();
        return view('admin.payment_settings.index', compact('bank'));
    }

    public function paystackUpdate(Request $request){
        $input = $request->all();

        $this->changeEnv([

            'PAYSTACK_PUBLIC_KEY' => $input['PAYSTACK_PUBLIC_KEY'], 
            'PAYSTACK_SECRET_KEY' => $input['PAYSTACK_SECRET_KEY'], 
            'PAYSTACK_PAYMENT_URL' => $input['PAYSTACK_PAYMENT_URL'],
            'MERCHANT_EMAIL' => $input['MERCHANT_EMAIL']
        ]);

        $this->config->paystack_enable = isset($request->paystack_enable) ? 1 : 0;
        $this->config->save();
        return back()->with('added','Paystack settings has been updated !');
    }

    public function updatePaytm(Request $request)
    {
        $input = $request->all();
        
        $this->changeEnv([

            'PAYTM_ENVIRONMENT' => $input['PAYTM_ENVIRONMENT'], 'PAYTM_MERCHANT_ID' => $input['PAYTM_MERCHANT_ID'], 'PAYTM_MERCHANT_KEY' => $input['PAYTM_MERCHANT_KEY'],

        ]);

        $this->config->paytm_enable = isset($request->paytmchk) ? 1 : 0;

        $this->config->save();

        return back()
            ->with('updated', 'Paytm settings has been updated !');
    }

    public function updaterazorpay(Request $request)
    {
        $input = $request->all();
        
        $this->changeEnv([

            'RAZOR_PAY_KEY' => $input['RAZOR_PAY_KEY'], 'RAZOR_PAY_SECRET' => $input['RAZOR_PAY_SECRET'],

        ]);

        $this->config->razorpay = isset($request->rpaycheck ) ? 1 : 0;

        $this->config->save();

        return back()
            ->with('updated', 'Razorpay settings has been updated !');

    }

    public function saveStripe(Request $request)
    {
        $input = $request->all();

        $this->changeEnv([
            'STRIPE_KEY' => $input['STRIPE_KEY'],
            'STRIPE_SECRET' => $input['STRIPE_SECRET'],
        ]);

        $this->config->stripe_enable = isset($request->strip_check) ? "1" : "0";

        $this->config->save();

        return back()->with('updated', 'Stripe settings has been updated !');
    }

    public function saveBraintree(Request $request)
    {
        $input = $request->all();

        $this->changeEnv([
            'BRAINTREE_ENV' => $input['BRAINTREE_ENV'],
            'BRAINTREE_MERCHANT_ID' => $input['BRAINTREE_MERCHANT_ID'],
            'BRAINTREE_PUBLIC_KEY' => $input['BRAINTREE_PUBLIC_KEY'],
            'BRAINTREE_PRIVATE_KEY' => $input['BRAINTREE_PRIVATE_KEY'],
            'BRAINTREE_MERCHANT_ACCOUNT_ID' => $input['BRAINTREE_MERCHANT_ACCOUNT_ID']
        ]);

        $this->config->braintree_enable = isset($request->braintree_enable) ? "1" : "0";

        $this->config->save();

        return back()->with('updated', 'Stripe settings has been updated !');
    }

    public function savePaypal(Request $request)
    {

        $input = $request->all();

        $this->changeEnv([

            'PAYPAL_CLIENT_ID' => $input['PAYPAL_CLIENT_ID'], 'PAYPAL_SECRET' => $input['PAYPAL_SECRET'], 'PAYPAL_MODE' => $input['PAYPAL_MODE'],

        ]);

        $this->config->paypal_enable = isset($request->paypal_check) ? 1 : 0;

        $this->config->save();

        return back()->with('updated', 'Paypal settings has been updated !');

    }

    public function instamojoupdate(Request $request)
    {

        $input = $request->all();

        $this->config->instamojo_enable = isset($request->instam_check) ? 1 : 0;

        $this->changeEnv([
            'IM_API_KEY' => $input['IM_API_KEY'],
            'IM_AUTH_TOKEN' => $input['IM_AUTH_TOKEN'],
            'IM_URL' => $input['IM_URL'],
            'IM_REFUND_URL' => $input['IM_REFUND_URL'],
        ]);

        $this->config->save();

        return back()->with('added', 'Instamojo Setting has been updated !');

    }

    public function payuupdate(Request $request)
    {
        $input = $request->all();

        $this->config->payu_enable = isset($request->payu_chk) ? 1 : 0;

        $this->changeEnv([

            'PAYU_METHOD' => $input['PAYU_METHOD'],
            'PAYU_DEFAULT' => $input['PAYU_DEFAULT'],
            'PAYU_MERCHANT_KEY' => $input['PAYU_MERCHANT_KEY'],
            'PAYU_MERCHANT_SALT' => $input['PAYU_MERCHANT_SALT'],
            'PAYU_AUTH_HEADER' => $input['PAYU_AUTH_HEADER'],
            'PAY_U_MONEY_ACC' => isset($request->PAY_U_MONEY_ACC) ? "true" : "false",
            'PAYU_REFUND_URL' => $input['PAYU_REFUND_URL'],
        ]);

        $this->config->save();

        return back()
            ->with('updated', 'PayUMoney settings has been updated !');

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
