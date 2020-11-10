<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Order;
use App\InvoiceDownload;
use App\Address;
use App\Invoice;
use App\OrderActivityLog;
use Mail;
use App\Mail\OrderStatus;
use App\Product;
use App\AddSubVariant;
use App\ProductAttributes;
use App\ProductValues;
use App\User;
use App\Notifications\SendOrderStatus;
use App\PendingPayout;
use App\multiCurrency;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class VenderOrderController extends Controller
{

    public function codorderconfirm(Request $request,$id){

      if(Auth::check()){

        $order = Order::find($id);

        $order->payment_receive = $request->status;

        $order->save();

        if($order){
           return response()->json(['showstatus' => 'Updated','status' => true]);
        }else{
           return response()->json(['showstatus' => 'Failed','status' => false]);
        }

      }else{
        return back()->with('warning','Access Denied');
      }

    }


    public function viewOrder($id)
    {	

    	 $order = Order::where('order_id',$id)->where('status','=','1')->first();

      if(!isset($order)){
        notify()->error('Order not found !');
        return redirect('/');
      }
      
      $query = InvoiceDownload::query();

      $x = $query->where('order_id','=',$order->id)->where('vender_id',Auth::user()->id)->get();

      $total = 0;

      $hc=0;

      foreach ($x as $key => $value) {
          $total = $total+$value->qty*$value->price+$value->tax_amount+$value->shipping+$value->handlingcharge;
          $hc = $hc+$value->handlingcharge;
      }

    	$address = Address::findorfail($order->delivery_address);
      $actvitylogs = OrderActivityLog::where('order_id',$order->id)->orderBy('id','desc')->get();
    	$inv_cus = Invoice::first();
    	return view('seller.order.show',compact('total','x','order','hc','address','inv_cus','actvitylogs'));
    }

    public function printOrder($id){
       $order = Order::findOrFail($id);
       $inv_cus = Invoice::first();
       $address = Address::findOrFail($order->delivery_address);
       $x = InvoiceDownload::where('order_id','=',$order->id)->where('vender_id',Auth::user()->id)->get();
       $total = 0;
       $hc=0;
        foreach ($x as $key => $value) {
            $total = $total+$value->qty*$value->price+$value->tax_amount+$value->shipping+$value->handlingcharge;
            $hc = $hc+$value->handlingcharge;
        }
       return view('seller.order.printorder',compact('total','address','hc','inv_cus','order'));
    }

    public function printInvoice($orderID, $id){
        $getInvoice = InvoiceDownload::where('id',$id)->first();
        $inv_cus = Invoice::first();
        $address = Address::findOrFail($getInvoice->order->delivery_address);        
        $invSetting = Invoice::where('user_id',$getInvoice->vender_id)->first();

        return view('seller.order.printinvoice',compact('invSetting','address','getInvoice','inv_cus'));
    }

    public function editOrder($orderid){
      $order = Order::where('order_id',$orderid)->first();
      $inv_cus = Invoice::first();
      $address = Address::findOrFail($order->delivery_address);
      $actvitylogs = OrderActivityLog::where('order_id',$order->id)->orderBy('id','desc')->get();
      $x = InvoiceDownload::where('order_id','=',$order->id)->where('vender_id',Auth::user()->id)->get();
      $total = 0;
      $hc = 0;

      foreach ($x as $key => $value) {
          $total = $total+$value->qty*$value->price+$value->tax_amount+$value->shipping+$value->handlingcharge;
          $hc = $hc+$value->handlingcharge;
      }


      return view('seller.order.edit',compact('x','total','order','address','hc','inv_cus','actvitylogs'));
    }

    public function delete($id){
     
      if(Auth::check()){
          if(Auth::user()->role_id == "v" || Auth::user()->role_id == 'a'){

               $inv = Order::findOrFail($id);
            if(Auth::user()->id == $inv->vender_id || Auth::user()->role_id == 'a'){

               $order = Order::findOrFail($id);
               $order->status = 0;
               $order->save();

               return back()->with('deleted','Order has been deleted');

            }else{
              return abort(404);
            }
          }else{
            return abort(404);
          }
        }else{
          return abort(404);
        }
    }

    public function updateStatus(Request $request, $id){
       
        if(Auth::check()){

          if(Auth::user()->role_id == "v" || Auth::user()->role_id == 'a'){
            $inv = InvoiceDownload::findOrFail($id);
            if(Auth::user()->id == $inv->vender_id || Auth::user()->role_id == 'a'){
              $inv->status = $request->status;
              $inv->save();
              $inv_cus = Invoice::first();
              $status = ucfirst($request->status);

              $create_activity = new OrderActivityLog();

              $create_activity->order_id = $inv->order_id;
              $create_activity->inv_id   = $inv->id;
              $create_activity->user_id  = Auth::user()->id;
              $create_activity->variant_id = $inv->variant_id;
              $create_activity->log      = $status;

              $create_activity->save();

              $lastlogdate = date('d-m-Y | h:i:a',strtotime($create_activity->updated_at)) ;

              $orivar = AddSubVariant::withTrashed()->find($create_activity->variant_id);
              $i=0;
              $varcount = count($orivar->main_attr_value);
              $productname = $orivar->products->name;

              foreach($orivar->main_attr_value as $key=> $orivars){
                          
                    $i++; 

                    $getattrname = ProductAttributes::where('id',$key)->first()->attr_name;
                    $getvarvalue = ProductValues::where('id',$orivars)->first();
                            

                      if($i < $varcount){
                                if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null){
                                  if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour"){

                                      $var_main =  $getvarvalue->values;

                                  }else{
                                    $var_main =  $getvarvalue->values.$getvarvalue->unit_value;
                                 }
                     }else{
                          $var_main =  $getvarvalue->values;
                     }
                               
                              }else{

                                if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null){
                                  
                                  if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour"){

                                    $var_main = $getvarvalue->values;
                                  }else{
                                  $var_main =  $getvarvalue->values.$getvarvalue->unit_value;
                                }

                                }else{
                                  $var_main =  $getvarvalue->values;
                                }
                                
                             }
              }

              /*Sending mail & Notifiation on specific event perform*/
               
                $order_id = $inv->order->order_id;
              

                if($request->status == 'shipped'){

                  $newpendingpay = PendingPayout::where('orderid','=',$inv->id)->first();

                  if(isset($newpendingpay)){
                        $newpendingpay->delete();
                  }
                
                /*Send Mail to User*/
                      try{
                        $e = Address::findOrFail($inv->order->delivery_address)->email;
                        Mail::to($e)->send(new OrderStatus($inv_cus,$inv,$status));
                      }catch(\Swift_TransportException $e){
                        //Throw exception if you want //
                      }
                /*End*/

                /*Sending Notification*/
                  User::find($inv->order->user_id)->notify(new SendOrderStatus($productname,$var_main,$status,$order_id));
                /*End*/

                }elseif($request->status == 'processed'){

                  $newpendingpay = PendingPayout::where('orderid','=',$inv->id)->first();

                  if(isset($newpendingpay)){
                        $newpendingpay->delete();
                  }

                }
                elseif($request->status == 'pending'){

                  $newpendingpay = PendingPayout::where('orderid','=',$inv->id)->first();

                  if(isset($newpendingpay)){
                        $newpendingpay->delete();
                  }

                }
                elseif($request->status == 'delivered'){
                 
                  //Register a record in pending payout if seller system enable (not for admin)
                  if($inv->seller->role_id == 'v'){

                    $from = $inv->order->paid_in_currency;
                   
                    $defCurrency = multiCurrency::where('default_currency','=',1)->first();

                    $defcurrate = currency(1.00, $from = $from, $to = $defCurrency->currency->code, $format = false);

                    $actualprice    =  sprintf("%2.f",($inv->price*$inv->qty)*$defcurrate);
                    $actualtax      =  sprintf("%2.f",($inv->tax_amount*$inv->qty)*$defcurrate);
                    $actualshipping =  sprintf("%2.f",$inv->shipping*$defcurrate);

                    $actualtotal = $actualprice+$actualtax+$actualshipping;

                    $defCurrency = multiCurrency::where('default_currency','=',1)->first();
                    $newpendingpay = new PendingPayout;
                    $newpendingpay->orderid = $inv->id;
                    $newpendingpay->sellerid = $inv->seller->id;
                    $newpendingpay->paidby = Auth::user()->id;
                    $newpendingpay->paid_in = $defCurrency->currency->code;
                    $newpendingpay->subtotal = $actualprice;
                    $newpendingpay->tax    = $actualtax;
                    $newpendingpay->shipping = $actualshipping;
                    $newpendingpay->orderamount = $actualtotal;

                    $newpendingpay->save();

                  }
                    
                //End
                
                /*Send Mail to User*/
                      try{
                        $e = Address::findOrFail($inv->order->delivery_address)->email;
                        Mail::to($e)->send(new OrderStatus($inv_cus,$inv,$status));
                      }catch(\Swift_TransportException $e){
                        //Throw exception if you want //
                      }
                /*End*/

                /*Sending Notification*/
                  User::find($inv->order->user_id)->notify(new SendOrderStatus($productname,$var_main,$status,$order_id));
                /*End*/

                }elseif($request->status == 'cancel_request'){

                  $newpendingpay = PendingPayout::where('orderid','=',$inv->id)->first();

                  if(isset($newpendingpay)){
                        $newpendingpay->delete();
                  }
                
                /*Send Mail to User*/
                      $status = 'Request for Cancellation';
                      try{
                        $e = Address::findOrFail($inv->order->delivery_address)->email;
                        Mail::to($e)->send(new OrderStatus($inv_cus,$inv,$status));
                      }catch(\Swift_TransportException $e){
                        //Throw exception if you want //
                      }
                /*End*/

                }
                elseif($request->status == 'canceled'){

                  $newpendingpay = PendingPayout::where('orderid','=',$inv->id)->first();

                  if(isset($newpendingpay)){
                        $newpendingpay->delete();
                  }
                
                /*Send Mail to User*/
                      try{
                        $e = Address::findOrFail($inv->order->delivery_address)->email;
                        Mail::to($e)->send(new OrderStatus($inv_cus,$inv,$status));
                      }catch(\Swift_TransportException $e){
                        //Throw exception if you want //
                      }
                /*End*/

                /*Sending Notification*/
                  User::find($inv->order->user_id)->notify(new SendOrderStatus($productname,$var_main,$status,$order_id));
                /*End*/

                }elseif($request->status == 'return_request'){

                  $newpendingpay = PendingPayout::where('orderid','=',$inv->id)->first();

                  if(isset($newpendingpay)){
                        $newpendingpay->delete();
                  }
                
                /*Send Mail to User*/
                      $status = 'Request for return';

                      try{
                        $e = Address::findOrFail($inv->order->delivery_address)->email;
                        Mail::to($e)->send(new OrderStatus($inv_cus,$inv,$status));
                      }catch(\Swift_TransportException $e){
                        //Throw exception if you want //
                      }

                /*End*/

                }elseif ($request->status == 'returned') {

                  $newpendingpay = PendingPayout::where('orderid','=',$inv->id)->first();

                  if(isset($newpendingpay)){
                        $newpendingpay->delete();
                  }
                
                /*Send Mail to User*/
                      try{
                        $e = Address::findOrFail($inv->order->delivery_address)->email;
                        Mail::to($e)->send(new OrderStatus($inv_cus,$inv,$status));
                      }catch(\Swift_TransportException $e){
                        //Throw exception if you want //
                      }
                /*End*/

                /*Sending Notification*/
                  User::find($inv->order->user_id)->notify(new SendOrderStatus($productname,$var_main,$status,$order_id));
                /*End*/

                }


              /*end*/ 
              return response()->json(['variant' => $var_main,  'proname' => $productname, 'lastlogdate' => $lastlogdate, 'dstatus' => $status, 'id' => $inv->id, 'status' => $request->status,  'invno' => $inv_cus->prefix.$inv->inv_no.$inv_cus->postfix] );

            }else{
              return abort(404);
            }
          }else{
            return abort(404);
          }
        }else {
          return abort(404);
        }

    }
}
