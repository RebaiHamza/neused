@extends("admin/layouts.master")
@section('title','Shipping Settings |')
@section("body")
           
  
     
    <div class="box" >
      <div class="box-header">
        <h3 class="box-title">Shipping</h3>
        <br><br>
      <a href="{{ url('admin/shipping/create') }}" class="btn btn-success owtbtn">
          +  Add new Shipping Method</a>
      </div>

              <div class="box-body">
                <div id="flash-message" class="display-none flash-message">
                  
                </div>

              <table id="full_detail_table" class="width100 table table-hover table-responsive">
               <thead>
                  <tr>
                    <th>Default</th>
                    <th>Shipping Title</th>
                    <th>Zone</th>
                    <th>Price</th>
                    <th>Status</th>
                     <th>Action</th>
                     <th></th>
                  </tr>
                </thead>
                <tbody>
                  
                  @foreach($shippings as $shipping)
                  
                  <tr>
                  <td><input {{ $shipping->name == 'Local Pickup' || $shipping->name == 'UPS Shipping' ? "disabled" : ""}} type="radio" class="kk" id="{{$shipping->id}}" {{$shipping->default_status=='1'?'checked':''}} name="radio"></td>
                  <td>{{$shipping->name}}</td>
                  @if ($shipping->zone_id)
                    @foreach ($zones as $zone)
                        @if ($shipping->zone_id == $zone->id)
                          <td>{{$zone->title}}</td>
                        @endif
                    @endforeach
                  @else
                    <td>--</td>
                  @endif
                  
                  <td>{{$shipping->price ?? '---'}}</td>
                 
                  <td>
                    @if($shipping->login=='1')
                        {{'Yes'}}
                        @else
                        {{'No'}}
                      @endif
                    </td><td>
                    <a {{ $shipping->name == 'Free Shipping' || $shipping->name == 'UPS Shipping' ? "disabled" : ""}} href=" {{url('admin/shipping/'.$shipping->id.'/edit')}} " class="btn btn-info btn-sm">Edit
                    </a>
                      
                      
                  </td>
                  <td>
                    @php
                      $swt = App\ShippingWeight::first();
                    @endphp
                    @if($swt->value == $shipping->name)
                     <a href="{{ route('get.wt') }}" class="btn btn-sm btn-primary">Manage Weight</a>
                    @else
                     {{ "--" }}
                    @endif
                  </td>
                  </tr>
                  @endforeach

                </tbody>
              </tbody>
              </table>
          
            </div>
            <!-- /.box-body -->
          </div>
        </div>

 
  @endsection

@section('custom-script')
  <script>var url = {!! json_encode( url('admin/shipping_update')) !!};</script>
  <script src="{{ url('js/ship.js') }}"></script>

@endsection