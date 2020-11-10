<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reset Password | {{ $title }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <link rel="icon" href="{{url('images/genral/'.$fevicon)}}" type="image/gif" sizes="16x16">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
 <link rel="stylesheet" href="{{url('css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{url('admin/vendor/Ionicons/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('admin/dist/css/adminlte.min.css')}}">

  <link rel="stylesheet" href="{{ url('vendor/mckenziearts/laravel-notify/css/notify.css') }}">
</head>
<body class="hold-transition login-page">

<div class="login-box">
   <div class="login-logo">
    <img title="{{ config('app.name') }}" width="100px" src="{{ url('images/genral/'.$front_logo) }}" alt="mainlogo">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">

     @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
    @endif

    <p class="login-box-msg">{{ __('staticwords.Enteremailtocontinue') }}</p>



    <form method="POST" action="{{ route('password.update') }}">
     @csrf
     <input type="hidden" name="token" value="{{ $token }}">
      <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
        <input placeholder="{{ __('staticwords.Enteryouremailaddress') }}" id="email" type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required autofocus>

         @if ($errors->has('email'))
                  <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
         @endif
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @if ($errors->has('email'))
          <span class="invalid-feedback text-danger" role="alert">
              <strong>{{ $errors->first('email') }}</strong>
          </span>
        @endif
      </div>

       <div class="form-group has-feedback">
        <input placeholder="{{ __('staticwords.EnterPassword') }}" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

       <div class="form-group has-feedback">
         <input placeholder="{{ __('staticwords.ConfirmPassword') }}" id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-block bg-navy btn-flat">
                {{ __('staticwords.ResetPassword') }}
          </button>
        </div>
      </div>
    
    </form>
 
    <hr>
    <p align="center">&copy; {{ date('Y').' | '. $Copyright }}</p>
    
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
@include('notify::messages')
<script src="{{ url('vendor/mckenziearts/laravel-notify/js/notify.js') }}"></script>
<!-- jQuery 3 -->
<script src="{{ url('js/jquery.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{url('js/bootstrap.min.js')}}"></script>
</body>
</html>
