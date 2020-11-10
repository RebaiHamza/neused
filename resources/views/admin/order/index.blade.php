@extends("admin/layouts.master")
@section('title',"All Orders |")
@section("body")

@section('data-field')
Orders
@endsection

  
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">
          Orders
        </h3>
      </div>
        <div class="panel-heading">
            <a type="button" class="btn btn-danger btn-md z-depth-0" data-toggle="modal" data-target="#bulk_delete"><i class="fa fa-trash"></i> Delete Selected</a>
        </div>


      <!-- /.box-header -->
      <div class="box-body">
        <table id="full_detail_table" class="width100 table table-bordered table-striped">
         <thead>
            <tr>
               <th>
                <div class="inline">
                <input id="checkboxAll" type="checkbox" class="filled-in" name="checked[]" value="all" id="checkboxAll">
                <label for="checkboxAll" class="material-checkbox"></label>
              </div>

              </th>
              <th>ID</th>
              <th>Order Type</th>
              <th>Order Id</th>
              <th>Customer Name</th>
              <th>Total Qty</th>
              <th>Total Amount</th>
              <th>Order Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>


             @foreach($all_orders as $key => $order)
            <tr class="table-heading-row">
            <td>
              <div class="inline">
                <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="{{$order->id}}" id="checkbox{{$order->id}}">
                <label for="checkbox{{$order->id}}" class="material-checkbox"></label>
              </div>
            </td>
            <td>{{$key+1}}</td>
            <td>
              @if($order->payment_method != 'COD' && $order->payment_method != 'BankTransfer')
                <label class="label label-success">PREPAID</label>
              @elseif($order->payment_method == 'BankTransfer' )
                 <label class="label label-default">Bank Transfer</label>
              @else
                <label class="label label-primary">COD</label>
              @endif
            </td>
            <td>{{$inv_cus->order_prefix.$order->order_id}}
             <p></p>
                <small><a title="View Order" href="{{ route('show.order',$order->order_id) }}">View Order</a></small> | <small><a title="Edit Order" href="{{ route('admin.order.edit',$order->order_id) }}">Edit Order</a></small>
            </td>
            <td>{{$order->user['name']}}</td>
            <td>{{$order->qty_total}}</td>
            <td> <i class="{{ $order->paid_in }}"></i> 
                
             
                {{ round($order->order_total+$order->handlingcharge,2) }}
              </td>

            <td>{{date('d-m-Y @ h:i:a',strtotime($order->created_at))}}</td>

            <td>

              <div class="row">

                <div class="col-md-2">

                <button @if(env('DEMO_LOCK') == 0) data-target="#{{ $order->id }}orderModel" data-toggle="modal" @else disabled="" title="This action is disabled in demo !" @endif class="btn btn-sm btn-danger">
                  <i class="fa fa-trash-o"></i>
                </button>
                </div>

                <div class="ml-5 col-md-2">
                  <a title="Print Order" href="{{ route('order.print',$order->id) }}" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-print"></i></a>
                </div>

              </div>



            </td>

            </tr>


      </div>
            @endforeach


          </tbody>
        </table>

      </div>
      <!-- /.box-body -->
    </div>
  

@foreach($all_orders as $key => $order)
<div id="{{ $order->id }}orderModel" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">

          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
              <h4 class="modal-heading">Are You Sure ?</h4>
              <p>Do you really want to delete this product? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
             <form method="POST" action="{{ route('order.delete',$order->id) }}">
                  @csrf
                  {{ method_field("DELETE") }}

                <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
              </form>
            </div>
          </div>
        </div>
</div>

@endforeach

<!--bulk delete modal -->

<div id="bulk_delete" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
              <h4 class="modal-heading">Are You Sure ?</h4>
              <p>Do you really want to delete these orders? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
             <form id="bulk_delete_form" method="post" action="{{ route('order.bulk.delete') }}">
              @csrf
              {{ method_field('DELETE') }}
                <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
              </form>
            </div>
          </div>
        </div>
      </div>

@endsection

@section('custom-script')
  <script>var baseUrl = "<?= url('/') ?>";</script>
  <script src="{{ url('js/order.js') }}"></script>
@endsection