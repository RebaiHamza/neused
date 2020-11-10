@extends("admin/layouts.master")
@section('title',"Edit Store : $store->name |")
@section("body")


<div class="box box-widget widget-user-2">
  <!-- Add the bg color to the header using any of the bg-* classes -->
  <div class="widget-user-header bg-blue">
    <div class="widget-user-image">
      @php
      $image = @file_get_contents('../public/images/store/'.$row->store_logo);
      @endphp
      <img title="{{ $store->name }}"
        src="{{ $image ? url('images/store/'.$store->store_logo) : Avatar::create($store->name)->toBase64() }}"
        alt="store logo">
    </div>
    <!-- /.widget-user-image -->
    <h3 class="widget-user-username">{{ $store->name }}</h3>
    <h5 class="widget-user-desc"><i class="fa fa-map-marker"></i> {{ $store->city->name }}, {{ $store->state->name }},
      {{ $store->country->nicename }}</h5>
  </div>
  <div class="box-footer no-padding">
    <ul class="nav nav-stacked">
      <li><a href="#">Created On <span
            class="pull-right badge bg-blue">{{ date('d-M-Y',strtotime($store->created_at)) }}</span></a></li>
      <li><a href="#">Owner <span class="pull-right badge bg-purple"> {{ $store->user->name }}</span></a></li>
      <li><a href="#">Total Orders <span class="pull-right badge bg-green">{{ $storeordercount }}</span></a></li>
      <li><a href="#">Total Products <span class="pull-right badge bg-aqua">{{ $store->products->count() }}</span></a>
      </li>
      <li><a href="#">Verified <span
            class="pull-right badge {{ $store->verified_store == '1' ? "bg-green" : "bg-primary" }}">{{ $store->verified_store == '1' ? "Yes" : "No" }}</span></a>
      </li>
    </ul>
  </div>
