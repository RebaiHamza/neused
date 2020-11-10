<?php
namespace App\Http\Controllers;

use App\multiCurrency;
use Illuminate\Http\Request;
Use DB;
use App\Location;
use App\AutoDetectGeo;
use App\CurrencyCheckout;
use App\CurrencyList;
use App\Genral;
use Illuminate\Support\Facades\Artisan;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class MultiCurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auto_geo = AutoDetectGeo::first();
        return view('admin.multiCurrency.index', compact('auto_geo'));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        multiCurrency::insert(array(
            'enable' => $request->enable,
            'fixed_price' => $request->fixed,
            'currency_id' => $request->currency,
            'rate' => $request->rate,
            'add_amount' => $request->add_amount,
            'currency_symbol' => $request->symbol
        ));

        return back()
            ->with("category_message", "Currency Has Been Created");
    }

    public function add_currency_ajax(Request $request)
    {

        $currency_show = multiCurrency::where('currency_id', $request->countryId)
            ->pluck('currency_id')
            ->first();

        if (isset($currency_show))
        {

            $allcurrency = multiCurrency::where('row_id', $request->rowId)
                ->pluck('row_id')
                ->first();

            $defcheck = multiCurrency::where('currency_id', '=', $currency_show)->first()->default_currency;

            if ($defcheck == 1)
            {
                $default = 1;
            }
            else
            {
                $default = 0;
            }

            if (isset($allcurrency))
            {
                multiCurrency::where('currency_id', $request->countryId)
                    ->update(array(
                    'row_id' => $request->rowId,
                    'default_currency' => $default,
                    'currency_id' => $request->countryId,
                    'rate' => $request->rateId,
                    'add_amount' => $request->add,
                    'currency_symbol' => $request->currencySymbol,
                    'position' => $request->positionId
                ));
                return 'success';
            }
            else
            {
                return 'Already exist !';
            }
            return 'Error !';
        }
        else
        {

             $allcurrency = multiCurrency::where('row_id', $request->rowId)
                ->pluck('row_id')
                ->first();

            if (isset($allcurrency))
            {

                $defcheck = multiCurrency::find($allcurrency)->default_currency;

                if ($defcheck == 1)
                {

                    $default = 1;

                }
                else
                {

                    $default = 0;

                }

                multiCurrency::where('row_id', $request->rowId)
                    ->update(array(
                    'row_id' => $request->rowId,
                    'default_currency' => $default,
                    'currency_id' => $request->countryId,
                    'rate' => $request->rateId,
                    'add_amount' => $request->add,
                    'currency_symbol' => $request->currencySymbol,
                    'position' => $request->positionId
                ));
                return 'Error !';
            }
            else
            {
                $currencycode = CurrencyList::find($request->countryId);
              
                multiCurrency::insert(array(
                    'row_id' => $request->rowId,
                    'default_currency' => 0,
                    'currency_id' => $request->countryId,
                    'rate' => $request->rateId,
                    'add_amount' => $request->add,
                    'currency_symbol' => $request->currencySymbol,
                    'position' => $request->positionId
                ));

                \Artisan::call('currency:manage add ' .$currencycode->code);
            }

        }

        echo "save";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\multiCurrency  $multiCurrency
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $currency = DB::table('currency_list')->where('id', $request->currency_id)
            ->first();

        $currency = multiCurrency::where('id', $request->id)
            ->update(array(
            'currency_id' => $request->currency_id
        ));

        return response()
            ->json(array(
            'id' => $currency->id,
            'code' => $currency->code
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\multiCurrency  $multiCurrency
     * @return \Illuminate\Http\Response
     */
    public function edit(multiCurrency $multiCurrency)
    {
        //
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\multiCurrency  $multiCurrency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, multiCurrency $multiCurrency)
    {
        //
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\multiCurrency  $multiCurrency
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = multiCurrency::findorFail($id);

        currency()->delete($obj->currency->code);

        $obj->delete();
        
        return back();
    }

    public function setDefault(Request $request)
    {
        $currency = multiCurrency::where('id', '<>', $request->id)
            ->update(array(
            'default_currency' => '0'
        ));

        $currency = multiCurrency::where('id', $request->id)
            ->update(array(
            'default_currency' => '1',
            'rate' => '1'
        ));

        $all = multiCurrency::all();
        $rates = array();
        $currencyList;
        foreach ($all as $data)
        {

            $currencyList = DB::table('currency_list')->where('id', $data->currency_id)
                ->first();
            $multi = multiCurrency::where('default_currency', '1')->first();
            $genral = DB::table('currency_list')->where('id', $multi->currency_id)
                ->first();
            if (!empty($genral))
            {

                if (isset(session()->get('currency') ['id']))
                {
                    $from = $genral->code;
                    $to = $currencyList->code;

                    $conv_id = "{$from}_{$to}";
                    $string = file_get_contents("http://free.currconv.com/api/v3/convert?q=$conv_id&compact=ultra&apiKey=56e74be1de81a531ab8c");

                    $json_a = json_decode($string, true);
                    $result = $json_a[$conv_id];

                    $change_currency = $multi->rate * round($result, 4);

                    array_push($rates, $change_currency);

                    multiCurrency::where('id', $data->id)
                        ->update(array(
                        'rate' => $change_currency
                    ));

                }
            }
        }

        return response()->json(array(
            'id' => $request->id,
            'rates' => $rates
        ));
    }

    public function addLocation(Request $request)
    {

        $currs = multiCurrency::all();
        foreach ($currs as $curr)
        {
            $id = "country" . $curr->id;
            $multi_curr = "multi_curr" . $curr->id;
            $multi_currency = "multi_currency" . $curr->id;

            if ($request->auto_detect == 'on')
            {
                $geo = '1';
            }
            else
            {
                $geo = '0';
            }

            $check_loc = Location::where('multi_currency', $curr->id)
                ->first();
            if (!empty($check_loc))
            {

                if (!empty($request->$id))
                {
                    $child_cat = implode(",", $request->$id);
                    Location::where('multi_currency', $curr->id)
                        ->update(array(
                        'currency' => $request->$multi_curr,
                        'country_id' => $child_cat,
                        'multi_currency' => $request->$multi_currency

                    ));
                }
                else
                {
                    Location::where('multi_currency', $curr->id)
                        ->update(array(
                        'currency' => $request->$multi_curr,
                        'country_id' => $request->$id,
                        'multi_currency' => $request->$multi_currency

                    ));
                }

            }
            else
            {

                if (is_array($request[$id]))
                {

                    if (!empty($request->$id))
                    {
                        $child_cat = implode(",", $request->$id);
                        Location::insert(array(
                            'currency' => $request->$multi_curr,
                            'country_id' => $child_cat,

                            'multi_currency' => $request->$multi_currency

                        ));
                    }
                    else
                    {
                        Location::insert(array(
                            'currency' => $request->$multi_curr,
                            'country_id' => $request->$id,

                            'multi_currency' => $request->$multi_currency

                        ));
                    }
                }

            }

        }

        return back()->with('updated', 'Currency Setting Updated !');

    }

    public function editLocation(Request $request)
    {

        $child_cat = implode(",", $request->country);
        Location::where('multi_currency', $request->id)
            ->update(array(

            'country_id' => $child_cat,
            'currency' => $request->currency,

        ));

    }

    public function deleteLocation($id)
    {
        $obj = Location::where('multicurrency', $id)->first;
        $obj->delete();
        return back();
    }

    public function auto_change(Request $request)
    {

        $g = multiCurrency::where('id', $request->id)
            ->update(array(
            $request->name => $request->value
        ));
        if ($g)
        {
            return "save";
        }
        else
        {
            return "try agin";
        }

    }

    public function auto_update_currency(Request $request)
    {

        $all = multiCurrency::all();
        $rates = array();
        $currencyList = '';

        foreach ($all as $data)
        {

            $currencyList = DB::table('currency_list')->where('id', $data->currency_id)
                ->first();
                
            $k = multiCurrency::where('default_currency','=','1')->first();

            $defaultCurrency = $k->currency->code;

            if (!empty($defaultCurrency))
            {

                if (isset(session()->get('currency') ['id']))
                {
                    $from = $defaultCurrency;
                    $to = $currencyList->code;

                    $conv_id = "{$from}_{$to}";
                    $string = file_get_contents("http://free.currconv.com/api/v7/convert?q=$conv_id&compact=ultra&apiKey=ce96b5c0add191b1c136");

                    $json_a = json_decode($string, true);
                    $conversion_rate = $json_a[$conv_id];

                    $change_currency = 1 * round($conversion_rate, 4);

                    array_push($rates, $change_currency);

                    multiCurrency::where('id', $data->id)
                        ->update(array(
                        'rate' => $change_currency
                    ));

                    try{

                        \Artisan::call('currency:update -o');

                    }catch(\Exception $e){
                        echo $e->getMessage();
                    }
                }
            }
        }
        return response()->json($rates);

    }

    public function auto_detect_location(Request $request)
    {

        $myip = $_SERVER['REMOTE_ADDR'];
        $ip = geoip()->getLocation($myip);

        $auto_detect = AutoDetectGeo::first();
        if (isset($auto_detect))
        {
            if ($request->auto != null)
            {
                if ($request->auto == 1)
                {
                }
                AutoDetectGeo::where('id', '1')
                    ->update(array(
                    'auto_detect' => $request->auto,

                ));
            }
            else if ($request->currencybyc != null)
            {
                AutoDetectGeo::where('id', '1')
                    ->update(array(

                    'currency_by_country' => $request->currencybyc
                ));
            }

            else if ($request->country_id != null)
            {

                if ($request->country_id == 0)
                {
                    $default_geo_location = null;
                }
                else
                {
                    $default_geo_location = $request->country_id;
                }
                AutoDetectGeo::where('id', '1')
                    ->update(array(

                    'default_geo_location' => $default_geo_location,

                ));
            }

            else if ($request->checkout_currency != null)
            {
                AutoDetectGeo::where('id', '1')
                    ->update(array(

                    'checkout_currency' => $request->checkout_currency

                ));
            }

            else if ($request->cart_page != null)
            {
                AutoDetectGeo::where('id', '1')
                    ->update(array(

                    'enable_cart_page' => $request->cart_page

                ));
            }

            else if ($request->enable_multicurrency != null)
            {
                AutoDetectGeo::where('id', '1')
                    ->update(array(

                    'enabel_multicurrency' => $request->enable_multicurrency

                ));
            }

        }
        else
        {
            AutoDetectGeo::insert(array(
                'auto_detect' => $request->auto,

                'currency_by_country' => $request->currencybyc
            ));
        }

        $flag = strtolower($ip->iso_code);

        $flag_url = url('/admin/flags/4x3/' . $flag . '.svg');

        return response()->json(array(
            'country' => $ip->country,
            'isoCode' => $flag_url
        ));
    }

    public function checkOutUpdate(Request $request)
    {

        echo $request->default_checkout;

        $show_checkout = CurrencyCheckout::where('multicurrency_id', $request->currencyId)
            ->first();
        if (!empty($show_checkout))
        {
            if (is_array($request->payment))
            {

                $payments = implode(",", $request->payment);

                CurrencyCheckout::where('multicurrency_id', $request->currencyId)
                    ->update(array(

                    'currency' => $request->currency_checkout,
                    'default' => $request->default_checkout,
                    'checkout_currency' => $request->checkout_currency_status,
                    'payment_method' => $payments,
                    'multicurrency_id' => $request->currencyId

                ));
            }
            else
            {

                CurrencyCheckout::where('multicurrency_id', $request->currencyId)
                    ->update(array(

                    'currency' => $request->currency_checkout,
                    'default' => $request->default_checkout,
                    'checkout_currency' => $request->checkout_currency_status,
                    'payment_method' => $request->payment,
                    'multicurrency_id' => $request->currencyId

                ));
            }
        }
        else
        {
            if (is_array($request->payment))
            {

                $payments = implode(",", $request->payment);
                CurrencyCheckout::insert(array(
                    'currency' => $request->currency_checkout,
                    'default' => $request->default_checkout,
                    'checkout_currency' => $request->checkout_currency_status,
                    'payment_method' => $payments,
                    'multicurrency_id' => $request->currencyId
                ));
            }
            else
            {

                CurrencyCheckout::insert(array(
                    'currency' => $request->currency_checkout,
                    'default' => $request->default_checkout,
                    'checkout_currency' => $request->checkout_currency_status,
                    'payment_method' => $request->payment,
                    'multicurrency_id' => $request->currencyId
                ));
            }

        }

    }

    public function defaul_check_checkout(Request $request)
    {

        CurrencyCheckout::where('multicurrency_id', '<>', $request->id)
            ->update(array(

            'default' => '0',
        ));
        CurrencyCheckout::where('multicurrency_id', $request->id)
            ->update(array(

            'default' => $request->default_checkout,

        ));
    }

}

