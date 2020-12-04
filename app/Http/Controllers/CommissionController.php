<?php
namespace App\Http\Controllers;

use App\Commission;
use App\Commission_used;
use App\Commission_ticket;
use App\Commission_bid;
use App\category;
use App\Product;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commissions = Commission::all();
        return view("admin.commission.index", compact("commissions"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = \App\Category::all();
        return view("admin.commission.add", compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $this->validate($request, ['rate' => 'required|integer|not_in:0', 'category_id' => 'required|not_in:0',

        ], ["rate.required" => "Rate Fild Accept Only Number",

        ]);

        $input = $request->all();
        $data = Commission::create($input);
        $data->save();
        return redirect('admin/commission')
            ->with('updated', 'Commission has been updated');
    }

    public function show($id)
    {
        //
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = \App\Category::all();
        $commission = commission::findOrFail($id);
        return view("admin.commission.edit", compact("commission", "category"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->validate($request, ['rate' => 'required|integer', 'category_id' => 'required',

        ], ["rate.required" => "Rate Fild Accept Only Number",

        ]);

        $tax = Commission::findOrFail($id);
        $input = $request->all();
        $tax->update($input);
        return redirect('admin/commission')->with('updated', 'Commission has been updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $daa = new Commission;
        $obj = $daa->findorFail($id);
        $value = $obj->delete();
        if ($value)
        {
            session()->flash("deleted", "Commission Has Been deleted");
            return redirect("admin/commission");
        }
    }

    // Used Products Commission

    public function indexUsed()
    {
        $commissions = Commission_used::all();
        return view("admin.commission_used.index", compact("commissions"));
    }

    public function createUsed()
    {
        $product = \App\Product::where('is_used', "=", "1")->get();
        return view("admin.commission_used.add", compact('product'));
    }

    public function storeUsed(Request $request)
    {

        $data = $this->validate($request, ['rate' => 'required|integer|not_in:0', 'used_id' => 'required|not_in:0',

        ], ["rate.required" => "Rate Fild Accept Only Number",

        ]);

        $input = $request->all();
        $data = Commission_used::create($input);
        $data->save();
        $pro = Product::findOrFail($input['used_id']);
        $pro->commission_rate = $input['rate'];
        $pro->update();
        
        return redirect('admin/commissionused')
            ->with('updated', 'Commission has been updated');
    }

    public function editUsed($id)
    {
        $product = \App\Product::where('is_used', "=", "1")->get();
        $commission = Commission_used::findOrFail($id);
        return view("admin.commission_used.edit", compact("commission", "product"));
    }

    public function updateUsed(Request $request, $id)
    {
        $data = $this->validate($request, ['rate' => 'required|integer', 'used_id' => 'required',

        ], ["rate.required" => "Rate Fild Accept Only Number",

        ]);

        $tax = Commission_used::findOrFail($id);
        $input = $request->all();
        $tax->update($input);
        
        $pro = Product::findOrFail($input['used_id']);
        $pro->commission_rate = $input['rate'];
        $pro->update();

        return redirect('admin/commissionused')->with('updated', 'Commission has been updated');

    }

    public function destroyUsed($id)
    {
        $daa = new Commission_used;
        $obj = $daa->findorFail($id);
        $value = $obj->delete();
        if ($value)
        {
            session()->flash("deleted", "Commission Has Been deleted");
            return redirect("admin/commissionused");
        }
    }

    // Tickets Commission

    public function indexTicket()
    {
        $commissions = Commission_ticket::all();
        return view("admin.commission_ticket.index", compact("commissions"));
    }

    public function createTicket()
    {
        $product = \App\Product::where('is_ticket', "=", "1")->get();
        return view("admin.commission_ticket.add", compact('product'));
    }

    public function storeTicket(Request $request)
    {

        $data = $this->validate($request, ['rate' => 'required|integer|not_in:0', 'ticket_id' => 'required|not_in:0',

        ], ["rate.required" => "Rate Fild Accept Only Number",

        ]);

        $input = $request->all();
        $data = Commission_ticket::create($input);
        $data->save();
        $pro = Product::findOrFail($input['ticket_id']);
        $pro->commission_rate = $input['rate'];
        $pro->update();
        
        return redirect('admin/commissionticket')
            ->with('updated', 'Commission has been updated');
    }

    public function editTicket($id)
    {
        $product = \App\Product::where('is_ticket', "=", "1")->get();
        $commission = Commission_ticket::findOrFail($id);
        return view("admin.commission_ticket.edit", compact("commission", "product"));
    }

    public function updateTicket(Request $request, $id)
    {
        $data = $this->validate($request, ['rate' => 'required|integer', 'ticket_id' => 'required',

        ], ["rate.required" => "Rate Fild Accept Only Number",

        ]);

        $tax = Commission_ticket::findOrFail($id);
        $input = $request->all();
        $tax->update($input);
        
        $pro = Product::findOrFail($input['ticket_id']);
        $pro->commission_rate = $input['rate'];
        $pro->update();

        return redirect('admin/commissionticket')->with('updated', 'Commission has been updated');

    }

    public function destroyTicket($id)
    {
        $daa = new Commission_ticket;
        $obj = $daa->findorFail($id);
        $value = $obj->delete();
        if ($value)
        {
            session()->flash("deleted", "Commission Has Been deleted");
            return redirect("admin/commissionticket");
        }
    }

    // Bid Commission

    public function indexBid()
    {
        $commissions = Commission_bid::all();
        return view("admin.commission_bid.index", compact("commissions"));
    }

    public function createBid()
    {
        $product = \App\Product::where('is_bid', "=", "1")->get();
        return view("admin.commission_bid.add", compact('product'));
    }

    public function storeBid(Request $request)
    {

        $data = $this->validate($request, ['rate' => 'required|integer|not_in:0', 'bid_id' => 'required|not_in:0',

        ], ["rate.required" => "Rate Fild Accept Only Number",

        ]);

        $input = $request->all();
        $data = Commission_bid::create($input);
        $data->save();
        $pro = Product::findOrFail($input['bid_id']);
        $pro->commission_rate = $input['rate'];
        $pro->update();
        
        return redirect('admin/commissionbid')
            ->with('updated', 'Commission has been updated');
    }

    public function editBid($id)
    {
        $product = \App\Product::where('is_bid', "=", "1")->get();
        $commission = Commission_bid::findOrFail($id);
        return view("admin.commission_bid.edit", compact("commission", "product"));
    }

    public function updateBid(Request $request, $id)
    {
        $data = $this->validate($request, ['rate' => 'required|integer', 'bid_id' => 'required',

        ], ["rate.required" => "Rate Fild Accept Only Number",

        ]);

        $tax = Commission_bid::findOrFail($id);
        $input = $request->all();
        $tax->update($input);
        
        $pro = Product::findOrFail($input['bid_id']);
        $pro->commission_rate = $input['rate'];
        $pro->update();

        return redirect('admin/commissionbid')->with('updated', 'Commission has been updated');

    }

    public function destroyBid($id)
    {
        $daa = new Commission_bid;
        $obj = $daa->findorFail($id);
        $value = $obj->delete();
        if ($value)
        {
            session()->flash("deleted", "Commission Has Been deleted");
            return redirect("admin/commissionbid");
        }
    }
}

