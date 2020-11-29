<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductAttributes;

class SellerProductAttributeController extends Controller
{
    public function index(){
    	$pattr = ProductAttributes::all();
    	return view('seller.product.attributes',compact('pattr'));
    }
}
