<?php
namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;
use App\Notifications\SendOrderStatus;
use App\User;
use App\Address;
use App\Invoice;
use PDF;
use App\InvoiceDownload;
use App\OrderActivityLog;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\CanceledOrders;
use App\FullOrderCancelLog;
use View;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_orders = Order::orderBy('id', 'desc')->where('status','=',1)->get();
        $inv_cus = Invoice::first();
        return view("admin.order.index", compact("all_orders", 'inv_cus'));
    }

    public function bulkdelete(Request $request)
    {

        $validator = Validator::make($request->all() , ['checked' => 'required', ]);

        if ($validator->fails())
        {

            return back()->with('warning', 'Please select one of them to delete');
        }

        foreach ($request->checked as $checked)
        {
            $order = Order::findorfail($checked);
            $order->status = 0;
            $order->save();
        }

        return redirect()
            ->route('order.index')
            ->with('added', 'Selected Orders Deleted Successfully !');

    }

    public function viewUserOrder($orderid)
    {

        require_once ('price.php');
        $order = Order::where('order_id', $orderid)->where('status','1')->first();

        if (!isset($order))
        {
            notify()->error('Order not found or has been deleted !');
            return redirect('/');
        }

        $inv_cus = Invoice::first();
        $address = Address::findOrFail($order->delivery_address);
        if (Auth::check())
        {

            if (Auth::user()->role_id == "a" || Auth::user()->id == $order->user_id)
            {
                $user = Auth::user();
                return view('user.viewfullorder', compact('conversion_rate', 'order', 'user', 'address', 'inv_cus'));
            }
            else
            {
                return abort(404);
            }

        }
        else
        {
            return abort(404);
        }

    }

    public function getUserInvoice($invid)
    {
        $inv_cus = Invoice::first();
        $getInvoice = InvoiceDownload::findOrFail($invid);
        $address = Address::findOrFail($getInvoice->order->delivery_address);
        $invSetting = Invoice::where('user_id', $getInvoice->vender_id)->first();

        if (Auth::check())
        {

            if(Auth::user()->role_id == "a" || Auth::user()->id == $getInvoice->order->user_id)
            {
                if ($getInvoice->status == 'delivered' || $getInvoice->status == 'return_request')
                {
                    $user = Auth::user();
                    return view('user.userinvoice', compact('invSetting', 'getInvoice', 'inv_cus', 'address'));
                }
                else
                {
                    notify()->error('Invoice not available yet !');
                    return back();
                }
            }
            else
            {
                return abort(404);
            }

        }
        else
        {
            return abort(404);
        }

    }

    public function getCancelOrders()
    {
        $inv_cus = Invoice::first();
        $cOrders = CanceledOrders::orderBy('id', 'desc')->get();
        $comOrder = FullOrderCancelLog::orderBy('id', 'desc')->get();
        $partialcount = CanceledOrders::where('read_at', '=', NULL)->count();
        $fullcount = FullOrderCancelLog::where('read_at', '=', NULL)->count();
        return view('admin.order.canorderindex', compact('cOrders', 'comOrder', 'inv_cus', 'partialcount', 'fullcount'));
    }

    public function pendingorder(){

        $inv_cus = Invoice::first();

        $pendingorders = Order::join('invoice_downloads','orders.id','=','invoice_downloads.order_id')->join('users','users.id','=','orders.user_id')->where('invoice_downloads.status','=','pending')->where('orders.status','=','1')->select('orders.id as id','orders.order_id as orderid','orders.paid_in as paid_in','order_total as total','users.name as customername','users.id as userid','orders.payment_method as payment_method','orders.created_at as orderdate','orders.handlingcharge as handlingcharge')->get();

        $orders = $pendingorders->unique('id');

        return view('admin.order.pendingorder',compact('orders','inv_cus'));

    }

    public function QuickOrderDetails(Request $request){

            $order = Order::find($request->orderid);
            $inv_cus = Invoice::first();

            if(isset($order)){

                return response()->json(['orderview' => View::make('admin.order.quickorder',compact('order','inv_cus'))->render()]);

            }else{
                return response()->json(['code' => 404, 'msg' => 'No Orders Found !']);
            }

    }

    

    public function show($id)
    {
        $order = Order::where('order_id', $id)->first();

        if (!isset($order) || $order->status != '1')
        {
            return back()->with('delete', 'Order not found or has been deleted !');
        }

        $actvitylogs = OrderActivityLog::where('order_id', $order->id)
            ->orderBy('id', 'desc')
            ->get();
        $inv_cus = Invoice::first();

        $address = Address::findOrFail($order->delivery_address);
        return view('admin.order.show', compact('order', 'address', 'inv_cus', 'actvitylogs'));
    }

    public function editOrder($orderid)
    {

        $order = Order::where('order_id', $orderid)->first();
        $inv_cus = Invoice::first();
        $actvitylogs = OrderActivityLog::where('order_id', $order->id)
            ->orderBy('id', 'desc')
            ->get();
        $address = Address::findOrFail($order->delivery_address);
        return view('admin.order.edit', compact('order', 'address', 'inv_cus', 'actvitylogs'));
    }

    public function printOrder($id)
    {
        $order = Order::findOrFail($id);
        $inv_cus = Invoice::first();
        $address = Address::findOrFail($order->delivery_address);

        return view('admin.order.printorder', compact('address', 'inv_cus', 'order'));
    }

    public function printInvoice($orderID, $id)
    {
        $getInvoice = InvoiceDownload::where('id', $id)->first();
        $inv_cus = Invoice::first();
        $address = Address::findOrFail($getInvoice
            ->order
            ->delivery_address);
        $invSetting = Invoice::where('user_id', $getInvoice->vender_id)
            ->first();
        return view('admin.order.printinvoices', compact('invSetting', 'address', 'getInvoice', 'inv_cus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $data = Order::create($input);
        $data->save();
        return back()
            ->with('updated', 'Order has been updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $order = Order::findOrFail($id);
        $order_status = order::all();
        return view("admin.order.edit", compact("order", "order_status"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $input = $request->all();
        $order->update($input);

        $sub = new Order;
        $obj = $sub->find($id);
        $obj->updated_at = date("Y-m-d h:i:s");
        $value = $obj->save();

        if ($request->order_status == "pending")
        {

            /*Sending to user*/
            User::find($order->user_id)
                ->notify(new SendOrderStatus($order));
            /*END*/
        }

        if ($request->order_status == "processed")
        {

            /*Sending to user*/
            User::find($order->user_id)
                ->notify(new SendOrderStatus($order));
            /*END*/
        }

        if ($request->order_status == "dispatched")
        {

            /*Sending to user*/
            User::find($order->user_id)
                ->notify(new SendOrderStatus($order));
            /*END*/
        }

        if ($request->order_status == "shipped")
        {

            /*Sending to user*/
            User::find($order->user_id)
                ->notify(new SendOrderStatus($order));
            /*END*/
        }

        if ($request->order_status == "delivered")
        {

            /*Sending to user*/
            User::find($order->user_id)
                ->notify(new SendOrderStatus($order));
            /*END*/
        }

        if ($request->order_status == "cancelled")
        {

            /*Sending to user*/
            User::find($order->user_id)
                ->notify(new SendOrderStatus($order));
            /*END*/
        }

        return redirect('admin/order')->with('updated', 'Order Status has been updated !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $order = Order::findorFail($id);

        $order->status = 0;

        $order->save();

        session()
            ->flash("deleted", "Order Has Been deleted");
        return redirect("admin/order");

    }

    public function pending()
    {
        $orders = Order::where('order_status', 'pending')->get();
        return view("admin.order.index", compact("orders"));
    }

    public function deliverd()
    {
        $orders = Order::where('order_status', 'delivered')->get();
        return view("admin.order.index", compact("orders"));
    }

}

