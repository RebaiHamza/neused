@extends("admin.layouts.sellermaster")
@section('title',"Edit Store - $store->name |")
@section("body")
    <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-blue">
              <div class="widget-user-image">
                @php
                  $image = @file_get_contents('../public/images/store/'.$store->store_logo);
                @endphp
                <img title="{{ $store->name }}" src="{{ $image ? url('images/store/'.$store->store_logo) : Avatar::create($store->name)->toBase64() }}" alt="Store logo">
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username">{{ $store->name }}</h3>
              <h5 class="widget-user-desc"><i class="fa fa-map-marker"></i> {{ $store->city['name'] }}, {{ $store->state['name'] }}, {{ $store->country['nicename'] }}</h5>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
                <li><a href="#">Created On <span class="pull-right badge bg-blue">{{ date('d-M-Y',strtotime($store->created_at)) }}</span></a></li>
                <li><a href="#">Owner <span class="pull-right badge bg-purple"> {{ $store->user->name }}</span></a></li>
                @php
                  $allorders = App\Order::all();

                  $sellerorder = collect();

                  foreach ($allorders as $key => $order) {
                      
                      if(in_array(Auth::user()->id, $order->vender_ids)){
                          $sellerorder->push($order);
                      }

                  }
                @endphp
                <li><a href="{{ url('seller/orders') }}">Total Orders <span class="pull-right badge bg-green">{{ count($sellerorder) }}</span></a></li>
                <li><a href="{{ url('seller/my/products') }}">Total Products <span class="pull-right badge bg-aqua">{{ $store->products->count() }}</span></a></li>
                <li><a href="#">Verified <span class="pull-right badge {{ $store->verified_store == '1' ? "bg-green" : "bg-primary" }}">{{ $store->verified_store == '1' ? "Yes" : "No" }}</span></a></li>
              </ul>
            </div>
      </div>

      <div class="box box-primary">
        <div class="box-header with-border">
          <div class="box-title">
            Edit Store Details
          </div>

          <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
           </div>
        </div>

        <div class="box-body">
          <div class="row">
          <form action="{{ route('store.update',$store->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            <div class="col-md-6">
              <div class="form-group">
                <label>Store Name: <span class="required">*</span></label>
                 <input placeholder="Enter store name" type="text" name="name" class="form-control" value="{{$store->name ?? ''}}">
              </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <label>Store Email: <span class="required">*</span></label>
                  <input type="text" name="email" class="form-control" value="{{$store->email ?? ''}}">
                </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="">Phone:</label>
                <input type="text" placeholder="Enter phone no." name="phone" class="form-control" value="{{$store->phone ?? ''}}">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="">Mobile:</label>
                <input type="text" placeholder="Enter mobile no." name="mobile" class="form-control" value="{{$store->mobile ?? ''}}">
              </div>
            </div>

            <div class="col-md-12">
              <div class="row">
                <div class="col-md-3">
                  <label for="">Store Address: <span class="required">*</span></label>
                  <textarea class="form-control" name="address" placeholder="Enter store address" cols="10" rows="5">{{ $store->address }}</textarea>
                </div>

              <div class="col-md-3">
                  <div class="form-group">
                    <label for="">Country: <span class="required">*</span></label>
                    <select name="country_id" id="country_id" class="form-control select2">
                                  <option value="0">Please Choose</option>
                                  @foreach($countrys as $c)
                                  <?php
                                    $iso3 = $c->country;

                                    $country_name = DB::table('allcountry')->
                                    where('iso3',$iso3)->first();

                                     ?>
                                  <option value="{{$country_name->id}}" {{ $country_name->id == $store->country_id ? 'selected="selected"' : '' }} >
                                    {{$country_name->nicename}}
                                  </option>
                                  @endforeach
                    </select>
                  </div>
              </div>

            <div class="col-md-3">
              <div class="form-group">
                 <label for="">State: <span class="required">*</span></label>
                  <select name="state_id" id="upload_id" class="form-control select2">
                              <option value="0">Please choose</option>
                                 @foreach($states as $c)
                                <option value="{{$c->id}}" {{ $c->id == $store->state_id ? 'selected="selected"' : '' }} >
                                  {{$c->name}}
                                </option>
                                @endforeach
                  </select>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="">City: <span class="required">*</span></label>
                <select name="city_id" id="city_id" class="form-control select2">
                           <option value="0">Please Choose</option>
                              @foreach($city as $c)
                              <option value="{{$c->id}}" {{ $c->id == $store->city_id ? 'selected="selected"' : '' }} >
                                {{$c->name}}
                              </option>
                              @endforeach
                </select>
              </div>
            </div>

            @if($pincodesystem == 1)
              <div class="col-md-3">
              <label for="">Pincode: <span class="required">*</span></label>
              <input type="text" value="{{ $store->pin_code }}" name="pin_code" placeholder="Enter pincode" class="form-control">
            </div>
            @endif

            <div class="col-md-3">
              <label for="">Your Store Status :</label>
              <div class="callout {{ $store->status == 1 ? "callout-success" : "callout-danger"}}">
                @if($store->status == 1)
                  <i class="fa fa-check-square"></i> Active
                @else
                 <i class="fa fa-ban"></i> Deactive
                @endif
              </div>
            </div>

            <div class="col-md-3">
              <label for="">Verified Store :</label>
               <div class="callout {{ $store->verified_store == 1 ? "callout-success" : "callout-info"}}">
                @if($store->verified_store == 1)
                  <i class="fa fa-check-square"></i> Verfied 
                @else
                 <i class="fa fa-info-circle"></i> Not verified 
                @endif
              </div>
            </div>

                
              </div>
            </div>


            <div class="col-md-6">
              <div class="form-group">
                <label for="">Choose Store Logo:</label>
                <input type="file" class="form-control" name="store_logo">
              </div>
            </div>

           

            

          </div>
          <div class="box-footer">
              <button @if(env('DEMO_LOCK') == 0) type="submit" @else title="This action is disabled in demo !" disabled="disabled" @endif class="btn btn-md btn-primary"><i class="fa fa-save"></i> Save Details </button>
            </form>
              <a @if(env('DEMO_LOCK') == 0) href="{{url('store/delete/'.$store->id)}}" @else title="This action is disabled in demo !" @endif type="button" class="btn btn-md btn-danger"><i class="fa fa-trash"></i> Delete Store </a>
          </div>
        </div>
      </div>
 
@endsection

@section('custom-script')
  <script>var baseUrl = "<?= url('/') ?>";</script>
  <script src="{{ url('js/ajaxlocationlist.js') }}"></script>
@endsection
