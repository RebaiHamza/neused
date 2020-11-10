@extends("front/layout.master") @php $sellerac = App\Store::where('user_id','=', $user->id)->first(); @endphp @section('title','Apply For Seller Account |') @section("body")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-3">
      <div class="bg-white">
        <div class="user_header">
          <h5 class="user_m">• {{ __('staticwords.Hi!') }} {{$user->name}}</h5></div>
        <div align="center"> @if($user->image !="") <img src="{{url('images/user/'.$user->image)}}" class="user-photo" /> @else <img src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" class="user-photo" /> @endif
          <h5>{{ $user->email }}</h5>
          <p>{{ __('staticwords.MemberSince') }}: {{ date('M jS Y',strtotime($user->created_at)) }}</p>
        </div>
        <br> </div>
      <!-- ===================== full-screen navigation start======================= -->
      <div class="bg-white navigation-small-block">
          <div class="user_header">
            <h5 class="user_m">• {{ __('staticwords.UserNavigation') }}</h5>
          </div>
      <p></p>
       <div class="nav flex-column nav-pills" aria-orientation="vertical">
          <a class="nav-link padding15 {{ Nav::isRoute('user.profile') }}" href="{{ url('/profile') }}"> <i class="fa fa-user-circle" aria-hidden="true"></i> {{ __('staticwords.MyAccount') }}</a>
          
          <a class="nav-link padding15 {{ Nav::isRoute('user.order') }}" href="{{ url('/order') }}"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> {{ __('staticwords.MyOrders') }}</a>

          @if($wallet_system == 1)
            <a class="nav-link padding15 {{ Nav::isRoute('user.wallet.show') }}" href="{{ route('user.wallet.show') }}"><i class="fa fa-credit-card" aria-hidden="true"></i>
            {{ __('staticwords.MyWallet') }}
            </a>
          @endif
          
          <a class="nav-link padding15 {{ Nav::isRoute('failed.txn') }}" href="{{ route('failed.txn') }}"> <i class="fa fa-spinner" aria-hidden="true"></i> {{ __('staticwords.MyFailedTrancations') }}</a>

          <a class="nav-link padding15 {{ Nav::isRoute('user_t') }}"  href="{{ route('user_t') }}">&nbsp;<i class="fa fa-ticket" aria-hidden="true"></i> {{ __('staticwords.MyTickets') }}</a>

          <a class="nav-link padding15 {{ Nav::isRoute('get.address') }}" href="{{ route('get.address') }}"><i class="fa fa-list-alt" aria-hidden="true"></i> {{ __('staticwords.ManageAddress') }}</a>
      
          <a class="nav-link padding15 {{ Nav::isRoute('mybanklist') }}" href="{{ route('mybanklist') }}"> <i class="fa fa-cube" aria-hidden="true"></i> {{ __('staticwords.MyBankAccounts') }}</a>
      
          
         @php
            $genral = App\Genral::first();
          @endphp
          @if($genral->vendor_enable==1)
          @if(empty($sellerac) && Auth::user()->role_id != "a")
         
          <a class="nav-link padding15 {{ Nav::isRoute('applyforseller') }}" href="{{ route('applyforseller') }}"><i class="fa fa-address-card-o" aria-hidden="true"></i> {{ __('staticwords.ApplyforSellerAccount') }}</a>
          
          @elseif(Auth::user()->role_id != "a")
           <a class="nav-link padding15 {{ Nav::isRoute('seller.dboard') }}" href="{{ route('seller.dboard') }}"><i class="fa fa-address-card-o" aria-hidden="true"></i> {{ __('staticwords.SellerDashboard') }}</a>
          
          @endif
          @endif
          
          
            <a class="nav-link padding15" data-toggle="modal" href="#myModal"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('staticwords.ChangePassword') }}</a>
          

              <a class="nav-link padding15" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-power-off" aria-hidden="true"></i>  {{ __('Sign out?') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="display-none">
                                        @csrf
                                    </form>
