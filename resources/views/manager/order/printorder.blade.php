<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Print Order: {{ $inv_cus->order_prefix.$order->order_id }}</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{url('css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{ url('admin/css/style.css') }}">
  
  </head>
  
  <body>
  			@php
              $x = App\InvoiceDownload::where('order_id','=',$order->id)->where('vender_id',Auth::user()->id)->get();
             @endphp
  	<div class="container">
  		<br>
  		<a href="{{ route('seller.view.order',$order->order_id) }}" title="Go back" class="p_btn2 pull-left btn btn-md btn-default"><i class="fa fa-reply" aria-hidden="true"></i>
  	</a>

  	 <button title="Print Order" onclick="printIT()" class="p_btn pull-right btn btn-md btn-default"><i class="fa fa-print"></i></button>
    <h3 class="h1" align="center">Order: {{ $inv_cus->order_prefix.$order->order_id }}</h3>
   
    <table class="table table-striped">
				<thead>	
					<tr>
						<th>Customer Information</th>
						<th>Shipping Address</th>
						<th>Billing Address</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>
							@php
								$user = App\User::findorfail($order->user_id);

			                    if($user->country_id !=''){
									$c = App\Allcountry::where('id',$user->country_id)->first()->nicename;
				                    $s = App\Allstate::where('id',$user->state_id)->first()->name;
				                    $ci = App\Allcity::where('id',$user->city_id)->first()->name;
								}
                 
							@endphp

							<p><b>{{$user->name}}</b></p>
							<p>{{ $user->email }}</p>
							<p>{{$user->mobile}}</p>
							@if(isset($c))
								<p><i class="fa fa-map-marker" aria-hidden="true"></i> {{$ci}}, {{ $s }}, {{ $c }}</p>
							@endif

						</td>
						<td>
							<p><b>{{ $address->name }}, {{ $address->phone }}</b></p>
							<p class="font-weight">{{ strip_tags($address->address) }},</p>
							@php
								$user = App\User::findorfail($order->user_id);

			                    $c = App\Allcountry::where('id',$address->country_id)->first()->nicename;
			                    $s = App\Allstate::where('id',$address->state_id)->first()->name;
			                    $ci = App\Allcity::where('id',$address->city_id)->first()->name;
                 
							@endphp
							<p class="font-weight">{{ $ci }}, {{ $s }}, {{ $ci }}</p>
							<p class="font-weight">{{ $address->pin_code }}</p>
						</td>
						<td>
							<p><b>{{ $order->billing_address['firstname'] }}, {{ $order->billing_address['mobile'] }}</b></p>
							<p class="font-weight">{{ strip_tags($order->billing_address['address']) }},</p>
							@php
								

			                    $c = App\Allcountry::where('id',$order->billing_address['country_id'])->first()->nicename;
			                    $s = App\Allstate::where('id',$order->billing_address['state'])->first()->name;
			                    $ci = App\Allcity::where('id',$order->billing_address['city'])->first()->name;
                 
							@endphp
							<p class="font-weight">{{ $ci }}, {{ $s }}, {{ $ci }}</p>
							<p class="font-weight">{{ $order->billing_address['pincode'] }}</p>
						</td>
					</tr>
				</tbody>
			</table>

			
			
			<table class="table table-striped">
				<thead>
					<tr>
						
						<th>Order Summary</th>
						<th></th>
						<th></th>
						<th></th>

					</tr>
				</thead>

				<tbody>
					<tr>
						<td>
							<p><b>Total Qty:</b> {{ $x->sum('qty')}}</p>
							</p>
							<p><b>Order Total: <i class="{{ $order->paid_in }}"></i> {{ $total }} </b></p>
							<p><b>Payment Recieved:</b> {{ ucfirst($order->payment_receive)  }}</p>
						</td>

						<td>
							<p><b>Payment Method: </b> {{ ucfirst($order->payment_method) }}
							<p><b>TXN ID:</b> <b><i>{{ $order->transaction_id }}</i></b></p>

							
						</td>

						<td>
							<p><b>Order Date:</b> {{ date('d/m/Y @ h:i a', strtotime($order->created_at)) }}</p>
						</td>
					</tr>
				</tbody>
			</table>
			
			<table class="table table-striped">
				<thead>
					<th>Order Details</th>
				</thead>
			</table>
				
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Invoice No</th>
						<th>Item Image</th>
						<th>Item Info</th>
						<th>Qty</th>
						<th>Status</th>
						<th>Pricing & TAX</th>
						<th>Total</th>
						
					</tr>
				</thead>
				<tbody>
					@foreach($x as $invoice)
						<tr>
							<td>
								<i>{{ $inv_cus->prefix }}{{ $invoice->inv_no }}{{ $inv_cus->postfix }}</i>
							</td>

							@php
									
									$orivar = App\AddSubVariant::withTrashed()->findorfail($invoice->variant_id);
									$i=0;
									$varcount = count($orivar->main_attr_value);
                  				@endphp

							<td>
								<img class="pro_img" width="70px" height="70px" src="{{url('variantimages/'.$orivar->variantimages['image2'])}}" alt="">
							</td>

							<td>
								
								
								<b>{{$orivar->products->name}}</b>

								<small>
								(@foreach($orivar->main_attr_value as $key=> $orivars)
			                    <?php $i++; ?>

			                        @php
			                          $getattrname = App\ProductAttributes::where('id',$key)->first()->attr_name;
			                          $getvarvalue = App\ProductValues::where('id',$orivars)->first();
			                        @endphp

			                        @if($i < $varcount)
			                          @if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null)
			                            @if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour")
			                            {{ $getvarvalue->values }},
			                            @else
			                            {{ $getvarvalue->values }}{{ $getvarvalue->unit_value }},
			                            @endif
			                          @else
			                            {{ $getvarvalue->values }},
			                          @endif
			                        @else
			                          @if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null)
			                            @if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour")
                                {{ $getvarvalue->values }}
                                @else
                                  {{ $getvarvalue->values }}{{ $getvarvalue->unit_value }}
                                  @endif
			                          @else
			                            {{ $getvarvalue->values }}
			                          @endif
			                        @endif
			                    @endforeach
			                    )

			                    </small>
								</a>
									<br>
								<small><b>Sold By:</b> {{$orivar->products->store->name}}</small>
									<br>
								<small><b>Price: </b> <i class="{{ $invoice->order->paid_in }}"></i>
										

											{{ round(($invoice->price),2) }}

								</small>
									<br>
								<small><b>Tax:</b> <i class="{{ $invoice->order->paid_in }}"></i>{{ round(($invoice->tax_amount/$invoice->qty),2) }}
										
										@if($invoice->variant->products->tax_r !='')
											({{ $invoice->variant->products->tax_r.'% '.$invoice->variant->products->tax_name }} )

										@endif
									</small>

							</td>

							

							<td>
								{{ $invoice->qty }}
							</td>

							<td>
								@if($invoice->status == 'delivered')
									<span class="label label-success">{{ ucfirst($invoice->status) }}</span>
								@elseif($invoice->status == 'processed')
									<span class="label label-info">{{ ucfirst($invoice->status) }}</span>
								@elseif($invoice->status == 'shipped')
									<span class="label label-primary">{{ ucfirst($invoice->status) }}</span>
								@elseif($invoice->status == 'return_request')
									<span class="label label-warning">Return Request</span>
								@elseif($invoice->status == 'returned')
									<span class="label label-danger">Returned</span>
								@elseif($invoice->status == 'cancel_request')
									<span class="label label-warning">Cancelation Request</span>
								@elseif($invoice->status == 'canceled')
									<span class="label label-danger">Canceled</span>
								@else
									<span class="label label-default">{{ ucfirst($invoice->status) }}</span>
								@endif
							</td>

							<td>
								<b>Total Price:</b> <i class="{{ $invoice->order->paid_in }}"></i>
								
									{{ round(($invoice->price*$invoice->qty),2) }}
								
								<p></p>
								<b>Total Tax:</b> <i class="{{ $invoice->order->paid_in }}"></i>{{ round(($invoice->tax_amount),2) }}
								<p></p>
								<b>Shipping Charges:</b> <i class="{{ $invoice->order->paid_in }}"></i>{{ round($invoice->shipping,2) }}
								<p></p>


								<small class="help-block">(Price & TAX Multiplied with Quantity)</small>
								<p></p>
								
								
							</td>

								<td>
								<i class="{{ $invoice->order->paid_in }}"></i>
								   
									{{ round($invoice->qty*$invoice->price+$invoice->tax_amount+$invoice->shipping,2) }}
									
								<br>
								<small>(Incl. of TAX & Shipping)</small>
							</td>
							

							
						</tr>
					@endforeach

					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><b>Handling Charge</b></td>
						<td><b><i class="{{ $invoice->order->paid_in }}"></i>{{ $total-$hc }}</b></td>
						
					</tr>


					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><b>Handling Charge</b></td>
						<td><b><i class="{{ $invoice->order->paid_in }}"></i>{{ round($hc,2) }}</b></td>
						
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><b>Grand Total</b></td>
						<td><b><i class="{{ $order->paid_in }}"></i>{{ $total}}</b></td>
						
					</tr>
				</tbody>
			</table>
  	</div>
  	

 <script src="{{url('js/jquery.js')}}"></script>
 <script src="{{url('js/bootstrap.min.js')}}"></script>
 <script src="{{ url('js/script.js') }}"></script>
 </body>

</html>