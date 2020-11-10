<?php
namespace App\Http\Controllers;

use App\City;
use App\State;
use App\Country;
use Illuminate\Http\Request;
use DataTables;
use App\Allcity;
/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax())
        {

            $countrys = Country::all();
            $country_name = collect();
            $data = collect();

            foreach ($countrys as $key => $con)
            {
                $country_name->push(\DB::table('allcountry')
                    ->where('iso3', $con->country)
                    ->orderBy('id', 'DESC')
                    ->get());
            }

            $country_name = $country_name->flatten();

            foreach ($country_name as $key => $c)
            {
                $data[] = \DB::table('allcities')->join('allstates', 'allstates.id', '=', 'allcities.state_id')
                    ->join('allcountry', 'allstates.country_id', '=', 'allcountry.id')
                    ->select('allcities.name as c', 'allstates.name as statename', 'allcountry.name as cname')
                    ->where('allstates.country_id', $c->id)
                    ->get();
            }

            $data = $data->flatten();

            return Datatables::of($data)->addIndexColumn()
                ->make(true);
        }

        return view("admin.city.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $state = State::all();
        $countrys = Country::all();
        return view("admin.city.add_city", compact("state", "countrys"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $request->validate([

            'name' => 'required|unique:allcities,name',
            'state_id' => 'required'
        ],[
            'name.required' => 'Please enter city name !',
            'name.unique' => 'City already exists !',
            'state_id.required' => 'Please select state !'
        ]);

        $input = $request->all();

        $newcity = new Allcity;
        $newcity->create($input);
        return back()->with('added',$request->name.' City is added !');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        //
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $city = City::findOrFail($id);

        $state = State::all();
        return view("admin.city.edit", compact("state", "city"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = $this->validate($request, ["city_name" => "required",

        ], [

        "city_name.required" => "City Name is needed",

        ]);

        $city = City::findOrFail($id);
        $input = $request->all();
        $city->update($input);
        if ($city)
        {
            session()->flash("category_message", "City Has Been Update");
            return redirect("admin/city/");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = City::find($id);
        $value = $city->delete();
        if ($value)
        {
            session()->flash("category_message", "City Has Been Deleted");
            return redirect("admin/city");
        }
    }
}

