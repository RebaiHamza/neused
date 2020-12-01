@extends("admin/layouts.master")
@section('title','Create a Blog post | ')
@section("body")


   
          <div class="box" >
            <div class="box-header with-border">
              <div class="box-title">
                Add new help file
              </div>
            </div>
            <br>
              <form id="demo-form2" method="post" enctype="multipart/form-data" action="{{url('admin/help/store')}}" data-parsley-validate class="form-horizontal form-label-left">
                {{csrf_field()}}
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="help_name">
                    Title <span class="required">*</span>
                  </label>
                        
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input placeholder="Enter file title" required type="text" id="help_name" name="help_name" class="form-control col-md-7 col-xs-12">          
                  </div>
                </div>    
                
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="help_file">
                    Upload file <span class="required">*</span>
                  </label>
                        
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" id="help_file" name="help_file" class="form-control col-md-7 col-xs-12" required>          
                  </div>
                </div>    
                <div class="box-footer">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-plus-circle"></i> Create</button>
                  </div>
            </form>
             <a title="Cancel" href="{{url('admin/help')}}" class="btn btn-md btn-default">Cancel</a>
          </div>
          <!-- /.box -->
        
@endsection

