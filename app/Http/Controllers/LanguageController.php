<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Language;
use Session;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class LanguageController extends Controller
{
    public function index()
    {   
        
        $allLang = Language::where('status', '=', 1)->get();
        return view('admin.language.index', compact('allLang'));
    }

    public function editStaticTrans($langCode)
    {
        $findlang = Language::where('lang_code', '=', $langCode)->first();

        if (isset($findlang))
        {

            if (file_exists('../resources/lang/' . $findlang->lang_code . '/staticwords.php'))
            {
                $file = file_get_contents("../resources/lang/$findlang->lang_code/staticwords.php");
                return view('admin.language.staticword', compact('findlang', 'file'));
            }
            else
            {

                if (is_dir('../resources/lang/' . $findlang->lang_code))
                {
                    copy("../resources/lang/en/staticwords.php", '../resources/lang/' . $findlang->lang_code . '/staticwords.php');
                    $file = file_get_contents("../resources/lang/$findlang->lang_code/staticwords.php");
                    return view('admin.language.staticword', compact('findlang', 'file'));
                }
                else
                {
                    mkdir('../resources/lang/' . $findlang->lang_code);
                    copy("../resources/lang/en/staticwords.php", '../resources/lang/' . $findlang->lang_code . '/staticwords.php');
                    $file = file_get_contents("../resources/lang/$findlang->lang_code/staticwords.php");
                    return view('admin.language.staticword', compact('findlang', 'file'));
                }

            }

        }
        else
        {
            return back()
                ->with('warning', '404 Language Not found !');
        }
    }

    public function updateStaticTrans(Request $request, $langCode)
    {
        $findlang = Language::where('lang_code', '=', $langCode)->first();
        if (isset($findlang))
        {

            $transfile = $request->transfile;
            file_put_contents('../resources/lang/' . $findlang->lang_code . '/staticwords.php', $transfile . PHP_EOL);
            return back()->with('updated', 'Language Translations Updated !');

        }
        else
        {
            return back()
                ->with('warning', '404 Language not found !');
        }
    }

    public function store(Request $request)
    {

        

        if (isset($request->name))
        {

            try{
                $ifalready = Language::where('lang_code',$request->lang_code)->first();

            if(isset($ifalready)){

                $ifalready->status = 1;

                if(isset($request->def)){
                    $findlang = Language::where('def', '=', 1)->first();
                    
                    if (isset($findlang))
                    {
                        $findlang->def = 0;
                        $findlang->save();
                    }

                     $ifalready->def = 1;

                    Session::put('changed_language', $ifalready->lang_code);
                }

                $ifalready->save();


            }else{
               $newlan = new Language;
                $newlan->lang_code = $request->lang_code;
                $newlan->status = 1;
                $newlan->name = $request->name;

                if (isset($newlan))
                {

                    if (isset($request->def))
                    {
                        $newlan->def = 1;
                        $findlang = Language::where('def', '=', 1)->first();
                        if (isset($findlang))
                        {
                            $findlang->def = 0;
                            $findlang->save();
                        }
                        Session::put('changed_language', $newlan->lang_code);
                    }
                    else
                    {
                        $newlan->def = 0;

                    }

                    $newlan->save();

                } 
            }

            return back()->with('added','Language added !');
        }catch(Exception $e){
                return back()
                    ->with('warning',"$e");
        }

        }
        else
        {
            return back()
                ->with('warning', 'Oops ! Something went wrong !');
        }

        return back()
            ->with('added', 'Language has been added !');

    }

    public function update(Request $request, $id)
    {
        $findlang = Language::find($id);
        $input = $request->all();

        if (isset($findlang))
        {

            if (isset($request->def))
            {
               
               
                    $deflang = Language::where('def', '=', 1)->first();
                    $deflang->def = 0;
                    $deflang->save();
                
                    $input['def'] = 1;
                    $findlang->update($input);
                
                
                     Session::put('changed_language', $findlang->lang_code);
                

            }
            else
            {

                if($findlang->def == 1){
                    $input['def'] = 1;
                }else{
                    $input['def'] = 0;
                }
                $findlang->update($input);
            }

            return back()->with("updated", "Language Details Updated !");
        }
        else
        {
            return back()->with("warning", "404 Language Not found !");
        }

    }

    public function delete($id)
    {

        $lang = Language::find($id);

        if (isset($lang))
        {

            if ($lang->def == 1)
            {
                return back()
                    ->with("warning", "Default language cannot be deleted !");
            }
            else
            {
                $lang->status = 0;
                $lang->save();
                return back()
                    ->with("deleted", "Language Deleted !");
            }

        }
        else
        {
            return back()
                ->with("warning", "404 Language Not found !");
        }
    }
}

