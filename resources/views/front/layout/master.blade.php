<!DOCTYPE html>
<!--
**********************************************************************************************************
    Copyright (c) 2020.
**********************************************************************************************************  -->
<!--
  Template Name: emart - Laravel Multi-Vendor Ecommerce Advanced CMS
  Version: 1.3.0
  Author: Media City
-->

<html lang="{{ Session::get('changed_language') }}">

<head>
  <!-- jQuery 3.5.4 -->
  <script src="{{url('js/jquery.min.js')}}"></script>

  <!-- Meta -->
  @inject('footermenus','App\FooterMenu')
  @inject('menus','App\Menu')
  @php


  //Run Cart Realtime updation if price of product change during when product is already in cart
  if(Auth::check() && !empty(Auth::user()->cart)){
  $cart = Auth::user()->cart;
  App\Jobs\CartPriceChange::dispatch($cart);
  }

  //Run Cart Realtime updation if price of product change during when product is already in cart
  if(!empty(session()->get('cart'))){
  $cart = session()->get('cart');
  App\Jobs\GuestCartPriceChange::dispatchNow($cart);
  }

  //Run wallet point expire background process
  if(!empty(Auth::user()->wallet)){
  $historywallet = Auth::user()->wallet->wallethistory;
  $wallet = Auth::user()->wallet;
  App\Jobs\WalletExpire::dispatch($wallet,$historywallet);
  }

  @endphp


  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta name="Description" content="{{$seoset->metadata_des}}" />
  <meta name="keyword" content="{{ $seoset->metadata_key }}">
  <meta name="robots" content="all">
  <meta name="csrf-token" content="{{csrf_token()}}">
  <!-- Theme Header Color -->
  <meta name="theme-color" content="#157ED2">
  <title>@yield('title') {{$title}} - {{ $seoset->metadata_des }} </title>
  <!-- Google Fonts -->
  <link href="//fonts.googleapis.com/css?family=Barlow:200,300,300i,400,400i,500,500i,600,700,800" rel="stylesheet">
  <link href='//fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
  <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,600,600italic,700,700italic,800'
    rel='stylesheet' type='text/css'>
  <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
  <!-- END Fonts -->
  @if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
  <!-- PWA Manifest -->
  <link rel="manifest" href="{{url('manifest.json')}}">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="#157ED2">
  <meta name="apple-mobile-web-app-title" content="PWA">
  <link rel="apple-touch-icon" href="{{ url('images/icons/icon-512x512.png') }}">
  @endif
  <!-- Favicon -->
  <link rel="icon" href="{{url('images/genral/'.$fevicon)}}" type="image/png" sizes="16x16">
  <!-- Bootstrap Core CSS -->
  <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
  <!-- Pace Preloader -->
  <link rel="stylesheet" href="{{ url('css/vendor/pace.min.css') }}">
  <!-- Default CSS -->
  <link rel="stylesheet" href="{{url('front/css/main.min.css')}}">
  <link rel="stylesheet" href="{{url('front/css/blue.css')}}">
  <!-- Additional CSS -->
  <link rel="stylesheet" href="{{ url('css/vendor/drift-basic.min.css') }}">
  <link rel="stylesheet" href="{{url('front/vendor/css/owl.carousel.min.css')}}">
  <link rel="stylesheet" href="{{url('front/vendor/css/owl.transitions.min.css')}}">
  <link rel="stylesheet" href="{{url('css/vendor/animate.min.css')}}">
  <link rel="stylesheet" href="{{url('front/vendor/css/rateit.min.css')}}">
  <link rel="stylesheet" href="{{url('front/vendor/css/bootstrap-select.min.css')}}">
  <link rel="stylesheet" href="{{ url('css/vendor/select2.min.css') }}" />
  <link rel="stylesheet" href="{{ url('css/vendor/jquery-ui.min.css') }}">
  <link rel="stylesheet" href="{{ url('css/vendor/card.css') }}" compile>
  <link rel="stylesheet" href="{{ url('css/user-style.min.css') }}" compile>
  <!-- Fontawesome icons -->
  <link rel="stylesheet" href="{{url('css/font-awesome.min.css')}}">
  <script src="{{ url('front/vendor/js/sweetalert.min.js') }}"></script>
  <!-- Custom Css -->
  @if(file_exists('css/custom-style.css'))
  <link rel="stylesheet" type="text/css" href="{{url('css/custom-style.css')}}">
  @endif
  <!-- End Custom css -->
  <!-- Laravel notify css -->
  <link rel="stylesheet" href="{{ url('vendor/mckenziearts/laravel-notify/css/notify.css') }}">
  <!-- Default front head script -->
  <script>
    var baseUrl = "<?= url('/') ?>";
  </script>
  <script src="{{ url('js/front-head.min.js') }}"></script>
  <!-- Custom script head -->
  @yield('head-script')
  <!-- Custom style head -->
  @yield('stylesheet')
</head>


