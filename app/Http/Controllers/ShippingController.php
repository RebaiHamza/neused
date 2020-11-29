<?php

namespace App\Http\Controllers;

use App\Shipping;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;
use Session;
use View;
use DB;
use Auth;
use App\Order;
use App\Product;
use App\Zone;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index()
    {
        $zones = Zone::all();
        $shippings = Shipping::orderBy('id','desc')->get();
        return view("admin.shipping.index",compact("shippings", "zones"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = Zone::where('status', '=', '1')->get();
        return view("admin.shipping.add", compact('zones'));
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
        ],[
            "name.required"=>"Shipping Fild is Required",
        ]);

        $input = $request->all();
        $shipping = Shipping::orderBy('id','desc')->select('id', 'id as id')->first();
        $input['id'] = $shipping['id'] + 1;
        $data = shipping::create($input);
        $data->save();
        return back()->with('category_message', 'Shipping method has been added'); 
    }
    

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shipping = Shipping::findOrFail($id);
        $zones = Zone::all();

        if($shipping->name == 'Shipping Price')
        {
            return redirect('admin/shipping-price-weight');
        }
        else
        {
            return view("admin.shipping.edit",compact("shipping", "zones"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
          $shipping = shipping::findOrFail($id);
          $input = $request->all();
          $input['status'] = $request->status;
          $shipping->update($input);

          return redirect('admin/shipping')->with('category_message', 'Shipping has been updated');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $daa = new Shipping;
         $obj = $daa->findorFail($id);
         //print_r($obj); die;
         $value = $obj->delete();
         if($value){
            session()->flash("category_message","Shipping Has Been deleted");
             return redirect("admin/shipping");
         }
    }


    public function shipping(Request $request){
       
         $id = $request['catId'];
        DB::table('shippings')->update(['default_status' => '0']);
       
        $UpdateDetails = Shipping::where('id', '=',  $id)->first();


        $UpdateDetails->default_status = "1";
        $UpdateDetails->save();

        if($id == 1){
           Product::query()->where('free_shipping','=','0')->update(['free_shipping' => '1','shipping_id' => NULL]);
        }else{
            Product::query()->where('free_shipping','=','0')->update(['free_shipping' => '0' ,'shipping_id' => $id]);
        }
        
        Session::flash('success', 'Default shipping method has been changed now.');
        return View::make('admin.shipping.message');
    }


}