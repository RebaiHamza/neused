@foreach($sellercanorders as $key=> $order)
	<div class="modal fade" id="ordertrack{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog model-lg width60" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Track REFUND FOR ORDER <b>#{{ $inv_cus->order_prefix.$order->order->order_id }}</b> | TXN ID : <b>{{  $order->transaction_id }}</b></h4>
	      </div>
	      <div class="modal-body">
	       	 <div id="refundArea{{ $order->id }}">
	       	 	
	       	 </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button onclick="trackrefund('{{ $order->id }}')" type="button" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i> REFRESH</button>
	      </div>
	    </div>
	  </div>
	</div>

		<!-- UPDATE ORDER Modal -->
		<div data-backdrop="static" data-keyboard="false" class="modal fade" id="orderupdate{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg width90" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">View Cancelled ORDER Details: <b>#{{ $inv_cus->order_prefix.$order->order->order_id }}</b></h4>
			      </div>
			      <div class="modal-body">
			        <h4><b>Order Summary</b></h4>
					<hr>
					<div class="row">
						<div class="col-md-3"><b>Customer name</b></div>
						<div class="col-md-3"><b>Cancel Order Date</b></div>
						<div class="col-md-3"><b>Cancel Order Total</b></div>
						<div class="col-md-3"><b>REFUND Transcation ID /REF. ID</b></div>

							@php
							    
							    	$realamount = round($order->singleorder->qty*$order->singleorder->price+$order->singleorder->tax_amount+$order->singleorder->shipping,2);
							    
								
							@endphp

						<div class="col-md-3">{{ $user = App\User::find($order->order->user_id)->name }}</div>
						<div class="col-md-3">{{ date('d-m-Y @ h:i A',strtotime($order->created_at)) }}</div>
						<div class="col-md-3">
							<i class="{{ $order->order->paid_in }}"></i>{{ $realamount }}
							
							@if($order->amount != $realamount)
								| Refunded Amount (<i class="{{ $order->order->paid_in }}"></i>{{$order->amount}})
							@endif
						</div>
						<div class="col-md-3"><b>{{ $order->transaction_id }}</b>
						</div>

						<div class="col-md-3 margin-top-15">
							<p><b>REFUND METHOD:</b></p>
							
							@if($order->order->payment_method !='COD' && $order->method_choosen != 'bank')
								{{ ucfirst($order->method_choosen) }} ({{ $order->order->payment_method }})
							@elseif($order->method_choosen == 'bank')
							{{ ucfirst($order->method_choosen) }}
							@else
								No Need for COD Orders
							@endif
							
						</div>
						
						<div class="col-md-6 margin-top-15">
							<p><b>Cancelation Reason:</b></p>
							<blockquote>
								{{ $order->comment }}
							</blockquote>
						</div>

						@if($order->method_choosen == 'bank')
							@php
								$bank = App\Userbank::where('id','=',$order->bank_id)->first();
							@endphp
							<div class="col-md-4">
								@if(isset($bank))
								<label>Refund {{ ucfirst($order->is_refunded) }} In {{ $bank->user->name }}'s Account Following are details:</label>
								

								<div class="well">
									
									<p><b>A/C Holder Name: </b>{{$bank->acname}}</p>
									<p><b>Bank Name: </b>{{ $bank->bankname }}</p>
									<p><b>Account No: </b>{{ $bank->acno }}</p>
									<p><b>IFSC Code: </b>{{ $bank->ifsc }}</p>


								</div>
								@else
									<p>User Deleted bank ac</p>
								@endif
							</div>
						@endif

						
						
					</div>
					<hr>
					<h4><b>Items</b></h4>

					@php
					   $inv = App\InvoiceDownload::findorfail($order->inv_id);
						$orivar = App\AddSubVariant::withTrashed()->findorfail($inv->variant_id);
						$varcount = count($orivar->main_attr_value);
						$i=0;
					@endphp

					<div class="row">
						<div class="col-md-4">

							<div class="row">
								<div class="col-md-2">
									<img class="pro-img" src="{{url('variantimages/'.$orivar->variantimages['image2'])}}" alt="">
								</div>
								<div class="col-md-6">
									<a class="margin-top-15 color111" target="_blank" title="Click to view"><b>{{$orivar->products->name}}</b>

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

		                    </small></a>
							<br>
		                    <small class="margin-top-15"><b>Sold By:</b> {{$orivar->products->store->name}}
		                    </small>
		                    <br>
		                     <small class="margin-top-15"><b>Qty:</b> {{ $order->singleorder->qty }}
		                     </small>
								</div>
							</div>
							
							
					
					
						</div>

						<div class="col-md-2 margin-top-15">
							<i class="{{ $order->order->paid_in }}"></i> {{ $realamount }}
						</div>
					
						<div class="col-md-2">
							<label for="">UPDATE TXN ID OR REF. NO:</label>
							<input type="text" readonly name="transaction_id" class="form-control" value="{{ $order->transaction_id }}" class="form-control">
							<br>
							
							<label>Amount :</label>
							<div class="input-group">
								 <div class="input-group-addon"><i class="{{ $order->order->paid_in }}"></i></div>
							<input readonly type="text" name="amount" {{ $order->method_choosen == 'bank' ? "" : "readonly" }} class="form-control" value="{{ $order->amount }}" class="form-control">
							</div>
							<small class="help-block">
								
								(UPDATE AMOUNT IF CHANGES OR TRANSCATION FEE IS CHARGED)

							</small>
							
					   </div>
					
						@csrf
						<div class="col-md-2">
							<label for="">UPDATE REFUND STATUS:</label>
							@if($order->order->payment_method != 'COD')
								@if($order->is_refunded == 'completed')
									<input class="form-control" readonly type="text" value="Completed">
								@elseif($order->is_refunded == 'pending')
									<input class="form-control" readonly="" type="text" value="Pending">
								@endif
							
							@else
							
								
								@if($order->is_refunded == 'completed')
									<input class="form-control" readonly type="text" value="Completed"/>
								@endif
								
							
							@endif

							<br>
							
							<label>Transcation Fee:</label>
							<div class="input-group">
								 <div class="input-group-addon"><i class="{{ $order->order->paid_in }}"></i></div>
							<input readonly="" placeholder="0.00" type="text" name="txn_fee" class="form-control" value="{{ $order->txn_fee }}" class="form-control">
						   </div>
							<small class="help-block">
								
								(UPDATE TRANSCATION FEE IF CHARGED)

							</small>
							
							
								
							
						</div>

						<div class="col-md-2">
							<label>
								(UPDATE ORDER STATUS)
							</label>
							@if($order->order->payment_method !='COD')
								
								<input type="text" readonly="" class="form-control" value="{{ ucfirst($order->singleorder->status) }}">
								
							@else
								<input type="text" readonly="" class="form-control" value="{{ ucfirst($order->singleorder->status) }}">
							@endif
							
						</div>

						
					</div>

					

			      </div>
			      <div class="modal-footer">
			        
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      
			      </div>
			    </div>
			  </div>
</div>
@endforeach
<!-- END Track-->