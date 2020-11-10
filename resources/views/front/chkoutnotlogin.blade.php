@extends('front.layout.master')

@section('body')
<div class="body-content">
  <div class="container">
	<div class="checkout-box">
		<div class="panel-group checkout-steps" id="accordion">
				<div class="panel panel-default checkout-step-01">

	<!-- panel-heading -->
		<div class="panel-heading">
    	<h4 class="unicase-checkout-title">
	        <a data-toggle="collapse" class="" data-parent="#accordion" href="#collapseOne">
	          <span>1</span>{{ __('Checkout Method') }}
	        </a>
	     </h4>
    </div>
    <!-- panel-heading -->

	<div id="collapseOne" class="panel-collapse collapse in show">

		<!-- panel-body  -->
	    <div class="panel-body">
			<div class="row">		

				<!-- guest-login -->			
				<div class="col-md-6 col-sm-6 guest-login">
					<h4 class="checkout-subtitle">{{ __('Guest or Register Login') }}</h4>
					<p class="text title-tag-line">{{ __('Register with us for future convenience') }}:</p>

					<!-- radio-form  -->
					<form class="register-form" method="post" action="{{url('user/process_to_guest')}}">
						 {{csrf_field()}}
					    <div class="radio radio-checkout-unicase">
					    @if($genrals_settings->guest_login == 1)  
					        <input id="guest" type="radio" name="checkValue" value="guest" checked>  
					        <label class="radio-button guest-check" for="guest">{{ __('Checkout as Guest') }}</label>
					    @endif  
					          <br>
					        <input id="register" type="radio" name="checkValue" value="register">  
					        <label class="radio-button" for="register">{{ __('Register') }}</label>  
					    </div>  

					
					<!-- radio-form  -->

					<h4 class="checkout-subtitle outer-top-vs">{{ __('Register and save time') }}</h4>
					<p class="text title-tag-line">{{ __('Register with us for future convenience') }}:</p>
					
					<ul class="text instruction inner-bottom-30">
						<li class="save-time-reg">- {{ __('Fast and easy check out') }}</li>
						<li>- {{ __('Easy access to your order history and status') }}</li>
					</ul>

					<button type="submit" class="btn-upper btn btn-primary checkout-page-button checkout-continue ">
						{{ __('Continue') }}
					</button>

					</form>
					
				</div>
				
				<!-- guest-login -->

				<!-- already-registered-login -->
				<div class="col-md-6 col-sm-6 already-registered-login">
					<h4 class="checkout-subtitle">{{ __('Already registered?') }}</h4>
					<p class="text title-tag-line">{{ __('Please log in below') }}:</p>
					<form method="POST" class="register-form outer-top-xs" role="form" action="{{ route('ref.cart.login') }}">
                        @csrf
						<div class="form-group">
					    <label class="info-title" for="exampleInputEmail1">{{ __('Email Address') }} <span>*</span></label>
					    <input type="email" class="form-control unicase-form-control text-input" id="exampleInputEmail1"  name="email" placeholder="{{ __('Enter Email') }}" value="{{ old('email') }}">
					    @if ($errors->has('email'))
                                    <span class="invalid-feedback colorred" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
					  </div>
					  <div class="form-group">
					    <label class="info-title" for="exampleInputPassword1">{{ __('Password') }} <span>*</span></label>
					    <input type="password" class="form-control unicase-form-control text-input" placeholder="****" name="password">
					    @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
					    <a href="{{ route('password.request') }}" class="forgot-password">{{ __('Forgot your Password?') }}</a>
					  </div>
					  <button type="submit" class="btn-upper btn btn-primary checkout-page-button">
					  	{{ __('Login') }}
					  </button>
					</form>
				</div>	
				<!-- already-registered-login -->		

			</div>			
		</div>
		<!-- panel-body  -->

	</div><!-- row -->
</div>
		</div>
	</div>
  </div>
</div>
@endsection