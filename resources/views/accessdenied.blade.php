<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ __('Warning !') }}</title>
	<link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{url('css/font-awesome.min.css')}}">
</head>
<body>
	<br>
	<div class="container">
		<h3 class="text-danger"><i class="fa fa-warning"></i> {{ __('Warning') }}</h3>
		<hr>
		<h4>{{ __('You can use this project only in single domain for multiple domain please check License standard') }} <a target="_blank" href="https://codecanyon.net/licenses/standard">{{ __('here') }}</a>.</h4>
		<hr>
		<div class="text-muted text-center">&copy; {{ date('Y') }} | {{ __('All rights reserved') }} | {{ config('app.name') }}</div>
	</div>
</body>
</html>