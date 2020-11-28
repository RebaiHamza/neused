@extends("admin/layouts.sellermaster")

@section("body")
<div class="col-xs-12">
        <!-- general form elements -->
        <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Shipping</h3>
                    <div class="panel-heading">
                          <a href=" {{url('seller/available/shipping')}} " class="btn btn-success pull-right owtbtn">< Back</a> 
                        </div>   
                    
                    <form id="demo-form2" method="post" enctype="multipart/form-data" action="{{url('seller/shipping')}}" data-parsley-validate class="form-horizontal form-label-left">
                        {{csrf_field()}}
                     
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                          Shipping Title *
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="name" class="form-control col-md-7 col-xs-12">
                          <p class="txt-desc">Please Enter Shipping Title </p>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                          Zone
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="zone_id" id="zone_id" class="select2 form-control">
                            <option value="">Please Select</option>
                           
                            @if(!empty($zones))
                              @foreach($zones as $zone)
                                <option value="{{$zone->id}}"> {{$zone->title}} </option>
                              @endforeach
                            @endif
                            
                          </select>
                          <p class="txt-desc">Please Enter Shipping Zone </p>
                        </div>
                      </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                          Price *
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="price" class="form-control col-md-7 col-xs-12">
                          <p class="txt-desc">Please Enter Price </p>
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>

                    </form>
                  </div>
          <!-- /.box -->
        </div>



        <!-- footer content -->
@endsection
