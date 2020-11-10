@extends("front/layout.master")
          @php
              $sellerac = App\Store::where('user_id','=', $user->id)->first();
          @endphp
          @section('title',__('staticwords.ManageAccount').' | ')
@section("body")

<div class="container-fluid">

    <div class="row">
        <div class="col-md-12 col-xl-3">
            <div class="bg-white">
              <div class="user_header"><h5 class="user_m">• {{ __('staticwords.Hi!') }} {{$user->name}}</h5></div>
              <div align="center">
                 @if($user->image !="")
                <img src="{{url('images/user/'.$user->image)}}" class="user-photo"/>
                @else
                <img src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" class="user-photo"/>
                @endif
                  <h5>{{ $user->email }}</h5>
                <p>{{ __('staticwords.MemberSince') }}: {{ date('M jS Y',strtotime($user->created_at)) }}</p>
              </div>
                <br>
            </div>

<!-- ===================== full-screen navigation start======================= -->

        <div class="bg-white navigation-small-block">
          <div class="user_header">
            <h4 class="user_m">• {{ __('staticwords.UserNavigation') }}</h5>
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
           <a class="nav-link padding15{{ Nav::isRoute('seller.dboard') }}" href="{{ route('seller.dboard') }}"><i class="fa fa-address-card-o" aria-hidden="true"></i> {{ __('staticwords.SellerDashboard') }}</a>
          
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
            
        

 <!-- ===================== full-screen navigation end ======================= -->      
        
  <!-- =========================small screen navigation start ============================ -->
<div class="order-accordion navigation-full-screen">
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            <div class="user_header">
              <h4 class="user_m">• {{ __('staticwords.UserNavigation') }}</h5>
            </div>
          </a>
        </h5>
      </div>
      <div id="collapseOne" class="panel-collapse collapseOne collapse" role="tabpanel" aria-labelledby="headingOne">
          <div class="panel-body">
            <ul class="mnu_user nav-pills nav nav-stacked">
          <li class="{{ Nav::isRoute('user.profile') }}">
 
            <a  href="{{ url('/profile') }}"><i class="fa fa-user-circle" aria-hidden="true"></i> {{ __('staticwords.MyAccount') }}</a></li>
          
          <li class="{{ Nav::isRoute('user.order') }}"><a href="{{ url('/order') }}"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>
 {{ __('staticwords.MyOrders') }}</a></li>

  <li class="{{ Nav::isRoute('failed.txn') }}"><a href="{{ route('failed.txn') }}"><i class="fa fa-spinner" aria-hidden="true"></i> {{ __('staticwords.MyFailedTrancations') }}</a></li>

 <li class="{{ Nav::isRoute('user_t') }}"><a href="{{ route('user_t') }}"><i class="fa fa-envelope-square" aria-hidden="true"></i>

 {{ __('staticwords.MyTickets') }}</a></li>

 <li class="{{ Nav::isRoute('get.address') }}"> <a href="{{ route('get.address') }}"><i class="fa fa-list-alt" aria-hidden="true"></i>

        {{ __('staticwords.ManageAddress') }}</a>
      </li>

       <li class="{{ Nav::isRoute('mybanklist') }}"> <a href="{{ route('mybanklist') }}"><i class="fa fa-cube" aria-hidden="true"></i>

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

