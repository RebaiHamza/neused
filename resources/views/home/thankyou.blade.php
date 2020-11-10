@extends("front/layout.master")
@section('title','Order Placed Successfully |')
@section("body")

	<div class="breadcrumb">
  <div class="container-fluid">
    <div class="breadcrumb-inner">
      <ul class="list-inline list-unstyled">
        <li><a href="{{ url('/') }}">{{ __('staticwords.Home') }}</a></li>
        <li class='active'>{{ __('Order Placed') }}</li>
      </ul>
    </div><!-- /.breadcrumb-inner -->
  </div><!-- /.container -->
</div><!-- /.breadcrumb -->

<div class="body-content">

  <div class="container-fluid">
    <div class="row">
      @if(Session::has('success'))

       @php
        Session::forget('success');
        Session::forget('failure');
       @endphp

      @endif

    <div class="col-md-12">
      <div class="card">
      <div class="card-body">
         <div class="thanku-block">
        <h1 class="thanku-heading">{{ __('Order Placed Successfully') }} !</h1>
        <hr>
        <h3 class="thanku-heading-one">{{ __('To Track your order go to My Account') }} ->> <a href="{{ url('/order') }}">{{ __('staticwords.MyOrders') }}</a> {{ __('Tab') }}</h3>
        <hr>
        <div class="continue-shopping">
        <a href="{{ url('/') }}" class="btn btn-primary">{{ __('Continue Shopping') }}</a>
        </div>
      </div>
      </div>
    </div>
    </div>
     
    </div>
  </div>

</div>

@endsection