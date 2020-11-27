@extends("front/layout.master")
@section('title','Register |')
@section("body")
@php
require_once(base_path().'/app/Http/Controllers/price.php');
@endphp
@section('stylesheet')
<style>
    .select2-selection__rendered {
        line-height: 38px !important;
    }

    .select2-container .select2-selection--single {
        height: 38px !important;
    }

    .select2-selection__arrow {
        height: 34px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        text-align: center;
    }
</style>
@endsection
<div class="body-content">
    <div class="container">
        <div class="sign-in-page">
            <h4 class="checkout-subtitle">{{ __('Create seller account') }}</h4>
            <form class="register-form outer-top-xs" role="form" method="POST"
                action="{{ route('seller.register.do') }}" enctype="multipart/form-data">
                @csrf
                <!-- create a new account -->

                <div class="row">

                    <div class="offset-md-3 col-md-6">
                        <h5 class="info-text">Store information</h5>
                        <hr>
                        <div class="form-group">
                            <label class="info-title"
                                for="exampleInputEmail1">{{ __('staticwords.Name') }}<span>*</span></label>
                            <input name="name" type="text" value="{{ old('name') }}"
                                class="form-control unicase-form-control text-input{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                id="name" autofocus required> 
                                @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span> @endif
                        </div>
                    </div>

                    <div class="offset-md-3 col-md-6">
                        <div class="form-group">
                            <label class="info-title">{{ __('staticwords.StoreName') }} <small
                                    class="required">*</small></label>
                            <input id="storename" type="text" name="storename" class="form-control unicase-form-control" required>
                        </div>
                    </div>

                    <div class="offset-md-3 col-md-6">
                        <div class="form-group">
                            <label for="store_logo">{{ __('Store Logo') }}</label>
                            <input type="file" name="store_logo" id="wizard-picture" class="form-control unicase-form-control" required>
                        </div>

                        <span class="required">{{$errors->first('store_logo')}}</span>
                    </div>

                    <div class="offset-md-3 col-md-6">
                        <div class="form-group">
                            <label for="register">{{ __('Trade Register') }}
                                <small class="required">*</small>
                            </label>
                            <input type="file" name="register" class="form-control unicase-form-control" required> 
                        </div>
                    </div>

                    <div class="offset-md-3 col-md-6">
                        <div class="form-group">
                            <label for="patent">{{ __('Store Patent') }}
                                <small class="required">*</small>
                            </label>
                            <input type="file" name="patent" class="form-control unicase-form-control" required>
                        </div>
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title" for="exampleInputEmail2">{{ __('staticwords.eaddress') }}
                            <span>*</span></label>
                        <input value="{{ old('email') }}" type="email"
                            class="form-control unicase-form-control text-input {{ $errors->has('email') ? ' is-invalid' : '' }}"
                            id="email" name="email" required>
                        @if ($errors->has('email'))
                        <span class="invalid-feedback"
                            role="alert"><strong>{{ $errors->first('email') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title" for="password">{{ __('staticwords.EnterPassword') }}
                            <span>*</span></label>
                        <input type="password" id="password"
                            class="form-control unicase-form-control text-input{{ $errors->has('password') ? ' is-invalid' : '' }}"
                            name="password" required> @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span> @endif
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title" for="exampleInputEmail1">{{ __('staticwords.ConfirmPassword') }}
                            <span>*</span></label>
                        <input type="password" name="password_confirmation" id="password-confirm"
                            class="form-control unicase-form-control text-input" required />
                    </div>
                </div>


                @if($genrals_settings->otp_enable == 0)
                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title" for="exampleInputEmail1">{{ __('staticwords.MobileNo') }}
                            <span>*</span></label>
                        <input pattern="[0-9]+" title="{{ __('Please enter valid mobile no') }}."
                            value="{{ old('mobile') }}" type="text"
                            class="form-control unicase-form-control text-input{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                            name="mobile" id="phone" required>
                        @if ($errors->has('mobile'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('mobile') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                @endif

                @if($genrals_settings->otp_enable == 1)
                <div class="offset-md-3 col-md-6">
                    <label class="info-title" for="exampleInputEmail1">{{ __('staticwords.MobileNo') }}
                        <span>*</span></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <select class="form-control select2" name="phonecode" id="phonecode">

                                @foreach(App\Allcountry::select('phonecode','nicename')->get() as $code)
                                <optgroup label="{{ $code->nicename }}">
                                    <option {{ old('phonecode') == $code->phonecode ? "selected" : "" }}
                                        value="{{ $code->phonecode }}">{{ $code->phonecode }}</option>
                                </optgroup>
                                @endforeach

                            </select>
                        </div>
                        <input pattern="[0-9]+" title="{{ __('Please enter valid mobile no') }}."
                            value="{{ old('mobile') }}" type="text"
                            class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile"
                            id="phone" required>
                        @error('mobile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <p class="text-danger error"></p>
                    <p class="text-success success"></p>
                </div>
                @endif

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title">{{ __('staticwords.Country') }} <small
                                class="required">*</small></label>
                        <select name="country_id" class="form-control unicase-form-control" id="country_id">
                            <option value="0">{{ __('staticwords.PleaseChooseCountry') }}</option> @foreach($country as
                            $c)
                            <?php
                                        $iso3 = $c->country;
    
                                        $country_name = DB::table('allcountry')->
                                        where('iso3',$iso3)->first();
                                      ?>
                            <option value="{{$country_name->id}}" /> {{$country_name->nicename}} </option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title">{{ __('staticwords.State') }} <small
                                class="required">*</small></label>
                        <select name="state_id" class="form-control unicase-form-control" id="upload_id">
                            <option value="0">{{ __('staticwords.PleaseChooseState') }}</option>
                        </select>
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title">{{ __('staticwords.City') }} <small class="required">*</small></label>
                        <select name="city_id" id="city_id" class="form-control unicase-form-control">
                            <option value="0">{{ __('staticwords.PleaseChooseCity') }}</option>
                        </select>
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title">{{ __('staticwords.StoreAddress') }} <small
                                class="required">*</small></label>
                        <input type="text" id="first-name" name="address" value="{{old('address')}}"
                            class="form-control unicase-form-control" required> <span
                            class="required">{{$errors->first('address')}}</span>
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title">{{ __('staticwords.Pincode') }}
                            ({{ __('staticwords.optional') }}) </label>
                        <input pattern="[0-9]+" title="Invalid pincode" type="text" id="first-name" name="pin_code"
                            value="{{old('pin_code')}}" class="form-control unicase-form-control" required/>
                    </div>
                </div>



                <div class="offset-md-3 col-md-6">
                    <hr>
                    <h5 class="info-text">Bank Information</h5>

                    <div class="form-group">
                        <label class="info-title"> {{ __('staticwords.AccountNumber') }} <small
                                class="required">*</small></label>
                        <input required pattern="[0-9]+" title="Invalid account no." type="text" id="first-name"
                            name="account" value="{{old('account')}}"
                            placeholder="{{ __('staticwords.PleaseEnterAccountNumber') }}" class="form-control"> <span
                            class="required">{{$errors->first('account')}}</span>
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title"> {{ __('staticwords.AccountName') }}: <small
                                class="required">*</small></label>
                        <input required type="text" id="first-name" name="account_name" value="{{old('account_name')}}"
                            placeholder="{{ __('staticwords.PleaseEnterAccountName') }}" class="form-control"> <span
                            class="required">{{$errors->first('bank_name')}}</span>
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title"> {{ __('staticwords.BankName') }}: <small
                                class="required">*</small></label>
                        <input required type="text" id="first-name" name="bank_name" value="{{old('bank_name')}}"
                            placeholder="{{ __('staticwords.PleaseEnterBankName') }}" class="form-control"> <span
                            class="required">{{$errors->first('bank_name')}}</span>
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title"> {{ __('IFSC Code') }}: <small class="required">*</small></label>
                        <input required="" type="text" name="ifsc" value="{{old('ifsc')}}"
                            placeholder="{{ __('staticwords.PleaseEnterIFSCCode') }}" class="form-control "> <span
                            class="required">{{$errors->first('ifsc')}}</span>
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title">{{ __('staticwords.BranchAddress') }}: <small
                                class="required">*</small></label>
                        <input required="" type="text" id="first-name" name="branch"
                            placeholder="Please Enter Branch Address" value="{{old('branch')}}" class="form-control">
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="info-title">{{ __('staticwords.PreferredPaymentMethod') }}: <small
                                class="required">*</small></label>
                        <select name="preferd" class="form-control" id="prefrd">
                            <option value="0">{{ __('staticwords.PleaseChoosePreferredPaymentmethod') }}</option>
                            <option value="paypal">{{ __('Paypal') }}</option>
                            <option value="paytem">{{ __('QPay') }}</option>
                        </select> <span class="required">{{$errors->first('prefard')}}</span>
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <hr>
                    <h5 class="info-text">{{ __('staticwords.ChooseCategoryyouwishtosell') }}</h5>
                    <div class="row form-group">
                        @php
                        $allcat = App\Category::where('status','1')->get();
                        @endphp
                        @foreach(App\Category::where('status','1')->get(); as $cat)
                        <div class="col-md-4">
                            <div class="card-body">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" value="{{$cat->title}}" name="vehicle[]"
                                        class="custom-control-input" id="cat{{ $cat->id }}">
                                    <label class="custom-control-label" for="cat{{ $cat->id }}">{{$cat->title}}</label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">{{ __('staticwords.Other') }}:</label>
                    <div class="custom-control custom-checkbox">
                      <input {{ count($allcat) < 1 ? "required" : "" }} type="checkbox" class="custom-control-input"
                        id="Other">
                      <label class="custom-control-label"
                        for="Other">{{ __('staticwords.MyCategoryisnotlistedhere') }}</label>
                    </div>
                    </div>
                </div>

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold"
                        for="">{{ __('staticwords.Doyousellproductatotherwebsitealso') }}?</label>
                      <div class="row">
                        <div class="col-md-2 col-xs-4">
                          <div class="custom-control custom-radio">
                            <input required type="radio" id="yes" value="Yes" name="name1" class="custom-control-input">
                            <label class="custom-control-label" for="yes">{{ __('Yes') }}</label>
                          </div>
                        </div>
                        <div class="m-55 col-md-2 col-xs-4">
                          <div class="custom-control custom-radio">
                            <input required type="radio" id="No" value="No" name="name1" class="custom-control-input">
                            <label class="custom-control-label" for="No">
                              {{ __('No') }}
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>

                <div class="offset-md-3 col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold" for="otherStores">If yes, what's the name of the website?</label>
                    
                          <div id="checkOtherStore">
                            <input type="text" id="otherStores" name="otherStores" class="form-control">
                          </div>
                        
                    </div>
                </div>

                @if($genrals_settings->captcha_enable == 1)

                <div class="offset-md-3 col-md-6">
                    <div class="form-group">
                        {!! no_captcha()->display() !!}
                    </div>

                    @error('g-recaptcha-response')
                    <p class="text-danger"><b>{{ $message }}</b></p>
                    @enderror
                </div>

                @endif



                <div class="offset-md-3 col-md-6">
                    <button type="submit"
                        class="register btn-upper btn btn-primary checkout-page-button">{{ __('staticwords.Register') }}</button>
                    <a class="float-right" href="{{ route('login') }}">{{ __('Already have account login here?') }}</a>
                </div>


        </div>

        </form>

    </div>
</div>
</div>
<!-- /.body-content -->
@endsection
@section('script')
<script>
    "use strict";

    var baseurl = "<?= url('/') ?>";
    $(document).ready(function () {
        $('.select2').select2({
            height: '100px'
        });
    });


</script>
<script src="{{ url('js/ajaxlocationlist.js') }}"></script>
@if($genrals_settings->captcha_enable == 1)
{!! no_captcha()->script() !!}
@endif

<script>
    $(".register-form").on('submit', function () {

        $('.register').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> {{ __('staticwords.Register') }}');

    });
</script>

@endsection