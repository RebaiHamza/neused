<?php

namespace App\Http\Controllers;

use App\Seo;
use Illuminate\Http\Request;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class SeoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $seo = Seo::first();
        
        return view("admin.Seo.edit",compact("seo"));
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         
         $cat = Seo::first();
         $input = $request->all();  
         if(empty($cat))
         {
            $data = $this->validate($request,[
            "metadata_des"=>"required",
            "metadata_key"=>"required",
            "google_analysis"=>"required",
            "fb_pixel"=>"required",
            
        ],[

            "metadata_des.required"=>"Metadata Description Fild is Required",
            "metadata_key.required"=>"Metadata Key Fild is Required",
            "google_analysis.required"=>"Google Analysis Description Fild is Required",
            "fb_pixel.required"=>"Fb Pixel Key Fild is Required",
            
          ]);
            $data = Seo::create($input);
            $data->save();
                return back()->with("added","Seo Has Been created !");
         }
        
         else{
                
                $cat->update($input);
                return back()->with("updated","Genral Has Been Updated !");
    }
}

   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $seo = Seo::findOrFail($id);
        
        return view("admin.Seo.edit",compact("seo"));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $data = $this->validate($request,[
            "metadata_des"=>"required",
            "metadata_key"=>"required",
            "google_analysis"=>"required",
            "fb_pixel"=>"required",
            
        ],[

            "metadata_des.required"=>"Metadata Fild is Required",
            "metadata_key.required"=>"Metadata Key Fild is Required",
            "google_analysis.required"=>"Google Analysis Description Fild is Required",
            "fb_pixel.required"=>"Fb Pixel Key Fild is Required",
            
          ]);
         
         $update = new Seo;
         $obj = $update->find($id);
         $obj->metadata_des=$request->metadata_des;
         $obj->metadata_key=$request->metadata_key;
         $obj->google_analysis=$request->google_analysis;
         $obj->fb_pixel=$request->fb_pixel;
        

         $value=$obj->save();
         if($value){
            session()->flash("category_message","Seo has been Update");
            return redirect("admin/seo/".$id."/edit");
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Seo::find($id);
        $value = $cat->delete();
         if($value){
            session()->flash("category_message","Seo Has Been Deleted");
            return redirect("seo");
         }
    }
    
}
