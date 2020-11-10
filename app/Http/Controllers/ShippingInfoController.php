<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipping;
use App\ShippingWeight;
use Session;
use DataTables;
use Avatar;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class ShippingInfoController extends Controller
{
    public function getinfo(){
    	$shippings = Shipping::all();
    	$sw = ShippingWeight::first();
    	return view('seller.shipping.index',compact('shippings','sw'));
    }

    public function getcategories(Request $request){
    	$lang = Session::get('changed_language');
    	$cat = \DB::table('categories')->where('status','=','1')->select("title->$lang AS title","description->$lang AS details",'image as thumbnail')->get();

    	if($request->ajax()){
    		return DataTables::of($cat)->addIndexColumn()->addColumn('thumbnail', function ($row)
            {
                $image = @file_get_contents('images/category/'.$row->thumbnail);

                if($image){
                    $image = '<img width="50px" height="70px" src="' . url("images/category/" . $row->thumbnail) . '"/>';
                }else{
                    $image = '<img width="50px" height="70px" src="' . Avatar::create($row->title)->toBase64() . '"/>';
                }
                
                return $image;

            })->addColumn('name', function($row){
            	return $row->title;
            })
            ->addColumn('details', function($row){
            	return strip_tags($row->details);
            })
            ->rawColumns(['thumbnail', 'name', 'details'])
            ->make(true);
    	}


            return view('seller.category.cat');
    }

    public function getsubcategories(Request $request){
    	$lang = Session::get('changed_language');
    	$cat = \DB::table('subcategories')->join('categories','subcategories.parent_cat','=','categories.id')->select("subcategories.title->$lang AS title","subcategories.description->$lang AS details",'subcategories.image as thumbnail',"categories.title->$lang AS parenttitle")->where('subcategories.status','=','1')->where('categories.status','=','1')->get();

    	if($request->ajax()){
    		return DataTables::of($cat)->addIndexColumn()->addColumn('thumbnail', function ($row)
            {
                $image = @file_get_contents('images/category/'.$row->thumbnail);

                if($image){
                    $image = '<img width="50px" height="70px" src="' . url("images/category/" . $row->thumbnail) . '"/>';
                }else{
                    $image = '<img width="50px" height="70px" src="' . Avatar::create($row->title)->toBase64() . '"/>';
                }
                
                return $image;

            })->addColumn('name', function($row){
            	return $row->title;
            })
            ->addColumn('parentcat', function($row){
            	return $row->parenttitle;
            })
            ->addColumn('details', function($row){
            	return strip_tags($row->details);
            })
            ->rawColumns(['thumbnail', 'name', 'parentcat', 'details'])
            ->make(true);
    	}


            return view('seller.category.sub');
    }

    public function getchildcategories(Request $request){

    	$lang = Session::get('changed_language');
    	$cat = \DB::table('grandcategories')
    			->join('subcategories','grandcategories.subcat_id','=','subcategories.id')
    			->join('categories','categories.id','=','grandcategories.parent_id')
    			->select("grandcategories.title->$lang AS title","grandcategories.description->$lang AS details",'grandcategories.image as thumbnail',"categories.title->$lang AS ptitle","subcategories.title->$lang AS subtitle")
    			->where('grandcategories.status','=','1')
    			->where('subcategories.status','=','1')
    			->where('categories.status','=','1')->get();

    	if($request->ajax()){
    		return DataTables::of($cat)->addIndexColumn()->addColumn('thumbnail', function ($row)
            {
                $image = @file_get_contents('images/category/'.$row->thumbnail);

                if($image){
                    $image = '<img width="50px" height="70px" src="' . url("images/category/" . $row->thumbnail) . '"/>';
                }else{
                    $image = '<img width="50px" height="70px" src="' . Avatar::create($row->title)->toBase64() . '"/>';
                }
                
                return $image;

            })->addColumn('name', function($row){
            	return $row->title;
            })
            ->addColumn('sub', function($row){
            	return $row->subtitle;
            })
            ->addColumn('main', function($row){
            	return $row->ptitle;
            })
            ->addColumn('details', function($row){
            	return strip_tags($row->details);
            })
            ->rawColumns(['thumbnail', 'name', 'sub', 'main', 'details'])
            ->make(true);
    	}


            return view('seller.category.child');
    }
}
