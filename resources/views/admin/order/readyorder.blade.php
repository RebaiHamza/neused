@extends("admin.layouts.master")
@section('title','Ready to ship Orders - ')
@section("body")
		
		@if(count($orders)>0)
		
		<div class="row no-gutter">

				<div class="col-md-12">
					@foreach($orders as $order)
			 
						<div onclick="orderget({{ $order['id'] }})" id="orderbox{{ $order['id'] }}" class="orderbox col-md-12">
							<div class="box box-solid">
								<div class="box-body admin-order">
									<span class="pull-right h4" href=""><sup><i class="{{ $order['paid_in'] }}"></i> </sup>{{ $order['total'] + $order['handlingcharge'] }}</span>
									<h4>#{{ $inv_cus['order_prefix'].$order['orderid'] }}</h4>
									<div class="order-confirm">
										<div class="row">
											<div class="col-md-6">
												<div class="admin-paid">
													<p>Order By: <span>{{ $order['customername'] }}</span></p>
													<p>Paid Via: <span>{{ $order['payment_method'] }}</span></p>
													<p>Status: <span style="background-color: green; color: white; border-radius:5px; padding:5px"> Ready to ship </span></p>
												</div>
											</div>
											<div class="col-md-6">
												<a data-toggle="modal" data-target="#cancelFULLOrder{{ $order['id'] }}" class="ml-5 pull-right btn btn-md btn-danger">
													<i class="fa fa-times"></i> Not shipped
												</a>
											
													<button type="button" data-toggle="modal" data-target="#confirm{{ $order['id'] }}" class="marginleft-5 pull-right btn btn-md btn-primary">
														<i class="fa fa-check"></i> Shipped
													</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Confirm Order before procced modal -->
							<div id="confirm{{ $order['id']}}" class="delete-modal modal fade" role="dialog">
		                        <div class="modal-dialog modal-sm">
		                          <!-- Modal content-->
		                          <div class="modal-content">
		                            <div class="modal-header">
		                              <button type="button" class="close" data-dismiss="modal">&times;</button>
		                              <div class="delete-icon"></div>
		                            </div>
		                            <div class="modal-body text-center">
		                              <h4 class="modal-heading">Are You Sure ?</h4>
		                              <p>Do you really want to change this order to Shipped ? it will confirm the whole order.</p>
		                            </div>
		                            <div class="modal-footer">
		                                <form action="{{ route('quick.ready.full.order',$order['id']) }}" method="POST">
											@csrf
											<input type="hidden" name="status" value="shipped">
			                                          
			                                 <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
		                                	<button type="submit" class="btn btn-danger">Yes</button>
		                              	</form>
		                            </div>
		                          </div>
		                        </div>
		                    </div>
						<!-- End -->

						<!-- Full order cancel modal-->
						<div id="cancelFULLOrder{{ $order['id']}}" class="delete-modal modal fade" role="dialog">
							<div class="modal-dialog modal-sm">
							  <!-- Modal content-->
							  <div class="modal-content">
								<div class="modal-header">
								  <button type="button" class="close" data-dismiss="modal">&times;</button>
								  <div class="delete-icon"></div>
								</div>
								<div class="modal-body text-center">
								  <h4 class="modal-heading">Are You Sure ?</h4>
								  <p>Do you really want to change this order to Not Shipped ? it will confirm the whole order.</p>
								</div>
								<div class="modal-footer">
									<form action="{{ route('full.order.notshipped',$order['id']) }}" method="POST">
										@csrf
										<input type="hidden" name="status" value="not shipped">
												  
										 <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
										<button type="submit" class="btn btn-danger">Yes</button>
									  </form>
								</div>
							  </div>
							</div>
						</div>
						<!--END-->

					@endforeach
				</div>
			
			

			<div class="quickorderview col-md-4">
				
				
				
			</div>
			
					

		</div>

		@else
			<h3 class="text-center">No Ready to ship Orders !</h3>
		@endif



@endsection
@section('custom-script')
<script>
	var url = {!!json_encode( route('quickorderdtls') )!!};
</script>
<script src="{{asset('js/pendingorder.js')}}"></script>
@endsection