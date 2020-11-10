<?php

namespace App\Http\Controllers;

use App\Store;
use App\Country;
use App\City;
use App\State;
use App\User;
use Image;
use App\Allstate;
use App\Allcity;
use App\Order;
use Illuminate\Http\Request;
use DataTables;
use Avatar;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
         $stores = \DB::table('stores')->join('allcities','allcities.id','=','stores.city_id')->join('allstates','stores.state_id','=','allstates.id')->join('allcountry','allcountry.id','=','stores.country_id')->join('users','users.id','=','stores.user_id')->select('stores.*','allcities.name as city','allstates.name as state','allcountry.name as country','users.name as username')->get();

        if($request->ajax()){
            return DataTables::of($stores)
               ->addIndexColumn()
               ->addColumn('logo',function($row){
                    $image = @file_get_contents('../public/images/store/'.$row->store_logo);
                    if($image){
                        $logo = '<img width="70px" height="70px" src="'.url("images/store/".$row->store_logo).'"/>';
                    }else{
                        $logo = '<img width="70px" height="70px" src="'.Avatar::create($row->name)->toBase64().'"/>';
                    }
                    
                    return $logo;

               })
               ->addColumn('info',function($store){

                    $html  = '<p><b>Name:</b> <span class="font-weight500">'.$store->name.'</span></p>';
                    $html .='<p><b>Email:</b> <span class="font-weight500">'.$store->email.'</span></p>';
                    $html .= '<p><b>Mobile:</b> <span class="font-weight500">'.$store->mobile.'</span></p>';
                    $html .= '<p><b>Address:</b> <span class="font-weight500">'.$store->address.' ,'.$store->city.' ,'.$store->state.' ,'.$store->country.'</p>';
                  
                    if($store->verified_store==1){
                        $html .= '<p><b>Verfied Store: </b> <span class="label label-success"><i class="fa fa-check-circle"></i> Verified</span></p>';
                    }else{
                        $html .= '<p><b>Verfied Store: </b> <span class="label label-danger">Not Verified</span></p>';
                    }
                                 
                                  
                    return $html;

               })
               ->editColumn('status','admin.store.status')
               ->editColumn('apply','admin.store.applybtn')
               ->addColumn('rd',function($store){
                    if($store->rd=='0'){
                        $btn = '<span class="label label-success">Not Received</span>'; 
                    }else{
                        $btn = '<span class="label label-danger">Received</span>';
                    }

                    return $btn;
               })
               ->editColumn('action','admin.store.action')
               ->rawColumns(['logo','info','status','apply','rd','action'])
               ->make(true);
        
        }


        return view("admin.store.index",compact("stores"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countrys = Country::all();
        $states = State::all();
        $citys = City::all();
        $users = User::where('role_id','v')->get();
        return view("admin.store.add",compact("states","countrys","citys","users"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $data = $this->validate($request,[
             "name"=>"required",
              "mobile"=>"required",
               'address'=>"required",
                'country_id' => 'required|not_in:0',
                'state_id' => 'required|not_in:0',
                'city_id' => 'required|not_in:0',
                'store_logo' => 'required | max:1000',
                "email"=>"required|unique:stores|email|max:255",

                 
            ],[
             "name.required"=>"Store Name is Required",
              "email.required"=>"Business Email is Required",
                "mobile.required"=>"Mobile No is Required",
                 
            
          ]);


         $input = $request->all();

        
        if ($file = $request->file('store_logo')) 
         {
            
          $optimizeImage = Image::make($file);
          $optimizePath = public_path().'/images/store/';
          $store_logo = time().$file->getClientOriginalName();
          $optimizeImage->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
          });
          $optimizeImage->save($optimizePath.$store_logo, 90);

          $input['store_logo'] = $store_logo;

          $data = Store::create($input);
        
          $data->save();
        }

        return back()->with("added","Store Has Been Added");
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $countrys = Country::all();
        $citys = Allcity::all();
        $users = User::where('role_id','v')->get();
        $store = Store::find($id);
        $states = Allstate::where('country_id',$store->country_id)->get();
        $citys = Allcity::where('state_id',$store->state_id)->get();
        
        $getallorder = Order::select('id','vender_ids')->get();
        $storeorder = array();
        foreach($getallorder as $order){
            if(in_array($store->user->id,$order->vender_ids)){
               array_push($storeorder,$order);
            }
        }

        $storeordercount = count($storeorder);

        return view("admin.store.edit",compact("store","countrys","states","citys","users",'storeordercount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $store = Store::find($id);


        $data = $this->validate($request,[
             "name"=>"required",
              "mobile"=>"required",
               'address'=>"required",
                'country_id' => 'required|not_in:0',
                'state_id' => 'required|not_in:0',
                'city_id' => 'required|not_in:0',
                "email"=>"required|email|max:255",

                 
            ],[
             "name.required"=>"Store Name is Required",
              "email.required"=>"Business Email is Required",
                "mobile.required"=>"Mobile No is Required",
                 
            
          ]);


       $store = Store::findOrFail($id);

       $input = $request->all();  
        
        if($file = $request->file('store_logo'))
        {
            
            if ($store->store_logo != null) 
            {

                if (file_exists(public_path() . '/images/store/' . $store->store_logo)) {
                    unlink(public_path() . '/images/store/' . $store->store_logo);
                }

            }

              $optimizeImage = Image::make($file);
              $optimizePath = public_path().'/images/store/';
              $name = time().$file->getClientOriginalName();
              $optimizeImage->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
              });
              $optimizeImage->save($optimizePath.$name, 90);
              $input['store_logo'] = $name;

        }

        if(isset($request->apply_vender)){
            $input['apply_vender'] = '1';
        }else{
            $input['apply_vender'] = '0';
        }

        $store->update($input);

        return redirect('admin/stores')->with('updated', 'Store has been updated !');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store = Store::find($id);
        if (file_exists(public_path() . '/images/store/' . $store->store_logo)) {
            unlink(public_path() . '/images/store/' . $store->store_logo);
        }
        $value = $store->delete();
        if($value){
            session()->flash("deleted","Store Has Been Deleted");
            return redirect("admin/stores");
        }
    }
}
