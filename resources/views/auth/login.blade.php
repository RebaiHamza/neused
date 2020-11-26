@extends("front/layout.master")
@section('title',__('staticwords.Login'))
@section('body')
@php
    require_once(base_path().'/app/Http/Controllers/price.php');
@endphp
    <div class="body-content">
    <div class="container-fluid">
        <div class="sign-in-page">
            <div class="row">

               
<div id="aniBox" class="col-md-6 col-sm-12 sign-in">
    <h4 class="">{{__('staticwords.Signin')}}</h4>
    <p class="">{{__('staticwords.LoginWelcome')}}</p>
    <div class="social-sign-in outer-top-xs">
         @if($config->fb_login_enable=='1')
            <a href="{{url('login/facebook')}}" title="{{__('staticwords.SignInwithFacebook')}}" class="facebook-sign-in"><i class="fa fa-facebook"></i> {{__('staticwords.SignInwithFacebook')}}</a>
        @endif
        @if($config->twitter_login_enable == '1')
                <a title="Sign in with Twitter" href="{{url('login/twitter')}}" style="background-color: #55acee; margin-right:10px" class="twitter-sign-in"><i class="fa fa-twitter"></i>Sign in with Twitter</a>
        @endif
        @if($config->google_login_enable=='1')
            <a title="{{__('staticwords.SignInwithGoogle')}}" href="{{url('login/google')}}" class="twitter-sign-in"><i class="fa fa-google"></i> {{__('staticwords.SignInwithGoogle')}}</a>
        @endif
    </div>
    <form id="loginform" method="POST" class="register-form outer-top-xs" role="form" action="{{ route('normal.login') }}">
                        @csrf

        <div class="form-group">
            <label class="info-title" for="exampleInputEmail1">{{ __('E-Mail Address') }} <span>*</span></label>
            <input type="email" name="email" class="form-control unicase-form-control text-input {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" value="{{ old('email') }}" required autofocus>

             @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="form-group">
            <label class="info-title" for="exampleInputPassword1">{{ __('Password') }} <span>*</span></label>
            <input type="password" name="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }} form-control unicase-form-control text-input" id="password" >

             @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
             @endif
        </div>
        <div class="radio outer-xs form-check">
            <label>
                <input type="radio" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>{{__('staticwords.Rememberme')}}
            </label>
            <a href="{{ route('password.request') }}" class="forgot-password pull-right">{{__('staticwords.ForgotYourPassword')}}</a>
            

        </div>
    <button type="submit" class="signin btn-upper btn btn-primary checkout-page-button">{{__('staticwords.Login')}}</button>
    <a href="{{ route('register') }}" class="signin btn-upper btn btn-primary checkout-page-button">{{__('staticwords.NewUserRegisterNow')}}</a>
    </form>                 
</div>
<!-- Sign-in -->

<div class="col-md-6">
    <canvas id="canvas" class="canvaslogin"></canvas>
</div>
</div><!-- /.row -->
        </div>    
    </div><!-- /.container -->
</div><!-- /.body-content -->
@endsection
@section('head-script')
<script src="{{ url('admin/plugins/flare/Flare.min.js') }}"></script>
<script src="{{ url('admin/plugins/flare/gl-matrix.js') }}"></script>
<script src="{{ url('admin/plugins/flare/canvaskit.js') }}"></script>
<script src="{{url('front/vendor/js/Event.js')}}"></script>
<script src="{{ url('front/vendor/js/loginanimation.js') }}"></script>
<script>var baseUrl = "<?= url('/') ?>";</script>
<script src="{{ url('js/login.js') }}"></script>
@endsection
@section('script')
<script>
    $("#loginform").on('submit', function () {
  
      $('.signin').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> {{ __('staticwords.Login') }}');
  
    });
</script>  
@endsection