<br>
        </div>
        </div>
    </div>
    <div class="col-md-9">
      <?php $auth_id = Auth::user()->id; ?>
        <div class="bg-white2"> 
          @if(empty($sellerac))
            <div class="wizard-container"> 
                @php 
                  $auth_id = Auth::user()->id; 
                @endphp
                <div class="wizard-card" data-color="orange" id="wizardProfile">
                  <form method="post" enctype="multipart/form-data" action="{{url('store_vender')}}"> 
                    {{csrf_field()}}
                    <div class="wizard-header text-left">
                      <h3 class="wizard-title">{{ __('staticwords.createStore') }}</h3> </div>
                    <div class="wizard-navigation">
                      <ul class="nav nav-tabs" role="tablist">
                        <li>
                          <a class="text-center" href="#about" role="tab" data-toggle="tab"> <i class="fa fa-user-o" aria-hidden="true"></i> {{ __('staticwords.sellerinfo') }} </a>
                        </li>
                        <li>
                          <a class="text-center" href="#account" data-toggle="tab"> <i class="{{session()->get('currency')['value']}}"></i> {{ __('staticwords.PaymentDetails') }} </a>
                        </li>
                        <li>
                          <a class="text-center" href="#cat" data-toggle="tab"> <i class="fa fa-comments-o" aria-hidden="true"></i> {{ __('staticwords.SellerInterview') }} </a>
                        </li>
                      </ul>
                    </div>
                    <div class="tab-content">
                      <div class="tab-pane show active" id="about">
                        <h5 class="info-text"> {{ __('staticwords.businessTell') }}.</h5>
                        <div class="row">
                          <div class="col-md-6 col-sm-6">
                            <div class="picture-container">
                              <div class="picture"> <img src="{{url('images/user.png')}}" class="picture-src img-fluid" id="wizardPicturePreview" title="" />
                                <input type="file" name="store_logo" id="wizard-picture"> </div>
                              <h6>{{ __('staticwords.PleaseChooseStoreLogo') }}</h6> </div> <span class="required">{{$errors->first('store_logo')}}</span> 
                            </div>
                          <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                              <label class="font-weight-bold">{{ __('staticwords.StoreName') }} <small class="required">*</small></label>
                              <input type="hidden" id="first-name" name="user_id" class="form-control" value="{{$auth_id}}">
                              <input id="firstname" type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="{{ __('staticwords.Pleaseenterstorename') }}"> </div>
                            <div class="form-group">
                              <label class="font-weight-bold">{{ __('staticwords.Email') }} <small class="required">*</small></label>
                              <input name="email" type="email" class="form-control" value="{{old('email')}}" placeholder="{{ __('staticwords.eaddress') }}"> </div>
                            <div class="form-group">
                              <label class="font-weight-bold">{{ __('staticwords.Country') }} <small class="required">*</small></label>
                              <select name="country_id" class="form-control" id="country_id">
                                <option value="0">{{ __('staticwords.PleaseChooseCountry') }}</option> @foreach($country as $c)
                                  <?php
                                    $iso3 = $c->country;

                                    $country_name = DB::table('allcountry')->
                                    where('iso3',$iso3)->first();
                                  ?>
                                  <option value="{{$country_name->id}}" /> {{$country_name->nicename}} </option> @endforeach </select>
                            </div>
                            <div class="form-group">
                              <label class="font-weight-bold">{{ __('staticwords.State') }} <small class="required"></small></label>
                              <select name="state_id" class="form-control" id="upload_id">
                                <option value="0">{{ __('staticwords.PleaseChooseState') }}</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label class="font-weight-bold">{{ __('staticwords.City') }} <small class="required">*</small></label>
                              <select name="city_id" id="city_id" class="form-control">
                                <option value="0">{{ __('staticwords.PleaseChooseCity') }}</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label class="font-weight-bold">{{ __('staticwords.Pincode') }} ({{ __('staticwords.optional') }}) </label>
                              <input pattern="[0-9]+" title="Invalid pincode" type="text" id="first-name" placeholder="{{ __('staticwords.PleaseEnterPinCode') }}" name="pin_code" value="{{old('pin_code')}}" class="form-control"/> </div>
                            <br>
                            <br>
                            <div class="form-group">
                              <label class="font-weight-bold">{{ __('staticwords.StoreAddress') }} <small class="required">*</small></label>
                              <input type="text" id="first-name" placeholder="{{ __('staticwords.PleaseEnterStoreAddress') }}" name="address" value="{{old('address')}}" class="form-control"> <span class="required">{{$errors->first('address')}}</span> </div>
                            <div class="gap form-group">
                              <label class="font-weight-bold">{{ __('staticwords.MobileNo') }} <small class="required">*</small></label>
                              <input title="Invalid mobile no" pattern="[0-9]+" type="text" id="first-name" placeholder="{{ __('staticwords.PleaseEnterMobileNo') }} " name="mobile" value="{{old('mobile')}}" class="form-control">
                              <p class="txt-desc"></p> <span class="required">{{$errors->first('mobile')}}</span> </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="account">
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="row">
                              <div class="form-group col-md-6">
                               
                                  <div class="custom-control custom-checkbox">
                                    <input onchange="valueChanged()" type="checkbox" class="paypal custom-control-input" id="paypal" name="paypal" value="1">
                                    <label class="custom-control-label" for="paypal">
                                      {{ __('Paypal') }}
                                    </label>
                                  </div>
                              </div>
                              <div class="form-group col-md-6">
                               
                                  
                                  <div class="custom-control custom-checkbox">
                                    <input type="checkbox" value="1" onchange="valueChanged()" name="paytem" class="custom-control-input paytem" id="paytem">
                                    <label class="custom-control-label" for="paytem">
                                      {{ __('Paytm') }}
                                    </label>
                                  </div>

                              </div>
                            </div>
                            
                            <div class="form-group">
                              <label class="font-weight-bold"> {{ __('staticwords.AccountNumber') }} <small class="required">*</small></label>
                              <input required pattern="[0-9]+" title="Invalid account no." type="text" id="first-name" name="account" value="{{old('account')}}" placeholder="{{ __('staticwords.PleaseEnterAccountNumber') }}" class="form-control"> <span class="required">{{$errors->first('account')}}</span> </div>
                            
                            <div class="gap form-group">
                              <label class="font-weight-bold"> {{ __('staticwords.AccountName') }}: <small class="required">*</small></label>
                              <input required type="text" id="first-name" name="account_name" value="{{old('account_name')}}" placeholder="{{ __('staticwords.PleaseEnterAccountName') }}" class="form-control"> <span class="required">{{$errors->first('bank_name')}}</span> </div>
                           
                            <div class="gap form-group">
                              <label class="font-weight-bold"> {{ __('staticwords.BankName') }}: <small class="required">*</small></label>
                              <input required type="text" id="first-name" name="bank_name" value="{{old('bank_name')}}" placeholder="{{ __('staticwords.PleaseEnterBankName') }}" class="form-control"> <span class="required">{{$errors->first('bank_name')}}</span> </div>
                           
                            <div class="gap form-group">
                              <label class="font-weight-bold"> {{ __('IFSC Code') }}: <small class="required">*</small></label>
                              <input required="" type="text" name="ifsc" value="{{old('ifsc')}}" placeholder="{{ __('staticwords.PleaseEnterIFSCCode') }}" class="form-control "> <span class="required">{{$errors->first('ifsc')}}</span> </div>
                          
                            <div class="gap form-group">
                              <label class="font-weight-bold">{{ __('staticwords.BranchAddress') }}: <small class="required">*</small></label>
                              <input required="" type="text" id="first-name" name="branch" placeholder="Please Enter Branch Address" value="{{old('branch')}}" class="form-control"> </div> <span class="required">{{$errors->first('branch')}}</span>
                            <div class="gap form-group" id="paypal_email">
                             
                              <label class="font-weight-bold">{{ __('PayPal Email') }}: <small class="required">*</small></label>
                              <input type="email" name="paypal_email" placeholder="Please Enter Paypal Email" value="{{old('paypal_email')}}" class="form-control"> <span class="required">{{$errors->first('paypal_email')}}</span> </div>
                            <div class="gap form-group" id="paytem_mobile">
                             
                              <label class="font-weight-bold">{{ __('Paytm Mobile Number') }}: <small class="required">*</small></label>
                              <input pattern="[0-9]+" title="Invalid Paytm No." type="text" name="paytem_mobile" placeholder="Please Enter Paytm Kyc Completed Mobile Number" value="{{old('paytem_mobile')}}" class="form-control"> <span class="required">{{$errors->first('paytem_mobile')}}</span> </div>
                            
                            <div class="gap form-group">
                              <label class="font-weight-bold">{{ __('staticwords.PreferredPaymentMethod') }}: <small class="required">*</small></label>
                              <select name="preferd" class="form-control" id="prefrd">
                                <option value="0">{{ __('staticwords.PleaseChoosePreferredPaymentmethod') }}</option>
                                <option value="paypal">{{ __('Paypal') }}</option>
                                <option value="paytem">{{ __('Paytm') }}</option>
                              </select> <span class="required">{{$errors->first('prefard')}}</span> </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="cat">
                        <h3 class="info-text"> {{ __('staticwords.ChooseCategoryyouwishtosell') }} </h3>
                        <div>
                          <div class="row form-group"> 
                            @php
                              $allcat = App\Category::where('status','1')->get();
                            @endphp
                            @foreach(App\Category::where('status','1')->get(); as $cat)
                            <div class="col-md-4">
                              <div class="card-body">
                                <div class="custom-control custom-checkbox">
                                  <input type="checkbox" value="{{$cat->title}}"  name="vehicle[]" class="custom-control-input" id="cat{{ $cat->id }}">
                                  <label class="custom-control-label" for="cat{{ $cat->id }}">{{$cat->title}}</label>
                                </div>
                                
                              </div>
                            </div> 
                            @endforeach 
                          </div>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label class="font-weight-bold" for="">{{ __('staticwords.Other') }}:</label>
                          <div class="custom-control custom-checkbox">
                                  <input {{ count($allcat) < 1 ? "required" : "" }} type="checkbox" class="custom-control-input" id="Other">
                                  <label class="custom-control-label" for="Other">{{ __('staticwords.MyCategoryisnotlistedhere') }}</label>
                          </div>
                          
                          
                          
                        <hr>
                        <div class="form-group">
                          <label class="font-weight-bold" for="">{{ __('staticwords.Doyousellproductatotherwebsitealso') }}?</label>
                          <div class="row">
                            <div class="col-md-2 col-xs-4">
                              <div class="custom-control custom-radio">
                                <input required="" type="radio" id="yes" value="Yes" name="name1" class="custom-control-input">
                                <label class="custom-control-label" for="yes">{{ __('Yes') }}</label>
                              </div>
                              
                              
                            </div>
                            <div class="m-55 col-md-2 col-xs-4">
                               <div class="custom-control custom-radio">
                                <input required="" type="radio" id="No" value="No" name="name1" class="custom-control-input">
                                <label class="custom-control-label" for="No">
                                  {{ __('No') }}
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="wizard-footer">
                      <div class="pull-right"> <a id="abc" class='btn btn-next btn-fill btn-primary btn-wd'><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>{{ __('staticwords.Next') }}</a>
                        <button type="submit" class='btn btn-finish btn-fill btn-primary btn-wd'> <i class="fa fa-floppy-o" aria-hidden="true"></i>{{ __('staticwords.Submit') }}</button>
                      </div>
                      <div class="pull-left">
                        <button type='button' class='blackcolor btn btn-previous btn-default btn-wd'/>{{ __('staticwords.Previous') }}</button> </div>
                      <div class="clearfix"></div>
                    </div>
                  </form>
                </div>
            </div> 
          @endif
        </div>
    </div>
  </div>
