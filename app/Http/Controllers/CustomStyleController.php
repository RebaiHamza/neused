<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomStyleController extends Controller
{
    public function addStyle()
    {
        $css = @file_get_contents('../public/css/custom-style.css');
        $js  = @file_get_contents('../public/js/custom-js.js');
        return view('admin.customstyle.add',compact('css','js'));
    }

    public function storeCSS(Request $request)
    {

        $css = $request->css;
        
        file_put_contents("../public/css/custom-style.css", $css . PHP_EOL);
        

        return redirect()->route('customstyle')
            ->with('added', 'Added Custom CSS !');

    }

    public function storeJS(Request $request)
    {
        $this->validate($request, array(
            'js' => 'required'
        ));

        $js = $request->js;
        file_put_contents("../public/js/custom-js.js", $js . PHP_EOL );
        return redirect()->route('customstyle')
            ->with('added', 'Added Javascript !');
    }

}

