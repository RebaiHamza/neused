<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ProductAttributes;
use App\ProductValues;

class ProductAttributeController extends Controller
{

	public function index()
	{
		$pattr = ProductAttributes::all();
		return view('admin.attributes.index',compact('pattr'));
	}

    public function create()
    {
    	return view('admin.attributes.addattr');
    }

    public function store(Request $request)
    {

    	$request->validate([
    		'attr_name' => 'required|unique:product_attributes,attr_name',
            'cats_id' => 'required'
    	],[
            'cats_id.required' => 'One Category is required atleast !',
            'attr_name.required' => 'Attribute name is required !',
            'attr_name.unique' => 'Option Already Added !'
        ]);

    	
		$newopt = new ProductAttributes;
    	
    	$newopt->attr_name = $request->attr_name;
        $newopt->unit_id = $request->unit_id;
        $newopt->cats_id = $request->cats_id;


		$newopt->save();

    	
		return redirect()->route('attr.index')->with('added','Option '.$request->attr_name.' Created Successfully !');
    }

    public function edit($id)
    {
    	$proattr = ProductAttributes::findorfail($id);

    	return view('admin.attributes.editattr',compact('proattr'));
    }


    public function update(Request $request, $id)
    {
    	$proattr = ProductAttributes::findorfail($id);

    	$input = $request->all();

        $findsameattr = ProductAttributes::where('attr_name','=',$request->attr_name)->first();

        if(isset($findsameattr))
        {
            if(strcasecmp($request->attr_name, $findsameattr->attr_name) == 0 && $proattr->id != $findsameattr->id)
            {
                return back()->with('warning','Variant is Already there !'); 
            }else {
               $proattr->update($input);

                return redirect()->route('attr.index')->with('updated','Option Updated to '.$input['attr_name'].' Successfully !');
            } 
        }else
        {
            $proattr->update($input);

            return redirect()->route('attr.index')->with('updated','Option Updated to '.$input['attr_name'].' Successfully !');
        }
       

        if(isset($findsameattr))
        {
            if($findsameattr->attr_name == $request->attr_name && $proattr->id != $findsameattr->id)
            {
            return back()->with('warning','Variant is Already there !');
            }  
        }else{
            $proattr->update($input);

            return redirect()->route('attr.index')->with('updated','Option Updated to '.$input['attr_name'].' Successfully !');
        }
        

    	
    }

    
}
