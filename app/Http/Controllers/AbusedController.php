<?php
namespace App\Http\Controllers;

use App\Abused;
use Illuminate\Http\Request;

class AbusedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abuse = Abused::first();
        return view('admin/abuse/edit', compact('abuse'));
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $abuse = Abused::first();
        $input = $request->all();
        if (empty($abuse))
        {
            $data = $this->validate($request, ["name" => "required", "rep" => "required",

            ], [

            "name.required" => "Name Fild is Required", "rep.required" => "Replace Fild is Required",

            ]);
            $data = Abused::create($input);
            $data->save();
            return back()
                ->with("added", "Abused Word Setting Has Been create");
        }
        else
        {

            $abuse->update($input);
            return back()->with("updated", "Abused Word Setting  Has Been Updated");
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Abused  $abused
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Abused  $abused
     * @return \Illuminate\Http\Response
     */
    public function edit(Abused $abused)
    {
        //
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Abused  $abused
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Abused $abused)
    {
        //
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Abused  $abused
     * @return \Illuminate\Http\Response
     */
    public function destroy(Abused $abused)
    {
        //
        
    }
}

