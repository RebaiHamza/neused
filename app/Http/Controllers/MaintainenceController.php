<?php

namespace App\Http\Controllers;

use App\Maintainence;
use Illuminate\Http\Request;

class MaintainenceController extends Controller
{

/*==========================================
=            Author: Media City            =
Author URI: https://mediacity.co.in
=            Developer: @nkit              =
=            Copyright (c) 2020            =
==========================================*/

    public function post(Request $request)
    {

        $request->validate([
            'allowed_ips' => 'required',
            'message' => 'required|max:5000',
        ]);

        $row_exist = Maintainence::first();

        if ($row_exist) {

            Maintainence::where('id', '=', 1)->update([

                'message' => clean($request->message),
                'allowed_ips' => $request->allowed_ips,
                'status' => isset($request->status) ? 1 : 0,
            ]);

        } else {

            Maintainence::create([

                'message' => clean($request->message),
                'allowed_ips' => $request->allowed_ips,
                'status' => isset($request->status) ? 1 : 0,
            ]);

        }

        return back()->with('added', 'Maintenance Settings Updated !');
    }

    public function getview()
    {
        $data = Maintainence::first();

        return view('admin.maintenance.index', compact('data'));
    }
}
