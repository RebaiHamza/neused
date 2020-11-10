@extends('admin.layouts.master')
@section('title','Currency List & Other Setting | ')
@section('body')
  <div class="box">
    <div class="box-header with-border">
        <div class="box-title">
          Multiple Currency & Location Setting
        </div>
    </div>

    <div class="box-body">
        <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="currencyTabs" role="tablist">
        
                  <li class="nav-item active">
                    <a class="nav-link " id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Currency List</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Location</a>
                  </li>
                  <!-- In Beta -->
                  <li class="nav-item">
                    <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="false">Checkout</a>
                  </li>
                  <!-- In Beta -->
 
                </ul>
        </div>
        <div class="tab-content">
        <div class="tab-pane fade in active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <br>
          <div class="container-fluid">
            <div class="form-group">

              <span class="margin-top-10 control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                <label>Enable</label>
              </span>

               
                <label class="switch"> 
                  
                  <input onchange="enabel_currency()" type="checkbox" name="default" id="enabel" {{$auto_geo->enabel_multicurrency=="1"?'checked':''}}>
                  <span class="knob"></span>
                     
                </label>

                <div class="row">
                  <br>
                   <div class="col-md-11">
                       <div class="callout callout-info">
                          <p><i class="fa fa-info-circle"></i> <b>Additioal fee:</b> If you enter additional fee for ex. 2 and your currency rate is 1 than at time of conversion total conversion rate will be 3 and new rate will be convert accroding to this conversion rate. It will not work on if above toggle is off.</p>
                       </div>
                   </div>
                </div>
                
                   <table class="table">

                      <thead>
                        <tr>
                          <th>#</th>
                          <th scope="col">Currency</th>
                          <th scope="col">Position</th>
                          <th scope="col">Rate</th>
                          <th scope="col">Additional Fee</th>
                          <th scope="col">Currency Symbol</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody class="xyz">
                            
                    
                        <?php $check_cur = App\multiCurrency::get();?>
                        @if(!empty($check_cur['0']))

                              @foreach($check_cur as $key => $cury)

                               <tr class="{{ $cury->default_currency == 1 ? "defcurbg" : "" }}">

                                <td class="tdwidth">
                                 @if($cury->default_currency !='1')
                                    {{ $key+1 }}
                                 @else
                                    <label class="label label-danger">Default</label> 
                                 @endif
                                </td>

                              <td class="fake-input">
                                @if($cury->default_currency != 1)
                                  <select id="cs{{$key+1}}" avl="yes" rowCount="{{$key+1}}" name="currency{{$cury->id}}" class="js-example-basic-single row-id" >
                                    <option value="0">Select Currency Code</option>
                                    <?php echo $currencys = DB::table('currency_list')->get(); ?>
                                      @foreach($currencys as $cur)
                                        <option value="{{($cur->id)}}" {{ $cury->currency_id == $cur->id ? 'selected="selected"' : '' }}>{{trans($cur->code)}}{{'-'}}{{trans($cur->country)}}</option>
                                       @endforeach
                                  </select>
                                @else

                                  <select disabled="" id="cs{{$key+1}}" avl="yes" rowCount="{{$key+1}}" name="currency{{$cury->id}}" class="js-example-basic-single row-id" >
                                   
                                    <?php  $currencys = App\multiCurrency::where('default_currency','=',1)->first(); 
                                    ?>
                                     
                                        <option value="{{($currencys->currency->id)}}" selected>{{trans($currencys->currency->code)}}{{'-'}}{{trans($currencys->currency->country)}}</option>
                                      
                                  </select>

                                @endif
                              </td>

                               <td class="fake-input">
                                  <select name="Position{{$cury->id}}" id='Position{{$key+1}}' class="js-example-basic-single">
                                      <option value="0">Select Currency Icon Position</option>
                                      <option {{ $cury->position == 'l' ? "selected" : "" }} value="l">Left without space $69</option>
                                      <option {{ $cury->position == 'r' ? "selected" : "" }} value="r">Right without space 69$</option>
                                      <option {{ $cury->position == 'ls' ? "selected" : "" }} value="ls">Left with space $ 69</option>
                                      <option {{ $cury->position == 'rs' ? "selected" : "" }} value="rs">Right with space 69 $</option>
                                </select>
                               </td>

                              <td class="fake-input">
                                <input class='exchange-rate-amount form-control' type="text" name="rate" id="rate{{$key+1}}" value="{{$cury->rate}}" disabled> <img class="rate-img" src="{{ url("images/load.gif") }}" width=20 />
                              </td>

                              <td class="fake-input">
                               <input placeholder="0.00" class='form-control exchange-amount' type="number" step='0.1' name="add_amount" id="add_amount{{$key+1}}" value="{{$cury->add_amount}}"/>
                              </td>

                               <td class="fake-input">
                                <input type="text" class="icp icp-auto action-create form-control showcur input-amount" name="currency_symbol" id="currency_symbol{{$key+1}}" value="{{$cury->currency_symbol}}"/>
                               </td>
                             
                               <td class="">

                                  <a class="btn btn-md btn-info" onclick="addNewCurrency('{{$key+1}}')">
                                    <img class="rate-img" id="rate-img{{ $key+1 }}" src="{{ url('images/save.gif') }}" height="18px" />
                                    <span id="savebtn{{ $key+1 }}"><i class="fa fa-save"></i></span>
                                   </a>

                                    @if($cury->default_currency != 1)
                                      <a href="{{url('admin/deleteCurrency/'.$cury->id)}}" onclick="return confirm('Are you sure you want to delete this?')" class="btn btn-md btn-danger"> 
                                        <i class="fa fa-trash"></i>
                                       </a>
                                    @else
                                       <a title="Default currency cannot be deleted !" disabled class="btn btn-md btn-danger"> 
                                        <i class="fa fa-trash"></i>
                                       </a>
                                   @endif
                               </td>


                             </tr>

                            
                             @endforeach
                            @endif
                         
                        
                      </tbody>
                      
                  </table>

                
                        <div class="row pull-left ">
                          <div class="col-md-6">

                            <a onclick="all_auto_update_currency()" class="btn btn-primary">
                             
                             <img class="rate-img" src="{{ url('images/savepre.gif') }}" height="18px" /> <span id="buttontext"><i class="fa fa-refresh"></i></span> Update Rates & Setting

                            </a>
                            
                          </div>
                          <div class="col-md-offset-1 col-md-5">
                            <a onclick="addRow()" class="btn btn-primary"><i class="fa fa-plus"></i> Add Currency</a>
                          </div>
                        </div>
                 
                  <br><br>
          </div>
          </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
         @include('admin.multiCurrency.location')
        </div>
        <!-- In Beta -->
        
        <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
         @include('admin.multiCurrency.checkout')
        </div> 

        <!-- In Beta -->
       
      </div>
    </div>
  </div>
@endsection
@section('custom-script')
  @include('admin.multiCurrency.currencyscript')
  <script>var baseUrl = "<?= url('/') ?>";</script>
  <script src="{{ url('js/currency.js') }}"></script>

@endsection