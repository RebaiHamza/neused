@extends("admin/layouts.master")
@section('title',"Create New Store |")
@section("body")
  <div class="box">
    <div class="box-header with-border">
      
        <h3 class="box-title">Add New Store</h3>
        <a href=" {{url('admin/stores')}} " class="btn btn-success pull-right owtbtn">< Back</a>

    </div>

    <div class="box-body">
      <form id="demo-form2" method="post" enctype="multipart/form-data" action="{{url('admin/stores')}}" data-parsley-validate>
                        {{csrf_field()}}
      <div class="row">

        <div class="col-md-4">

           <label class="control-label" for="first-name">
                          User Name: <span class="required">*</span>
            </label>

            <select name="user_id" class="form-control">
                           <option value="0">Please Choose</option>
                            @foreach($users as $user)
                            <option value="{{$user->id}}"> {{$user->name}} </option>
                            @endforeach
                          </select>
              
        </div>

        <div class="col-md-4">

                        <label class="control-label" for="first-name">
                          Store name: <span class="required">*</span>
                        </label>
                        
                       
                          <input placeholder="Please enter store name" type="text" id="first-name" name="name" class="form-control" value="{{old('name')}}">
        </div>

        <div class="col-md-4">
          <label class="control-label" for="first-name">
                         Business Email: <span class="required">*</span>
                        </label>
                        
                      
                          <input placeholder="Please enter buisness email" type="email" id="first-name" name="email" class="form-control" value="{{old('email')}}">
                        
        </div>

        <div class="last_btn col-md-4">
           <label class="control-label" for="first-name">
                          Phone:
                        </label>
        <input pattern="[0-9]+" title="Invalid phone no." placeholder="Please enter phone no." type="text" id="first-name" name="phone" value="{{ old('phone') }}" class="form-control">
        </div>

        <div class="last_btn col-md-4">
           <label class="control-label" for="first-name">
                          Mobile: <span class="required">*</span>
                        </label>
          <input pattern="[0-9]+"  title="Invalid mobile no." placeholder="Please enter mobile no." type="text" id="first-name" name="mobile" class="form-control" value="{{old('mobile')}}">
        </div>

        <div class="last_btn col-md-4">
          <label class="control-label" for="first-name">
                         Store Address: <span class="required">*</span>
                        </label>
          <textarea placeholder="Please enter address" type="text" id="first-name" name="address" class="form-control" rows="1" cols="1">{{old('address')}}</textarea>
        </div>

        <div class="col-md-4 last_btn">
          <label class="control-label" for="first-name">
                          Country: <span class="required">*</span>
          </label>

          <select name="country_id" id="country_id" class="form-control col-md-7 col-xs-12">
                            <option value="0">Please Choose</option>
                            @foreach($countrys as $country)
                              <?php
                              $iso3 = $country->country;

                              $country_name = DB::table('allcountry')->
                              where('iso3',$iso3)->first();

                               ?>
                            <option value="{{$country_name->id}} ">{{$country_name->nicename}}</option>
                            @endforeach
            </select>
        </div>

        <div class="col-md-4 last_btn">
          <label class="control-label" for="first-name">
                          State: <span class="required">*</span>
          </label>

           <select name="state_id" id="upload_id" class="form-control">
                         
                         <option value="0">Please Choose</option>
                            <option value=""></option>
            </select>
        </div>

        <div class="col-md-4 last_btn">
                        <label class="control-label" for="first-name">
                          City: <span class="required">*</span>
                        </label>

                          <select name="city_id" id="city_id" class="form-control">
                            <option value="0">Please Choose</option>
                        
                            <option value=""></option>
                           
                          </select>
        </div>

        <div class="last_btn  col-md-6">
          <label class="control-label" for="first-name">
                          Pin Code:
                        </label>
          <input pattern="[0-9]+" title="Invalid pincode/zipcode" placeholder="Please enter pincode" type="text" id="first-name" name="pin_code" class="form-control" value="{{ old('pin_code') }}">
        </div>

        <div class="last_btn col-md-6">
          <label class="control-label" for="first-name">
                          Store Logo: <span class="required">*</span>
                        </label>
        <input type="file" id="first-name" name="store_logo" class="form-control" value="{{old('mobile')}}">
        </div>
        
        <div class="last_btn col-md-2">
          <label class="control-label" for="first-name">
                          Status:
          </label>

          <input id="toggle-event3" type="checkbox" class="tgl tgl-skewed">
           <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event3"></label>
          <input type="hidden" name="status" value="0" id="status3">
        </div>

        <div class="last_btn col-md-2">
          <label class="control-label" for="first-name">
                         Verify Status:
                        </label>
                        
                        
          <input id="toggle-event2" type="checkbox" class="tgl tgl-skewed">
          <label class="tgl-btn" data-tg-off="No" data-tg-on="Yes" for="toggle-event2"></label>
          <input type="hidden" name="verified_store" value="0" id="status2">
          
        </div>

        <div class="col-md-12">
          <div class="box-footer">
            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-plus-circle"></i> Add Store </button>

            &nbsp;&nbsp;<button type="reset" class="btn btn-md btn-danger"><i class="fa fa-minus-circle"></i> Reset Form</button>
          </div>
        </div>
        

       
        <!--Main Row END-->
      </div>
    </form>
    </div>
  </div>
@endsection

@section('custom-script')
  <script>var baseUrl = "<?= url('/') ?>";</script>
  <script src="{{ url('js/ajaxlocationlist.js') }}"></script>
@endsection