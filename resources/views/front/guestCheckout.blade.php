@extends("front/layout.master")
@section('title','Guest Checkout |')
@section("body")
    <div class="body-content">
    <div class="container">
        <div class="sign-in-page">

<form class="register-form outer-top-xs" role="form" method="POST" action="{{ route('ref.guest.register') }}">
       @csrf
            <div class="row">
<!-- create a new account -->
<div class="col-md-6 col-sm-6 create-new-account">
    <h4 class="checkout-subtitle">{{ __('Checkout as Guest') }}</h4>
    
        <div class="form-group">
            <label class="info-title" for="exampleInputEmail2">{{ __('staticwords.eaddress') }}<span>*</span></label>
            <input value="{{ old('email') }}" type="email" class="form-control unicase-form-control text-input {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" required autofocus >

            @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="form-group">
            <label class="info-title" for="exampleInputEmail1">{{ __('staticwords.Name') }}<span>*</span></label>
            <input name="name" type="text" value="{{ old('name') }}" class="form-control unicase-form-control text-input{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" >

            @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
            @endif
        </div>
       
        
</div>  

<div  class="col-md-6 col-sm-6 create-new-account">
    <h4 class="checkout-subtitle">&nbsp;</h4>
    <p class="text title-tag-line"></p>
    
    
              
</div>


<div class="col-md-12">
  <button type="submit" class="btn-upper btn btn-primary checkout-page-button">{{ __('staticwords.Register') }}</button>  
</div>
 
        </div>
    </form>
      </div>
  </div>
</div><!-- /.body-content -->
@endsection