</div>
  <!-- Change password Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ __('staticwords.ChangePassword') }} ?</h5>
      </div>
      <div class="modal-body">
        <form id="form1" action="{{ route('pass.update',$user->id) }}" method="POST">
          {{ csrf_field() }}

          <div class="form-group eyeCy">
           
              
          <label class="font-weight-bold" for="confirm">{{ __('Old Password') }}:</label>
          <input required="" type="password" class="form-control @error('old_password') is-invalid @enderror" placeholder="Enter old password" name="old_password" id="old_password" />
          
          <span toggle="#old_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

          @error('old_password')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>



          <div class="form-group eyeCy">
         

            
               <label class="font-weight-bold" for="password">{{ __('staticwords.EnterPassword') }}:</label>
                <input required="" id="password" min="6" max="255" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" name="password" minlength="8"/>
              
               <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            
             @error('password')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         
          
          </div>

          
          
          <div class="form-group eyeCy">
           
              
          <label class="font-weight-bold" for="confirm">{{ __('staticwords.ConfirmPassword') }}:</label>
          <input required="" minlength="8" id="confirm_password" type="password" class="form-control" placeholder="Re-enter password for confirmation" name="password_confirmation"/>
          
          <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

           <p id="message"></p>
          </div>
          

          <button type="submit" id="test" class="btn btn-md btn-success"><i class="fa fa-save"></i> {{ __('staticwords.SaveChanges') }}</button>
          <button id="btn_reset" data-dismiss="modal" class="btn btn-danger btn-md" type="reset">X {{ __('staticwords.Cancel') }}</button>
        </form>
        
      </div>
      
    </div>
  </div>
</div>
@endsection 
@section('script')

<script src="{{ url('js/profile.js') }}"></script>
<script>var baseUrl = "<?= url('/') ?>";</script>
<script src="{{ url('js/ajaxlocationlist.js') }}"></script>
<script src="{{ url('js/sellerform.js') }}"></script>
@endsection