@extends("admin.layouts.master")

@section("body")
  
                    <div class="box" >
                      <div class="box-header with-border">
                        <h3 class="box-title">
                        Bank Details
                        </h3>
                      </div>
                    <br>
                    <form id="demo-form2" method="post" enctype="multipart/form-data" action="{{url('admin/bank_details/')}}" data-parsley-validate class="form-horizontal form-label-left">
                        {{csrf_field()}}
                    
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                          Bank Name <span class="required">*</span>
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input placeholder="Please enter bank name" type="text" id="first-name" name="bankname" class="form-control col-md-7 col-xs-12" value="{{$bank->bankname ?? ''}} " >
                          
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                          Branch Name <span class="required">*</span>
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input placeholder="Please enter branch name" type="text" id="first-name" name="branchname" class="form-control col-md-7 col-xs-12" value="{{$bank->branchname ?? ''}} " >
                          
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                          IFSC Code <span class="required">*</span>
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input placeholder="Enter IFSC code" type="text" id="first-name" name="ifsc" class="form-control col-md-7 col-xs-12" value="{{$bank->ifsc ?? ''}} " >
                          
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                          Account Number <span class="required">*</span>
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input placeholder="Enter account no." type="text" id="first-name" name="account" class="form-control col-md-7 col-xs-12" value="{{$bank->account ?? ''}}">
                          
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                          Account Name <span class="required">*</span>
                        </label>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input placeholder="Enter account name" type="text" id="first-name" value="{{$bank->acountname ?? ''}}"  name="acountname" class="form-control col-md-7 col-xs-12">
                         
                        </div>
                      </div>
                       
                   <div class="box-footer">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                 
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
            
          </div>
          <!-- /.box -->
        

@endsection
