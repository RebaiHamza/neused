@extends("admin/layouts.master")
@section('title','Create a Blog post | ')
@section("body")


   
          <div class="box" >
            <div class="box-header with-border">
              <div class="box-title">
        Add new blog post
        </div>
            </div>
                    <br>
                    <form id="demo-form2" method="post" enctype="multipart/form-data" action="{{url('admin/studio')}}" data-parsley-validate class="form-horizontal form-label-left">
                        {{csrf_field()}}
                        
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">
                            Title <span class="required">*</span>
                          </label>
                        
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input placeholder="Enter title" type="text" id="title" name="title" value="{{old('heading')}}" class="form-control col-md-7 col-xs-12" required>
                          </div>
                        </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="editor1"> Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <textarea cols="2" id="editor1" name="des" rows="5" required>
                          {{old('des')}}
                         </textarea>
                         <small class="txt-desc">(Please Enter Description)</small>
                        </div>
                     </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price">
                        Price <span class="required">*</span>
                      </label>
                    
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input pattern="[0-9]+(\.[0-9][0-9]?)?" title="Price Format must be in this format : 200 or 200.25" required=""
                        type="text" id="price" name="price" value="{{ old('price') }}" placeholder="Please enter Pack price"
                        class="form-control">
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
                         <small class="txt-desc">(Choose status for your post) </small>
                        </div>
                      </div>
                       
                      <div class="box-footer">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-plus-circle"></i> Create</button>
              </div>
            </form>
             <a title="Cancel" href="{{url('admin/studio')}}" class="btn btn-md btn-default">Cancel</a>
          </div>
          <!-- /.box -->
        
@endsection

