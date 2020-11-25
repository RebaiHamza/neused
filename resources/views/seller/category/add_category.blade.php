@extends('admin.layouts.sellermaster')
@section('title','Add New Category |')
@section("body")


       
          <div class="box" >
            <div class="box-header with-border">
              <h3 class="box-title">Request Category</h3>
            </div>
             <div class="panel-heading">
                          <a href=" {{url('seller/categories')}} " class="btn btn-success pull-right owtbtn">< Back</a> 
                    </div>   
           
            <form id="demo-form2" method="post" enctype="multipart/form-data" action="{{url('seller/categories')}}" data-parsley-validate class="form-horizontal form-label-left">
                {{csrf_field()}}
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                          Category: <span class="required">*</span>
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input placeholder="Please enter Category name" type="text" id="first-name" name="title" class="form-control col-md-7 col-xs-12" value="{{old('title')}}">
                          
                        </div>

                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> Description <span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <textarea cols="2" id="editor1" name="description" rows="5" >
                          {{old('description')}}
                         </textarea>
                         <small class="txt-desc">(Please Enter Description)</small>
                        </div>
                     </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> Image:
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="file" id="first-name" name="image" class="form-control col-md-7 col-xs-12">
                           <small class="txt-desc">(Please Choose Category image)</small>
                          </div>
                      </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                 
                <button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add</button>
              </div>
            </form>
       
          </div>
       
@endsection
