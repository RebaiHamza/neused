<?php

namespace App\Http\Controllers;
use App\Help;

use Illuminate\Http\Request;

class HelpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $helps = Help::all();
        return view('admin.helps.index', compact('helps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.helps.add");
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
            "help_name" => "required",
            'help_file' => 'required | max:1000'], [

            "help_name.required" => "Title is required", "help_file.required" => "file is required",
        ]);
        
        $help = new Help;
        $input = $request->all();
        
        $help_file = $request->file('help_file');
        $path = 'files/help_file/';
        $filename = time().'.'.$help_file->getClientOriginalExtension();
        $help_file->move($path, $filename);
        $input['help_file'] = $filename;

        $help->create($input);
        return back()->with("added", "Help file has been created !");
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
