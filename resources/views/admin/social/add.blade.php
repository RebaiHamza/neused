@extends("admin/layouts.master")
@section('title','Add new social icon to footer')
@section("body")
   
          <div class="box" >
            <div class="box-header with-border">
              <h3 class="box-title">Add new social icon </h2>
                  <form id="demo-form2" method="post" enctype="multipart/form-data" action="{{url('admin/social')}}" data-parsley-validate class="form-horizontal form-label-left">
                        {{csrf_field()}}
                     <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                        Url <span class="required">*</span>
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input placeholder="http://" type="text" id="first-name" name="url" 
                          value="{{ old('url')}}" class="form-control col-md-7 col-xs-12">
                          
                        </div>
                      </div>
                     
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                        Icon <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="icon" class="form-control col-md-7 col-xs-12">
                            <option value="youtube">You Tube</option>
                            <option value="linkedin">LinkedIn</option>
                            <option value="pintrest">Pinterest</option>
                            <option value="rss">Rss</option>
                            <option value="googleplus">Google+</option>
                            <option value="tw">Twitter</option>
                            <option value="fb">Facebook</option>
                           </select>
                          <small class="txt-desc">Please Enter Icon</small>
                        </div>
                      </div>
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                        Status
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                       <input id="toggle-event3" type="checkbox" class="tgl tgl-skewed">
                       <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event3"></label>
                      <input type="hidden" name="status" value="0" id="status3">
                      
                       <small class="txt-desc">(Please Choose Status) </small>
                      </div>
                    </div>

                <div class="box-footer">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                  </form>
                    <button class="btn btn-default "><a href="{{url('admin/social')}}" class="links">Cancel</a></button>
                  </div>
                
@endsection
