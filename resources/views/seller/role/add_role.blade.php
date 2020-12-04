@extends("admin/layouts.sellermaster")
@section('title','Add User |')
@section("body")

@section('data-field')
Add User to your store
@endsection


<div class="box" >
  <div class="box-header">
    
     
        <h3 class="box-title">Add User</h3> 
      
        <a title="Go back" href="{{ url('seller/roles') }}" class="pull-right btn btn-md btn-default"><< Back</a>
      
    
  </div>

  <div class="box-body">
     <form id="demo-form2" method="post" enctype="multipart/form-data" action="{{url('seller/roles/store')}}" data-parsley-validate class="form-horizontal form-label-left">
      {{csrf_field()}}
    <!-- Main Row Start-->
    <div class="row">
      

        <div class="col-md-6">
           <label for="">
              User Name: <span class="color-red">*</span>
           </label>

           <input type="text" placeholder="Please enter username" name="name" value="{{ old('name')}}" class="form-control">
        </div>

        <div class="col-md-6">
           <label for="">
              User Email: <span class="color-red">*</span>
            </label>

            <input type="text" placeholder="Please enter user email" name="email" value="{{ old('email')}}" class="form-control">
        </div>

       <div class="last_btn col-md-6">
          <div class="eyeCy">
              <label for="MAIL_PASSWORD">Password: <span class="required">*</span></label>
              <input type="password"  name="password" id="password-field" type="password" placeholder="Please enter password" class="form-control">
              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span> 
          </div>
                   
        </div>

        <div class="last_btn col-md-4">
          <label class="control-label" for="">
             Mobile:
          </label>

          <input pattern="[0-9]+" placeholder="Please Enter Phone Number" type="text" name="mobile" value="{{ old('mobile')}}" class="form-control">
        </div>

        <div class="last_btn col-md-12">
          <div class="row">

            <div class="col-md-12">
              
              <label class="control-label" for="">
                          User Role: <span class="required"></span>
              </label>
              <div class="row">
                <br>
                <div class="col-md-3">
                  <input type="checkbox" name="pm" id="role1" value="1"> Products Manager
                </div>
                <div class="col-md-3">
                  <input type="checkbox" name="om" id="role2" value="1"> Orders Manager
                </div>
                <div class="col-md-3">
                  <input type="checkbox" name="mm" id="role3" value="1"> Marketing Manager
                </div>
              </div>               
            </div>


          
          </div>

            <div class="col-md-4">
              <br>
               <label class="control-label" for="">
                          Status:
               </label>

                 <input id="toggle-event3" type="checkbox" class="tgl tgl-skewed">
                         <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event3"></label>
                 <input type="hidden" name="status" value="0" id="status3">
            </div>

        </div>
        
        <div  class="col-md-6 margin-top-15">

          <button type="Submit" class="btn col-md-4 btn-md btn-primary">+ Add User</button>
        </div>
    <!-- Main Row End-->
    </div>

    </form>
  </div>
</div>


@endsection
       
@section('custom-script')
  <script>var baseUrl = "<?= url('/') ?>";</script>
  <script src="{{ url("js/ajaxlocationlist.js") }}"></script>
@endsection