@extends('admin.layouts.sellermaster')
@section('title','Create a Blog post | ')
@section("body")



<div class="box">
  <div class="box-header with-border">
    <div class="box-title">
      Request new blog post
    </div>
  </div>
  <br>
  <form id="demo-form2" method="post" enctype="multipart/form-data" action="{{url('seller/blog/store')}}" data-parsley-validate
    class="form-horizontal form-label-left">
    {{csrf_field()}}
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
        Heading <span class="required">*</span>
      </label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        <input placeholder="Enter heading" type="text" id="first-name" name="heading" value="{{old('heading')}}"
          class="form-control col-md-7 col-xs-12">

      </div>

    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> Description <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea cols="2" id="editor1" name="des" rows="5">
                          {{old('des')}}
                         </textarea>
        <small class="txt-desc">(Please Enter Description)</small>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> Author Name <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input placeholder="Enter author name" type="text" id="first-name" name="user" value="{{old('user')}}"
          class="form-control col-md-7 col-xs-12">



      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> About Author (optional)
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea placeholder="Write something about author" type="text" id="first-name" name="about"
          value="{{old('about')}}" class="form-control col-md-7 col-xs-12"></textarea>


      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> Designation (optional)
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input placeholder="Author Designation eg. Admin, CEO" type="text" id="first-name" name="post"
          value="{{old('post')}}" class="form-control col-md-7 col-xs-12">
        <p class="txt-desc">


      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> Image <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="file" id="first-name" name="image" class="form-control col-md-7 col-xs-12">
        <small class="txt-desc">(Choose Image for blog post)</small>

      </div>
    </div>

    <div class="box-footer">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-plus-circle"></i> Create</button>
      </div>
  </form>
  <a title="Cancel" href="{{url('seller/blog')}}" class="btn btn-md btn-default">Cancel</a>
</div>
<!-- /.box -->

@endsection