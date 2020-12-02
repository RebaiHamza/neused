<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pack;

class StudiosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packs = Pack::all();
        return view('admin.studio.index', compact('packs'));
    }

    public function indexSeller()
    {
        return view('seller.studio.index');
    }

    public function requestedPacks()
    {
        $packs = Pack::where('status', '0')->get();
        return view('admin.studio.requested', compact('packs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.studio.add");
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
            "title" => "required",

            "des" => 'required | max:1000',

            "price" => 'required' ], [

            "title.required" => "Pack Title is required",
            "des.required" => "Pack Description is required",
            "price.required" => 'Price is required',

        ]);

        $pack = new Pack;
        $input = $request->all();
        $input['description'] = clean($request->des);
        $pack->create($input);

        return back()->with("added", "Pack Has Been Created !");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
