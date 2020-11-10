@extends("front/layout.master")
          @php
              $user = Auth::user();
              $sellerac = App\Store::where('user_id','=', $user->id)->first();
          @endphp
@section('title',__('MyFailedTransactions').' | ')
@section("body")

<div class="container-fluid">

    <div class="row">
        <div class="col-xl-3 col-lg-12 col-sm-12">
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
            
        

 <!-- ===================== full-screen navigation end ======================= -->      
        
  <!-- =========================small screen navigation start ============================ -->
<div class="order-accordion navigation-full-screen">
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h5 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            <div class="user_header">
              <h5 class="user_m">• {{ __('staticwords.UserNavigation') }}</h5>
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

       
        
        

       
         
         <div class="col-xl-9 col-lg-12 col-sm-12">

            <div class="bg-white2">
                <h5 class="user_m2">{{ __('staticwords.MyFailedTranscations') }} ({{ $auth->failedtxn->count() }})</h5>
                <hr>
                <div class="table-responsive">
               	<table class="table table-striped filed-block">
               		<thead>
               			<th>#</th>
               			<th>{{ __('TXN ID') }}</th>
               			<th>{{ __('Time') }}</th>
               		</thead>

               		<tbody>
               			@foreach($failedtranscations as $key=> $ftxn)
      							<tr>
      								<td>{{ $key+1 }}</td>
      								<td><b>{{ $ftxn->txn_id }}</b></td>
      								<td>{{ date('d-m-Y h:i A',strtotime($ftxn->created_at)) }}</td>
      							</tr>
               			@endforeach
               		</tbody>
               	</table>
                </div>
               	<div class="center">
               		{!! $failedtranscations->links() !!}
               	</div>
                
              
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
          <input required="" type="password" class="form-control @error('old_password') is-invalid @enderror" placeholder="{{ __('Enter old password') }}" name="old_password" id="old_password" />
          
          <span toggle="#old_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

          @error('old_password')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>



          <div class="form-group eyeCy">
         

            
               <label class="font-weight-bold" for="password">{{ __('staticwords.EnterPassword') }}:</label>
                <input minlength="8" required="" id="password" min="6" max="255" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('staticwords.EnterPassword') }}" name="password" />
              
               <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            
             @error('password')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         
          
          </div>

          
          
          <div class="form-group eyeCy">
           
              
                <label class="font-weight-bold" for="confirm">{{ __('staticwords.ConfirmPassword') }}:</label>
          <input minlength="8" required="" id="confirm_password" type="password" class="form-control" placeholder="{{ __("Re-enter password for confirmation")}}" name="password_confirmation"/>
          
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
@endsection