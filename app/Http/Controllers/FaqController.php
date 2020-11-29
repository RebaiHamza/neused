<?php
namespace App\Http\Controllers;

use App\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = Faq::all();
        return view("admin.faq.index", compact("faqs"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {

        $faq = Faq::all();
        return view("admin.faq.add", compact("faq"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, ["ans" => "required", "que" => "required",

        ], [

        "ans.required" => "Answer Fild is Required", "que.required" => "Question Fild is Required",

        ]);

        $input = $request->all();
        $faq = Faq::create($input);
        $faq->save();

        return back()
            ->with('added', 'Faq has been Create');
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

        $faq = Faq::findOrFail($id);

        return view("admin.faq.edit", compact("faq"));

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
        $data = $this->validate($request, ["ans" => "required", "que" => "required",

        ], [

        "ans.required" => "Answer Fild is Required", "que.required" => "Question Fild is Required",

        ]);

        $faq = Faq::findOrFail($id);
        $input = $request->all();
        $faq->update($input);

        return redirect('admin/faq')->with('updated', 'Faq has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Faq::find($id);
        $value = $cat->delete();
        if ($value)
        {
            session()->flash("deleted", "Faq Has Been Deleted");
            return redirect("admin/faq");
        }
    }

}