</div>
<!-- general form elements -->
<div class="box box-primary">
  <div class="box-header with-border">


    <h3 class="box-title">Edit Store</h2>





      <a href=" {{url('admin/stores')}} " class="btn btn-success pull-right owtbtn">
        < Back</a> </div> <div class="box-body">

          <form id="demo-form2" method="post" enctype="multipart/form-data" action="{{url('admin/stores/'.$store->id)}}"
            data-parsley-validate class="form-horizontal form-label-left">
            {{csrf_field()}}
            {{ method_field('PUT') }}

            <div class="row">
              <div class="col-md-6">
                <label class="control-label" for="first-name">
                  User Name: <span class="required">*</span>
                </label>

                <select name="user_id" class="form-control">

                  <option value="{{$store->user->id}}"> {{$store->user->name}} </option>

                </select>

              </div>

              <div class="col-md-6">
                <label class="control-label" for="first-name">
                  Store Name: <span class="required">*</span>
                </label>

                <input placeholder="Please enter store name" type="text" id="first-name" name="name"
                  class="form-control" value="{{$store->name}}">
              </div>

              <div class="col-md-4">
                <label class="control-label" for="first-name">
                  Business Email:
                </label>

                <input placeholder="Please enter buisness email" type="email" id="first-name" name="email"
                  class="form-control" value="{{$store->email}}">
              </div>

              <div class="col-md-4">
                <label class="control-label" for="vat_no">
                  VAT NO. / GSTIN No:
                </label>

                <input placeholder="Please enter your GSTIN/VAT NO" type="text" id="vat_no" name="vat_no"
                  class="form-control" value="{{$store->vat_no}}">
              </div>

              <div class="col-md-4">
                <div class="row">
                  <div class="col-md-10">
                    <label class="control-label" for="first-name">
                      Store Logo:
                    </label>
                    <input type="file" id="first-name" name="store_logo" class="form-control">
                  </div>

                  <div class="col-custom col-md-2">
                    <img
                      src=" {{ $image ? url('images/store/'.$store->store_logo) : Avatar::create($store->name)->toBase64() }} "
                      height="50px" width="70px" />
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label class="control-label" for="first-name">
                  Phone:
                </label>

                <input pattern="[0-9]+" title="Invalid phone no." placeholder="Please enter phone no." type="text"
                  id="first-name" name="phone" class="form-control" value="{{$store->phone}}">
              </div>

              <div class="col-md-4">
                <label class="control-labe" for="first-name">
                  Mobile:
                </label>

                <input pattern="[0-9]+" title="Invalid mobile no." placeholder="Please enter mobile no." type="text"
                  id="first-name" name="mobile" class="form-control" value="{{$store->mobile}}">
              </div>

              <div class="col-md-4">
                <label class="control-label" for="first-name">
                  Address:
                </label>

                <textarea rows="1" cols="1" placeholder="Please enter address" type="text" id="first-name"
                  name="address" class="form-control">{{$store->address}}</textarea>

              </div>




              <div class="col-md-4">
                <label class="control-label" for="first-name">
                  Country: <span class="required">*</span>
                </label>

                <select name="country_id" id="country_id" class="select2 form-control">
                  <option value="0">Please Choose</option>
                  @foreach($countrys as $c)
                  <?php
                              $iso3 = $c->country;

                              $country_name = DB::table('allcountry')->
                              where('iso3',$iso3)->first();

                               ?>
                  <option value="{{$country_name->id}}"
                    {{ $country_name->id == $store->country_id ? 'selected="selected"' : '' }}>
                    {{$country_name->nicename}}
                  </option>
                  @endforeach
                </select>


              </div>

              <div class="col-md-4">
                <label class="control-label" for="first-name">
                  State: <span class="required">*</span>
                </label>

                <select name="state_id" id="upload_id" class="select2 form-control">
                  <option value="0">Please choose</option>
                  @foreach($states as $c)
                  <option value="{{$c->id}}" {{ $c->id == $store->state_id ? 'selected="selected"' : '' }}>
                    {{$c->name}}
                  </option>
                  @endforeach
                </select>

              </div>

              <div class="col-md-4">
                <label class="control-label" for="first-name">
                  City: <span class="required">*</span>
                </label>

                <select name="city_id" id="city_id" class="select2 form-control">
                  <option value="0">Please Choose</option>
                  @foreach($citys as $c)
                  <option value="{{$c->id}}" {{ $c->id == $store->city_id ? 'selected="selected"' : '' }}>
                    {{$c->name}}
                  </option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-4">

                <label class="control-label" for="first-name">
                  Pincode:
                </label>

                <input pattern="[0-9]+" title="Invalid Pincode/ zipcode" placeholder="Please enter pincode" type="text"
                  id="first-name" name="pin_code" value="{{$store->pin_code}}" class="form-control" />

              </div>
              <br>

              <div class="col-md-4">

                <label class="control-label" for="first-name">
                  Paypal email:
                </label>

                <input title="enter paypal email" placeholder="enter paypal email" type="email" name="paypal_email"
                  value="{{$store->paypal_email}}" class="form-control" />

              </div>

              <br>

              <div class="col-md-4">

                <label class="control-label" for="first-name">
                  Paytm Registered No: (ONLY FOR INDIA)
                </label>

                <input name="paytem_mobile" pattern="[0-9]+" title="enter valid paytm mobile no"
                  placeholder="enter paytm no (KYC verified)" type="text" value="{{$store->paytem_mobile}}"
                  class="form-control" />

              </div>

              <br>
              <div class="col-md-3">
                <label class="control-label">Prefered Payment Method:</label>
                <select name="preferd" class="select2">
                  <option {{ $store->preferd == 'paypal' ? "selected" : "" }} value="paypal">Paypal</option>
                  <option {{ $store->preferd == 'paytm' ? "selected" : "" }} value="paytm">Paytm</option>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label" for="first-name">
                  Status:
                </label>

                <input <?php echo ($store->status=='1')?'checked':'' ?> id="toggle-event3" type="checkbox"
                  class="tgl tgl-skewed">
                <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event3"></label>
                <input type="hidden" name="status" value="{{$store->status}}" id="status3">
                <small class="txt-desc">(Please Choose Status) </small>
              </div>

              <div class="col-md-3">

                <label class="control-label" for="first-name">
                  Verify Status:
                </label>

                <input <?php echo ($store->verified_store=='1')?'checked':'' ?> id="toggle-event5" type="checkbox"
                  class="tgl tgl-skewed">

                <label class="tgl-btn" data-tg-off="Not verified" data-tg-on="Verified" for="toggle-event5"></label>

                <input type="hidden" name="verified_store" value="{{$store->verified_store}}" id="status5">
                <small class="txt-desc">(Please Choose Status) </small>
              </div>

              <div class="col-md-3">
                <label class="control-label" for="first-name">
                  Accept Vender Request:
                </label>

                <input <?php echo ($store->apply_vender=='1')? 'checked' :'' ?> id="toggle-event6" type="checkbox"
                  class="tgl tgl-skewed" name="apply_vender">

                <label class="tgl-btn" data-tg-off="Not Accept" data-tg-on="Accept" for="toggle-event6"></label>
                <small class="txt-desc">(Please Choose an option) </small>
              </div>

              <div class="last_btn col-md-4">
                <button @if(env('DEMO_LOCK')==0) type="submit" @else disabled=""
                  title="This action is disabled in demo !" @endif class="btn btn-primary btn-block col-md-4">
                  <i class="fa fa-save"></i> Save Store
                </button>
              </div>

              <!-- Main RowEnd-->
            </div>
          </form>


  </div>

  @endsection

  @section('custom-script')
  <script>
    var baseUrl = "<?= url('/') ?>";
  </script>
  <script src="{{ url('js/ajaxlocationlist.js') }}"></script>
  @endsection