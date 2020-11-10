<!doctype html>
<html lang="{{ Session::get('changed_language') }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="Description" content="{{$seoset->metadata_des}}" />
  <meta name="keyword" content="{{ $seoset->metadata_key }}">
  <title>{{ config('app.name') }} | {{ __('Seller') }} {{ __('staticwords.Login') }}</title>

  <!-- Bootstrap core CSS -->
  <link href="{{ url('css/bootstrap.min.css') }}" rel="stylesheet" crossorigin="anonymous">
  <!-- Fontawesome icons -->
  <link rel="stylesheet" href="{{url('css/font-awesome.min.css')}}">
  <link rel="icon" href="{{url('images/genral/'.$fevicon)}}" type="image/png" sizes="32x32">
  <link rel="stylesheet" href="{{ url('vendor/mckenziearts/laravel-notify/css/notify.css') }}">
  <!-- Theme Header Color -->
  <meta name="theme-color" content="#157ED2">
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .authenticate-bg {
      background: url('{{ url('images/authentication-bg.svg') }}');
      background-size: contain;
      background-position: center;
      min-height: 100vh;
    }
  </style>
  <!-- Custom styles for this template -->
  <link href="{{ url('css/floating-labels.min.css') }}" rel="stylesheet">
</head>

<body class="authenticate-bg">
  <form class="form-signin" action="{{ route('seller.login.do') }}" method="post">
    @csrf
    <div class="text-center mb-4">
      @if($image = @file_get_contents('images/icons/icon96x96.png'))
      <img class="mb-4" src="{{url('images/icons/icon96x96.png')}}" alt="Icon" />
      @else
      <img class="mb-4" src="{{ Avatar::create(config('app.name'))->toBase64()}}" alt="No Image" />
      @endif
      <h1 class="h3 mb-3 font-weight-normal">{{ __('Seller Login') }}</h1>
    </div>

    <div class="form-label-group">
      <input type="email" value="{{ old('email') }}" id="inputEmail"
        class="@error('email') is-invalid @enderror form-control" placeholder="Email address" required autofocus
        name="email">
      <label for="inputEmail">{{ __('staticwords.Email') }}</label>
      @error('email')
      <span class="invalid-feedback text-danger" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <div class="form-label-group">
      <input type="password" id="inputPassword" class="form-control" placeholder="Password" required name="password">
      <label for="inputPassword">{{ __('staticwords.Password') }}</label>
    </div>

    <div class="checkbox mb-3">
      <label>
        <input name="remember" {{ old('remember') ? 'checked' : '' }} type="checkbox">
        {{ __('staticwords.Rememberme') }}
      </label>
    </div>
    <button type="submit" class="signin btn btn-lg btn-primary btn-block"
      type="submit">{{ __('staticwords.Signin') }}</button>
    <p class="mt-5 mb-3 text-muted text-center">&copy; {{ date('Y')." | All rights reserved | ".config('app.name') }}
    </p>
  </form>
  @include('notify::messages')
</body>
<!-- jQuery 3.5.4 -->
<script src="{{url('js/jquery.min.js')}}"></script>
<!-- Bootstrap JS -->
<script src="{{url('js/bootstrap.bundle.min.js')}}"></script> <!-- bootstrap  js -->
<script src="{{ url('vendor/mckenziearts/laravel-notify/js/notify.js') }}"></script>

<script>
  $("form").on('submit', function () {

    $('.signin').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> {{ __('staticwords.Signin') }}');

  });
</script>

</html>