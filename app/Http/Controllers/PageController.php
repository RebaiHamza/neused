<?php
namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::all();
        return view("admin.page.index", compact("pages"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {

        return view("admin.page.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, ["name" => "required", "slug" => "required",

        ], [

        "name.required" => "Name Fild is Required", "slug.required" => "Slug Fild is Required",

        ]);

        $input = $request->all();
        $input['des'] = clean($request->des);
        $page = Page::create($input);
        $page->save();

        return back()
            ->with('updated', 'Page has been updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Category $category)
    {
        //
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $page = Page::findOrFail($id);

        return view("admin.page.edit", compact("page"));

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

        $data = $this->validate($request, ["name" => "required", "slug" => "required",

        ], [

        "name.required" => "Name Fild is Required", "slug.required" => "Slug Fild is Required",

        ]);
        $page = Page::findOrFail($id);
        $input = $request->all();
        $input['des'] = clean($request->des);
        $page->update($input);

        return redirect('admin/page')->with('updated', 'Page has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Page::find($id);
        $value = $cat->delete();
        if ($value)
        {
            session()->flash("deleted", "Page Has Been Deleted");
            return redirect("admin/page");
        }
    }

}

