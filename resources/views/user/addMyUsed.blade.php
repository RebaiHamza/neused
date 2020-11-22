@extends("front/layout.master")
@php
$user = Auth::user();
$sellerac = App\Store::where('user_id','=', $user->id)->first();
@endphp
@section('title',__('staticwords.MyUsedProducts').' | ')
@section("body")

<div class="preL display-none">
  <div class="loaderT display-none">
  </div>
</div>
<div class="container-fluid">

  <div class="row">
    <div class="col-md-12 col-xl-3">
      <div class="bg-white">
        <div class="user_header">
          <h5 class="user_m">• {{ __('staticwords.Hi!') }} {{$user->name}}</h5>
        </div>
        <div align="center">
          @if($user->image !="")
          <img src="{{url('images/user/'.$user->image)}}" class="user-photo" />
          @else
          <img src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" class="user-photo" />
          @endif
          <h5>{{ $user->email }}</h5>
          <p>{{ __('staticwords.MemberSince') }}: {{ date('M jS Y',strtotime($user->created_at)) }}</p>
        </div>
        <br>
      </div>

      <!-- ===================== full-screen navigation start======================= -->

      <div class="bg-white navigation-small-block">
        <div class="user_header">
          <h5 class="user_m">• {{ __('staticwords.UserNavigation') }}</h5>
        </div>
        <p></p>
        <div class="nav flex-column nav-pills" aria-orientation="vertical">
          <a class="nav-link padding15 {{ Nav::isRoute('user.profile') }}" href="{{ url('/profile') }}"> <i
              class="fa fa-user-circle" aria-hidden="true"></i> {{ __('staticwords.MyAccount') }}</a>

          <a class="nav-link padding15 {{ Nav::isRoute('myusedlist') }}" href="{{ route('myusedlist') }}"> <i
              class="fa fa-cube" aria-hidden="true"></i> {{ __('staticwords.MyUsedProducts') }}</a>

          <a class="nav-link padding15 {{ Nav::isRoute('user.order') }}" href="{{ url('/order') }}"> <i
              class="fa fa-dot-circle-o" aria-hidden="true"></i> {{ __('staticwords.MyOrders') }}</a>

          <a class="nav-link padding15 {{ Nav::isRoute('failed.txn') }}" href="{{ route('failed.txn') }}"> <i
              class="fa fa-spinner" aria-hidden="true"></i> {{ __('staticwords.MyFailedTrancations') }}</a>

          <a class="nav-link padding15 {{ Nav::isRoute('user_t') }}" href="{{ route('user_t') }}">&nbsp;<i
              class="fa fa-ticket" aria-hidden="true"></i> {{ __('staticwords.MyTickets') }}</a>

          <a class="nav-link padding15 {{ Nav::isRoute('get.address') }}" href="{{ route('get.address') }}"><i
              class="fa fa-list-alt" aria-hidden="true"></i> {{ __('staticwords.ManageAddress') }}</a>

          <a class="nav-link padding15 {{ Nav::isRoute('mybanklist') }}" href="{{ route('mybanklist') }}"> <i
              class="fa fa-cube" aria-hidden="true"></i> {{ __('staticwords.MyBankAccounts') }}</a>


          @php
          $genral = App\Genral::first();
          @endphp
          @if($genral->vendor_enable==1)
          @if(empty($sellerac) && Auth::user()->role_id != "a")

          <a class="nav-link padding15 {{ Nav::isRoute('applyforseller') }}" href="{{ route('applyforseller') }}"><i
              class="fa fa-address-card-o" aria-hidden="true"></i> {{ __('staticwords.ApplyforSellerAccount') }}</a>

          @elseif(Auth::user()->role_id != "a")
          <a class="nav-link padding15 {{ Nav::isRoute('seller.dboard') }}" href="{{ route('seller.dboard') }}"><i
              class="fa fa-address-card-o" aria-hidden="true"></i> {{ __('staticwords.SellerDashboard') }}</a>

          @endif
          @endif


          <a class="nav-link padding15" data-toggle="modal" href="#myModal"><i class="fa fa-eye" aria-hidden="true"></i>
            {{ __('staticwords.ChangePassword') }}</a>


          <a class="nav-link padding15" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
            <i class="fa fa-power-off" aria-hidden="true"></i> {{ __('Sign out?') }}
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="display-none">
            @csrf
          </form>
          <br>
        </div>
      </div>



      <!-- ===================== full-screen navigation end ======================= -->

      <!-- =========================small screen navigation start ============================ -->
      <div class="order-accordion navigation-full-screen">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <h5 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false"
                  aria-controls="collapseOne">
                  <div class="user_header">
                    <h5 class="user_m">• {{ __('staticwords.UserNavigation') }}</h5>
                  </div>
                </a>
              </h5>
            </div>
            <div id="collapseOne" class="panel-collapse collapseOne collapse" role="tabpanel"
              aria-labelledby="headingOne">
              <div class="panel-body">
                <ul class="mnu_user nav-pills nav nav-stacked">
                  <li class="{{ Nav::isRoute('user.profile') }}">

                    <a href="{{ url('/profile') }}"><i class="fa fa-user-circle" aria-hidden="true"></i>
                      {{ __('staticwords.MyAccount') }}</a></li>

                  <li class="{{ Nav::isRoute('user.order') }}"><a href="{{ url('/order') }}"><i
                        class="fa fa-dot-circle-o" aria-hidden="true"></i>
                      {{ __('staticwords.MyOrders') }}</a></li>

                  <li class="{{ Nav::isRoute('failed.txn') }}"><a href="{{ route('failed.txn') }}"><i
                        class="fa fa-spinner" aria-hidden="true"></i> {{ __('staticwords.MyFailedTrancations') }}</a>
                  </li>

                  <li class="{{ Nav::isRoute('user_t') }}"><a href="{{ route('user_t') }}"><i
                        class="fa fa-envelope-square" aria-hidden="true"></i>

                      {{ __('staticwords.MyTickets') }}</a></li>

                  <li class="{{ Nav::isRoute('get.address') }}"> <a href="{{ route('get.address') }}"><i
                        class="fa fa-list-alt" aria-hidden="true"></i>

                      {{ __('staticwords.ManageAddress') }}</a>
                  </li>

                  <li class="{{ Nav::isRoute('mybanklist') }}"> <a href="{{ route('mybanklist') }}"><i
                        class="fa fa-cube" aria-hidden="true"></i>

                      {{ __('staticwords.MyBankAccounts') }}</a>
                  </li>

                  @php
                  $genral = App\Genral::first();
                  @endphp
                  @if($genral->vendor_enable==1)
                  @if(empty($sellerac) && Auth::user()->role_id != "a")

                  <li><a href="{{ route('applyforseller') }}"><i class="fa fa-address-card-o" aria-hidden="true"></i>
                      {{ __('staticwords.ApplyforSellerAccount') }}</a>
                  </li>
                  @elseif(Auth::user()->role_id != "a")
                  <li><a href="{{ route('seller.dboard') }}"><i class="fa fa-address-card-o" aria-hidden="true"></i>
                      {{ __('staticwords.SellerDashboard') }}</a>
                  </li>
                  @endif
                  @endif

                  <li>
                    <a data-toggle="modal" href="#myModal"><i class="fa fa-eye" aria-hidden="true"></i>
                      {{ __('staticwords.ChangePassword') }}</a>
                  </li>

                  <li>

                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                      <i class="fa fa-power-off" aria-hidden="true"></i> {{ __('Sign out?') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="display-none">
                      @csrf
                    </form>
                  </li>
                  <br>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- =========================small screen navigation end ============================ -->
    </div>

    <div class="col-md-12 col-xl-9">

      <div class="bg-white2">

        <h4 class="user_m2">{{ __('staticwords.ADDUsedProduct') }}</h5>
          <hr>
          <form method="post" action="{{url('user./'.$user->id)}}" enctype="multipart/form-data">
            {{csrf_field()}}

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="first-name">
                                Product Name: <span class="required">*</span>
                              </label>
                        
                              <input required="" placeholder="Please enter product name" type="text" autofocus="" name="name"
                                value="{{ old('name') }}" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <label>
                                Select Brand: <span class="required">*</span>
                              </label>
                              <select required="" name="brand_id" class="select2 form-control col-md-7 col-xs-12">
                                <option value="">Please Select</option>
                                @if(!empty($brands))
                                    @foreach($brands as $brand)
                                        <option value="{{$brand->id}}">{{$brand->name}} </option>
                                    @endforeach
                                @endif
                              </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="category_id">
                                Category: <span class="required">*</span>
                              </label>
                        
                              <select required="" name="category_id" id="category_id" class="select2 form-control">
                                <option value="">Please Select</option>
                        
                                @if(!empty($categorys))
                                @foreach($categorys as $category)
                                <option value="{{$category->id}}"> {{$category->title}} </option>
                                @endforeach
                                @endif
                        
                              </select>
                        </div>
                        <div class="col-md-3">
                            <label>
                                Subcategory: <span class="required">*</span>
                              </label>
                        
                              <select required="" name="child" id="upload_id" class="select2 form-control">
                                <option value="">Please Select</option>
                                @if(!empty($child))
                                    @foreach($child as $category)
                                        <option value="{{$category->id}}"> {{$category->title}} </option>
                                    @endforeach
                                @endif
                              </select>                        
                        </div>
                        <div class="col-md-3">
                            <label>
                                Child Category:
                              </label>
                        
                              <select name="grand_id" id="grand" class="select2 form-control">
                                <option value="">Please Select</option>
                                @if(!empty($child))
                                @foreach($grand as $category)
                                <option value="{{$category->id}}"> {{$category->title}} </option>
                                @endforeach
                                @endif
                              </select>                           
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            {{-- <label>
                                Select Owner :
                              </label>
                              <select required="" name="user_id" class="form-control select2">
                        
                        
                                @foreach($users as $user)
                        
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                        
                                @endforeach
                        
                        
                              </select>
                              <small class="txt-desc">(Please Choose Owner Name )</small> --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="editor2"> Key Features :
                            </label>
                            <textarea class="form-control" id="editor2" name="key_features"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="editor1"> Description:</label>
                            <textarea class="form-control" id="editor1" name="des">{{ old('des') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <label style="padding-top: 10px;padding-left:10px;">
                                Images 1:
                            </label>
                            <div class="panel panel-primary height-shadow" style="background-color: #643DC6">                      
                                <div align="center" class="panel-body padding-0">
                                  <img id="preview1" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
                                    alt="" class="margin-top-5 margin-bottom-10">
                                </div>
                                <div class="file-upload heyx">
                                  <div class="file-select">
                                    <input required name="image1" type="file" name="chooseFile" id="image1" style="color: cornsilk">
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label style="padding-top: 10px;padding-left:10px;">
                                Images 2:
                            </label>
                            <div class="panel panel-primary height-shadow" style="background-color: #643DC6">                      
                                <div align="center" class="panel-body padding-0">
                                  <img id="preview1" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
                                    alt="" class="margin-top-5 margin-bottom-10">
                                </div>
                                <div class="file-upload heyx">
                                  <div class="file-select">
                                    <input required name="image2" type="file" name="chooseFile" id="image2" style="color: cornsilk">
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label style="padding-top: 10px;padding-left:10px;">
                                Images 3:
                            </label>
                            <div class="panel panel-primary height-shadow" style="background-color: #643DC6">                      
                                <div align="center" class="panel-body padding-0">
                                  <img id="preview1" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
                                    alt="" class="margin-top-5 margin-bottom-10">
                                </div>
                                <div class="file-upload heyx">
                                  <div class="file-select">
                                    <input required name="image3" type="file" name="chooseFile" id="image3" style="color: cornsilk">
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label style="padding-top: 10px;padding-left:10px;">
                                Images 4:
                            </label>
                            <div class="panel panel-primary height-shadow" style="background-color: #643DC6">                      
                                <div align="center" class="panel-body padding-0">
                                  <img id="preview1" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
                                    alt="" class="margin-top-5 margin-bottom-10">
                                </div>
                                <div class="file-upload heyx">
                                  <div class="file-select">
                                    <input required name="image4" type="file" name="chooseFile" id="image4" style="color: cornsilk">
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label style="padding-top: 10px;padding-left:10px;">
                                Images 5:
                            </label>
                            <div class="panel panel-primary height-shadow" style="background-color: #643DC6">                      
                                <div align="center" class="panel-body padding-0">
                                  <img id="preview1" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
                                    alt="" class="margin-top-5 margin-bottom-10">
                                </div>
                                <div class="file-upload heyx">
                                  <div class="file-select">
                                    <input required name="image5" type="file" name="chooseFile" id="image5" style="color: cornsilk">
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label style="padding-top: 10px;padding-left:10px;">
                                Images 6:
                            </label>
                            <div class="panel panel-primary height-shadow" style="background-color: #643DC6">                      
                                <div align="center" class="panel-body padding-0">
                                  <img id="preview1" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
                                    alt="" class="margin-top-5 margin-bottom-10">
                                </div>
                                <div class="file-upload heyx">
                                  <div class="file-select">
                                    <input required name="image6" type="file" name="chooseFile" id="image6" style="color: cornsilk">
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="warranty_info">Warranty:</label>

                            <label>(Duration)</label>
                            <select class="form-control" name="w_d" id="">
                                <option>None</option>
                                @for($i=0;$i<=12;$i++) <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Days/Months/Year:</label>

                            <select class="form-control" name="w_my" id="">
                                <option>None</option>
                                <option value="day">Day</option>
                                <option value="month">Month</option>
                                <option value="year">Year</option>
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label>Type:</label>
                            <select class="form-control" name="w_type" id="">
                                <option>None</option>
                                <option value="Guarantee">Guarantee</option>
                                <option value="Warranty">Warranty</option>
                            </select>                           
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Start Selling From:
                              </label>
                              <div class='input-group date' id='datetimepicker1'>
                                <input value="{{ old('selling_start_at') }}" name="selling_start_at" type='date' class="form-control" />
                                <span class="input-group-addon">
                                  <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                              </div>
                        </div>
                        <div class="col-md-3">
                            <label>
                              Tags:
                            </label>
                        
                            <input value="{{ old('tags') }}" placeholder="Please enter tag seprated by Comma(,)" type="text" name="tags" class="form-control">                        
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <label>
                              Model:
                            </label>
                    
                            <input type="text" id="model" name="model" class="form-control" placeholder="Please Enter Model Number"
                                value="{{ old('model') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="sku">
                              SKU:
                            </label>
                            <input type="text" id="sku" name="sku" value="{{ old('sku') }}" placeholder="Please enter SKU" class="form-control">                        
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <br>
                            <label>
                                <input checked type="checkbox" id="tax_manual" class="toggle-input toggle-buttons" name="tax_manual">
                                <span class="knob"></span>
                            </label>
                                <label class="ptax">Price Including Tax ?</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <label>
                                Price: <span class="required">*</span>
                                <span class="help-block">(Price you entering is in {{ $genrals_settings->currency_code }})</span>
                            </label>
                        
                            <input pattern="[0-9]+(\.[0-9][0-9]?)?" title="Price Format must be in this format : 200 or 200.25" required=""
                                type="text" id="price" name="price" value="{{ old('price') }}" placeholder="Please enter product price"
                                class="form-control">
                            <br>
                            <small class="text-muted"><i class="fa fa-question-circle"></i> Don't put comma whilt entering PRICE</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>
                                Free Shipping:
                              </label>
                          
                              <input name="free_shipping" class="tgl tgl-skewed" id="toggle-event5" type="checkbox" />
                              <label class="tgl-btn" data-tg-off="No" data-tg-on="Yes" for="toggle-event5"></label>
                              <br>
                              <small class="txt-desc">(If Enabled Then Free Shipping will enabled for this product) </small>
                        </div>
                        <div class="col-md-3">
                            <label for="featured">
                                Featured:
                              </label>
                          
                          
                              <input class="tgl tgl-skewed" id="toggle-event2" type="checkbox" />
                              <label class="tgl-btn" data-tg-off="No" data-tg-on="Yes" for="toggle-event2"></label>
                          
                          
                              <input type="hidden" name="featured" id="featured">
                              <br>
                              <small class="txt-desc">(If enabled than product will be featured) </small>
                        </div>
                        <div class="col-md-3">
                            <label for="status3">
                                Status:
                              </label>
                          
                              <input id="toggle-event3" class="tgl tgl-skewed" type="checkbox">
                              <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event3"></label>
                              <input @if(!empty($products)) value="{{ $products->status }}" @else value="0" @endif type="hidden" name="status"
                                id="status3">
                                <br>
                              <small class="txt-desc">(Please Choose Status )</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="status4">
                                Cancel Available:
                              </label>
                          
                              <input id="toggle-event4" class="tgl tgl-skewed" type="checkbox">
                              <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event4"></label>
                              <input type="hidden" name="cancel_avl" id="status4">
                              <br>
                              <small class="txt-desc">(Please Choose Cancel Available )</small>
                        </div>
                        <div class="col-md-3">
                            <label for="codcheck">
                                Cash On Delivery:
                              </label>
                          
                              <input id="codcheck" name="codcheck" class="tgl tgl-skewed" type="checkbox">
                              <label class="tgl-btn" data-tg-off="Disable" data-tg-on="Enable" for="codcheck"></label>
                          <br>
                              <small class="txt-desc">(Please Choose Cash on Delivery Available On This Product or Not)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="choose_policy">Return Available :</label>
                            <select required id="choose_policy" class="col-md-4 form-control" name="return_avbls">
                            <option value="">Please choose an option</option>
                            <option value="1">Return Available</option>
                            <option value="0">Return Not Available</option>
                            </select>
                            
                            <small class="text-desc">(Please choose an option that return will be available for this product or not)</small>
                        </div>
                        <div class="col-md-6">
                            <label for="return_policy">Choose Return Policy: </label>
                            <select class="col-md-4 form-control" id="return_policy" name="return_policy">
                            <option value="">Please choose an option</option>
                            @foreach(App\admin_return_product::where('created_by',Auth::user()->id)->get() as $policy)
                            <option value="{{ $policy->id }}">{{ $policy->name }}</option>
                            @endforeach
                            </select>
                            
                            <small class="text-desc">(Please choose an option that return will be available for this product or not)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-6">
                            <button type="submit" class="col-md-4 btn btn-block btn-primary"><i class="fa fa-plus"></i> Add Product</button>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>

            </div>

          </form>
      </div>
    </div>


  </div>

</div>

<!--end-->

<!-- Change password Modal -->
<div class="z-index99 modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ __('staticwords.ChangePassword') }} ?</h5>
      </div>
      <div class="modal-body">
        <form id="form1" action="{{ route('pass.update',$user->id) }}" method="POST">
          {{ csrf_field() }}

          <div class="form-group eyeCy">


            <label class="font-weight-bold" for="confirm">{{ __('Old Password') }}:</label>
            <input required="" type="password" class="form-control @error('old_password') is-invalid @enderror"
              placeholder="Enter old password" name="old_password" id="old_password" />

            <span toggle="#old_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

            @error('old_password')
            <span class="invalid-feedback text-danger" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>



          <div class="form-group eyeCy">



            <label class="font-weight-bold" for="password">{{ __('staticwords.EnterPassword') }}:</label>
            <input required="" id="password" min="6" max="255" type="password"
              class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" name="password"
              minlength="8" />

            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

            @error('password')
            <span class="invalid-feedback text-danger" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror


          </div>



          <div class="form-group eyeCy">


            <label class="font-weight-bold" for="confirm">{{ __('staticwords.ConfirmPassword') }}:</label>
            <input required="" id="confirm_password" type="password" class="form-control"
              placeholder="{{ __('Re-enter password for confirmation') }}" name="password_confirmation" minlength="8" />

            <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

            <p id="message"></p>
          </div>


          <button @if(env('DEMO_LOCK')==0) type="submit" @else title="disabled"
            title="This action is disabled in demo !" @endif id="test" class="btn btn-md btn-success"><i
              class="fa fa-save"></i> {{ __('staticwords.SaveChanges') }}</button>
          <button id="btn_reset" data-dismiss="modal" class="btn btn-danger btn-md" type="reset">X
            {{ __('staticwords.Cancel') }}</button>
        </form>

      </div>

    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ url('js/jquery.js') }}"></script>
<script>
   
   $('#category_id').on('change',function() {
 
    var up = $('#upload_id').empty();
    var cat_id = $(this).val();    
    if(cat_id){
      $.ajax({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:"GET",
        url: baseUrl+'/admin/dropdown',
        data: {catId: cat_id},
        success:function(data){   
          
          up.append('<option value="">Please Choose</option>');
          $.each(data, function(id, title) {
            up.append($('<option>', {value:id, text:title}));
          });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log(XMLHttpRequest);
        }
      });
    }
  });
  $('#upload_id').on('change',function() {
      var up = $('#grand').empty();
      var cat_id = $(this).val();    
      if(cat_id){
        $.ajax({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:"GET",
          url: baseUrl+'/admin/gcat',
          data: {catId: cat_id},
          success:function(data){   
            
            up.append('<option value="">Please Choose</option>');
            $.each(data, function(id, title) {
              up.append($('<option>', {value:id, text:title}));
            });
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest);
          }
        });
      }
    });
   
   </script>

@endsection