<body class="cnt-home">
  @include('sweet::alert')
  {{-- <!-- preloader -->
  <div class="display-none payment-verify-preloader">
    <div class="payment">
      <div class="payment-message">
        <i class="fa fa-spinner fa-pulse fa-fw"></i> <span class="sr-only">{{ __('Loading') }}...</span>
        {{ __('staticwords.verifyPayment') }}
        <br>
        <div class="jsonstatus">

        </div>
      </div>
    </div>
  </div> --}}


  {{-- <!-- preloader -->
  <div class="front-preloader">
    <div class="status">
      <div class="status-message">
        <img height="100px" src="{{url('images/preloader/preloader.png')}}" alt="preloader">
      </div>
    </div>
  </div> --}}
  <!-- form submit preloader -->
  {{-- <div class="display-none preL">
    <div class="display-none preloader3"></div>
  </div> --}}

  <!-- ============================================== HEADER ============================================== -->
  <header class="header-style-1">
    <!-- ============================================== TOP MENU ============================================== -->
    <div class="top-bar animate-dropdown top-main-block-one">
      <div class="container-fluid">
        <div class="header-top-inner">
          <div class="cnt-account">
            <div class="display-none-block">
              <ul class="list-unstyled">

                @if(Auth::check())

                <li class="dropdown notifications-menu">
                  <a title="{{ __('staticwords.notification') }}" href="#" class="dropdown-toggle"
                    data-toggle="dropdown">
                    <i class="fa fa-bell"></i>
                    @if(auth()->user()->unreadnotifications->count())
                    @if(auth()->user()->unreadnotifications->where('n_type','!=','order_v')->count()>0)
                    <sup id="countNoti" class="bell-badge">
                      {{ auth()->user()->unreadnotifications->where('n_type','!=','order_v')->count() }}
                    </sup>
                    @endif
                    @endif
                  </a>



                  <ul id="dropdown" class="z-index99 dropdown-menu">
                    <li class="notiheadergrey header">
                      @if(auth()->user()->unreadnotifications->where('n_type','!=','order_v')->count())
                      {{ __('staticwords.Youhave') }}
                      <b>{{ auth()->user()->unreadnotifications->where('n_type','!=','order_v')->count() }}</b>
                      {{ __('staticwords.notification') }} !
                      <a class="color111 float-right"
                        href="{{ route('clearall') }}">{{ __('staticwords.MarkallasRead') }}</a>
                      @else
                      <span class="text-center">{{ __('staticwords.NoNotifications') }}</span>
                      @endif
                    </li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu notification-menu">
                      @if(auth()->user()->unreadnotifications->count())

                      @foreach(auth()->user()->unreadNotifications as $notification)
                      @if($notification->n_type != "order_v")
                      <li class="notiheaderlightgrey hey1" id="{{ $notification->id }}"
                        onclick="markread('{{ $notification->id }}')">
                        <p></p>
                        <small class="padding5P float-right"><i class="fa fa-clock-o" aria-hidden="true"></i>
                          {{ date('jS M y',strtotime($notification->created_at)) }}</small>
                        <a class="font-weight600 color111" @if($notification->n_type == "order")
                          @foreach(App\Order::where('order_id','=',$notification->url)->get() as $order)
                          href="{{ url('view/order/'.$notification->url) }}"
                          @endforeach
                          @else
                          href="{{ url('mytickets') }}"
                          @endif

                          onclick="markread('{{ $notification->id }}')"><i class="fa fa-circle-o"
                            aria-hidden="true"></i>
                          {{ $notification->data['data'] }}</a>

                        <p></p>

                      </li>

                      @endif

                      @endforeach
                      @endif
                    </ul>
                  </ul>
                </li>

                @if(Auth::user()->role_id == "a")
                <li class="first"><a target="_blank" title="GO to Admin Panel" href="{{route('admin.main')}}"
                    title="Admin">Admin</a></li>
                @elseif(Auth::user()->role_id == 'v')
                @if(isset(Auth::user()->store))
                <li class="first"><a target="_blank" title="{{ __('staticwords.SellerDashboard') }}"
                    href="{{route('seller.dboard')}}" title="Admin">{{ __('staticwords.SellerDashboard') }}</a>
                </li>
                @endif
                @endif
                <li class="myaccount"><a href="{{url('profile')}}"
                    title="My Account"><span>{{ __('staticwords.MyAccount') }}</span></a></li>
                @inject('wish','App\Wishlist')
                @php
                $data = $wish->where('user_id',Auth::user()->id)->get();
                $count = [];

                foreach ($data as $key => $var) {

                if($var->variant->products->status == '1'){
                $count[] = $var->id;
                }

                }

                $wishcount = count($count);
                @endphp
                <li class="wishlist"><a href="{{url('wishlist')}}"
                    title="Wishlist"><span>{{ __('staticwords.Wishlist') }}(<span
                        id="wishcount">{{$wishcount}}</span>)</a></li>
                @endif
                @if(Auth::user()->role_id == "u")
                <li class="first"><a target="_blank" title="become a vender" href="{{route('seller.register.page')}}"
                    title="vendor">Become a vendor</a></li>
                @endif
                @if(Auth::check())
                <li class="login">

                  <a role="button" onclick="logout()">
                    {{ __('staticwords.Logout') }}
                  </a>

                  <form action="{{ route('logout') }}" method="POST" class="logout-form display-none">
                    {{ csrf_field() }}
                  </form>

                </li>
                @else

                <li class="login animate-dropdown-one">
                  <a href="{{url('login')}}" title="Login">
                    <span>
                      {{ __('staticwords.Login') }}
                    </span>
                  </a>
                </li>
                <li class="myaccount"><a href="{{url('register')}}" title="Register"><span>
                      {{ __('staticwords.Register') }}
                    </span></a></li>
                @endif
                <li><a title="Your Comparison list" href="{{ route('compare.list') }}">
                    {{ __('staticwords.Compare') }}
                    @if(Session::has('comparison'))
                    ({{ count(Session::get('comparison')) }})
                    @else
                    (0)
                    @endif
                  </a></li>
                @auth
                <li class="check"><a data-toggle="modal" href="#feed"
                    title="Feedback"><span>{{ __('staticwords.Feedback') }}</span></a></li>

                <li><a href="{{ route('hdesk') }}" title="Help Desk & Support">{{ __('staticwords.hpd') }}</a></li>

                <!-- Feedback Modal -->
                <div data-backdrop="static" data-keyboard="false" class="z-index99 modal fade" id="feed" tabindex="-1"
                  role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="feed-head modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h5 class="modal-title" id="myModalLabel"><i class="fa fa-envelope-o" aria-hidden="true"></i>
                          {{ __('staticwords.FeedBackUs') }} </h5>
                      </div>
                      <div class="modal-body">
                        <div class="info-feed alert bg-yellow">
                          <i class="fa fa-info-circle"></i>&nbsp;{{ __('staticwords.feedline') }}
                        </div>
                        <form action="{{ route('send.feedback') }}" method="POST">
                          {{ csrf_field() }}
                          <div class="form-group">
                            <label class="font-weight-bold" for="">{{ __('staticwords.Name') }}: <span
                                class="required">*</span></label>
                            <input required="" type="text" name="name" class="form-control" value="{{ $auth->name }}">
                          </div>
                          <div class="form-group">
                            <label class="font-weight-bold" for="">{{ __('staticwords.Email') }}: <span
                                class="required">*</span></label>
                            <input required="" type="email" name="email" class="form-control"
                              value="{{ $auth->email }}">
                          </div>
                          <div class="form-group">
                            <label class="font-weight-bold" for="">{{ __('staticwords.Message') }}: <span
                                class="required">*</span></label>
                            <textarea required name="msg"
                              placeholder="Tell us What You Like about us? or What should we do to more to improve our portal."
                              cols="30" rows="10" class="form-control"></textarea>
                          </div>
                          <div class="rat">
                            <label class="font-weight-bold">&nbsp;{{ __('staticwords.RateUs') }}: <span
                                class="required">*</span></label>
                            <ul id="starRating" data-stars="5">
                            </ul>
                            <input type="hidden" id="" name="rate" value="1" class="getStar">
                          </div>
                          <button type="submit" class="btn btn-primary">
                            {{ __('staticwords.Send') }}
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                @endauth
              </ul>
            </div>
          </div>
          <!-- /.cnt-account -->

          <div class="cnt-block">
            <ul class="list-unstyled list-inline">

              @php
              $auto = App\AutoDetectGeo::first();
              @endphp

              @if($auto->currency_by_country == 1)

              @php
              //if manual currency by country is enable//
              $myip = $_SERVER['REMOTE_ADDR'];
              $ip = geoip()->getLocation($myip);
              $findcountry = App\Allcountry::where('iso',$ip->iso_code)->first();
              $location = App\Location::all();
              $countryArray = array();
              $manualcurrency = array();

              foreach ($location as $value) {
              $s = explode(',', $value->country_id);

              foreach ($s as $a) {

              if ($a == $findcountry->id) {
              array_push($countryArray, $value);
              }

              }

              }

              foreach ($countryArray as $cid) {
              $c = App\multiCurrency::where('id',$cid->multi_currency)->first();
              array_push($manualcurrency, $c);
              }


              @endphp

              @endif





              <select name="currency" onchange="val()" id="currency">

                @if($auto->currency_by_country == 1)
                @if(!empty($manualcurrency))
                @foreach($manualcurrency as $currency)

                @if(isset($currency->currency))

                <option {{ Session::get('currency')['mainid'] == $currency->currency->id ? "selected" : "" }}
                  value="{{ $currency->currency->id }}">{{ $currency->currency->code }}
                </option>

                @endif

                @endforeach
                @else
                <option value="{{ $defCurrency->currency->id }}">{{ $defCurrency->currency->code }}</option>
                @endif
                @else

                @foreach(App\multiCurrency::all() as $currency)
                <option {{ Session::get('currency')['mainid'] == $currency->currency->id ? "selected" : "" }}
                  value="{{ $currency->currency->id }}">{{ $currency->currency->code }}
                </option>
                @endforeach

                @endif

              </select>

              <select class="changed_language" name="" id="changed_lng">
                @foreach(\DB::table('locales')->where('status','=',1)->get() as $lang)
                <option {{ Session::get('changed_language') == $lang->lang_code ? "selected" : ""}}
                  value="{{ $lang->lang_code }}">{{ $lang->name }}</option>
                @endforeach
              </select>
            </ul>
            <!-- /.list-unstyled -->
          </div>
          <!-- /.cnt-cart -->
          <div class="clearfix"></div>
        </div>
        <!-- /.header-top-inner -->
      </div>
      <!-- /.container -->
    </div>
    <!-- /.header-top -->
    <!-- ============================================== TOP MENU : END ============================================== -->
    <div class="main-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-6  col-md-2 col-sm-2 col-lg-2 logo-holder">
            <!-- ============ LOGO ========================================= -->
            <div class="logo"> <a href="{{url('/')}}" title="{{$title}}"> <img height="50px"
                  src="{{url('images/genral/'.$front_logo)}}" alt="logo"> </a> </div>
            <!-- /.logo -->
            <!--=================== LOGO : END ================= -->
          </div>
          <!-- /.logo-holder -->

          <div class="col-lg-7 col-md-7 col-sm-7 col-12 top-search-holder">
            <!-- ====================== SEARCH AREA ======================== -->
            <div class="search-area">

              <form method="get" enctype="multipart/form-data" action="{{url('search/')}}">

                <div class="control-group search-cat-box">

                  <div class="input-group">
                    <span class="input-group-btn">
                      <select id="searchDropMenu" class="" name="cat">
                        <option value="all">{{ __('All Categories') }}</option>
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                        @foreach(App\Category::orderBy('id','desc')->where('status','1')->get(); as $cat)
                        <option value="{{$cat->id}}">{{$cat->title}}</option>
                        @endforeach
                      </select>
                    </span>
                    <input required="" class="search-field" value="" placeholder="{{ __('staticwords.search') }}"
                      name="keyword">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="submit">
                        <i class="fa fa-search"></i>
                      </button>
                    </span>
                  </div>
                  <!-- <button class="search-button"></button> -->
                </div>

              </form>

            </div>
            <!-- ============================= SEARCH AREA : END ============================ -->
          </div>


          <!-- /.top-search-holder -->
          <div class="col-lg-3 col-md-3 col-sm-3 col-0 animate-dropdown top-cart-row">

            <!-- ==================== SHOPPING CART DROPDOWN ============================================================= -->
            <div class="dropdown dropdown-cart dropdown-cart-one">
              <a href="#" class="dropdown-toggle lnk-cart" data-toggle="dropdown" type="button" id="dropdownMenu1"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="items-cart-inner">
                  <div class="basket">
                    <div class="basket-item-count">
                      <span class="count">

                        @if(!empty($auth)) {{App\Cart::where('user_id',Auth::user()->id)->count()}}
                        @else
                        @php
                        $c = array();
                        $c = Session::get('cart');
                        if(!empty($c)){
                        $c = array_filter($c);
                        }else{
                        $c = [];
                        }

                        @endphp
                        {{ count($c) }}
                        @endif
                      </span></div>
                    <div class="total-price-basket">
                      <span class="lbl">{{ __('staticwords.ShoppingCart') }}</span>
                      <?php
                          $total = 0;
                          $cart_total = Session::get('cart');
                          if(!empty($auth)){
                            $carts = App\Cart::where('user_id',$auth->id)->get();
                            foreach($carts as $key=>$val){

                                if($val->semi_total != null && $val->semi_total != 0 && $val->semi_total != ''){
                                    $price = $val->semi_total;
                                }else{
                                    $price = $val->price_total;
                                }
                                
                                $total =  sprintf("%.2f",$total+$price);
                            } 
                          }
                          else{
                          if(!empty($cart_total)){
                            $cart = Session('cart');
                            
                            if(!empty($cart)){
                            foreach($cart as $key => $val){
                               if($val['varofferprice'] != 0){
                              $price = $val['qty']*$val['varofferprice'];
                            }
                           else{
                              
                              $price = $val['qty']*$val['varprice'];
                           
                            }

                            $total =  sprintf("%.2f",$total+$price);
                            
                            } 
                          }
                          }
                        }
      
                            ?>

                      <?php $shipping = 0; ?>
                      @if(!empty($auth))
                      <?php $cart_table = App\Cart::where('user_id',$auth->id)->get();?>
                      @foreach($cart_table as $key=>$cart)


                      @if($cart->product->free_shipping==0)



                      <?php   
                                $free_shipping = App\Shipping::where('id',$cart->product->shipping_id)->first();

                                    if(!empty($free_shipping)){
                                    if($free_shipping->name=="Shipping Price")
                                        {

                                        $weight = App\Shipping::first();
                                        
                                        $pro_weight =  $cart->variant->weight;
                                        if($weight->weight_to_0 >= $pro_weight)
                                        {
                                           if($weight->per_oq_0=='po')
                                          {
                                            $shipping = $shipping+$weight->weight_price_0;
                                          }
                                          else{
                                              $shipping = $shipping+$weight->weight_price_0*$cart->qty;
                                          }
                                        }
                                        elseif($weight->weight_to_1 >= $pro_weight){
                                             if($weight->per_oq_1=='po')
                                          {
                                            $shipping = $shipping+$weight->weight_price_1;
                                          }
                                          else{
                                              $shipping = $shipping+$weight->weight_price_1*$cart->qty;
                                          }
                                        }
                                        elseif($weight->weight_to_2 >= $pro_weight){
                                             if($weight->per_oq_2=='po')
                                          {
                                            $shipping = $shipping+$weight->weight_price_2;
                                          }
                                          else{
                                              $shipping = $shipping+$weight->weight_price_2*$cart->qty;
                                          }
                                        }
                                        elseif($weight->weight_to_3 >= $pro_weight){
                                           if($weight->per_oq_3=='po')
                                          {
                                            $shipping = $shipping+$weight->weight_price_3;
                                          }
                                          else{
                                              $shipping = $shipping+$weight->weight_price_3*$cart->qty;
                                          }
                                        }
                                        else{
                                           if($weight->per_oq_4=='po')
                                          {
                                            $shipping = $shipping+$weight->weight_price_4;
                                          }
                                          else{
                                              $shipping = $shipping+$weight->weight_price_4*$cart->qty;
                                          }
                                            
                                        }
                                          
                                      }else{
                                          
                                          $shipping = $shipping+$free_shipping->price;    

                                      }
                                    }
                                      
                               ?>


                      @endif



                      @endforeach
                      @else

                      <?php 

                              $value = Session::get('cart');

                              if(!empty($value)){  ?>

                      @foreach($value as $key=>$cart)
                      @php
                      $pros = App\Product::where('id','=',$cart['pro_id'])->first();
                      $variant = App\AddSubVariant::withTrashed()->where('id','=',$cart['variantid'])->first();
                      $free_shipping =App\Shipping::where('id',$pros->shipping_id)->first();
                      @endphp
                      @if($pros->free_shipping==0)

                      <?php  

                                if(!empty($free_shipping)){
                                  if($free_shipping->name=="Shipping Price")
                                        {

                                        $weight = App\Shipping::first();
                                        $pro_weight =  $variant->weight;
                                        if($weight->weight_to_0 >= $pro_weight)
                                        {
                                           if($weight->per_oq_0=='po')
                                          {
                                            $shipping = $shipping+$weight->weight_price_0;
                                          }
                                          else{
                                              $shipping = $shipping+$weight->weight_price_0*$cart['qty'];
                                          }
                                        }
                                        elseif($weight->weight_to_1 >= $pro_weight){
                                             if($weight->per_oq_1=='po')
                                          {
                                            $shipping = $shipping+$weight->weight_price_1;
                                          }
                                          else{
                                              $shipping = $shipping+$weight->weight_price_1*$cart['qty'];
                                          }
                                        }
                                        elseif($weight->weight_to_2 >= $pro_weight){
                                             if($weight->per_oq_2=='po')
                                          {
                                            $shipping = $shipping+$weight->weight_price_2;
                                          }
                                          else{
                                              $shipping = $shipping+$weight->weight_price_2*$cart['qty'];
                                          }
                                        }
                                        elseif($weight->weight_to_3 >= $pro_weight){
                                           if($weight->per_oq_3=='po')
                                          {
                                            $shipping = $shipping+$weight->weight_price_3;
                                          }
                                          else{
                                              $shipping = $shipping+$weight->weight_price_3*$cart['qty'];
                                          }
                                        }
                                        else{
                                           if($weight->per_oq_4=='po')
                                          {
                                            $shipping = $shipping+$weight->weight_price_4;
                                          }
                                          else{
                                              $shipping = $shipping+$weight->weight_price_4*$cart['qty'];
                                          }
                                            
                                        }
                                          
                                      }else{
                                          
                                          $shipping = $shipping+$free_shipping->price;    

                                      }
                                }
                                  
                           ?>



                      @endif



                      @endforeach
                      <?php } ?>
                      @endif


                      @if(Session::get('currency')['position']== 'l' || Session::get('currency')['position'] == 'ls')
                      <i class="{{session()->get('currency')['value']}}"></i>
                      @endif
                      @if(Session::get('currency')['position'] == 'ls')
                      &nbsp;
                      @endif
                      <span class="value" id="total_cart">
                        @if(Session::has('coupanapplied'))

                        {{  sprintf("%.2f",(($total-session('coupanapplied')['discount'])*$conversion_rate)) }}

                        @else
                        {{ sprintf("%.2f",($total*$conversion_rate))}}
                        @endif
                      </span>
                      @if(Session::get('currency')['position'] == 'rs')
                      &nbsp;
                      @endif
                      @if(Session::get('currency')['position']== 'r' || Session::get('currency')['position'] == 'rs')
                      <i class="{{session()->get('currency')['value']}}"></i>
                      @endif

                    </div>
                  </div>
                </div>
              </a>

              <div class="square kdrop dropdown-menu" aria-labelledby="dropdownMenuButton">
                @php
                $total = 0;
                if(!empty($auth))
                {
                $cart_table = App\Cart::where('user_id',$auth->id)->get();
                }
                $value = session('item');
                @endphp

                @if(!empty($auth))


                @php
                $oot= array();
                @endphp

                @foreach($cart_table as $row)

                @php
                $stock = App\AddSubVariant::withTrashed()->findorfail($row->variant_id)->stock;
                @endphp

                @if($stock == 0)
                @php
                array_push($oot, 0);
                @endphp
                @endif

                @php


                $orivar = App\AddSubVariant::withTrashed()->where('id','=',$row->variant_id)->first();

                $var_name_count = count($orivar['main_attr_id']);
                unset($name);
                $name = array();
                $var_name;

                $newarr = array();
                for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
                  $var_name[$i]=$orivar['main_attr_value'][$var_id];
                  $name[$i]=App\ProductAttributes::where('id',$var_id)->first();

                  }

                  try{
                  $url =
                  url('details').'/'.$row->pro_id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                  }catch(Exception $e)
                  {
                  $url = url('details').'/'.$row->pro_id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                  }

                  @endphp

                  <div class="cart-row">
                    <div class="row">
                      <div class="col-3 col-xs-3 col-sm-3 col-md-3">

                        <a href="{{url(url('details').'/'.$url)}}" title="{{$row->product->name}}">
                          <img class="img-fluid pro-img2"
                            src="{{url('/variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}" alt=""></a>

                      </div>

                      <div class="col-7 col-xs-7 col-sm-7 col-md-7">
                        <h6 class="name"><a href="{{ url($url) }}"
                            title="{{$row->product->name}}">{{substr(strip_tags($row->product->name), 0, 10)}}{{strlen(strip_tags($row->product->name))>10 ? '...' : ""}}&nbsp;&nbsp;(

                            @php
                            $varcount = count($orivar->main_attr_value);
                            $i=0;
                            @endphp

                            @foreach($orivar->main_attr_value as $key=> $ori)
                            <?php $i++; ?>

                            @php

                            $getvarvalue = App\ProductValues::where('id',$ori)->first();
                            @endphp

                            @if($i < $varcount) @if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 &&
                              $getvarvalue->unit_value != null)
                              @if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name ==
                              "Colour" || $getvarvalue->proattr->attr_name == "color" ||
                              $getvarvalue->proattr->attr_name == "colour")
                              {{ $getvarvalue->values }},
                              @else
                              {{ $getvarvalue->values }}{{ $getvarvalue->unit_value }},
                              @endif
                              @else
                              {{ $getvarvalue->values }},
                              @endif
                              @else
                              @if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 &&
                              $getvarvalue->unit_value != null)
                              @if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name ==
                              "Colour" || $getvarvalue->proattr->attr_name == "color" ||
                              $getvarvalue->proattr->attr_name == "colour")
                              {{ $getvarvalue->values }}
                              @else
                              {{ $getvarvalue->values }}{{ $getvarvalue->unit_value }}
                              @endif
                              @else
                              {{ $getvarvalue->values }}
                              @endif
                              @endif
                              @endforeach
                              )</a>
                        </h6>

                        <div class="price">

                          <i class="{{session()->get('currency')['value']}}"></i>
                          @if($row->ori_offer_price != 0 && $row->ori_offer_price != '')
                          {{  sprintf("%.2f",$row->ori_offer_price*$conversion_rate) }}
                          @else
                          {{  sprintf("%.2f",$row->ori_price*$conversion_rate) }}
                          @endif

                        </div>

                        x <span id="qty{{ $row->id }}">{{ $row->qty }}</span>


                        @if($stock == 0)
                        <small class="margin-left-15 required">
                          {{ __('Currently out of stock') }}
                        </small>
                        @endif
                      </div>

                      <div class="col-2 col-md-2 col-xs-2 col-sm-2">
                        <div class="action"> <a href="{{url('remove_table_cart/'.$row->variant_id)}}"><i
                              class="fa fa-trash"></i></a>
                        </div>
                      </div>
                    </div>
                    <hr />
                  </div>
                  @endforeach
                  @else

                  @if(!empty(Session::get('cart')))
                  @php
                  $oot= array();
                  @endphp
                  @foreach(Session::get('cart') as $a=> $row)


                  @php
                  $stock = App\AddSubVariant::withTrashed()->findorfail($row['variantid'])->stock;
                  @endphp

                  @if($stock == 0)
                  @php
                  array_push($oot, 0);
                  @endphp
                  @endif

                  @php

                  $pro = App\Product::where('id',$row['pro_id'])->first();
                  $orivar = App\AddSubVariant::withTrashed()->where('id','=',$row['variantid'])->first();


                  $var_name_count = count([$orivar['main_attr_id']]);
                  unset($name);
                  $name = array();
                  $var_name;

                  $newarr = array();
                  for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
                    $var_name[$i]=$orivar['main_attr_value'][$var_id];
                    array_push($name,App\ProductAttributes::where('id',$var_id)->first());


                    }


                    try{
                    $url =
                    url('details').'/'.$row['pro_id'].'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                    }catch(Exception $e)
                    {
                    $url = url('details').'/'.$row['pro_id'].'?'.$name[0]['attr_name'].'='.$var_name[0];
                    }

                    @endphp
                    <div class="cart-row">
                      <div class="row">
                        <div class="col-3 col-xs-3 col-sm-3 col-md-3">
                          <a href="{{url($url)}}" title="{{$pro->name}}">
                            <img class="pro-img2 img-fluid"
                              src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                              alt="{{$pro->name}}">
                          </a>

                        </div>

                        <div class="col-7 col-xs-7 col-sm-7 col-md-7">
                         
                          <h6 class="name"><a href="{{ url($url) }}"
                              title="{{$pro->name}}">{{substr(strip_tags($pro->name), 0, 10)}}{{strlen(strip_tags($pro->name))>10 ? '...' : ""}}&nbsp;&nbsp;(

                              @php
                              $varcount = count($orivar->main_attr_value);
                              $i=0;
                              @endphp

                              @foreach($orivar->main_attr_value as $key=> $ori)
                              <?php $i++; ?>

                              @php

                              $getvarvalue = App\ProductValues::where('id',$ori)->first();
                              @endphp

                              @if($i < $varcount) @if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 &&
                                $getvarvalue->unit_value != null)
                                @if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name ==
                                "Colour" || $getvarvalue->proattr->attr_name == "color" ||
                                $getvarvalue->proattr->attr_name == "colour")
                                {{ $getvarvalue->values }},
                                @else
                                {{ $getvarvalue->values }} {{ $getvarvalue->unit_value }},
                                @endif
                                @else
                                {{ $getvarvalue->values }},
                                @endif
                                @else
                                @if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 &&
                                $getvarvalue->unit_value != null)
                                @if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name ==
                                "Colour" || $getvarvalue->proattr->attr_name == "color" ||
                                $getvarvalue->proattr->attr_name == "colour")
                                {{ $getvarvalue->values }}
                                @else
                                {{ $getvarvalue->values }} {{ $getvarvalue->unit_value }}
                                @endif
                                @else
                                {{ $getvarvalue->values }}
                                @endif
                                @endif
                                @endforeach
                                )


                            </a></h6>

                          <div class="price"><i
                              class="{{session()->get('currency')['value']}}"></i>{{$row['varofferprice'] == 0 && $row['varofferprice']=='' ? $row['varprice']*$conversion_rate : $row['varofferprice']*$conversion_rate }}
                          </div>

                          x <span id="sqty{{ $row['variantid'] }}">{{ $row['qty'] }}</span>


                          @if($stock == 0)
                          <small class="margin-left-15 required">{{ __('staticwords.CurrentlyOutofstock') }}</small>
                          @endif
                        </div>

                        <div class="col-2 col-md-2 col-xs-2 col-sm-2">
                          <div class="action"> <a href="{{url('remove_cart/'.$row['variantid'])}}"><i
                                class="fa fa-trash"></i></a>
                          </div>
                        </div>
                      </div>
                      <hr />

                    </div>
                    @endforeach
                    @endif
                    @endif


                    <div class="clearfix"></div>
                    <div class="clearfix cart-total cart-checkout">
                      <div class="float-right"> <span class="text">{{ __('staticwords.Subtotal') }} :</span><span
                          class='price'>
                          @php

                          $total = 0;

                          @endphp
                          @guest
                          @if(Session::has('cart'))

                          @foreach(Session::get('cart') as $row)
                          @if($row['varofferprice'] != 0 && $row['varofferprice'] != NULL)
                          <?php $price = $row['qty']*$row['varofferprice']; ?>
                          @else
                          <?php $price = $row['qty']*$row['varprice']; ?>
                          @endif

                          <?php $total = $total+$price;
                                  
                            ?>

                          @endforeach
                          <i class="{{session()->get('currency')['value']}}"></i><span id="subtotal">
                            @if($total == 0)
                            0
                            @else
                            {{  sprintf("%.2f",$total*$conversion_rate) }}
                            @endif
                          </span>
                          @else
                          <i class="{{session()->get('currency')['value']}}"></i>0
                          @endif
                          @else
                          @php
                          $cart_items = App\Cart::where('user_id',Auth::user()->id)->get();
                          @endphp

                          @if(!empty($cart_items))
                          @foreach($cart_items as $cart)

                          @if($cart['ori_offer_price'] != 0 && $cart['ori_offer_price'] != NULL)
                          <?php $price = $cart['qty']*$cart['ori_offer_price']; ?>
                          @else
                          <?php $price = $cart['qty']*$cart['ori_price']; ?>
                          @endif

                          @php
                          $total = $total+$price;
                          @endphp

                          @endforeach
                          @endif
                          <span id="subtotal">
                            @if($total != 0)
                            @if(!Session::has('coupanapplied'))
                            <i class="{{session()->get('currency')['value']}}"></i>
                            {{  sprintf("%.2f",$total*$conversion_rate) }}
                            @else
                            <i class="{{session()->get('currency')['value']}}"></i>
                            {{  sprintf("%.2f",($total-session('coupanapplied')['discount'])*$conversion_rate) }}
                            @endif
                            @else
                            <i class="{{session()->get('currency')['value']}}"></i>0
                            @endif
                          </span>
                          @endguest

                        </span> </div>
                      <div class="clearfix"></div>

                      @auth
                      <?php  $c = count($cart_table); ?>
                      @else
                      @if(!empty(Session::get('cart')))
                      <?php  $c = count(Session::get('cart'));?>
                      @else
                      <?php $c = 0; ?>
                      @endif
                      @endauth

                      @if(!empty($oot) && in_array(0, $oot) || $c<1) <button disabled="disabled" type="button"
                        class="btn btn-upper btn-primary btn-block m-t-20">
                        {{ __('staticwords.PROCCEDTOCHECKOUT') }}</button>
                        @else
                        <form action="{{url('checkout')}}" method="post" class="cart-checkout">
                          {{ csrf_field() }}
                          <input type="hidden" name="shipping" value="{{ $shipping }}">
                          <button type="submit"
                            class="btn btn-upper btn-primary btn-block m-t-20">{{ __('staticwords.PROCCEDTOCHECKOUT') }}</button>
                        </form>
                        @endif

                        @if(!empty($oot) && in_array(0, $oot) || $c!=0)
                        <a href="{{url('cart')}}" class="btn btn-upper btn-primary btn-block m-t-20"
                          title="{{ __('Go To Cart') }}">{{ __('staticwords.GoToCart') }}</a>
                        @endif

                    </div>

                    @auth
                    {{-- When user logged in empty cart by table--}}
                    @if(count($cart_table)>0)
                    <form action="{{ route('empty.cart') }}" method="POST" class="cart-checkout">
                      @csrf
                      <button type="submit" class="btn btn-upper btn-primary btn-block m-t-20">
                        {{ __('staticwords.EmptyCart') }}
                      </button>
                    </form>

                    @endif

                    @else

                    @if(Session::has('cart'))

                    <?php $token = md5(uniqid(rand(), true)); ?>
                    {{-- When user logged in empty cart by session--}}
                    <form action="{{ route('s.cart',$token) }}" method="POST" class="cart-checkout">
                      @csrf
                      <button type="submit"
                        class="btn btn-upper btn-primary btn-block m-t-20">{{ __('staticwords.EmptyCart') }}</button>
                    </form>

                    @endif

                    @endauth
              </div>

              <!-- /.dropdown-menu-->
            </div>
            @auth
            @if($wallet_system == 1)
            <div class="dropdown dropdown-cart">

              <a title="My Wallet" href="{{ route('user.wallet.show') }}" class="lnk-cart">

                <div class="items-cart-inner">

                  <img style="width: 35px" class="wallet" src="{{ url('images/wallet.png') }}" alt="wallet_icon">

                  <div class="total-price-basket"> <span class="lbl">{{ __('staticwords.Wallet') }}</span>
                    <span class="value">
                      <i class="{{ session()->get('currency')['value'] }}"></i>
                      @if(isset(Auth::user()->wallet) && Auth::user()->wallet->status == 1)

                      {{ sprintf("%.2f",currency(Auth::user()->wallet->balance, $from = $defCurrency->currency->code, $to = session()->get('currency')['id'] , $format = false)) }}

                      @else
                      0.00
                      @endif
                    </span>
                  </div>

                </div>
              </a>

            </div>

            @endif
            <ul class="list-unstyled list-unstyled-one">
              <li class="dropdown notifications-menu">
                <a title="Notification" href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell"></i>
                  @if(auth()->user()->unreadnotifications->count())
                  @if(auth()->user()->unreadnotifications->where('n_type','!=','order_v')->count()>0)
                  <sup id="countNoti" class="bell-badge">
                    {{ auth()->user()->unreadnotifications->where('n_type','!=','order_v')->count() }}
                  </sup>
                  @endif
                  @endif
                </a>



                <ul id="dropdown" class="dropdown-menu">
                  <li class="notiheadergrey header">
                    @if(auth()->user()->unreadnotifications->where('n_type','!=','order_v')->count())
                    {{ __('staticwords.Youhave') }}
                    <b>{{ auth()->user()->unreadnotifications->where('n_type','!=','order_v')->count() }}</b>
                    {{ __('staticwords.notification') }} !
                    <a class="color111 float-right"
                      href="{{ route('clearall') }}">{{ __('staticwords.MarkallasRead') }}</a>
                    @else
                    <span class="text-center">{{ __('staticwords.NoNotifications') }}</span>
                    @endif
                  </li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu notification-menu">
                    @if(auth()->user()->unreadnotifications->count())

                    @foreach(auth()->user()->unreadNotifications as $notification)
                    @if($notification->n_type != "order_v")
                    <li class="hey1 notiheaderlightgrey" id="{{ $notification->id }}"
                      onclick="markread('{{ $notification->id }}')">


                      <small class="padding5P float-right"><i class="fa fa-clock-o" aria-hidden="true"></i>
                        {{ date('jS M y',strtotime($notification->created_at)) }}</small>
                      <a class="color111 font-weight600" @if($notification->n_type == "order")
                        @foreach(App\Order::where('order_id','=',$notification->url)->get() as $order) --}}
                        href="{{ url('view/order/'.$notification->url) }}"
                        @endforeach
                        @else
                        href="{{ url('mytickets') }}"
                        @endif

                        onclick="markread('{{ $notification->id }}')" ><i class="fa fa-circle-o" aria-hidden="true"></i>
                        {{ $notification->data['data'] }}</a>


                    </li>
                    @endif

                    @endforeach
                    @endif
                  </ul>
                </ul>
              </li>
            </ul>
            @else

            <div class="login-block">
              <a href="{{ route('login') }}">{{ __('staticwords.Login') }}</a>
            </div>
            @endauth
            <!-- /.dropdown-cart -->

            <!-- ======================= SHOPPING CART DROPDOWN : END================================ -->
          </div>
          <!-- /.top-cart-row -->
        </div>
        <!-- /.row -->

      </div>
      <!-- /.container -->

    </div>
    <!-- /.main-header -->

    <!-- ============================================== NAVBAR ============================================== -->
    <div class="header-nav animate-dropdown header-nav-screen">
      <div class="container-fluid">
        <div class="yamm navbar navbar-default" role="navigation">

          <div class="nav-bg-class">
            <div class="navbar-collapse collapse display-none" id="mc-horizontal-menu-collapse">
              <div class="nav-outer">
                <ul class="nav navbar-nav">

                  @include('front.layout.topmenu')

                </ul>
                <!-- /.navbar-nav -->
                <div class="clearfix"></div>
              </div>
              <!-- /.nav-outer -->
            </div>
            <!-- /.navbar-collapse -->

          </div>
          <!-- /.nav-bg-class -->
        </div>
        <!-- /.navbar-default -->
      </div>
      <!-- /.container-class -->

    </div>
    <!-- /.header-nav -->
    <!-- ============================================== NAVBAR : END ============================================== -->

    <!-- ============================================== NAVBAR-small-screen : start ============================================== -->

    <div class="header-nav animate-dropdown header-nav-smallscreen">
      <div class="container">
        <div class="yamm navbar navbar-default" role="navigation">
          <div id="b-wrap">
            <div class="mhead">
              <span class="menu-ham cursor-pointer font-size-20" id="myOverlay">&#9776; </span>
            </div>
          </div>
          <div class="menu-new">
            <div class="close-menu-new">
              <a href="javascript:void(0)" class="closebtn">&times;</a>
            </div>
            <div class="smallscreen-bg-block">
              <div class="smallscreen-bg-block-heading"><span id="welcomeHeading">{{ __('staticwords.Welcome') }} @auth
                  {{ Auth::user()->name }} @endauth </span> </div>
            </div>
            <div class="sidenav nav-bg-class" onclick="event.stopPropagation();">
              <!-- <div class="nav-bg-class"> -->
              <div class="navbar-collapse collapse" id="mc-horizontal-menu-collapse">
                <div class="nav-outer">
                  <div class="nav-heading-one">{{ __('staticwords.Menu') }}</div>
                  <ul class="nav navbar-nav">
                    <!-- small screen nav menu -->
                    @include('front.layout.mobilemenu')
                    <li class="sidebar">
                      <div class="side-menu animate-dropdown outer-bottom-xs">
                        <div class="head">{{ __('staticwords.Categories') }}</div>
                        <?php $home_slider = App\Widgetsetting::where('name','category')->first(); ?>
                        @if(!empty($home_slider))
                        @if($home_slider->home=='1')
                        <nav class="megamenu-horizontal">

                          <ul class="nav">
                            <?php 
                                      $price_array = array();
                                    ?>
                            @foreach(App\Category::where('status','1')->orderBy('position','ASC')->get(); as $item)

                            @if($item->products->count()>0)
                            @foreach($item->products as $old)

                            @if($genrals_settings->vendor_enable == 1)
                            @foreach($old->subvariants as $orivar)

                            @if($price_login == 0 || Auth::check())
                            @php

                            $convert_price = 0;
                            $show_price = 0;

                            $commision_setting = App\CommissionSetting::first();

                            if($commision_setting->type == "flat"){

                            $commission_amount = $commision_setting->rate;
                            if($commision_setting->p_type == 'f'){

                            if($old->tax_r !=''){
                            $cit = $commission_amount*$old->tax_r/100;
                            $totalprice = $old->vender_price+$orivar->price+$commission_amount+$cit;
                            $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount+$cit;
                            }else{
                            $totalprice = $old->vender_price+$orivar->price+$commission_amount;
                            $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount;
                            }



                            if($old->vender_offer_price == 0){
                            $totalprice;
                            array_push($price_array, $totalprice);
                            }else{
                            $totalsaleprice;
                            $convert_price = $totalsaleprice==''?$totalprice:$totalsaleprice;
                            $show_price = $totalprice;
                            array_push($price_array, $totalsaleprice);

                            }


                            }else{

                            $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                            $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                            $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                            $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                            if($old->vender_offer_price ==0){
                            $bprice = round($buyerprice,2);

                            array_push($price_array, $bprice);
                            }else{
                            $bsprice = round($buyersaleprice,2);
                            $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                            $show_price = $buyerprice;
                            array_push($price_array, $bsprice);

                            }


                            }
                            }else{

                            $comm = App\Commission::where('category_id',$old->category_id)->first();
                            if(isset($comm)){
                            if($comm->type=='f'){

                            if($old->tax_r !=''){
                            $cit =$comm->rate*$old->tax_r/100;
                            $price = $old->vender_price + $comm->rate+$orivar->price+$cit;
                            $offer = $old->vender_offer_price + $comm->rate+$orivar->price+$cit;
                            }else{
                            $price = $old->vender_price + $comm->rate+$orivar->price;
                            $offer = $old->vender_offer_price + $comm->rate+$orivar->price;
                            }



                            $convert_price = $offer==''?$price:$offer;
                            $show_price = $price;

                            if($old->vender_offer_price == 0){

                            array_push($price_array, $price);
                            }else{
                            array_push($price_array, $offer);
                            }



                            }
                            else{

                            $commission_amount = $comm->rate;

                            $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                            $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                            $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                            $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                            if($old->vender_offer_price ==0){
                            $bprice = round($buyerprice,2);
                            array_push($price_array, $bprice);
                            }else{
                            $bsprice = round($buyersaleprice,2);
                            $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                            $show_price = round($buyerprice,2);
                            array_push($price_array, $bsprice);
                            }



                            }
                            }else{
                            $commission_amount = 0;

                            $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                            $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                            $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                            $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                            if($old->vender_offer_price ==0){
                            $bprice = round($buyerprice,2);
                            array_push($price_array, $bprice);
                            }else{
                            $bsprice = round($buyersaleprice,2);
                            $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                            $show_price = round($buyerprice,2);
                            array_push($price_array, $bsprice);
                            }
                            }
                            }

                            @endphp


                            @endif


                            @endforeach
                            @else
                            @if($old->vender['role_id'] == 'a')
                            @foreach($old->subvariants as $orivar)

                            @if($price_login == 0 || Auth::check())
                            @php

                            $convert_price = 0;
                            $show_price = 0;

                            $commision_setting = App\CommissionSetting::first();

                            if($commision_setting->type == "flat"){

                            $commission_amount = $commision_setting->rate;
                            if($commision_setting->p_type == 'f'){

                            if($old->tax_r !=''){
                            $cit = $commission_amount*$old->tax_r/100;
                            $totalprice = $old->vender_price+$orivar->price+$commission_amount+$cit;
                            $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount+$cit;
                            }else{
                            $totalprice = $old->vender_price+$orivar->price+$commission_amount;
                            $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount;
                            }



                            if($old->vender_offer_price == 0){
                            $totalprice;
                            array_push($price_array, $totalprice);
                            }else{
                            $totalsaleprice;
                            $convert_price = $totalsaleprice==''?$totalprice:$totalsaleprice;
                            $show_price = $totalprice;
                            array_push($price_array, $totalsaleprice);

                            }


                            }else{

                            $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                            $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                            $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                            $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                            if($old->vender_offer_price ==0){
                            $bprice = round($buyerprice,2);

                            array_push($price_array, $bprice);
                            }else{
                            $bsprice = round($buyersaleprice,2);
                            $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                            $show_price = $buyerprice;
                            array_push($price_array, $bsprice);

                            }


                            }
                            }else{

                            $comm = App\Commission::where('category_id',$old->category_id)->first();
                            if(isset($comm)){
                            if($comm->type=='f'){

                            if($old->tax_r !=''){
                            $cit =$comm->rate*$old->tax_r/100;
                            $price = $old->vender_price + $comm->rate+$orivar->price+$cit;
                            $offer = $old->vender_offer_price + $comm->rate+$orivar->price+$cit;
                            }else{
                            $price = $old->vender_price + $comm->rate+$orivar->price;
                            $offer = $old->vender_offer_price + $comm->rate+$orivar->price;
                            }



                            $convert_price = $offer==''?$price:$offer;
                            $show_price = $price;

                            if($old->vender_offer_price == 0){

                            array_push($price_array, $price);
                            }else{
                            array_push($price_array, $offer);
                            }



                            }
                            else{

                            $commission_amount = $comm->rate;

                            $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                            $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                            $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                            $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                            if($old->vender_offer_price ==0){
                            $bprice = round($buyerprice,2);
                            array_push($price_array, $bprice);
                            }else{
                            $bsprice = round($buyersaleprice,2);
                            $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                            $show_price = round($buyerprice,2);
                            array_push($price_array, $bsprice);
                            }



                            }
                            }else{
                            $commission_amount = 0;

                            $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                            $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                            $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                            $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                            if($old->vender_offer_price ==0){
                            $bprice = round($buyerprice,2);
                            array_push($price_array, $bprice);
                            }else{
                            $bsprice = round($buyersaleprice,2);
                            $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                            $show_price = round($buyerprice,2);
                            array_push($price_array, $bsprice);
                            }
                            }
                            }

                            @endphp


                            @endif


                            @endforeach
                            @endif
                            @endif

                            @endforeach

                            @if($price_login == 0 || Auth::check())
                            <?php
                                          if($price_array != null){
                                           $first =  min($price_array);
                                           $startp =  round($first);
                                           if($startp >= $first){
                                              $startp = $startp-1;
                                            }else{
                                              $startp = $startp;
                                            }

                                            
                                           $last = max($price_array);
                                           $endp =  round($last);

                                           if($endp <= $last){
                                              $endp = $endp+1;
                                            }else{
                                              $endp = $endp;
                                            }

                                          }
                                          else{
                                            $startp = 0.00;
                                            $endp = 0.00;
                                          }

                                          if(isset($first)){

                                            if($first == $last){
                                              $startp=0.00;
                                            }

                                          }
                                          

                                           unset($price_array); 
                                          
                                          $price_array = array();
                                          ?>

                            @endif




                            <li class="dropdown menu-item" id="dropdown">

                              <a aria-expanded="false" data-id="{{ $item->id }}" title="{{ $item->title }}"
                                class="categoryrec cat-page">
                                <i class="fa {{ $item->icon }}"></i>
                                @if($price_login == 0 || Auth::check())

                                <label class="cursor-pointer"
                                  onclick="categoryfilter('{{$item->id}}','','','{{ $startp }}','{{ $endp }}')">{{$item->title}}</label>

                                @else

                                <label class="cursor-pointer">{{$item->title}}</label>

                                @endif
                              </a>


                              <ul class="display-none" id="childOpen{{ $item->id }}">

                                @foreach($item->subcategory->where('status','1')->sortBy('position') as $s)


                                @foreach($s->products as $old)

                                @if($genrals_settings->vendor_enable == 1)
                                @foreach($old->subvariants as $orivar)

                                @if($price_login== 0 || Auth::check())
                                @php
                                $convert_price = 0;
                                $show_price = 0;

                                $commision_setting = App\CommissionSetting::first();

                                if($commision_setting->type == "flat"){

                                $commission_amount = $commision_setting->rate;
                                if($commision_setting->p_type == 'f'){

                                if($old->tax_r !=''){
                                $cit = $commission_amount*$old->tax_r/100;
                                $totalprice = $old->vender_price+$orivar->price+$commission_amount+$cit;
                                $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount+$cit;
                                }else{
                                $totalprice = $old->vender_price+$orivar->price+$commission_amount;
                                $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount;
                                }

                                if($old->vender_offer_price == 0){
                                $totalprice;
                                array_push($price_array, $totalprice);
                                }else{
                                $totalsaleprice;
                                $convert_price = $totalsaleprice==''?$totalprice:$totalsaleprice;
                                $show_price = $totalprice;
                                array_push($price_array, $totalsaleprice);

                                }


                                }else{

                                $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($old->vender_offer_price ==0){
                                $bprice = round($buyerprice,2);

                                array_push($price_array, $bprice);
                                }else{
                                $bsprice = round($buyersaleprice,2);
                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = $buyerprice;
                                array_push($price_array, $bsprice);

                                }


                                }
                                }else{

                                $comm = App\Commission::where('category_id',$old->category_id)->first();
                                if(isset($comm)){
                                if($comm->type=='f'){

                                if($old->tax_r !=''){
                                $cit =$comm->rate*$old->tax_r/100;
                                $price = $old->vender_price + $comm->rate+$orivar->price+$cit;
                                $offer = $old->vender_offer_price + $comm->rate+$orivar->price+$cit;
                                }else{
                                $price = $old->vender_price + $comm->rate+$orivar->price;
                                $offer = $old->vender_offer_price + $comm->rate+$orivar->price;
                                }

                                $convert_price = $offer==''?$price:$offer;
                                $show_price = $price;

                                if($old->vender_offer_price == 0){

                                array_push($price_array, $price);
                                }else{
                                array_push($price_array, $offer);
                                }



                                }
                                else{

                                $commission_amount = $comm->rate;

                                $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($old->vender_offer_price ==0){
                                $bprice = round($buyerprice,2);
                                array_push($price_array, $bprice);
                                }else{
                                $bsprice = round($buyersaleprice,2);
                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = round($buyerprice,2);
                                array_push($price_array, $bsprice);
                                }



                                }
                                }else{
                                $commission_amount = 0;

                                $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($old->vender_offer_price ==0){
                                $bprice = round($buyerprice,2);
                                array_push($price_array, $bprice);
                                }else{
                                $bsprice = round($buyersaleprice,2);
                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = round($buyerprice,2);
                                array_push($price_array, $bsprice);
                                }
                                }
                                }

                                @endphp


                                @endif


                                @endforeach
                                @else
                                @if($old->vender['role_id'] == 'a')
                                @foreach($old->subvariants as $orivar)

                                @if($price_login== 0 || Auth::check())
                                @php
                                $convert_price = 0;
                                $show_price = 0;

                                $commision_setting = App\CommissionSetting::first();

                                if($commision_setting->type == "flat"){

                                $commission_amount = $commision_setting->rate;
                                if($commision_setting->p_type == 'f'){

                                if($old->tax_r !=''){
                                $cit = $commission_amount*$old->tax_r/100;
                                $totalprice = $old->vender_price+$orivar->price+$commission_amount+$cit;
                                $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount+$cit;
                                }else{
                                $totalprice = $old->vender_price+$orivar->price+$commission_amount;
                                $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount;
                                }

                                if($old->vender_offer_price == 0){
                                $totalprice;
                                array_push($price_array, $totalprice);
                                }else{
                                $totalsaleprice;
                                $convert_price = $totalsaleprice==''?$totalprice:$totalsaleprice;
                                $show_price = $totalprice;
                                array_push($price_array, $totalsaleprice);

                                }


                                }else{

                                $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($old->vender_offer_price ==0){
                                $bprice = round($buyerprice,2);

                                array_push($price_array, $bprice);
                                }else{
                                $bsprice = round($buyersaleprice,2);
                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = $buyerprice;
                                array_push($price_array, $bsprice);

                                }


                                }
                                }else{

                                $comm = App\Commission::where('category_id',$old->id)->first();
                                if(isset($comm)){
                                if($comm->type=='f'){

                                if($old->tax_r !=''){
                                $cit =$comm->rate*$old->tax_r/100;
                                $price = $old->vender_price + $comm->rate+$orivar->price+$cit;
                                $offer = $old->vender_offer_price + $comm->rate+$orivar->price+$cit;
                                }else{
                                $price = $old->vender_price + $comm->rate+$orivar->price;
                                $offer = $old->vender_offer_price + $comm->rate+$orivar->price;
                                }

                                $convert_price = $offer==''?$price:$offer;
                                $show_price = $price;

                                if($old->vender_offer_price == 0){

                                array_push($price_array, $price);
                                }else{
                                array_push($price_array, $offer);
                                }



                                }
                                else{

                                $commission_amount = $comm->rate;

                                $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($old->vender_offer_price ==0){
                                $bprice = round($buyerprice,2);
                                array_push($price_array, $bprice);
                                }else{
                                $bsprice = round($buyersaleprice,2);
                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = round($buyerprice,2);
                                array_push($price_array, $bsprice);
                                }


                                }
                                }else{
                                $commission_amount = 0;

                                $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($old->vender_offer_price ==0){
                                $bprice = round($buyerprice,2);
                                array_push($price_array, $bprice);
                                }else{
                                $bsprice = round($buyersaleprice,2);
                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = round($buyerprice,2);
                                array_push($price_array, $bsprice);
                                }
                                }
                                }

                                @endphp


                                @endif


                                @endforeach
                                @endif
                                @endif

                                @endforeach

                                @if($price_login == 0 || Auth::check())
                                <?php

                                        
                                        if($price_array != null){
                                         $firstsub =  min($price_array);
                                         $startp =  round($firstsub);
                                         if($startp >= $firstsub){
                                            $startp = $startp-1;
                                          }else{
                                            $startp = $startp;
                                          }

                                          
                                         $lastsub = max($price_array);
                                         $endp =  round($lastsub);

                                         if($endp <= $lastsub){
                                            $endp = $endp+1;
                                          }else{
                                            $endp = $endp;
                                          }

                                        }else{
                                          $startp = 0.00;
                                          $endp = 0.00;
                                        }

                                        if(isset($firstsub)){
                                             if($firstsub == $lastsub){
                                                $startp=0.00;
                                            }
                                        }
                                       

                                         unset($price_array); 
                                        
                                        $price_array = array();
                                        ?>
                                @endif
                                <li
                                  class="dropdown menu-item {{ $s->childcategory->count()>0 ? "" : "side-sub-list" }}">
                                  <a class="childrec" title="{{$s->title}}" data-id="{{ $s->id }}"><i
                                      class="fa {{ $s->icon }}"></i>
                                    @if($price_login == 0 || Auth::check())

                                    <label class="cursor-pointer"
                                      onclick="categoryfilter('{{$item->id}}','{{ $s->id }}','','{{ $startp }}','{{ $endp }}')">{{$s->title}}</label>

                                    @else

                                    <label class="cursor-pointer">{{$s->title}}</label>

                                    @endif
                                  </a>

                                  <!-- child category loop -->

                                  @if($s->childcategory->count()>0)

                                  <ul class="display-none" id="childcollapseExample{{ $s->id }}">
                                    @foreach($s->childcategory->where('status','1')->sortBy('position') as $child)

                                    @foreach($child->products as $old)

                                    @if($genrals_settings->vendor_enable == 1)
                                    @foreach($old->subvariants as $orivar)

                                    @if($price_login== 0 || Auth::user())
                                    @php
                                    $convert_price = 0;
                                    $show_price = 0;

                                    $commision_setting = App\CommissionSetting::first();

                                    if($commision_setting->type == "flat"){

                                    $commission_amount = $commision_setting->rate;
                                    if($commision_setting->p_type == 'f'){

                                    if($old->tax_r !=''){
                                    $cit = $commission_amount*$old->tax_r/100;
                                    $totalprice = $old->vender_price+$orivar->price+$commission_amount+$cit;
                                    $totalsaleprice = $old->vender_offer_price + $orivar->price +
                                    $commission_amount+$cit;
                                    }else{
                                    $totalprice = $old->vender_price+$orivar->price+$commission_amount;
                                    $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount;
                                    }

                                    if($old->vender_offer_price == 0){
                                    $totalprice;
                                    array_push($price_array, $totalprice);
                                    }else{
                                    $totalsaleprice;
                                    $convert_price = $totalsaleprice==''?$totalprice:$totalsaleprice;
                                    $show_price = $totalprice;
                                    array_push($price_array, $totalsaleprice);

                                    }


                                    }else{

                                    $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                    $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                    $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                    $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                    if($old->vender_offer_price ==0){
                                    $bprice = round($buyerprice,2);

                                    array_push($price_array, $bprice);
                                    }else{
                                    $bsprice = round($buyersaleprice,2);
                                    $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                    $show_price = $buyerprice;
                                    array_push($price_array, $bsprice);

                                    }


                                    }
                                    }else{

                                    $comm = App\Commission::where('category_id',$old->category_id)->first();
                                    if(isset($comm)){
                                    if($comm->type=='f'){

                                    if($old->tax_r !=''){
                                    $cit =$comm->rate*$old->tax_r/100;
                                    $price = $old->vender_price + $comm->rate+$orivar->price+$cit;
                                    $offer = $old->vender_offer_price + $comm->rate+$orivar->price+$cit;
                                    }else{
                                    $price = $old->vender_price + $comm->rate+$orivar->price;
                                    $offer = $old->vender_offer_price + $comm->rate+$orivar->price;
                                    }

                                    $convert_price = $offer==''?$price:$offer;
                                    $show_price = $price;

                                    if($old->vender_offer_price == 0){

                                    array_push($price_array, $price);
                                    }else{
                                    array_push($price_array, $offer);
                                    }



                                    }
                                    else{

                                    $commission_amount = $comm->rate;

                                    $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                    $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                    $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                    $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                    if($old->vender_offer_price ==0){
                                    $bprice = round($buyerprice,2);
                                    array_push($price_array, $bprice);
                                    }else{
                                    $bsprice = round($buyersaleprice,2);
                                    $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                    $show_price = round($buyerprice,2);
                                    array_push($price_array, $bsprice);
                                    }



                                    }
                                    }else{
                                    $commission_amount = 0;

                                    $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                    $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                    $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                    $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                    if($old->vender_offer_price ==0){
                                    $bprice = round($buyerprice,2);
                                    array_push($price_array, $bprice);
                                    }else{
                                    $bsprice = round($buyersaleprice,2);
                                    $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                    $show_price = round($buyerprice,2);
                                    array_push($price_array, $bsprice);
                                    }
                                    }
                                    }

                                    @endphp


                                    @endif


                                    @endforeach
                                    @else
                                    @if($old->vender['role_id'] == 'a')
                                    @foreach($old->subvariants as $orivar)

                                    @if($price_login== 0 || Auth::user())
                                    @php
                                    $convert_price = 0;
                                    $show_price = 0;

                                    $commision_setting = App\CommissionSetting::first();

                                    if($commision_setting->type == "flat"){

                                    $commission_amount = $commision_setting->rate;
                                    if($commision_setting->p_type == 'f'){

                                    if($old->tax_r !=''){
                                    $cit = $commission_amount*$old->tax_r/100;
                                    $totalprice = $old->vender_price+$orivar->price+$commission_amount+$cit;
                                    $totalsaleprice = $old->vender_offer_price + $orivar->price +
                                    $commission_amount+$cit;
                                    }else{
                                    $totalprice = $old->vender_price+$orivar->price+$commission_amount;
                                    $totalsaleprice = $old->vender_offer_price + $orivar->price + $commission_amount;
                                    }

                                    if($old->vender_offer_price == 0){
                                    $totalprice;
                                    array_push($price_array, $totalprice);
                                    }else{
                                    $totalsaleprice;
                                    $convert_price = $totalsaleprice==''?$totalprice:$totalsaleprice;
                                    $show_price = $totalprice;
                                    array_push($price_array, $totalsaleprice);

                                    }


                                    }else{

                                    $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                    $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                    $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                    $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                    if($old->vender_offer_price ==0){
                                    $bprice = round($buyerprice,2);

                                    array_push($price_array, $bprice);
                                    }else{
                                    $bsprice = round($buyersaleprice,2);
                                    $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                    $show_price = $buyerprice;
                                    array_push($price_array, $bsprice);

                                    }


                                    }
                                    }else{

                                    $comm = App\Commission::where('category_id',$old->category_id)->first();
                                    if(isset($comm)){
                                    if($comm->type=='f'){

                                    if($old->tax_r !=''){
                                    $cit =$comm->rate*$old->tax_r/100;
                                    $price = $old->vender_price + $comm->rate+$orivar->price+$cit;
                                    $offer = $old->vender_offer_price + $comm->rate+$orivar->price+$cit;
                                    }else{
                                    $price = $old->vender_price + $comm->rate+$orivar->price;
                                    $offer = $old->vender_offer_price + $comm->rate+$orivar->price;
                                    }

                                    $convert_price = $offer==''?$price:$offer;
                                    $show_price = $price;

                                    if($old->vender_offer_price == 0){

                                    array_push($price_array, $price);
                                    }else{
                                    array_push($price_array, $offer);
                                    }

                                    }
                                    else{

                                    $commission_amount = $comm->rate;

                                    $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                    $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                    $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                    $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                    if($old->vender_offer_price ==0){
                                    $bprice = round($buyerprice,2);
                                    array_push($price_array, $bprice);
                                    }else{
                                    $bsprice = round($buyersaleprice,2);
                                    $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                    $show_price = round($buyerprice,2);
                                    array_push($price_array, $bsprice);
                                    }


                                    }
                                    }else{
                                    $commission_amount = 0;

                                    $totalprice = ($old->vender_price+$orivar->price)*$commission_amount;

                                    $totalsaleprice = ($old->vender_offer_price+$orivar->price)*$commission_amount;

                                    $buyerprice = ($old->vender_price+$orivar->price)+($totalprice/100);

                                    $buyersaleprice = ($old->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                    if($old->vender_offer_price ==0){
                                    $bprice = round($buyerprice,2);
                                    array_push($price_array, $bprice);
                                    }else{
                                    $bsprice = round($buyersaleprice,2);
                                    $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                    $show_price = round($buyerprice,2);
                                    array_push($price_array, $bsprice);
                                    }
                                    }
                                    }

                                    @endphp


                                    @endif


                                    @endforeach
                                    @endif
                                    @endif

                                    @endforeach

                                    <?php
                                                      
                                                      if($price_login == 0 || Auth::check()){
                                                          if($price_array != null){
                                                       $first =  min($price_array);
                                                       $startp =  round($first);
                                                       if($startp >= $first){
                                                          $startp = $startp-1;
                                                        }else{
                                                          $startp = $startp;
                                                        }

                                                        
                                                       $last = max($price_array);
                                                       $endp =  round($last);

                                                       if($endp <= $last){
                                                          $endp = $endp+1;
                                                        }else{
                                                          $endp = $endp;
                                                        }

                                                      }else{
                                                        $startp = 0.00;
                                                        $endp = 0.00;
                                                      }

                                                      if(isset($firstsub))
                                                      { 
                                                        if($firstsub == $lastsub){
                                                          $startp=0.00;
                                                      }
                                                      }
                                                      

                                                       unset($price_array); 
                                                        
                                                      
                                                      $price_array = array();
                                                      }
                                                      
                                                      ?>
                                    <li class="left-15 menu-item side-sub-list">
                                      @if($price_login == 0 || Auth::check())
                                      <a title="{{$child->title}}" class="cursor-pointer"
                                        onclick="categoryfilter('{{$item->id}}','{{ $s->id }}','{{ $child->id }}','{{ $startp }}','{{ $endp }}')"><i
                                          class="fa fa-angle-double-right" aria-hidden="true"></i> {{$child->title}}
                                      </a>
                                      @else
                                      <a class="cursor-pointer" title="{{ $child['title'] }}"><i
                                          class="fa fa-angle-double-right" aria-hidden="true"></i>
                                        {{$child['title']}}</a>
                                      @endif
                                    </li>

                                    @endforeach

                                  </ul>

                                  @endif



                                </li>
                                @endforeach
                              </ul>

                              @endif
                              @endforeach
                          </ul>

                          <!-- /.nav -->
                        </nav>
                        @endif
                        @endif
                        <!-- /.megamenu-horizontal -->
                      </div>
                    </li>
                    <li>
                      <div class="module-heading">
                        <h4 class="module-title">{{$footer3_widget->footer2}}</h4>
                      </div>
                      <!-- /.module-heading -->

                      <div class="module-body">
                        <ul class='list-unstyled'>
                          @if(Auth::check())

                          <li class="first"><a href="{{url('profile')}}"
                              title="My Account">{{ __('staticwords.MyAccount') }}</a></li>
                          <li>
                            <a href="{{ route('user.wallet.show') }}">
                              {{ __('staticwords.MyWallet') }}
                              ( @if(isset(Auth::user()->wallet) && Auth::user()->wallet->status == 1)
                              <i
                                class="{{session()->get('currency')['value']}}"></i>{{ round(Auth::user()->wallet->balance) }}
                              @else
                              <i class="{{session()->get('currency')['value']}}"></i>0
                              @endif )
                            </a>
                          </li>
                          <li><a href="{{url('order')}}" title="Order History">{{ __('staticwords.OrderHistory') }}</a>
                          </li>
                          @endif
                          <li><a href="{{url('faq')}}" title="faq">{{ __('staticwords.faqs') }}</a></li>

                        </ul>
                      </div>
                    </li>
                    <li>
                    </li>
                    <li>
                      <div class="module-heading">
                        <h4 class="module-title">{{$footer3_widget->footer3}}</h4>
                      </div>


                      @foreach($footermenus->where('widget_postion','=','footer_wid_3')->where('status','1')->get() as
                      $fm)
                      <div class="module-body">
                        <ul class='list-unstyled'>
                          <li class="first">

                            @if($fm->link_by == 'page')
                            <a title="{{ $fm->title }}" href="{{ route('page.slug',$fm->gotopage['slug']) }}">
                              {{ $fm->title }}
                            </a>
                            @else
                            <a target="__blank" title="{{ $fm->title }}" href="{{ $fm->url }}">
                              {{ $fm->title }}
                            </a>
                            @endif

                          </li>
                        </ul>
                      </div>
                      @endforeach




                    </li>
                    <li>
                      <div class="module-heading">
                        <h4 class="module-title">{{$footer3_widget->footer4}}</h4>
                      </div>

                      @foreach($footermenus->where('widget_postion','=','footer_wid_4')->where('status','1')->get() as
                      $foo)
                      <div class="module-body">
                        <ul class='list-unstyled'>
                          <li class="first">

                            @if($foo->link_by == 'page')
                            <a title="{{ $foo->title }}" href="{{ route('page.slug',$foo->gotopage['slug']) }}">
                              {{ $foo->title }}
                            </a>
                            @else
                            <a target="__blank" title="{{ $foo->title }}" href="{{ $foo->url }}">
                              {{ $foo->title }}
                            </a>
                            @endif

                          </li>
                        </ul>
                      </div>
                      @endforeach

                    </li>
                    <li class="top-bar animate-dropdown">
                      <div class="header-top-inner">
                        <div class="cnt-account">
                          <ul class="list-unstyled">

                            @auth
                            <li class="myaccount"><a href="{{url('profile')}}"
                                title="My Account">{{ __('staticwords.MyAccount') }}</a></li>
                            @inject('wish','App\Wishlist');
                            @php
                            $data = $wish->where('user_id',Auth::user()->id)->get();
                            $count = [];

                            foreach ($data as $key => $var) {

                            if($var->variant->products->status == '1'){
                            $count[] = $var->id;
                            }

                            }

                            $wishcount = count($count);
                            @endphp
                            <li class="wishlist"><a href="{{url('wishlist')}}" title="Wishlist">
                                {{ __('staticwords.Wishlist') }} ({{ $wishcount }})</a></li>
                            <li class="login">

                              <a role="button" onclick="logout()">
                                {{ __('staticwords.Logout') }}
                              </a>

                              <form action="{{ route('logout') }}" method="POST" class="logout-form display-none">
                                {{ csrf_field() }}
                              </form>
                            </li>

                            <li class="check"><a data-toggle="modal" href="#feedonmobile"
                                title="Feedback">{{ __('staticwords.Feedback') }}</a></li>
                            @endauth
                            <li><a href="{{ route('hdesk') }}" title="Help Desk &amp; Support"><i
                                  class="fa fa-question-circle"></i>{{ __('staticwords.hpd') }}</a></li>


                          </ul>
                          <!-- Feedback Modal -->
                          <div data-backdrop="static" data-keyboard="false" class="z-index99 modal fade"
                            id="feedonmobile" tabindex="99" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="feed-head modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                      aria-hidden="true">×</span></button>
                                  <h5 class="modal-title" id="myModalLabel"><i class="fa fa-envelope-o"
                                      aria-hidden="true"></i>
                                    {{ __('staticwords.FeedBackUs') }}
                                  </h5>
                                </div>
                                <div class="modal-body">
                                  <div class="info-feed alert bg-yellow">
                                    <i class="fa fa-info-circle"></i>&nbsp;{{ __('staticwords.feedline') }}.
                                  </div>
                                  <form action="{{ route('send.feedback') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                      <label class="font-weight-bold" for="">{{ __('staticwords.Name') }}: <span
                                          class="required">*</span></label>
                                      <input required="" type="text" name="name" class="form-control" value="Admin">
                                    </div>
                                    <div class="form-group">
                                      <label class="font-weight-bold" for="">{{ __('staticwords.Email') }}: <span
                                          class="required">*</span></label>
                                      <input required="" type="email" name="email" class="form-control"
                                        value="info@example.com">
                                    </div>
                                    <div class="form-group">
                                      <label class="font-weight-bold" for="">{{ __('staticwords.Message') }}: <span
                                          class="required">*</span></label>
                                      <textarea required="" name="msg"
                                        placeholder="{{ __('Tell us What You Like about us? or What should we do to more to improve our portal.') }}"
                                        cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                    <div class="rat">
                                      <label class="font-weight-bold">&nbsp;{{ __('staticwords.RateUs') }}: <span
                                          class="required">*</span></label>
                                      <ul id="starRating" data-stars="5" class="starRating starrating-init">
                                        <li class="star"><i class="fa fa-star"></i></li>
                                        <li class="star"><i class="fa fa-star"></i></li>
                                        <li class="star"><i class="fa fa-star"></i></li>
                                        <li class="star"><i class="fa fa-star"></i></li>
                                        <li class="star"><i class="fa fa-star"></i></li>
                                      </ul>
                                      <input type="hidden" id="" name="rate" value="1">
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ __('staticwords.Send') }}</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- /.cnt-account -->
                        <div class="cnt-block">
                          <ul class="list-unstyled list-inline">
                            @php
                            $auto = App\AutoDetectGeo::first();
                            @endphp
                            @if($auto->currency_by_country == 1)

                            @php
                            //if manual currency by country is enable//
                            $myip = $_SERVER['REMOTE_ADDR'];
                            $ip = geoip()->getLocation($myip);
                            $findcountry = App\Allcountry::where('iso',$ip->iso_code)->first();
                            $location = App\Location::all();
                            $countryArray = array();
                            $manualcurrency = array();

                            foreach ($location as $value) {
                            $s = explode(',', $value->country_id);

                            foreach ($s as $a) {

                            if ($a == $findcountry->id) {
                            array_push($countryArray, $value);
                            }
                            }

                            }

                            foreach ($countryArray as $cid) {
                            $c = App\multiCurrency::where('id',$cid->multi_currency)->first();
                            array_push($manualcurrency, $c);
                            }

                            @endphp

                            @endif

                            <select name="currency" onchange="val2()" id="currencySmall">

                              @if($auto->currency_by_country == 1)

                              @if(!empty($manualcurrency))
                              @foreach($manualcurrency as $currency)

                              @if(isset($currency->currency))

                              <option
                                {{ Session::get('currency')['mainid'] == $currency->currency->id ? "selected" : "" }}
                                value="{{ $currency->currency->id }}">{{ $currency->currency->code }}
                              </option>

                              @endif

                              @endforeach
                              @else
                              <option value="{{ $defCurrency->currency->id }}">{{ $defCurrency->currency->code }}
                              </option>
                              @endif

                              @else

                              @foreach(App\multiCurrency::all() as $currency)
                              <option
                                {{ Session::get('currency')['mainid'] == $currency->currency->id ? "selected" : "" }}
                                value="{{ $currency->currency->id }}">{{ $currency->currency->code }}
                              </option>
                              @endforeach

                              @endif

                            </select>

                            <select class="changed_language" name="changed_language" id="changed_lng">
                              @foreach(\DB::table('locales')->where('status','=',1)->get() as $lang)
                              <option {{ Session::get('changed_language') == $lang->lang_code ? "selected" : ""}}
                                value="{{ $lang->lang_code }}">{{ $lang->name }}</option>
                              @endforeach
                            </select>

                          </ul>
                          <!-- /.list-unstyled -->
                        </div>
                        <!-- /.cnt-cart -->
                        <div class="clearfix"></div>
                      </div>
                    </li>
                  </ul>
                  <!-- /.navbar-nav -->
                  <div class="clearfix"></div>
                </div> <!-- /.nav-outer -->
              </div> <!-- /.navbar-collapse -->
            </div><!--  </div> -->

          </div>

        </div>
      </div>
    </div>


    <!-- ============================================== NAVBAR-small-screen : END ============================================== -->

  </header>

  @if(session()->has("success"))
  <div class="hideAlert animated fadeInDown col-md-offset-4 col-md-6 alert alert-primary alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <i class="fa fa-check-circle-o" aria-hidden="true"></i> {{ Session::get('success') }}
  </div>
  @elseif(session()->has("failure"))
  <div class="hideAlert animated fadeInDown col-md-offset-4 col-md-6 alert alert-error alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{ Session::get('failure') }}
  </div>
  <?php Session::forget('failure');?>
  @endif


  <!-- Main Body -->

  @yield('body')

  <!-- Main Body End -->


  <!-- ==================== FOOTER ======================== -->
  <?php $footer = App\Footer::first(); ?>
  @if(!empty($footer ))
  <div class="row our-features-box">
    <div class="container">
      <ul>
        <li>
          <div class="feature-box">
            @if(isset($footer->icon_section1))<i class="footericon fa {{ $footer->icon_section1 }}"></i>@endif
            <p></p>
            <div class="content-blocks">{{ $footer->shiping }}</div>
          </div>
        </li>
        <li>
          <div class="feature-box">
            @if(isset($footer->icon_section2))<i class="footericon fa {{ $footer->icon_section2 }}"></i>@endif
            <p></p>
            <div class="content-blocks">
              {{ $footer->mobile }} </div>
          </div>
        </li>
        <li>
          <div class="feature-box">
            @if(isset($footer->icon_section3))<i class="footericon fa {{ $footer->icon_section3 }}"></i>@endif
            <p></p>
            <div class="content-blocks">{{ $footer->return }}</div>
          </div>
        </li>
        <li>
          <div class="feature-box">
            @if(isset($footer->icon_section4))<i class="footericon fa {{ $footer->icon_section4 }}"></i>@endif
            <p></p>
            <div class="content">{{ $footer->money }}</div>
          </div>
        </li>

      </ul>
    </div>
  </div>
  @endif
  <div id="brands-carousel" class="logo-slider logo-slider-one">
    <div class="logo-slider-inner">

      <div id="brand-slider" class="owlCarousel brand-slider custom-carousel owl-theme">
        @foreach(App\Brand::where('show_image','1')->where('status','1')->orderBy('id', 'desc')->get() as $datas)
        <div class="item m-t-15"> <a href="#" class="image"> <img title="{{ $datas->name }}" width="100px"
              height="110px" data-echo="{{url('images/brands/'.$datas->image)}}"
              src="{{url('images/brands/'.$datas->image)}}" alt="{{ $datas->name }}"> </a>
        </div>
        @endforeach

      </div>
      <!-- /.owl-carousel #logo-slider -->
    </div>
    <!-- /.logo-slider-inner -->
  </div>
  <!-- /.logo-slider-end -->
  <footer id="footer" class="footer color-bg">
    <div class="footer-bottom">
      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="address-block">
              @if(isset($genrals_settings))
              <div class="module-body">
                <ul class="toggle-footer">
                  @if(!empty($genrals_settings->address))
                  <li class="media">
                    <div class="pull-left"> <span class="icon fa-stack fa-lg"> <i
                          class="fa fa-map-marker fa-stack-1x fa-inverse"></i> </span> </div>
                    <div class="media-body">
                      <p>{{$genrals_settings->address}}</p>
                    </div>
                  </li>
                  @endif
                  @if(!empty($genrals_settings->mobile))
                  <li class="media">
                    <div class="pull-left"> <span class="icon fa-stack fa-lg"> <i
                          class="fa fa-mobile fa-stack-1x fa-inverse"></i> </span> </div>
                    <div class="media-body">
                      <a href="tel:{{$genrals_settings->mobile}}" tiltle="Mobile No.">{{$genrals_settings->mobile}}</a>
                    </div>
                  </li>
                  @endif
                  @if(!empty($genrals_settings->email))
                  <li class="media">
                    <div class="pull-left"> <span class="icon fa-stack fa-lg"> <i
                          class="fa fa-envelope fa-stack-1x fa-inverse"></i> </span> </div>
                    <div class="media-body"> <span><a href="mailto:{{$genrals_settings->email}}"
                          tiltle="E-Mail">{{$genrals_settings->email}}</a></span> </div>
                  </li>
                  @endif
                </ul>
              </div>
              @endif
            </div>
            <!-- /.module-body -->
          </div>
          <!-- /.col -->

          <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="module-heading module-small-screen">
              <h4 class="module-title">{{ $footer3_widget->footer2 }}</h4>
            </div>
            <!-- /.module-heading -->

            <div class="module-body module-small-screen">
              <ul class='list-unstyled'>
                @if(Auth::check())

                <li class="first"><a href="{{url('profile')}}" title="My Account">{{ __('staticwords.MyAccount') }}</a>
                </li>
                <li><a href="{{url('order')}}" title="Order History">{{ __('staticwords.OrderHistory') }}</a></li>
                @endif
                <li><a href="{{url('faq')}}" title="Faq">{{ __('staticwords.faqs') }}</a></li>
                <li class="last"><a href="{{ route('hdesk') }}"
                    title="Help Center">{{ __('staticwords.HelpCenter') }}</a></li>
              </ul>
            </div>
            <!-- /.module-body -->
          </div>

          <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="module-heading module-small-screen">
              <h4 class="module-title">{{ $footer3_widget->footer3 }}</h4>
            </div>

            @foreach($footermenus->where('widget_postion','=','footer_wid_3')->where('status','1')->get() as $foo)
            <div class="module-body module-small-screen">
              <ul class='list-unstyled'>
                <li class="first">

                  @if($foo->link_by == 'page')
                  <a title="{{ $foo->title }}" href="{{ route('page.slug',$foo->gotopage['slug']) }}">
                    {{ $foo->title }}
                  </a>
                  @else
                  <a target="__blank" title="{{ $foo->title }}" href="{{ $foo->url }}">
                    {{ $foo->title }}
                  </a>
                  @endif

                </li>
              </ul>
            </div>
            @endforeach
            <!-- /.module-body -->
          </div>
          <!-- /.col -->

          <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="module-heading module-small-screen">
              <h4 class="module-title">{{ $footer3_widget->footer4 }}</h4>
            </div>

            @foreach($footermenus->where('widget_postion','=','footer_wid_4')->where('status','1')->get() as $foo)
            <div class="module-body module-small-screen">
              <ul class='list-unstyled'>
                <li class="first">

                  @if($foo->link_by == 'page')
                  <a title="{{ $foo->title }}" href="{{ route('page.slug',$foo->gotopage['slug']) }}">
                    {{ $foo->title }}
                  </a>
                  @else
                  <a target="__blank" title="{{ $foo->title }}" href="{{ $foo->url }}">
                    {{ $foo->title }}
                  </a>
                  @endif

                </li>
              </ul>
            </div>
            @endforeach

            <!-- /.module-body -->
          </div>

        </div>
      </div>
    </div>
    <div class="copyright-bar header-nav-screen">
      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-sm-4 col-lg-4 no-padding social">
            <ul class="link">
              @foreach(App\Social::where('status','1')->get() as $social)
              <li class="{{$social->icon}} pull-left"><a target="_blank" rel="nofollow" href="{{$social->url}}"></a>
              </li>
              @endforeach
            </ul>
          </div>
          <div class="col-xs-12 col-sm-4 col-lg-4 no-padding copyright">
            &copy; {{ date("Y") }} @if(isset($Copyright)) {{ $Copyright }}@endif
          </div>
          <div class="col-xs-12 col-sm-4 col-lg-4 no-padding">
            <div class="clearfix payment-methods">
              <div class="row">

                @if($Api_settings->paypal_enable=='1')
                <div class="col-md-2">
                  <a title="Paypal" target="__blank" href="https://paypal.com"><img
                      src="{{ url('images/payment/paypal.png') }}" alt="Paypal" class="img-fluid"></a>
                </div>
                @endif


                @if($Api_settings->stripe_enable=='1')
                <div class="col-md-2">
                  <a title="Stripe" target="__blank" href="https://stripe.com/"><img
                      src="{{ url('images/payment/stripe.png') }}" alt="Razorpay" class="img-fluid"></a>
                </div>
                @endif

                @if($Api_settings->braintree_enable=='1')
                <div class="col-md-2">
                  <a title="Braintree" target="__blank" href="https://www.braintreepayments.com/"><img
                      src="{{ url('images/payment/braintree.png') }}" alt="Braintree" class="img-fluid"></a>
                </div>
                @endif

                @if($Api_settings->paystack_enable =='1')
                <div class="col-md-2">
                  <a title="Paystack" target="__blank" href="https://paystack.com/"><img
                      src="{{ url('images/payment/paystack.png') }}" alt="Paystack" class="img-fluid"></a>
                </div>
                @endif

                @if($Api_settings->paytm_enable=='1')
                <div class="col-md-2">
                  <a title="Paytm" target="__blank" href="https://paytm.com"><img
                      src="{{ url('images/payment/paytm.png') }}" alt="Paytm" class="img-fluid"></a>
                </div>
                @endif

                @if($Api_settings->razorpay=='1')
                <div class="col-md-2">
                  <a title="Razorpay" target="__blank" href="https://razorpay.com/"><img
                      src="{{ url('images/payment/razorpay.png') }}" alt="Razorpay" class="img-fluid"></a>
                </div>
                @endif

                @if($Api_settings->instamojo_enable=='1')
                <div class="p-3 col-md-2">
                  <a title="Instamojo" target="__blank" href="https://www.instamojo.com/"><img
                      src="{{ url('images/payment/instamojo.png') }}" alt="Razorpay" class="img-fluid"></a>
                </div>
                @endif

                @if($Api_settings->payu_enable=='1')
                <div class="p-3 col-md-2">
                  <a title="PayUMoney" target="__blank" href="https://www.payu.in/"><img
                      src="{{ url('images/payment/payumoney.png') }}" alt="Razorpay" class="img-fluid"></a>
                </div>
                @endif


              </div>
            </div>
            <!-- /.payment-methods -->
          </div>
        </div>
      </div>
    </div>

    <!-- small screen copyright-bar start -->
    <div class="copyright-bar header-nav-smallscreen">
      <button class="accordion no-padding social">
        <ul class="link">
          @foreach(App\Social::where('status','1')->get() as $social)
          <li class="{{$social->icon}} pull-left"><a target="_blank" rel="nofollow" href="{{$social->url}}"></a></li>
          @endforeach
        </ul>
      </button>
      <div class="panel max-height360">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 no-padding copyright text-center">
          &copy; {{ date("Y") }} @if(isset($Copyright)) {{$Copyright}}@endif
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12 no-padding btm-class">
          <div class="clearfix payment-methods">
            <ul>
              <li>
                @if($Api_settings->paypal_enable=='1')
              <li><a title="Paypal" target="__blank" href="https://paypal.com"><img
                    src="{{ url('images/payment/paypal.png') }}" alt="Paypal" class="img-fluid"></a></li>
              @endif

              @if($Api_settings->stripe_enable=='1')
              <li><a title="Stripe" target="__blank" href="https://stripe.com/"><img
                    src="{{ url('images/payment/stripe.png') }}" alt="Razorpay" class="img-fluid"></a></li>
              @endif

              @if($Api_settings->braintree_enable=='1')
              <li><a title="Braintree" target="__blank" href="https://www.braintreepayments.com/"><img
                    src="{{ url('images/payment/braintree.png') }}" alt="Braintree" class="img-fluid"></a></li>
              @endif

              @if($Api_settings->paystack_enable =='1')
              <li><a title="Paystack" target="__blank" href="https://paystack.com/"><img
                    src="{{ url('images/payment/paystack.png') }}" alt="Paystack" class="img-fluid"></a></li>
              @endif

              @if($Api_settings->paytm_enable=='1')
              <li>
                <a title="Paytm" target="__blank" href="https://paytm.com"><img
                    src="{{ url('images/payment/paytm.png') }}" alt="Paytm" class="img-fluid"></a>
              </li>
              @endif

              @if($Api_settings->razorpay=='1')
              <li class="m-1">
                <a title="Razorpay" target="__blank" href="https://razorpay.com/"><img
                    src="{{ url('images/payment/razorpay.png') }}" alt="Razorpay" class="img-fluid"></a>
              </li>
              @endif

              @if($Api_settings->instamojo_enable=='1')
              <li class="m-1">
                <a title="Instamojo" target="__blank" href="https://www.instamojo.com/"><img
                    src="{{ url('images/payment/instamojo.png') }}" alt="Razorpay" class="img-fluid"></a>
              </li>
              @endif

              @if($Api_settings->payu_enable=='1')
              <li class="m-1">
                <a title="PayUMoney" target="__blank" href="https://www.payu.in/"><img
                    src="{{ url('images/payment/payumoney.png') }}" alt="Razorpay" class="img-fluid"></a>
              </li>
              @endif
            </ul>
          </div>
          <!-- /.payment-methods -->
        </div>
        <div class="sidebar-widget newsletter outer-bottom-small">
          <h6 class="section-title">{{ __('staticwords.nw') }}</h6>
          <div class="sidebar-widget-body outer-top-xs">
            <p>{{ __('staticwords.newsletterword') }}</p>
            <form method="post" action="{{url('newsletter')}}">
              {{csrf_field()}}
              <div class="form-group">
                <label class="font-weight-bold" class="sr-only" for="">{{ __('staticwords.eaddress') }}</label>
                <input type="email" name="email" class="form-control" id=""
                  placeholder="{{ __('staticwords.subscribeword') }}">
              </div>
              <button class="btn btn-primary">{{ __('staticwords.Subscribe') }}</button>
            </form>
          </div>
          <!-- /.sidebar-widget-body -->
        </div>
      </div>
    </div>
    <!-- small screen copyright-bar end -->
  </footer>

  <!-- Display GDPR7 Cokkie message -->
  @include('cookieConsent::index')
  <!-- Session Flash Messages -->
  @include('notify::messages')

  <!-- Messenger Bubble -->
  @if(Request::ip() != '::1' && env('MESSENGER_CHAT_BUBBLE_URL') != '')
  <script src="{{ env('MESSENGER_CHAT_BUBBLE_URL') }}" async></script>
  @endif
  <script src="{{ url('vendor/mckenziearts/laravel-notify/js/notify.js') }}"></script>
  <!-- Bootstrap JS -->
  <script src="{{url('js/bootstrap.bundle.min.js')}}"></script> <!-- bootstrap  js -->
  <!-- jQuery UI JS -->
  <script src="{{ url('admin/vendor/jquery-ui/jquery-ui.min.js') }}"></script>

  <script src="{{ url('front/vendor/js/card.js') }}"></script>
  <!-- Drfit ZOOM JS -->
  <script src="{{ url('front/vendor/js/drift.min.js') }}"></script>
  <!-- Pace JS -->
  <script src="{{ url('admin/plugins/pace/pace.min.js') }}"></script>
  <!-- Select2 JS -->
  <script src="{{ url('front/vendor/js/select2.min.js') }}"></script>
  <!-- Moment JS -->
  <script src="{{ url('front/vendor/js/moment-with-locales.min.js') }}"></script>
  <!-- jQuery Validation JS -->
  <script src="{{ url('front/vendor/js/jquery.validate.min.js') }}"></script>
  <!-- Bootstrap Hover Dropdown JS -->
  <script src="{{url('front/vendor/js/bootstrap-hover-dropdown.min.js')}}"></script>
  <!-- Owlcarousel JS -->
  <script src="{{url('front/vendor/js/owl.carousel.min.js')}}"></script>
  <!-- Echo JS -->
  <script src="{{url('front/vendor/js/echo.min.js')}}"></script>
  <!-- Erashing Jquery JS -->
  <script src="{{url('front/vendor/js/jquery.easing-1.3.min.js')}}"></script>
  <!-- Bootstarp Slider JS -->
  <script src="{{url('front/vendor/js/bootstrap-slider.min.js')}}"></script>
  <!-- Rating JS -->
  <script src="{{url('front/vendor/js/jquery.rateit.min.js')}}"></script>
  <!-- Lightbox JS -->
  <script src="{{url('front/vendor/js/lightbox.min.js')}}"></script>
  <!-- Select JS -->
  <script src="{{url('front/vendor/js/bootstrap-select.min.js')}}"></script>
  <!-- Wow JS -->
  <script src="{{url('front/vendor/js/wow.min.js')}}"></script>
  <!-- Default Front JS -->
  <script src="{{url('front/js/scripts.min.js')}}"></script>
  <!-- Starrating JS -->
  <script src="{{url('front/vendor/js/starrating.min.js')}}"></script>
  <!-- jQuery Countdown JS -->
  <script src="{{url('front/vendor/js/jquery.countdown.min.js')}}"></script>
  <!-- Bootstrap Wizard JS -->
  <script src="{{url('front/vendor/js/jquery.bootstrap.wizard.min.js')}}"></script>
  <!-- Tab JS -->
  <script src="{{url('front/vendor/js/paper-bootstrap-wizard.js')}}"></script>
  <!-- Addtional jQuery Validation JS -->
  <script src="{{url('front/vendor/js/additional-methods.min.js')}}"></script>

  @if(file_exists('css/custom-js.js'))
  <script src="{{url('js/custom-js.js')}}"></script>
  @endif
  <!-- Sweetalert JS -->
  <script src="{{ url('front/vendor/js/sweetalert.min.js') }}"></script>
  <!-- Sticky JS -->
  <script src="{{ url('front/vendor/js/sticky.min.js') }}"></script>
  <!-- Extra Front JS -->
  <script>
    var baseUrl = "<?= url('/') ?>";
  </script>
  <script src="{{ url('js/front.min.js') }}"></script>
  <script>
    var sendurl = "<?= route('ajaxsearch') ?>";
  </script>
  <!-- Search JS -->
  <script src="{{ url('js/search.min.js') }}"></script>

  <script src="{{ url('js/htmlsession.min.js') }}"></script>
  <script>
    var httpson = @if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 1 @else 0 @endif;
    var sw = @json(url('sw.js'));
    var rightclick = @json($rightclick);
    var inspect = @json($inspect);
  </script>
  <script src="{{url('js/frontmaster.min.js')}}"></script>
  <script>
    var crate = @json(round($conversion_rate, 4));
    var exist = @json(url('shop'));
    var setstart = @json(url('setstart'));
  </script>
  <script src="{{url('js/frontlayout.min.js')}}"></script>
  @yield('script')
</body>

</html>