@extends("admin.layouts.sellermaster")
@section('title','Completed Payments | ')
@section("body")
	
  <div class="box box-default box-body">
    {!! $sellerpayouts->container() !!}
  </div>

  <div class="box">

    <div class="box-header with-border">
      <div class="box-title">
        Received Payouts
      </div>
    </div>

      <div class="box-body">
         <table id="completedPayouts" class="width100 table table-striped table-bordered">
            <thead>
              <th>
                #
              </th>
              <th>
                Transfer TYPE
              </th>
              <th>
                Order ID
              </th>
              <th>
                Amount
              </th>
              <th>
                Seller Details
              </th>
              <th>
                Paid On
              </th>
              <th>
                Action
              </th>
            </thead>

            <tbody>
              
            </tbody>
         </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
<div class="modal fade" id="trackmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Track Payout Status</h4>
      </div>
      <div class="modal-body">
        <div id="trackstatus">
            
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection
@section('custom-script')
<script src="{{ url('front/vendor/js/Chart.min.js') }}" charset="utf-8"></script>
 {!! $sellerpayouts->script() !!}
<script>
  var url = {!! json_encode(url('/track/payput/status/')) !!};
  var sellerpayouturl = {!! json_encode( route('seller.payout.index') ) !!};
</script>
<script src="{{ url('js/seller/sellerpayout.js') }}"></script>
@endsection