<a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-power-off" aria-hidden="true"></i>  {{ __('Sign out?') }}
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

                <h4 class="user_m2">{{ __('staticwords.MyAccount') }}</h5>
                <hr>
                <form method="post" action="{{url('update_profile/'.$user->id)}}"  enctype="multipart/form-data">
                  {{csrf_field()}}
  
                  <div class="row">
                    <div class="col-md-12">

                      <div class="row">
                        <div class="col-md-6">
                          <h5>{{ __('staticwords.PersonalInformation') }}</h5>
                        </div>
                        <div class="col-md-6">
                           <p id="edit1" class="cursor-pointer text-primary"><b>{{ __('staticwords.Edit') }}</b></p>
                           <p id="cancel1" class="cursor-pointer display-none text-primary"><b>{{ __('staticwords.Cancel') }}</b></p>
                        </div>
              
                           <div class="col-md-6">
      
      
      <label class="font-weight-bold" for="first-name">
      {{ __('staticwords.UserName') }}:<span class="required">*</span>
      </label>
      <input disabled="disabled" autofocus type="text" id="fname" name="name" value="{{$user->name}}" class="form-control" placeholder="Please enter User name">
      <span class="required">{{$errors->first('name')}}</span>
      <small class="txt-desc">({{ __('staticwords.PleaseEnterUserName') }})</small>
        </div>
      
        <div class="col-md-6">
            <label class="font-weight-bold" class="font-weight-bold" for="first-name">
        {{ __('staticwords.UserEmail') }}: <span class="required">*</span>
      </label>
        <input disabled="disabled" autofocus type="text" id="mail" name="email" value="{{$user->email}}" class="form-control" placeholder="Please enter email">
        <span class="required">{{$errors->first('email')}}</span>
        <small class="txt-desc">({{ __('staticwords.PleaseEnterUserEmail') }})</small>
        </div>

        <div id="save_btn1" class="display-none margin-top-22 col-md-4">
          <button type="submit" class="btn btn-md btn-primary">{{ __('staticwords.Save') }}</button>
        </div>

                      </div>

                    </div>
        
                    <div class="col-md-12">
                      <hr>
                      <div class="row">
                        <div class="col-md-6">
                          <h5>{{ __('staticwords.MobilePhone') }}</h5>
                        </div>
                        <div class="col-md-6">
                           <p id="edit2" class="cursor-pointer text-primary"><b>{{ __('staticwords.Edit') }}</b></p>
        <p id="cancel2" class="cursor-pointer display-none text-primary"><b>{{ __('staticwords.Cancel') }}</b></p>
                        </div>

                        <div class="col-md-6">
                          <label class="font-weight-bold" for="first-name">
        {{ __('staticwords.MobileNo') }} <span class="required">*</span>
      </label>
        <input disabled placeholder="Please enter Mobile No" type="text" id="mob" name="mobile" value=" {{$user->mobile}} " class="form-control">
        <span class="required">{{$errors->first('mobile')}}</span>
          <small class="txt-desc">({{ __('staticwords.PleaseEnterMobileNo') }} )</small>
                        </div>
                        <div class="col-md-6">
                          <label class="font-weight-bold" class="control-label" for="first-name">
        {{ __('staticwords.PhoneNo') }}
      </label>
        <input disabled placeholder="Please enter Phone no" type="text" id="phone" name="phone" value="{{$user->phone}} " class="form-control">
        <small class="txt-desc">({{ __('staticwords.PleaseEnterPhoneNo') }}. )</small>
                        </div>

                        <div id="save_btn2" class="display-none margin-top-22 col-md-4">
          <button type="submit" class="btn btn-md btn-primary">{{ __('staticwords.Save') }}</button>
        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                    <hr>
                      <div class="row">
                       <div class="col-md-6">
                          <h5>{{ __('staticwords.GeoLocation') }}</h5>
                        </div>
                        <div class="col-md-6">
                           <p id="edit3" class="cursor-pointer text-primary"><b>{{ __('staticwords.Edit') }}</b></p>
        <p id="cancel3" class="cursor-pointer display-none text-primary"><b>{{ __('staticwords.Cancel') }}</b></p>
                        </div>

                        <div class="gap col-md-4">
             <label class="font-weight-bold" class="control-label">
        {{ __('staticwords.Country') }}: <span class="required">*</span>
      </label>
        <select disabled name="country_id" class="form-control col-md-7 col-xs-12" id="country_id">
        <option>{{ __('staticwords.PleaseChoose') }}</option>
          @foreach($country as $c)
          <?php
            $iso3 = $c->country;

            $country_name = DB::table('allcountry')->
            where('iso3',$iso3)->first();
            ?>
          <option value="{{$country_name->id}}" {{ $country_name->id == $user->country_id ? 'selected="selected"' : '' }} >
            {{$country_name->nicename}}
          </option>
          @endforeach
        </select>
         <small class="txt-desc">({{ __('staticwords.PleaseChooseCountry') }})</small>
        </div>

        <div class="gap col-md-4">
            <label class="font-weight-bold" class="control-label" for="first-name">
        {{ __('staticwords.State') }}: <span class="required">*</span>
      </label>
         <select disabled name="state_id" class="form-control col-md-7 col-xs-12" id="upload_id" >
          <option>{{ __('staticwords.PleaseChoose') }}</option>
           @foreach($states as $c)
          <option value="{{$c->id}}" {{ $c->id == $user->state_id ? 'selected="selected"' : '' }} >
            {{$c->name}}
          </option>
          @endforeach
        </select>
         <small class="txt-desc">({{ __('staticwords.PleaseChooseState') }}) </small>
        </div>

        <div class="gap col-md-4">
            
            <label class="font-weight-bold" class="control-label">{{ __('staticwords.City') }}: <span class="required">*</span></label>
        <select disabled name="city_id" id="city_id" class="form-control col-md-7 col-xs-12">
          <option>{{ __('staticwords.PleaseChoose') }}</option>
           @foreach($citys as $c)
            <option value="{{$c->id}}" {{ $c->id == $user->city_id ? 'selected' : '' }}>
              {{$c->name}}
            </option>
          @endforeach
        </select>
        <small class="text-desc">({{ __('staticwords.PleaseChooseCity') }})</small>
        </div>

        <div id="save_btn3" class="display-none margin-top-35 col-md-3">
          <button type="submit" class="btn btn-md btn-primary">
            {{ __('staticwords.Save') }}
          </button>
        </div>

                      </div>

                    </div>

                   

                    <div class="col-md-12">
                      <hr>
                      <div class="row">
                        <div class="col-md-6">
                          <h5>{{ __('staticwords.UserImage') }}</h5>
                        </div>
                        <div class="col-md-6">
                           <p id="edit5" class="cursor-pointer text-primary"><b>{{ __('staticwords.Edit') }}</b></p>
        <p id="cancel5" class="display-none cursor-pointer text-primary"><b>{{ __('staticwords.Cancel') }}</b></p>
                        </div>

                          <div class="col-md-7">
                     <label class="font-weight-bold" class="control-label" for="first-name">
                      {{ __('staticwords.Image') }}:
                    </label>
                <input disabled type="file" id="user_img" name="image" class="form-control">
                <small class="txt-desc">({{ __('staticwords.PleaseChooseImage') }}) </small>
                </div>

                <div id="save_btn5" class="display-none margin-top-22 col-md-4">
          <button type="submit" class="btn btn-md btn-primary">{{ __('staticwords.Save') }}</button>
        </div>
                      </div>
                    </div>
                  </div>


   
    
                </form>
                <hr>
			     <?php $faqs = App\Faq::paginate(10); ?>
            <h3>{{ __('staticwords.faqs') }}</h3>
            <hr>
			 @foreach($faqs as $faq)
  			<h6>{{$faq->que}}?</h6>
  			<p>{{$faq->ans}}.</p>
  			<hr>
  			@endforeach
			         {{$faqs->links()}}
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
                <input minlength="8" required="" id="password" min="6" max="255" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" name="password" />
              
               <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            
             @error('password')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         
          
          </div>

          
          
          <div class="form-group eyeCy">
           
              
                <label class="font-weight-bold" for="confirm">{{ __('staticwords.ConfirmPassword') }}:</label>
          <input minlength="8" required="" id="confirm_password" type="password" class="form-control" placeholder="Re-enter password for confirmation" name="password_confirmation"/>
          
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

@endsection