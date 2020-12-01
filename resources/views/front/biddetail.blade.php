@extends("front/layout.master")
@section('title', "$pro->name")
@section('head-script')
@endsection
@section("body")
<nav class="navbar navbar-light display-none navbarBlue stickCartNav fixed-top">
  
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      
      <a class="navbar-brand" href="#">
        <div class="margin-top-minus-5" id="pro_section">
          <div id="pro-img"></div>
          <div id="pro-title"></div>
        </div>
      </a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div id="navbarSupportedContent">
      <ul class="nav navbar-nav navbar-right">
        <li class="nav-item">
          <div class="quant-input">
            <input type="number" value="1" class="qty-section">
          </div>
        </li>

        <li class="nav-item active">
          <div id="cartForm">
          </div>
        </li>
        <li class="nav-item">
          <div class="favorite-button header-nav-smallscreen">


           
            <span class="favorite-button-box">
             
            </span>    
              
             
          </div>
        </li>
        <li class="nav-item">
          <div class="favorite-button header-nav-smallscreen">
            <span class="favorite-button-one">
              <a class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="{{ __('Add to Compare') }}" href="{{ route('compare.product',$pro->id) }}">
                 <i class="fa fa-signal"></i>
              </a>
            </span>
          </div>
        </li>
      </ul>


    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav> 
<div class="breadcrumb">
  <div class="container-fluid">
    <div class="breadcrumb-inner">
      <ul class="list-inline list-unstyled">
        <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
        <li><a href="#">{{ $pro->category->title }}</a></li>
        <li><a href="#">{{ $pro->subcategory->title }}</a></li>
        @if(!empty($pro->childcat->title))
              <li class='active'>{{ $pro->childcat->title }}</li>
        @endif
      </ul>
    </div><!-- /.breadcrumb-inner -->
  </div><!-- /.container -->
</div><!-- /.breadcrumb -->

<div class="body-content outer-top-xs outer-top-xs-one detail-page-block">
  <div class='container-fluid'>
    <div class='row no-gutters single-product'>
     <div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-2 sidebar left-sidebar'>
        <div class="side-content">
        <div class="sidebar-module-container">

        @php
          $isad = App\DetailAds::where('position','=','prodetail')->where('linked_id',$pro->id)->where('status','=','1')->first();
        @endphp
        
        @if(isset($isad))
        <div class="home-banner outer-top-n outer-bottom-xs">
            @if($isad->adsensecode != '')
              @php
                html_entity_decode($isad->adsensecode);
              @endphp
            @else
                @if($isad->show_btn == '1')
                   <h3 class="buy-heading" style="color:{{ $isad->hcolor }}">{{ $isad->top_heading }}</h3>
                   <h5 class="buy-sub-heading" style="color:{{ $isad->scolor }}">{{ $isad->sheading }}</h5>
                   <center><a href="
                   @if($isad->linkby == 'category')
                     @include('front.ads.adcaturl')
                   @elseif($isad->linkby == 'detail')
                     @include('front.ads.adprourl')
                   @elseif($isad->linkby == 'url')
                    {{ $isad->url }}
                   @endif" style="color:{{ $isad->btn_txt_color }};background: {{ $isad->btn_bg_color }}" class="btn buy-button">{{ $isad->btn_text }}</a></center>
                   <img src="{{ url('images/detailads/'.$isad->adimage) }}" alt="advertise" class="img-responsive img-fluid">
                @elseif($isad->show_btn == 0 && $isad->top_heading != '')
                   <a href="
                  @if($isad->linkby == 'category')
                    @include('front.ads.adcaturl')
                  @elseif($isad->linkby == 'detail')
                    @include('front.ads.adprourl')
                  @elseif($isad->linkby == 'url')
                    {{ $isad->url }}
                  @endif
                  ">
                    <h3 class="buy-heading" style="color:{{ $isad->hcolor }}">{{ $isad->top_heading }}</h3>
                    <h5 class="buy-sub-heading" style="color:{{ $isad->scolor }}">{{ $isad->sheading }}</h5>
                    <img class="img-responsive img-fluid" src="{{ url('images/detailads/'.$isad->adimage) }}" alt="advertise">
                  </a>
                @else
                  <a href="
                  @if($isad->linkby == 'category')
                    @include('front.ads.adcaturl')
                  @elseif($isad->linkby == 'detail')
                    @include('front.ads.adprourl')
                  @elseif($isad->linkby == 'url')
                    {{ $isad->url }}
                  @endif
                  ">
                    <img class="img-responsive img-fluid" src="{{ url('images/detailads/'.$isad->adimage) }}" alt="advertise">
                  </a>
                @endif
            @endif
        </div>
        @endif

        @php
          $enable_hotdeal = App\Widgetsetting::where('name','hotdeals')->first();
          $date  =  date('Y-m-d h:i:s');
          $price_array = array();
          if($genrals_settings->vendor_enable != 1){
            $hotdeals = App\Hotdeal::join('products','products.id','=','hotdeals.pro_id')->join('users','users.id','=','products.vender_id')->where('users.status','1')->where('products.status','=','1')->where('hotdeals.status','=','1')->where('users.role_id','!=','v')->get();
          }else{
            $hotdeals = App\Hotdeal::join('products','products.id','=','hotdeals.pro_id')->join('users','users.id','=','products.vender_id')->where('users.status','1')->where('products.status','=','1')->where('hotdeals.status','=','1')->get();
          }
        @endphp

     <!-- /.side-menu -->
        <!-- ================================== TOP NAVIGATION : END ================================== -->

        <!-- ============================================== HOT DEALS ============================================== -->
@if(isset($enable_hotdeal) && $enable_hotdeal->shop == "1")
      <div class="sidebar-widget hot-deals outer-bottom-xs  display-none-block">
          <h3 class="section-title">{{ __('staticwords.Hotdeals') }}</h3>
          <div class="owl-carousel sidebar-carousel custom-carousel owl-theme outer-top-ss">
          
          
            @foreach($hotdeals as $value)
              
              @if(isset($value->pro))
               

                  @foreach($value->pro->subvariants as $key=> $orivar)
                       @if($orivar->def ==1)

                           @if($price_login == 0 || Auth::check())
                                    @php
                                              $convert_price = 0;
                                              $show_price = 0;
                                              
                                              $commision_setting = App\CommissionSetting::first();

                                              if($commision_setting->type == "flat"){

                                                 $commission_amount = $commision_setting->rate;
                                                if($commision_setting->p_type == 'f'){
                                                
                                                  $totalprice = $value->pro->vender_price+$orivar->price+$commission_amount;
                                                  $totalsaleprice = $value->pro->vender_offer_price + $orivar->price + $commission_amount;

                                                   if($value->pro->vender_offer_price == 0){
                                                       $totalprice;
                                                       array_push($price_array, $totalprice);
                                                    }else{
                                                      $totalsaleprice;
                                                      $convert_price = $totalsaleprice==''?$totalprice:$totalsaleprice;
                                                      $show_price = $totalprice;
                                                      array_push($price_array, $totalsaleprice);
                                                    
                                                    }

                                                   
                                                }else{

                                                  $totalprice = ($value->pro->vender_price+$orivar->price)*$commission_amount;

                                                  $totalsaleprice = ($value->pro->vender_offer_price+$orivar->price)*$commission_amount;

                                                  $buyerprice = ($value->pro->vender_price+$orivar->price)+($totalprice/100);

                                                  $buyersaleprice = ($value->pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);

                                                 
                                                    if($value->pro->vender_offer_price ==0){
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
                                                
                                              $comm = App\Commission::where('category_id',$value->pro->category_id)->first();
                                              if(isset($comm)){
                                             if($comm->type=='f'){
                                               
                                               $price =  $value->pro->vender_price  + $comm->rate+$orivar->price;
                                               $offer =  $value->pro->vender_offer_price  + $comm->rate+$orivar->price;

                                                $convert_price = $offer==''?$price:$offer;
                                                $show_price = $price;

                                                 if($value->pro->vender_offer_price == 0){

                                                       array_push($price_array, $price);
                                                    }else{
                                                      array_push($price_array, $offer);
                                                    }

                                                
                                                 
                                            }
                                            else{

                                                  $commission_amount = $comm->rate;

                                                  $totalprice = ($value->pro->vender_price+$orivar->price)*$commission_amount;

                                                  $totalsaleprice = ($value->pro->vender_offer_price+$orivar->price)*$commission_amount;

                                                  $buyerprice = ($value->pro->vender_price+$orivar->price)+($totalprice/100);

                                                  $buyersaleprice = ($value->pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);

                                                 
                                                    if($value->pro->vender_offer_price ==0){
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

                                                  $totalprice = ($value->pro->vender_price+$orivar->price)*$commission_amount;

                                                  $totalsaleprice = ($value->pro->vender_offer_price+$orivar->price)*$commission_amount;

                                                  $buyerprice = ($value->pro->vender_price+$orivar->price)+($totalprice/100);

                                                  $buyersaleprice = ($value->pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);

                                                 
                                                    if($value->pro->vender_offer_price ==0){
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

                          @php 
                            
                            $var_name_count = count($orivar['main_attr_id']);
                           
                            $name;
                            $var_name;
                               $newarr = array();
                               
                              for($i = 0; $i<$var_name_count; $i++){
                                $var_id =$orivar['main_attr_id'][$i];
                                $var_name[$i] = $orivar['main_attr_value'][$var_id];
                                 
                                  $name[$i] = App\ProductAttributes::where('id',$var_id)->first();
                                   
                              }


                            try{
                              $url = url('details').'/'.$value->pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                            }catch(Exception $e)
                            {
                              $url = url('details').'/'.$value->pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                            }

                          @endphp
                        
                         @if($date <= $value->end)
                              <div class="item hot-deals-item">
                                <div class="products">
                                  <div class="hot-deal-wrapper">
                                    <div class="image"> 
                                    <a href="{{$url}}" title="{{$value->pro->name}}">
                                          @if(count($value->pro->subvariants)>0)

                                          @if(isset($orivar->variantimages['image2']))
                                           <img class="{{ $orivar->stock == 0 ? "filterdimage" : ""}}" src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}" alt="{{$value->name}}">
                                           <img class="{{ $orivar->stock == 0 ? "filterdimage" : ""}} hover-image" src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt=""/>
                                          @endif

                                          @else
                                          <img title="{{ $value->name }}" src="{{url('images/no-image.png')}}" alt="{{ __('No Image') }}"/>
                                          @endif  

                                          
                                    </a>
                                    </div>

                                    @if($value->pro->vender_offer_price != 0 || $value->pro->vender_offer_price != null)
                                     @php
                                        $getdisprice = $value->pro->vender_price - $value->pro->vender_offer_price;
                                        $gotdis = $getdisprice/$value->pro->vender_price;
                                        $offamount = $gotdis*100;
                                     @endphp
                                    <div class="sale-offer-tag"><span><?php echo Round($offamount)."%"; ?><br>
                                      off</span>
                                    </div>  
                                    
                                    @endif
                                    <div class="countdown">
                                      <div class="timing-wrapper" data-startat="{{ $value->start }}" data-countdown="{{$value->end}}"></div>
                                    </div>
                                   </div>
                                  <!-- /.hot-deal-wrapper -->
                                  
                                  <div class="product-info text-left m-t-20">
                                    <h3 class="name"><b><a href="{{$url}}" title="{{$value->pro->name}}">{{$value->pro->name}}</a></b></h3>
                                    <?php 
                                              $review_t = 0;
                                              $price_t = 0;
                                              $value_t = 0;
                                              $sub_total = 0;
                                              $sub_total = 0;
                                              $reviews = App\UserReview::where('pro_id',$value->pro->id)->where('status','1')->get();
                                              ?> @if(!empty($reviews[0]))<?php
                                              $count =  App\UserReview::where('pro_id',$value->pro->id)->count();
                                                foreach($reviews as $review){
                                                $review_t = $review->price*5;
                                                $price_t = $review->price*5;
                                                $value_t = $review->value*5;
                                                $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                }
                                                $count = ($count*3) * 5;
                                                $rat = $sub_total/$count;
                                                $ratings_var = ($rat*100)/5;
                                                ?>
                          
                                            <div>
                                    <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span></div>
                                    </div>
                                           
                                             @else
                                             <div class="text-center">
                                               {{ __('No Rating') }}
                                             </div>
                                             @endif 

                                             <div class="product-price"> <span class="price">
                                      
                                              @if($price_login == 0 || Auth::check())
                                                          @php
                                       $convert_price = 0;
                                       $show_price = 0;
                                       
                                       $commision_setting = App\CommissionSetting::first();

                                       if($commision_setting->type == "flat"){

                                          $commission_amount = $commision_setting->rate;
                                         if($commision_setting->p_type == 'f'){
                                         
                                           if($value->pro->tax_r !=''){

                                              $cit =$commission_amount*$value->pro->tax_r/100;
                                              $totalprice = $value->pro->vender_price+$orivar->price+$commission_amount+$cit;
                                              $totalsaleprice = $value->pro->vender_offer_price + $cit + $orivar->price + $commission_amount;

                                              if($value->pro->vender_offer_price == 0){
                                                $show_price = $totalprice;
                                              }else{
                                                $totalsaleprice;
                                                $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                                $show_price = $totalprice;
                                              }


                                              }else{

                                              $totalprice = $value->pro->vender_price+$orivar->price+$commission_amount;
                                              $totalsaleprice = $value->pro->vender_offer_price + $orivar->price + $commission_amount;

                                              if($value->pro->vender_offer_price == 0){
                                                $show_price = $totalprice;
                                              }else{
                                                $totalsaleprice;
                                                $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                                $show_price = $totalprice;
                                              }

                                              }

                                            
                                         }else{

                                           $totalprice = ($value->pro->vender_price+$orivar->price)*$commission_amount;

                                           $totalsaleprice = ($value->pro->vender_offer_price+$orivar->price)*$commission_amount;

                                           $buyerprice = ($value->pro->vender_price+$orivar->price)+($totalprice/100);

                                           $buyersaleprice = ($value->pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);

                                          
                                             if($value->pro->vender_offer_price ==0){
                                               $show_price =  round($buyerprice,2);
                                             }else{
                                                round($buyersaleprice,2);
                                                
                                               $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                               $show_price = $buyerprice;
                                             }
                                          

                                         }
                                       }else{
                                         
                                       $comm = App\Commission::where('category_id',$value->pro->category_id)->first();
                                       if(isset($comm)){
                                      if($comm->type=='f'){
                                        
                                        

                                        if($value->pro->tax_r != ''){

                                                  $cit = $comm->rate*$value->pro->tax_r/100;

                                                  $price = $value->pro->vender_price + $comm->rate + $orivar->price + $cit;

                                                  if($value->pro->vender_offer_price != null){
                                                    $offer =  $value->pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                                  }else{
                                                    $offer =  $value->pro->vender_offer_price;
                                                  }

                                                  if($value->pro->vender_offer_price == 0 || $value->pro->vender_offer_price == null){
                                                  $show_price = $price;
                                                  }else{

                                                  $convert_price = $offer;
                                                  $show_price = $price;
                                                  }

                                                  }else{


                                                  $price = $value->pro->vender_price + $comm->rate + $orivar->price;

                                                  if($value->pro->vender_offer_price != null){
                                                    $offer =  $value->pro->vender_offer_price + $comm->rate + $orivar->price;
                                                  }else{
                                                    $offer =  $value->pro->vender_offer_price;
                                                  }

                                                  if($value->pro->vender_offer_price == 0 || $value->pro->vender_offer_price == null){
                                                    $show_price = $price;
                                                  }else{

                                                    $convert_price = $offer;
                                                    $show_price = $price;
                                                  }

                                                  }
                                       
                                        
                                     }
                                     else{

                                          $commission_amount = $comm->rate;

                                           $totalprice = ($value->pro->vender_price+$orivar->price)*$commission_amount;

                                           $totalsaleprice = ($value->pro->vender_offer_price+$orivar->price)*$commission_amount;

                                           $buyerprice = ($value->pro->vender_price+$orivar->price)+($totalprice/100);

                                           $buyersaleprice = ($value->pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);

                                          
                                             if($value->pro->vender_offer_price == 0){
                                                $show_price = round($buyerprice,2);
                                             }else{
                                                round($buyersaleprice,2);
                                                
                                               $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                               $show_price = round($buyerprice,2);
                                             }
                                               
                                     }
                                  }else{
                                           $commission_amount = 0;

                                           $totalprice = ($value->pro->vender_price+$orivar->price)*$commission_amount;

                                           $totalsaleprice = ($value->pro->vender_offer_price+$orivar->price)*$commission_amount;

                                           $buyerprice = ($value->pro->vender_price+$orivar->price)+($totalprice/100);

                                           $buyersaleprice = ($value->pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);

                                          
                                             if($value->pro->vender_offer_price == 0){
                                                $convert_price = round($buyerprice,2);
                                             }else{
                                                round($buyersaleprice,2);
                                                
                                               $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                               $show_price = round($buyerprice,2);
                                             }
                                  }
                                     }
                                       $convert_price = $convert_price*$conversion_rate;
                                       $show_price = $show_price*$conversion_rate;
                                       
                                     @endphp
                                               
                                               @if(Session::has('currency'))
                                                 @if($convert_price == 0 || $convert_price == 'null')
                                                   <span class="price"><i class="{{session()->get('currency')['value']}}"></i>{{round($show_price*$conversion_rate,2)}}</span>
                                                 @else
                   
                                                   <span class="price"><i class="{{session()->get('currency')['value']}}"></i>{{round($convert_price*$conversion_rate,2)}}</span>
                   
                                                   <span class="price-before-discount"><i class="{{session()->get('currency')['value']}}"></i>{{round($show_price*$conversion_rate,2)}}</span>
                   
                   
                                                 @endif
                                               @endif
                   
                                                 @endif
                                                </div>
                                     
                                    <!-- /.product-price --> 
                                    
                                  </div>
                                  <!-- /.product-info -->

                                    <div class="cart clearfix animate-effect">
                                        <div class="action">
                                          <ul class="list-unstyled">
                                          @php
                                             $isInCart= App\Cart::where('variant_id',$orivar->id)->first();
                                             $in_session = 0;

                                             if(!empty(Session::has('cart'))){
                                                foreach (Session::get('cart') as $scart) {
                                                 if($orivar->id == $scart['variantid']){
                                                    $in_session = 1;
                                                 }
                                               }
                                             }
                                             
                                             
                                          @endphp
                                          @if($value->pro->selling_start_at == '' || $value->pro->selling_start_at <= date("Y-m-d H:i:s"))
                                          @if(!isset($isInCart) && $in_session == 0 && $orivar->stock>0)
                                           @if($price_login != 1)
                                            <form method="POST" action="{{route('add.cart',['id' => $value->pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                                                              {{ csrf_field() }}
                                            <li class="add-cart-button btn-group">
                                              <button class="btn btn-primary icon" data-toggle="dropdown" type="button"> <i class="fa fa-shopping-cart"></i> </button>
                                              <button class="btn btn-primary cart-btn" type="submit">{{ __('staticwords.AddtoCart') }}</button>
                                            </li>
                                          </form>
                                           @endif
                                          @else
                                          @if($orivar->stock>0 && $in_session == 1)
                                            <li class="add-cart-button btn-group">
                                              
                                                <button class="btn btn-primary icon" data-toggle="dropdown" type="button"> <i class="fa fa-check"></i> </button>
                                                <button onclick="window.location='{{ url('/cart') }}'" class="btn btn-primary cart-btn" type="button">{{ __('Deal in Cart') }}</button>
                                            
                                            </li>
                                          @endif
                                            @if($orivar->stock==0)

                                            <h5 class="required" align="center">{{ __('staticwords.Outofstock') }}</h5>
                                          
                                            @endif
                                          @endif
                                         @else
                                            <h5>{{ __('ComingSoon') }}</h5>
                                          @endif
                                            
                                          </ul>
                                        </div>
                                        <!-- /.action --> 
                                    </div>
                                  
                                 
                                  
                                </div>
                              </div>
                              
                              
                         @endif
                         
                      @endif
                  @endforeach
              

              @endif

            @endforeach

           

           </div>
            <!-- /.sidebar-widget -->
      </div>
@endif
<!-- ============================================== HOT DEALS: END ============================================== -->

        @php
          $enable_newsletter_widget = App\Widgetsetting::where('name','newsletter')->first();
        @endphp

<!-- ============================================== NEWSLETTER ============================================== -->
@if(isset($enable_newsletter_widget) && $enable_newsletter_widget->shop == "1")
      <div class="sidebar-widget newsletter outer-bottom-small outer-top-vs header-nav-screen">
        <h3 class="section-title">{{ __('staticwords.nw') }}</h3>
        <div class="sidebar-widget-body outer-top-xs">
          <p>{{ __('staticwords.newsletterword') }}</p>
              <form method="post" action="{{url('newsletter')}}">
                      {{csrf_field()}}
                    <div class="form-group">
                      <label class="sr-only">{{ __('staticwords.eaddress') }}</label>
                      <input type="email" name="email" class="form-control" placeholder="{{ __('staticwords.subscribeword') }}">
                    </div>
                    <button class="btn btn-primary">{{ __('staticwords.Subscribe') }}</button>
                  </form>
        </div><!-- /.sidebar-widget-body -->
      </div><!-- /.sidebar-widget -->
<!-- ============================================== NEWSLETTER: END ============================================== -->
@endif
<!-- ============================================== Testimonials============================================== -->
@php
  $testimonials = App\Testimonial::where('status','1')->get();
  $enable_testimonial_widget = App\Widgetsetting::where('name','testimonial')->first();
@endphp
@if(isset($enable_testimonial_widget) && $enable_testimonial_widget->shop == '1')
@if(count($testimonials)>0)
      <div class="sidebar-widget outer-top-vs advertisement-main-block-one">
                <div id="advertisement" class="advertisement">
                   <?php $home_slider = App\Widgetsetting::where('name', 'testimonial')->first();?>
                   @if($home_slider->shop=='1' && $home_slider->page=='1')
                  @foreach($testimonials as $value)


                  <div class="item">
                    <div class="avatar"><img src="{{url('/images/testimonial/'.$value->image)}}" alt="Image"></div>
                    <div class="testimonials"><em>"</em> {{strip_tags($value->des)}}<em>"</em></div>
                    <div class="clients_author">{{$value->name}}<span>{{$value->post}}</span> </div>
                    <!-- /.container-fluid -->
                  </div>

                  @endforeach
                  @endif
                  <!-- /.item -->

                </div>
                <!-- /.owl-carousel -->
      </div>
@endif
@endif
<!-- ============================================== Testimonials: END ============================================== -->

        </div>

        </div>
      </div><!-- /.sidebar -->
          <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12  col-xl-10 rht-col main-content'>
            
            <div class="detail-block">
              <!-- ====================== data sticky: start ============= --> 
                <div class="row">

                     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4 gallery-holder left-sidebar">
                      <div class="product-item-holder size-big single-product-gallery small-gallery">

                     <div class="data-sticky ">
                        <div class="single-product-gallery-item">


                        {{-- single image through js here --}}

                          
                        </div>

                      <!-- /.single-product-gallery-item -->

                        <div class="single-product-gallery-thumbs gallery-thumbs">
                          <div id="owl-demo" class="owl-carousel" >
                          </div>
                        </div><!-- /.gallery-thumbs -->

                        <div class="notifymeblock">

                        </div>
                        
                      </div>


<!-- ======================= data sticky: END ========================== --> 

            </div><!-- /.single-product-gallery -->



          </div><!-- /.gallery-holder -->

       <div class='col-sm-12 col-md-12 col-lg-12 col-xl-8 product-info-block main-content'>

  <div id="details-container"></div>
           
            <div class="product-info">
              <div class="stock-container info-container">
                <div class="row">
                 <div class="col-lg-8">
                    <div class="pull-left">
                     
                      <div class="stock-box">
                        {{-- <span class="label">{{ __('Highest Bid') }} :</span> --}}
                      </div>
                    </div>
                   <div class="pull-left">
                      <div class="stock-box">
                        <span class="text-success stockval value">

                        </span>
                      </div>
                    </div>

                    
                  <br><br>
                     <h1 class="name">{{$pro->name}} </h1>
                    <span class="productVars name-type"></span>
                    <div class="seller-info">{{ __('staticwords.by') }} <a href="#" class="lnk">
                      {{ $pro->store->name }} @if($pro->store->verified_store) <i title="Verified" class="text-green fa fa-check-circle"></i> @endif
                    </a>
                    </div>
                   <p></p>
                <?php

                  $review_t = 0;
                  $price_t = 0;
                  $value_t = 0;
                  $sub_total = 0;
                  $count = count($mainproreviews);
                  $onlyrev = array();

                  $reviewcount = App\UserReview::where('pro_id', $pro->id)->where('status', "1")->WhereNotNull('review')->count();

                  foreach ($mainproreviews as $review) {
                      $review_t = $review->qty * 5;
                      $price_t = $review->price * 5;
                      $value_t = $review->value * 5;
                      $sub_total = $sub_total + $review_t + $price_t + $value_t;
                  }

                  $count = ($count * 3) * 5;

                  if ($count != "") {
                    $rat = $sub_total / $count;

                    $ratings_var = ($rat * 100) / 5;

                    $overallrating = ($ratings_var / 2) / 10;
                  }

                  ?>

                 

                  @php
                    $count = 0;
                  @endphp

                  @if(isset($overallrating))

                   @if(isset($ratings_var))
                    <div class="pull-left">
                      <div class="star-ratings-sprite">
                        <span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span>
                      </div>
                    </div>
                  @endif

                  <div class="pull-left">
                    <div class="reviews-rating">
                     {{ round($overallrating,1)}} <i class="fa fa-star" aria-hidden="true"></i>
                    </div>
                  </div>

                  <div class="margin-left25">
                    <div class="reviews">
                      <a href="{{ route('allreviews',$pro->id) }}" class="lnk">&nbsp;&nbsp;{{  $count =  count($mainproreviews) }} {{ __('ratings and') }} {{ $reviewcount }} {{ __('reviews') }}</a>
                    </div>
                  </div>

                  <p></p>
                  @else
                  <div class="pull-left">

                   <i class="color147ED2 fa fa-star-half-o" aria-hidden="true"></i> {{ __('staticwords.ratetext') }}
                  </div>
                  <br>
                  <p></p>
                  @endif

                  @if($pro->w_d !='None' && $pro->w_my !='None' && $pro->w_type !='None')

                   <p > <i class="color147ED2 fa fa-refresh" aria-hidden="true"></i> {{$pro->w_d}} {{ ucfirst($pro->w_my) }} {{ __('staticwords.of') }} {{ $pro->w_type }}</p>
                  @endif

                  <p><i class="color147ED2 fa fa-handshake-o" aria-hidden="true"></i>  Trust of <b> {{$pro->brand->name}}</b>
                   @if($image = @file_get_contents('images/brands/'.$pro->brand->image))
                    <img class="pro-brand" src="{{ url('/images/brands/'.$pro->brand->image) }}">
                   @else
                    <img class="pro-brand" src="{{ Avatar::create($pro->brand->name)->toBase64() }}">
                   @endif
                  </p>

                  @if($pro->selling_start_at <= date("Y-m-d H:i:s"))

                  @else

                   <h3>{{ __('ComingSoon') }}</h3>

                  @endif

                </div>

                <div class="col-md-4 col-xs-12 col-sm-12">
                 
                    <div id="qbc" class="quantity-block">
                      
                      <div class="quantity-top" style="
                        font-size: 36px;
                        margin-bottom: 10px;
                        color: #fff;" 
                      ><span id="demo"></span></div>
                      {{-- {{$pro->is_bid==1}} --}}
                      <div class="quantity-heading">{{ __('staticwords.bid') }}</div>
                      <div class="qty-parent-cont">
                        <div class="quantity-container info-container">
                          <div class="row">
                            <div class="qty-count">
                              <div class="cart-quantity">
                                <div class="quant-input">



                                </div>
                              </div>
                            </div>
                         
                            <div   class="add-btn">

                            </div>
                          
                            </div><!-- /.row -->
                        </div>
                      </div>
                    </div>
                  </div>

                 </div><!-- /.row -->
              </div><!-- /.stock-container -->



              <div class="rating-reviews">


              <div class="price-container info-container">
                <div class="row">


                  <div class="col-sm-6 col-xs-12 col-md-8">
                    <div class="price-box price-box-border">
                      <span class="price price-main">
                        {{$MaxBid}}
                      </span>
                      <span class="margin-top-15 font-size-20 price-strike price-strike-main dtl-price-strike-main"></span>

                    &nbsp;<i data-toggle="tooltip" data-placement="left" title="{{ $pro->tax_r =='' ? __('Taxes Not Included') : __('Taxes Included') }}" class="color111 fa fa-info-circle"></i>
                    </div>
                     @if($pro->offer_price != 0)
                      <div class="progress-text"></div>
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>

                    @endif
                  </div>

                  <!-- close-->
                  <div class="col-sm-6 col-xs-12 col-md-4">
                      <div class="favorite-button header-nav-screen">

                        <a class="btn btn-primary" data-toggle="modal" data-placement="right" title="Share" data-target="#sharemodal">
                          
                          <i class="text-white fa fa-share-alt" aria-hidden="true"></i>

                        </a>

                      <div class="modal fade" id="sharemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                           
                            <div class="share-content modal-body">
                              
                            </div>
                            
                          </div>
                        </div>
                      </div>

                        <span class="favorite-button-box">
                          
                        </span>
                       
                         @php
                          $m=0;
                         @endphp
                        @if(!empty(Session::get('comparison')))

              @foreach(Session::get('comparison') as $p)

                @if($p['proid'] == $pro->id)
                  @php
                    $m = 1;
                    break;
                  @endphp
                @else
                  @php
                    $m = 0;
                  @endphp
                @endif
              @endforeach
                        @endif
                        
                        @if($m == 0)
                          <a class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Add to Compare" href="{{ route('compare.product',$pro->id) }}">
                             <i class="fa fa-signal"></i>
                          </a>
                        @else
                          <a class="abg btn btn-primary" data-toggle="tooltip" data-placement="right" title="Remove From Compare List" href="{{ route('remove.compare.product',$pro->id) }}">
                             <i class="fa fa-signal"></i>
                          </a>
                        @endif
                       
                      </div>
                    </div>

                </div>

                 
              </div><!-- /.price-container -->

              <div class="dc row">
                @if($pincodesystem == 1)
                <div class="col-lg-7 col-sm-6 col-xs-6 description-container">
                  <p></p>
                   <div class="delivery-location  description-heading"><img src="{{url('/images/shopping-bag.png')}}">{{ __('staticwords.deliverytext') }}</div>


                    <form id="myForm" method="post">
                      {{csrf_field()}}
                      <div class="form-group">

                      <div class="input-group mb-3">
                        <input placeholder="{{ __('Enter Pincode') }}" required class="pincode-input form-control" onchange="SubmitFormData()" type="text" id="deliveryPinCode" value="">
                        <div class="input-group-append">
                          <span class="input-group-text" id="basic-addon2">
                            <i id="marker-map" class="fa fa-map-marker"></i>
                          </span>
                        </div>
                      </div>

                      <span id="pincodeResponce"></span>
                      </div>
                    </form>

                </div>
                 @endif

                 <div class="col-sm-6 col-xs-6 d-block d-sm-none">
                    <b>{{ __('staticwords.Share') }}</b> : <div class="share-content"></div>
                 </div>
                <div class="{{ $pincodesystem == 1 ? "col-md-5" : "col-md-8" }} description-container ">
                  <p></p>
                  <div class="description-heading">{{ __('staticwords.otherservices') }}</div>
                  <div class="price-container info-container">
                      <div class="delivery-detail text-center">
                            <div class="row">
                              @if($pro->codcheck == 1)
                              <div class="col-lg-3 col-4">
                                <div class="image">
                                  <img src="{{url('/images/icon-cod.png')}}" class="img-fluid" alt="img">
                                </div>
                                <div class="detail text-center">{{ __('staticwords.podtext') }}</div>
                              </div>
                              @endif
                               @if($pro->return_avbl == 1)
                              <div class="col-lg-3 col-4">
                                <div data-toggle="modal" data-target="#returnmodal" class="image">
                                  <img src="{{url('/images/icon-returns.png')}}" class="img-fluid" alt="img">
                                </div>
                                <div class="detail">{{ $pro->returnPolicy->days }} {{ __('staticwords.returndays') }}</div>
                              </div>

                                                      <!-- Modal -->
                            <div class="modal fade" id="returnmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h5 class="modal-title" id="myModalLabel">{{ $pro->returnPolicy->name }}</h5>
                                  </div>
                                  <div class="modal-body">
                                    {{ $pro->returnPolicy->des }}
                                  </div>

                                </div>
                              </div>
                            </div>
                            @else
                             <div class="col-lg-3 col-4">
                                <div data-toggle="modal" data-target="#returnmodal" class="image">
                                  <img src="{{url('/images/icon-returns.png')}}" class="img-fluid" alt="img">
                                </div>
                                <div class="detail">{{ __('staticwords.noreturn') }}</div>
                              </div>
                              @endif
                              @if($pro->free_shipping == 1)
                              <div class="col-lg-4 col-4">
                                <div class="image">
                                  <img src="{{url('/images/icon-delivered.png')}}" class="img-fluid" alt="img">
                                </div>
                                <div class="detail">{{config('app.name')}} {{ __('staticwords.freedelivery') }}</div>
                              </div>
                              @endif
                            </div>
                      </div>
                  </div>
                </div>
              </div>

               <hr>

              <div class="delivery-container">
                <div class="row">
                
           
                
                  <div class="col-lg-6">
                


                     <div class="var-box">

                    
                        @if(isset($pro->commonvars))

                        <table border="0" class="width100">
                          @foreach($pro->commonvars as $cvar)
                          @php
                              $attrkey = "_";
                          @endphp
                            <tr>
                              <td width="20%">
                                @if (strpos($cvar->attribute->attr_name, $attrkey) == false)

                                <span class="font-size-14 font-weight-bold">{{ $cvar->attribute->attr_name }}</span>

                                @else
                              
                                <span class="font-size-14 font-weight-bold">{{ str_replace('_', ' ', $cvar->attribute->attr_name) }}</span>
                                
                                @endif
                              </td>
                              <td width="20%">
                                @if($cvar->attribute->attr_name == "Color" || $cvar->attribute->attr_name == "color" || $cvar->attribute->attr_name == "Colour" || $cvar->attribute->attr_name == "colour")
                                

                                  <div class="inline-flex left-minus-10">
                                    <div class="color-options">
                                      <ul>
                                        <li data-toggle="tooltip" data-placement="auto" title="{{ $cvar->provalues->values }}"
                                          class="color varcolor active"><a href="#" title=""><i
                                              style="color: {{ $cvar->provalues->unit_value }}"
                                              class="fa fa-circle"></i></a>
                                          <div class="overlay-image overlay-deactive">
                                          </div>
                                        </li>
                                      </ul>
                                    </div>
                                  </div>

                                @else

                               
                                @if(strcasecmp($cvar->provalues->values, $cvar->provalues->unit_value) !=0 && $cvar->provalues->unit_value != null)
                                  <span data-toggle="tooltip" data-placement="auto" title="{{ $cvar->provalues->values }} {{ $cvar->provalues->unit_value }}" class="commonvar font-weight-bold">{{ $cvar->provalues->values }} {{ $cvar->provalues->unit_value }}</span>
                                @else
                                  <span data-toggle="tooltip" data-placement="top" title="{{ $cvar->provalues->values }}" class="commonvar font-weight-bold">{{ $cvar->provalues->values }}</span>
                                @endif
                           

                                @endif
                              </td>
                            </tr>
                          @endforeach
                        </table>
                        @endif

                   @php

                    $indexNum = 0;

                   @endphp

                   <div class="full_var_box">

                    <div class="table-responsive">
                      <table border="0" class="width100">
                        @foreach($pro->variants as $key=> $mainvariant)
                          <tr>
                            <td width="33%">
                              @php 

                                $getattrname = App\ProductAttributes::where('id','=',$mainvariant->attr_name)->first();

                              @endphp

                              <span id="Size" class="font-weight-bold">
                                <label class="atrbName" indexnum="{{$indexNum}}" id="{{ $getattrname->id }}" value="{{ $getattrname->attr_name }}">
                                    @php
                                      $k = '_'; 
                                    @endphp
                                    @if (strpos($getattrname->attr_name, $k) == false)
                                      {{ $getattrname->attr_name }}
                                    @else
                                      {{str_replace('_', ' ',$getattrname->attr_name)}}
                                    @endif
                                </label>
                              </span>
                            </td>
                            <td>
                             
                              @foreach($mainvariant->attr_value as $subvalue)
                    
                                @php
                                  $getvaluename = App\ProductValues::where('id','=',$subvalue)->first();
                                @endphp
        
                                @foreach($pro->subvariants as $key=> $ss)
                                 
                                    @if($ss->main_attr_value[$getattrname->id] == $getvaluename->id)
        
                                    @if($getvaluename->proattr->attr_name == "Color")
                                  
                                     
                                     <a class="mainvar font-weight-bold xyz" 
                                       data-toggle="tooltip" 
                                       data-placement="top" 
                                       @if(strcasecmp($getvaluename->values, $getvaluename->unit_value) !=0) 
                                        title="{{ $getvaluename->values }}"
                                       @else
                                        title="{{ $getvaluename->values }}"
                                       @endif
                                       attr_id="{{ $getattrname->id }}"
                                       @if(strcasecmp($getvaluename->values, $getvaluename->unit_value) !=0)
                                         @if($getvaluename->proattr->attr_name == "Color")
                                          valname="{{ $getvaluename->values }}"
                                         @else
                                          valname="{{ $getvaluename->values }}{{ $getvaluename->unit_value }}"
                                         @endif
                                       @else
                                        valname="{{ $getvaluename->values }}"
                                       @endif
                                       val="{{ $getvaluename->id }}"
                                       name="{{ $getattrname->attr_name }}"
                                       s="0"
                                       id="{{ $getattrname->attr_name }}{{ $getvaluename->id }}"
                                       onclick="tagfilter('{{ $getattrname->attr_name }}','{{ $getvaluename->id }}', '{{$indexNum}}')"
                                       >
                
                                       
                                             <span class="ankit"><i style="color:{{ $getvaluename->unit_value }}" class="fa fa-circle"></i></span>
                                       
                                       
                                      </a>
        
                                    @else
        
                                    
                                         <a class="mainvar font-weight-bold xyz" data-toggle="tooltip" data-placement="top" @if(strcasecmp($getvaluename->values, $getvaluename->unit_value) !=0)  title="{{ $getvaluename->values }} {{ $getvaluename->unit_value }}" @else title="{{ $getvaluename->values }}" @endif attr_id="{{ $getattrname->id }}" @if(strcasecmp($getvaluename->values, $getvaluename->unit_value) !=0) @if($getvaluename->proattr->attr_name == "Color") valname="{{ $getvaluename->values }}" @else valname="{{ $getvaluename->values }}{{ $getvaluename->unit_value }}" @endif @else valname="{{ $getvaluename->values }}" @endif val="{{ $getvaluename->id }}" name="{{ $getattrname->attr_name }}" s="0" id="{{ $getattrname->attr_name }}{{ $getvaluename->id }}" onclick="tagfilter('{{ $getattrname->attr_name }}','{{ $getvaluename->id }}', '{{$indexNum}}')">
                
                                        @if(strcasecmp($getvaluename->values, $getvaluename->unit_value) !=0 && $getvaluename->unit_value != null)
                                          {{ $getvaluename->values }} {{ $getvaluename->unit_value }}
                                        @else
                                             {{ $getvaluename->values }}
                                        @endif
                                       
                                      </a>
        
                                    @endif
        
                                  
                                    @endif
        
                                @endforeach
                                @endforeach
                            </td>
                          </tr>
                        @endforeach
                      </table>
                    </div>
                      
                     
         
                   </div>

                </div>


                  </div>

                </div>
                <hr>
</div><!-- /.delivery-container -->

<!-- ============================ small-screen start ========================================= -->

<!-- ============================ small-screen end ========================================= -->
@if(isset($pro->key_features))
              <div class="description-container">
                <div class="description-heading">{{ __('staticwords.Highlight') }}</div>
                <div class="description-list">
                  {!! $pro->key_features !!}
                </div>
                <div class="report-text"><a href="#reportproduct" data-toggle="modal" title=""><img src="{{url('/images/comment.png')}}">{{ __('staticwords.rprotext') }}.</a></div>
              </div><!-- /.description-container -->
  @endif
              <br>

            </div><!-- /.product-info -->
          </div><!-- /.col-sm-7 -->

        </div><!-- /.row -->
      </div>
</div>
<!-- ============================================== full-screen tab start ============================================== -->
<!-- ==============================================    ============================================== -->     
<br>
<div class="product-feature">
  <div class="fast-delivery-block-block">
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="fast-delivery-block">
              <i class="fa fa-truck"></i>
              <div class="delivery-heading">{{ __('staticwords.FastDelivery') }}</div>
              <p>{{ __('staticwords.fastdtext') }}.</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="fast-delivery-block">
              <i class="fa fa-cubes" aria-hidden="true"></i>
              <div class="delivery-heading">{{ __('staticwords.QualityAssurance') }}</div>
              <p>{{ __('staticwords.With') }} {{ config('app.name') }} {{ __('staticwords.qtext') }}.</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="fast-delivery-block">
              <i class="fa fa-money"></i>
              <div class="delivery-heading">{{ __('staticwords.PurchaseProtection') }}</div>
              <p>{{ __('staticwords.PayementGatewaytext') }}</p>
            </div>
          </div>
        </div>
      </div>
</div>   

<div>
  <div id="product-tabs" class="product-tabs inner-bottom-xs display-none-block">
    
<div class="nav-tabs-custom">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist" id="myTabs">
    <li role="presentation" class="nav-item"><a class="nav-link active" href="#productdescription" aria-controls="productdescription" role="tab" data-toggle="tab">{{ __('staticwords.Description') }}</a></li>
    <li class="nav-item" role="presentation"><a class="nav-link" href="#prospecs" aria-controls="prospecs" role="tab" data-toggle="tab">{{ __('staticwords.prospecs') }}</a></li>
    <li class="nav-item" role="presentation"><a class="nav-link" href="#proreviews" aria-controls="proreviews" role="tab" data-toggle="tab">{{ __('staticwords.reviewratetext') }}</a></li>
    <li class="nav-item" role="presentation"><a class="nav-link" href="#procomments" aria-controls="procomments" role="tab" data-toggle="tab"><span id="countComment">{{ count($pro->comments) }}</span> {{ __('staticwords.Comments') }}</a></li>
    <li class="nav-item" role="presentation"><a class="nav-link" href="#profaqs" aria-controls="profaqs" role="tab" data-toggle="tab">{{ __('staticwords.faqs') }}</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in show active" id="productdescription">
      <br>
      @if($pro->des != '')
        {!! $pro->des !!}
      @else
        {{ __('No Description') }}
      @endif
      <hr>
      <p><b>{{ __('Tags') }}:</b> 
      @php
        $x = explode(',', $pro->tags);
      @endphp 
      @foreach($x as $tag)
        <span class="badge badge-secondary"><i class="fa fa-tags"></i> {{ $tag }}</span>
      @endforeach</p>
    </div>

    <div role="tabpanel" class="fade tab-pane" id="prospecs">
      <br>
        @if(count($pro->specs)>0)
      
              <div class="row">
                @foreach($pro->specs as $spec)
                  
                  <div class="col-md-3 keycol">
                    <b>{{ $spec->prokeys }}</b>
                  </div>
                  
                  <div class="col-md-9 keyval">
                    {{ $spec->provalues }}
                  </div> 
                      
                @endforeach
              </div>
        @else
          <h4>
            {{ __('No Specifications') }}
          </h4>
        @endif
     
    </div>

    <div role="tabpanel" class="fade tab-pane" id="proreviews">
      <br>
         @auth

                @php
                  $purchased = App\Order::where('user_id',Auth::user()->id)->get();
                  $findproinorder = 0;
                  $alreadyrated = App\UserReview::where('user',Auth::user()->id)->where('pro_id',$pro->id)->first();
                @endphp

                @if(isset($purchased))
                    @foreach($purchased as $value)
                        @if(in_array($pro->id, $value->main_pro_id))
                            @php
                              $findproinorder = 1;
                            @endphp
                         @endif
                    @endforeach
                @endif

                @if($findproinorder == 1)
                  @if(isset($alreadyrated))
                     
                 
                      <h5>
                        {{ __('Your Review') }}
                      </h5>
                      <hr>
                    <div class="row">

                       <div class="col-md-2">
                           @if($alreadyrated->users->image !='')
                            <img src="{{ url('/images/user/'.$alreadyrated->users->image) }}" alt="" class="img img-fluid rounded-circle">
                            @else
                            <img class="img img-fluid rounded-circle" src="{{ Avatar::create($alreadyrated->users->name)->toBase64() }}">
                            @endif
                       </div>

                       <div class="col-md-8">
                          <p>
                            <b><i>{{ $alreadyrated->users->name }}</i></b>
                            <small class="pull-right">On {{ date('jS M Y',strtotime($alreadyrated->created_at)) }}
                              @if($alreadyrated->status == 1)
                                <span class="badge badge-success font-weight-bold"><i class="fa fa-check" aria-hidden="true"></i> {{ __('Approved') }}</span>
                              @else
                                <span class="badge badge-success font-weight-bold"><i class="fa fa-info-circle" aria-hidden="true"></i> {{ __('Pending') }}</span>
                              @endif
                            </small>
                            <br>

                            <?php

                                $user_count = count([$alreadyrated]);
                                $user_sub_total = 0;
                                $user_review_t = $alreadyrated->price * 5;
                                $user_price_t = $alreadyrated->price * 5;
                                $user_value_t = $alreadyrated->value * 5;
                                $user_sub_total = $user_sub_total + $user_review_t + $user_price_t + $user_value_t;

                                $user_count = ($user_count * 3) * 5;
                                $rat1 = $user_sub_total / $user_count;
                                $ratings_var1 = ($rat1 * 100) / 5;

                                ?>
                          <div class="pull-left">
                              <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var1; ?>%" class="star-ratings-sprite-rating"></span>
                              </div>
                          </div>
                          <br>
                            <span class="font-weight500">{{ $alreadyrated->review }}</span>
                          </p>
                       </div>
                       
                     </div>
                      
                      <hr>
                     <a title="View all reviews" class="font-weight-bold pull-right" href="{{ route('allreviews',$pro->id) }}">{{ __('staticwords.vall') }}</a>
                     <h5 class="title">{{ __('staticwords.recReviews') }}</h5>

                     <hr>
                     
                     <div class="row">
                      
                      <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="overall-rating-main-block">
                          <div class="overall-rating-block text-center">
                            @php
                              if(!isset($overallrating)){
                                $overallrating = 0;
                              }
                            @endphp
                            <h1>{{ round($overallrating,1) }}</h1>
                            <div class="overall-rating-title">{{ __('staticwords.OverallRating') }}</div>
                            <div class="rating">
                                                 @php
                                                      $review_t = 0;
                                                      $price_t = 0;
                                                      $value_t = 0;
                                                      $sub_total = 0;
                                                      $sub_total = 0;
                                                      $reviews2 = App\UserReview::where('pro_id', $pro->id)->where('status', '1')->get();
                                                      @endphp @if(!empty($reviews2[0]))
                                                      @php
                                                      $count = App\UserReview::where('pro_id', $pro->id)->count();
                                                      foreach ($reviews2 as $review) {
                                                        $review_t = $review->price * 5;
                                                        $price_t = $review->price * 5;
                                                        $value_t = $review->value * 5;
                                                        $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                      }
                                                      $count = ($count * 3) * 5;
                                                      $rat = $sub_total / $count;
                                                      $ratings_var2 = ($rat * 100) / 5;
                                                    @endphp

                                                   
                                                      <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span></div>
                                                    

                                                     @else
                                                      <div class="text-center">
                                                        {{ __('No Rating') }}
                                                      </div>
                                                     @endif
                            </div>
                            <div class="total-review">{{$count =  count($mainproreviews)}} {{ __('Ratings &') }} {{$reviewcount}} {{ __('reviews') }}</div>
                          </div>
                          <div>
                            <div class="stat-levels">
                                <label>{{ __('staticwords.Quality') }}</label>
                                <div class="stat-1 stat-bar">
                                  <span class="stat-bar-rating" role="stat-bar" style="width: {{ $qualityprogress }}%;">{{ $qualityprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Price') }}</label>
                                <div class="stat-2 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-one" role="stat-bar" style="width: {{ $priceprogress }}%;">{{ $priceprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Value') }}</label>
                                <div class="stat-3 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-two" role="stat-bar" style="width: {{ $valueprogress }}%;">{{ $valueprogress }}%</span>
                                </div>
                            </div>
                          </div>
                          @if($overallrating>3.9)
                            <div class="overall-rating-block satisfied-customer-block text-center">
                              <h3>100%</h3>
                              <div class="overall-rating-title">{{ __('staticwords.SatisfiedCustomer') }}</div>
                              <p>{{ __('staticwords.customerText') }}.</p>
                            </div>
                          @endif
                        </div>
                      </div>

                       <div class="col-md-9">
                          <!-- All reviews will show here-->
                          @foreach($mainproreviews->take(5) as $review)

                             @if($review->status == "1")
                              <div class="row">

                                  <div class="col-md-2">
                                    @if($review->users->image !='')
                                    <img src="{{ url('/images/user/'.$review->users->image) }}" alt="" class="img rounded-circle img-fluid">
                                    @else
                                    <img class="img rounded-circle img-fluid" src="{{ Avatar::create($review->users->name)->toBase64() }}">
                                    @endif
                                  </div>



                                  <div class="col-md-10">
                                    <p>
                                      <b><i>{{ $review->users->name }}</i></b>
                                      <?php

                                        $user_count = count([$review]);
                                        $user_sub_total = 0;
                                        $user_review_t = $review->price * 5;
                                        $user_price_t = $review->price * 5;
                                        $user_value_t = $review->value * 5;
                                        $user_sub_total = $user_sub_total + $user_review_t + $user_price_t + $user_value_t;

                                        $user_count = ($user_count * 3) * 5;
                                        $rat1 = $user_sub_total / $user_count;
                                        $ratings_var1 = ($rat1 * 100) / 5;

                                        ?>
                                    <div class="pull-left">
                                        <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var1; ?>%" class="star-ratings-sprite-rating"></span>
                                        </div>
                                    </div>

                                      <small class="pull-right">{{ __('On') }} {{ date('jS M Y',strtotime($review->created_at)) }}</small>
                                      <br>
                                      <span class="font-weight500">{{ $review->review }}</span>
                                    </p>
                                  </div>

                              </div>
                              <hr>
                          @endif

                        @endforeach

                        
                                       <!--end-->
                       </div>
                     </div>


                  @else
                     <h5>{{ __('staticwords.ratereviewPurchase') }}</h5>
                      <hr>
                      @php
                       if(!isset($overallrating)){
                          $overallrating = 0;
                       }
                      @endphp
                     <div class="row">
                      <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="overall-rating-main-block">
                          <div class="overall-rating-block text-center">
                            <h1>{{ round($overallrating,1) }}</h1>
                            <div class="overall-rating-title">{{ __('staticwords.OverallRating') }}</div>
                            <div class="rating">
                                                 @php
                                                      $review_t = 0;
                                                      $price_t = 0;
                                                      $value_t = 0;
                                                      $sub_total = 0;
                                                      $sub_total = 0;
                                                      $reviews2 = App\UserReview::where('pro_id', $pro->id)->where('status', '1')->get();
                                                      @endphp @if(!empty($reviews2[0]))
                                                      @php
                                                      $count = App\UserReview::where('pro_id', $pro->id)->count();
                                                      foreach ($reviews2 as $review) {
                                                        $review_t = $review->price * 5;
                                                        $price_t = $review->price * 5;
                                                        $value_t = $review->value * 5;
                                                        $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                      }
                                                      $count = ($count * 3) * 5;
                                                      $rat = $sub_total / $count;
                                                      $ratings_var2 = ($rat * 100) / 5;
                                                    @endphp

                                                   
                                                      <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span></div>
                                                    

                                                     @else
                                                     <div class="text-center">
                                                       {{ __('No Rating') }}
                                                     </div>
                                                     @endif
                            </div>
                            <div class="total-review">{{$count =  count($mainproreviews)}} Ratings & {{$reviewcount}} reviews</div>
                          </div>
                          <div>
                           <div class="stat-levels">
                                <label>{{ __('staticwords.Quality') }}</label>
                                <div class="stat-1 stat-bar">
                                  <span class="stat-bar-rating" role="stat-bar" style="width: {{ $qualityprogress }}%;">{{ $qualityprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Price') }}</label>
                                <div class="stat-2 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-one" role="stat-bar" style="width: {{ $priceprogress }}%;">{{ $priceprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Value') }}</label>
                                <div class="stat-3 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-two" role="stat-bar" style="width: {{ $valueprogress }}%;">{{ $valueprogress }}%</span>
                                </div>
                            </div>
                          </div>
                          @if($overallrating>3.9)
                            <div class="overall-rating-block satisfied-customer-block text-center">
                              <h3>100%</h3>
                              <div class="overall-rating-title">{{ __('staticwords.SatisfiedCustomer') }}</div>
                              <p>{{ __('staticwords.customerText') }}.</p>
                            </div>
                          @endif
                        </div>
                      </div>

                       <div class="col-md-8 product-add-review">
                         <div class="review-table">
                            <div class="table-responsive">
                               <table class="table">
                                  <thead>
                                     <tr>
                                        <th class="cell-label">&nbsp;</th>
                                        <th>1 star</th>
                                        <th>2 stars</th>
                                        <th>3 stars</th>
                                        <th>4 stars</th>
                                        <th>5 stars</th>
                                     </tr>
                                  </thead>
                                  <form class="cnt-form" method="post" action="{{url('user_review/'.$pro->id)}}">
                                     {{csrf_field()}}
                                     <div class="required">{{$errors->first('quality')}}</div>
                                     <div class="required">{{$errors->first('Price')}}</div>
                                     <div class="required">{{$errors->first('Value')}}</div>
                                     <tbody>
                                        <tr>
                                           <td class="cell-label">{{ __('staticwords.Quality') }} <span class="required">*</span></td>
                                           <td><input type="radio" name="quality" class="radio" value="1"></td>
                                           <td><input type="radio" name="quality" class="radio" value="2"></td>
                                           <td><input type="radio" name="quality" class="radio" value="3"></td>
                                           <td><input type="radio" name="quality" class="radio" value="4"></td>
                                           <td><input type="radio" name="quality" class="radio" value="5"></td>
                                        </tr>
                                        <tr>
                                           <td class="cell-label">{{ __('staticwords.Price') }} <span class="required">*</span></td>
                                           <td><input type="radio" name="Price" class="radio" value="1"></td>
                                           <td><input type="radio" name="Price" class="radio" value="2"></td>
                                           <td><input type="radio" name="Price" class="radio" value="3"></td>
                                           <td><input type="radio" name="Price" class="radio" value="4"></td>
                                           <td><input type="radio" name="Price" class="radio" value="5"></td>
                                        </tr>
                                        <tr>
                                           <td class="cell-label">{{ __('staticwords.Value') }} <span class="required">*</span></td>
                                           <td><input type="radio" name="Value" class="radio" value="1"></td>
                                           <td><input type="radio" name="Value" class="radio" value="2"></td>
                                           <td><input type="radio" name="Value" class="radio" value="3"></td>
                                           <td><input type="radio" name="Value" class="radio" value="4"></td>
                                           <td><input type="radio" name="Value" class="radio" value="5"></td>
                                        </tr>
                                     </tbody>
                               </table>
                               <!-- /.table .table-bordered -->
                            </div>
                            <!-- /.table-responsive -->
                         </div>
                         <!-- /.review-table -->
                         <div class="review-form">
                         <div class="form-container">
                           <div class="row">
                           <div class="col-sm-6">
                           <div class="form-group">
                           <input type="hidden" class="form-control txt" id="exampleInputName" name="name" value="
                              @if(isset($auth)) {{$auth->id}} @endif" placeholder="">
                           <div class="text-red">{{$errors->first('name')}}</div>
                           </div>
                           </div>
                           <div class="col-md-12">
                           <div class="form-group">
                           <label class="margin-left15" for="exampleInputReview">{{ __('staticwords.Review') }}:</label>
                           <textarea class="form-control text-rev" name="review" id="exampleInputReview" rows="5" cols="50" placeholder=""></textarea>
                           </div>
                           </div>
                           </div><!-- /.row -->
                           <div class="action text-right">
                           <button class="btn btn-primary btn-upper">{{ __('staticwords.SUBMITREVIEW') }}</button>
                           </div><!-- /.action -->
                         </form><!-- /.cnt-form -->
                         </div><!-- /.form-container -->
                         </div><!-- /.review-form -->
                       </div>
                     </div>
                      <!-- /.product-add-review -->
                    <h5>{{ __('staticwords.recReviews') }}</h5>

                    <hr>
                    
                    @if(count($mainproreviews)>0)
                        @foreach($mainproreviews->take(5) as $review)

                             @if($review->status == "1")
                              <div class="row">

                                  <div class="col-md-1">
                                    @if($review->users->image !='')
                                    <img src="{{ url('/images/user/'.$review->users->image) }}" alt="" width="70px" height="70px" class="rounded-circle">
                                    @else
                                    <img width="70px" height="70px" src="{{ Avatar::create($review->users->name)->toBase64() }}" class="rounded-circle">
                                    @endif
                                  </div>

                                  <div class="col-md-10">
                                    <p>
                                      <b><i>{{ $review->users->name }}</i></b>
                                      <?php

                                        $user_count = count([$review]);
                                        $user_sub_total = 0;
                                        $user_review_t = $review->price * 5;
                                        $user_price_t = $review->price * 5;
                                        $user_value_t = $review->value * 5;
                                        $user_sub_total = $user_sub_total + $user_review_t + $user_price_t + $user_value_t;

                                        $user_count = ($user_count * 3) * 5;
                                        $rat1 = $user_sub_total / $user_count;
                                        $ratings_var1 = ($rat1 * 100) / 5;

                                        ?>
                                    <div class="pull-left">
                                        <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var1; ?>%" class="star-ratings-sprite-rating"></span>
                                        </div>
                                    </div>

                                      <small class="pull-right">{{ __('On') }} {{ date('jS M Y',strtotime($review->created_at)) }}</small>
                                      <br>
                                      <span class="font-weight500">{{ $review->review }}</span>
                                    </p>
                                  </div>

                              </div>
                              <hr>
                            @endif

                        @endforeach
                    @else
                      <h5><i class="fa fa-star"></i> {{ __('staticwords.ratetext') }}</h5>
                    @endif

                  @endif
                @else
                  <h5>{{ __('staticwords.purchaseProText') }}</h5>
                  <hr>
                  <h5>{{ __('staticwords.recReviews') }}</h5>
                  <hr>
                   @if(count($mainproreviews)>0)

                   @if(!isset($overallrating))
                       @php
                          $overallrating = 0;
                       @endphp
                  @endif
                      <div class="row">
                    
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="overall-rating-main-block">
                          <div class="overall-rating-block text-center">
                            <h1>{{ round($overallrating,1) }}</h1>
                            <div class="overall-rating-title">{{ __('staticwords.OverallRating') }}</div>
                            <div class="rating">
                                                 @php
                                                      $review_t = 0;
                                                      $price_t = 0;
                                                      $value_t = 0;
                                                      $sub_total = 0;
                                                      $sub_total = 0;
                                                      $reviews2 = App\UserReview::where('pro_id', $pro->id)->where('status', '1')->get();
                                                      @endphp @if(!empty($reviews2[0]))
                                                      @php
                                                      $count = App\UserReview::where('pro_id', $pro->id)->count();
                                                      foreach ($reviews2 as $review) {
                                                        $review_t = $review->price * 5;
                                                        $price_t = $review->price * 5;
                                                        $value_t = $review->value * 5;
                                                        $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                      }
                                                      $count = ($count * 3) * 5;
                                                      $rat = $sub_total / $count;
                                                      $ratings_var2 = ($rat * 100) / 5;
                                                    @endphp

                                                   
                                                      <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span></div>
                                                    

                                                     @else
                                                     <div class="text-center">
                                                       {{ __('No Rating') }}
                                                     </div>
                                                     @endif
                            </div>
                            <div class="total-review">{{$count =  count($mainproreviews)}} Ratings & {{$reviewcount}} {{ __('reviews') }}</div>
                          </div>
                          <div>
                            <div class="stat-levels">
                                <label>{{ __('staticwords.Quality') }}</label>
                                <div class="stat-1 stat-bar">
                                  <span class="stat-bar-rating" role="stat-bar" style="width: {{ $qualityprogress }}%;">{{ $qualityprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Price') }}</label>
                                <div class="stat-2 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-one" role="stat-bar" style="width: {{ $priceprogress }}%;">{{ $priceprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Value') }}</label>
                                <div class="stat-3 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-two" role="stat-bar" style="width: {{ $valueprogress }}%;">{{ $valueprogress }}%</span>
                                </div>
                            </div>
                          </div>
                          @if($overallrating>3.9)
                            <div class="overall-rating-block satisfied-customer-block text-center">
                              <h3>100%</h3>
                              <div class="overall-rating-title">{{ __('staticwords.SatisfiedCustomer') }}</div>
                              <p>{{ __('staticwords.customerText') }}</p>
                            </div>
                          @endif
                        </div>
                    </div>

                     <div class="col-md-9">
                       @foreach($mainproreviews->take(5) as $review)

                         @if($review->status == "1")
                          <div class="row">

                              <div class="col-md-2">
                                @if($review->users->image !='')
                                <img src="{{ url('/images/user/'.$review->users->image) }}" alt="" class="img rounded-circle img-fluid">
                                @else
                                <img class="img rounded-circle img-fluid" src="{{ Avatar::create($review->users->name)->toBase64() }}">
                                @endif
                              </div>

                              <div class="col-md-10">
                                <p>
                                  <b><i>{{ $review->users->name }}</i></b>
                                  <?php

                                    $user_count = count([$review]);
                                    $user_sub_total = 0;
                                    $user_review_t = $review->price * 5;
                                    $user_price_t = $review->price * 5;
                                    $user_value_t = $review->value * 5;
                                    $user_sub_total = $user_sub_total + $user_review_t + $user_price_t + $user_value_t;

                                    $user_count = ($user_count * 3) * 5;
                                    $rat1 = $user_sub_total / $user_count;
                                    $ratings_var1 = ($rat1 * 100) / 5;

                                  ?>
                                <div class="pull-left">
                                    <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var1; ?>%" class="star-ratings-sprite-rating"></span>
                                    </div>
                                </div>

                                  <small class="pull-right">{{ __('On') }} {{ date('jS M Y',strtotime($review->created_at)) }}</small>
                                  <br>
                                  <span class="font-weight500">{{ $review->review }}</span>
                                </p>
                              </div>

                          </div>
                          <hr>
                        @endif

                    @endforeach
                     </div>
                   </div>
                   @else
                      <h5><i class="fa fa-star"></i> {{ __('staticwords.ratetext') }}</h5>
                   @endif
                @endif
          
         @else
          <h5>{{ __('staticwords.Please') }} <a href="{{ route('login') }}">{{ __('staticwords.Login') }}</a> {{ __('staticwords.toratethisproduct') }}</h5>

          @if(count($mainproreviews)>0)
            <hr>
            <h5>{{ __('staticwords.recReviews') }}</h5>

            <hr>
              <div class="row">
                     
                     <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="overall-rating-main-block">
                          <div class="overall-rating-block text-center">
                            <h1>{{ round($overallrating,1) }}</h1>
                            <div class="overall-rating-title">{{ __('staticwords.OverallRating') }}</div>
                            <div class="rating">
                                                 @php
                                                      $review_t = 0;
                                                      $price_t = 0;
                                                      $value_t = 0;
                                                      $sub_total = 0;
                                                      $sub_total = 0;
                                                      $reviews2 = App\UserReview::where('pro_id', $pro->id)->where('status', '1')->get();
                                                      @endphp @if(!empty($reviews2[0]))
                                                      @php
                                                      $count = App\UserReview::where('pro_id', $pro->id)->count();
                                                      foreach ($reviews2 as $review) {
                                                        $review_t = $review->price * 5;
                                                        $price_t = $review->price * 5;
                                                        $value_t = $review->value * 5;
                                                        $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                      }
                                                      $count = ($count * 3) * 5;
                                                      $rat = $sub_total / $count;
                                                      $ratings_var2 = ($rat * 100) / 5;
                                                    @endphp

                                                   
                                                      <div class="star-ratings-sprite">
                                                        <span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span>
                                                      </div>
                                                    

                                                     @else
                                                     <div class="text-center">
                                                       {{ __('No Rating') }}
                                                     </div>
                                                     @endif
                            </div>
                            <div class="total-review">{{$count =  count($mainproreviews)}} Ratings & {{$reviewcount}} reviews</div>
                          </div>
                          <div>
                           <div class="stat-levels">
                                <label>{{ __('staticwords.Quality') }}</label>
                                <div class="stat-1 stat-bar">
                                  <span class="stat-bar-rating" role="stat-bar" style="width: {{ $qualityprogress }}%;">{{ $qualityprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Price') }}</label>
                                <div class="stat-2 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-one" role="stat-bar" style="width: {{ $priceprogress }}%;">{{ $priceprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Value') }}</label>
                                <div class="stat-3 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-two" role="stat-bar" style="width: {{ $valueprogress }}%;">{{ $valueprogress }}%</span>
                                </div>
                            </div>
                          </div>
                          @if($overallrating>3.9)
                            <div class="overall-rating-block satisfied-customer-block text-center">
                              <h3>100%</h3>
                              <div class="overall-rating-title">{{ __('staticwords.SatisfiedCustomer') }}</div>
                              <p>{{ __('staticwords.customerText') }}</p>
                            </div>
                          @endif
                        </div>
                    </div>

                     <div class="col-md-9">
                       @foreach($mainproreviews->take(5) as $review)

                         @if($review->status == "1")
                          <div class="row">

                              <div class="col-md-2">
                                @if($review->users->image !='')
                                <img src="{{ url('/images/user/'.$review->users->image) }}" alt="" class="img rounded-circle img-fluid">
                                @else
                                <img class="img rounded-circle img-fluid" src="{{ Avatar::create($review->users->name)->toBase64() }}">
                                @endif
                              </div>

                              <div class="col-md-10">
                                <p>
                                  <b><i>{{ $review->users->name }}</i></b>
                                  <?php

                                    $user_count = count([$review]);
                                    $user_sub_total = 0;
                                    $user_review_t = $review->price * 5;
                                    $user_price_t = $review->price * 5;
                                    $user_value_t = $review->value * 5;
                                    $user_sub_total = $user_sub_total + $user_review_t + $user_price_t + $user_value_t;

                                    $user_count = ($user_count * 3) * 5;
                                    $rat1 = $user_sub_total / $user_count;
                                    $ratings_var1 = ($rat1 * 100) / 5;

                                    ?>
                                <div class="pull-left">
                                    <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var1; ?>%" class="star-ratings-sprite-rating"></span>
                                    </div>
                                </div>

                                  <small class="pull-right">{{ __('On') }} {{ date('jS M Y',strtotime($review->created_at)) }}</small>
                                  <br>
                                  <span class="font-weight500">{{ $review->review }}</span>
                                </p>
                              </div>

                          </div>
                          <hr>
                        @endif

                    @endforeach
                     </div>
                   </div>
          @endif
         @endauth
    </div>

    <div role="tabpanel" class="fade tab-pane" id="procomments">
       
      
       <h3><i class="fa fa-commenting-o" aria-hidden="true"></i> {{ __('staticwords.RecentComments') }}</h3>
          <hr>
           <div class="appendComment">
              @if(count($pro->comments)>0)
                 @foreach($pro->comments->sortByDesc('id')->take(5) as $comment)
                              
                                @if($comment->approved == 1)
                                  
                                  
                                    <div class="row">
                                        <div class="col-lg-1">
                                            <div class="author-img">
                                               <img class="rounded-circle" width="70px" title="{{ $comment->name }}" src="{{ Avatar::create($comment->name)->toBase64() }}"/>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="author-discription">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="author-name"><a href="#" title="{{ $comment->name }}">{{ $comment->name }}</a></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="author-date text-right"><a href="#" title="{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</a></div>
                                                    </div>
                                                </div>
                                                <p>{!! $comment->comment !!}</p>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                

                                @endif
                                     
                              @endforeach
                              
                              @if(count($pro->comments)>5)
                              <p></p>
                              <div align="center" class="remove-row">
                                  <button data-proid="{{ $pro->id }}" data-id="{{ $comment->id }}" class="btn-more btn btn-info btn-sm" >{{ __('staticwords.LoadMore') }}</button>
                              </div>
                              <p></p>
                              
                              @endif

                              @endif
                              
            </div>
          
        
             <div class="card">
               <div class="card-header">
                  <h5 class="card-title">{{ __('staticwords.LeaveAComment') }}</h5>
                </div>

               <div class="card-body">
                  
                             <form id="comment_us">
                                {{csrf_field()}}

                                <div class="form-group">
                                   <label>{{ __('staticwords.Name') }}: <span class="required">*</span></label>
                                   <input required="" type="text" name="send" class="form-control col-md-7 col-xs-12">
                                   <input type="hidden" name="id" value="{{$pro->id}}">
                                   <span class="text-red">{{$errors->first('name')}}</span>
                                </div>
                                
                                <div class="form-group">
                                   <label>{{ __('staticwords.Email') }}: <span class="required">*</span></label>
                                  <input required="" type="email" name="email" class="form-control col-md-7 col-xs-12"> <span class="text-red">{{$errors->first('email')}}</span>
                                </div>
                                
                               <div class="form-group">
                                  <label>{{ __('staticwords.Comment') }}: <span class="required">*</span></label>
                                  <textarea required="" name="comment" rows="3" class="form-control col-md-7 col-xs-12" placeholder="{{ __('Please Enter Your Comment') }}"></textarea>
                                  <span class="text-red">{{$errors->first('comment')}}</span>
                               </div>
                            
                              <div class="form-group">
                                <button type="submit" id="submit-form" class="btn btn-primary">{{ __('staticwords.Submit') }}</button>
                              </div>

                              </form>
               </div>
             </div>
  
      </div>
      <div role="tabpanel" class="fade tab-pane" id="profaqs">
        @if(count($faqs)>0)
          @foreach($faqs as $qid => $fq)
            <h5>[Q.{{ $qid+1 }}] {{ $fq->question }}</h5>
            <p class="h6">{!! $fq->answer !!}</p>
            <hr>
          @endforeach
        @else
          {{ __('staticwords.NOFAQ') }}
        @endif
      </div>
    </div>
    </div>

  </div>



</div>

<!-- ============================================== small-screen tab  start ============================================== -->
<!-- ==============================================    ============================================== --> 
<div class="header-nav-smallscreen">
<div class="description-block">
  <h5 class="description-heading">{{ __('staticwords.Description') }}</h5>
  <div class="product-tab">
    <p class="text">{!! $pro->des !!}</p>
    <hr>
    
      <p><b>{{ __('staticwords.tags') }}:</b> 
      @php
        $x = explode(',', $pro->tags);
      @endphp 
      @foreach($x as $tag)
        <span class="badge badge-secondary"><i class="fa fa-tags"></i> {{ $tag }}</span>
      @endforeach</p>
    <!-- Product Specifications -->
      @if(count($pro->specs)>0)
       <h3>{{ __('staticwords.prospecs') }}</h3>
       <hr>
        <table class="width100" border="1">
          @foreach($pro->specs as $spec)
            <tbody>
              <tr>
                <td><b>{{ $spec->prokeys }}</b></td>
                <td>{{ $spec->provalues }}</td>
              </tr>
            </tbody>
          @endforeach
        </table>
      @endif
    <!--end-->
  </div>
</div>
<!-- =======================decription-end ================== -->

<div class="your-review-block">
    <h5 class="description-heading">{{ __('staticwords.YOURREVIEW') }}</h5>
    @auth
    @php
      $purchased = App\Order::where('user_id',Auth::user()->id)->get();
      $status = 0;
      $alreadyrated = App\UserReview::where('user',Auth::user()->id)->where('status','=','1')->where('pro_id',$pro->id)->first();

    @endphp
       @if(isset($purchased))
        @foreach($purchased as $value)

         @if(in_array($pro->id, $value->main_pro_id))
            @php
              $status = 1;
            @endphp
         @endif
        @endforeach
      @endif

    @if($status == 1)
   @if($alreadyrated)
    <!-- show user review-->
    <div class="row">

        <div class="col-md-2 col-3">
          @if($alreadyrated->users->image !='')
          <img src="{{ url('/images/user/'.$alreadyrated->users->image) }}" alt="" class="img-fluid">
          @else
          <img class="img-fluid" src="{{ Avatar::create($alreadyrated->users->name)->toBase64() }}">
          @endif
        </div>

        <div class="col-md-10 col-9">
          <p>
            <b><i>{{ $alreadyrated->users->name }}</i></b>
            <small class="pull-right">{{ __('On') }} {{ date('jS M Y',strtotime($alreadyrated->created_at)) }}
              @if($alreadyrated->status == 1)
                <span class="badge font-weight-bold badge-success"><i class="fa fa-check" aria-hidden="true"></i> 
                {{ __('Approved') }}</span>
              @else
                <span class="badge font-weight-bold badge-success"><i class="fa fa-info" aria-hidden="true"></i>
                  {{ __('Pending') }}
                </span>
              @endif
            </small>
            <br>
          <?php

              $user_count = count([$alreadyrated]);
              $user_sub_total = 0;
              $user_review_t = $alreadyrated->price * 5;
              $user_price_t = $alreadyrated->price * 5;
              $user_value_t = $alreadyrated->value * 5;
              $user_sub_total = $user_sub_total + $user_review_t + $user_price_t + $user_value_t;

              $user_count = ($user_count * 3) * 5;
              $rat1 = $user_sub_total / $user_count;
              $ratings_var1 = ($rat1 * 100) / 5;

          ?>
          <div class="pull-left">
              <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var1; ?>%" class="star-ratings-sprite-rating"></span>
              </div>
          </div>
          <br>
            <span class="font-weight500">{{ $alreadyrated->review }}</span>
          </p>
        </div>
    </div>
      <!--end-->
      @else
        <div class="product-tab">
          <div class="product-reviews">
            <h5 class="title">{{ __('staticwords.RateThisProduct') }}</h5>
                     <!-- /.reviews -->
          </div><!-- /.product-reviews -->

            <div class="product-add-review">
              <h5 class="title">{{ __('staticwords.Writeyourownreview') }}</h5>
              <div class="review-table">
                <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="cell-label">&nbsp;</th>
                          <th>1 {{ __('star') }}</th>
                          <th>2 {{ __('stars') }}</th>
                          <th>3 {{ __('stars') }}</th>
                          <th>4 {{ __('stars') }}</th>
                          <th>5 {{ __('stars') }}</th>
                        </tr>
                      </thead>
                  <form class="cnt-form" method="post" action="{{url('user_review/'.$pro->id)}}">
              {{csrf_field()}}
              <div class="required">{{$errors->first('quality')}}</div>
              <div class="required">{{$errors->first('Price')}}</div>
              <div class="required">{{$errors->first('Value')}}</div>
                <tbody>
                  <tr>
                    <td class="cell-label">{{ __('staticwords.Quality') }} <span class="required">*</span></td>
                    <td><input type="radio" name="quality" class="radio" value="1"></td>
                    <td><input type="radio" name="quality" class="radio" value="2"></td>
                    <td><input type="radio" name="quality" class="radio" value="3"></td>
                    <td><input type="radio" name="quality" class="radio" value="4"></td>
                    <td><input type="radio" name="quality" class="radio" value="5"></td>
                  </tr>
                  <tr>
                    <td class="cell-label">{{ __('staticwords.Price') }} <span class="required">*</span></td>
                    <td><input type="radio" name="Price" class="radio" value="1"></td>
                    <td><input type="radio" name="Price" class="radio" value="2"></td>
                    <td><input type="radio" name="Price" class="radio" value="3"></td>
                    <td><input type="radio" name="Price" class="radio" value="4"></td>
                    <td><input type="radio" name="Price" class="radio" value="5"></td>
                  </tr>
                  <tr>
                    <td class="cell-label">{{ __('staticwords.Value') }} <span class="required">*</span></td>
                    <td><input type="radio" name="Value" class="radio" value="1"></td>
                    <td><input type="radio" name="Value" class="radio" value="2"></td>
                    <td><input type="radio" name="Value" class="radio" value="3"></td>
                    <td><input type="radio" name="Value" class="radio" value="4"></td>
                    <td><input type="radio" name="Value" class="radio" value="5"></td>
                  </tr>
                </tbody>
              </table><!-- /.table .table-bordered -->
          </div><!-- /.table-responsive -->
        </div><!-- /.review-table -->

  <div class="review-form">
    <div class="form-container">
      <div class="row">
          <div class="col-sm-6">
            <div class="form-group">

              <input type="hidden" class="form-control txt" id="exampleInputName" name="name" value="
              @if(isset($auth)) {{$auth->id}} @endif" placeholder="">
              <div class="text-red">{{$errors->first('name')}}</div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label class="margin-left-0" for="exampleInputReview">Review:</label>
              <textarea class="form-control text-rev" name="review" id="exampleInputReview" rows="5" cols="50" placeholder=""></textarea>
            </div>

          </div>
        </div><!-- /.row -->

        <div class="action text-right">
          <button class="btn btn-primary btn-upper">
            {{ __('staticwords.SUBMITREVIEW') }}
          </button>
        </div><!-- /.action -->

      </form><!-- /.cnt-form -->
    </div><!-- /.form-container -->
  </div><!-- /.review-form -->

</div><!-- /.product-add-review -->

</div><!-- /.product-tab -->
@endif
@else
 <h3>{{ __('staticwords.purchaseProText') }}</h3>
@endif
@else
  <h3><a href="{{ route('login') }}">{{ __('staticwords.Login') }}</a> {{ __('staticwords.toratethisproduct') }}</h3>
@endif
</div><!-- /.tab-pane -->



<!-- =======================your-review-end ================== -->
<div class="review-block">
  
         @auth

                @php
                  $purchased = App\Order::where('user_id',Auth::user()->id)->get();
                  $findproinorder = 0;
                  $alreadyrated = App\UserReview::where('user',Auth::user()->id)->where('pro_id',$pro->id)->first();
                @endphp

                @if(isset($purchased))
                    @foreach($purchased as $value)
                        @if(in_array($pro->id, $value->main_pro_id))
                            @php
                              $findproinorder = 1;
                            @endphp
                         @endif
                    @endforeach
                @endif

                @if($findproinorder == 1)
                  @if(isset($alreadyrated))
                  
                     <a title="View all reviews" class="font-weight-bold pull-right" href="{{ route('allreviews',$pro->id) }}">{{ __('staticwords.vall') }}</a>
                     <h5 class="title">{{ __('staticwords.recReviews') }}</h5>

                     <hr>

                     <div class="row">
                      
                      <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="overall-rating-main-block">
                          <div class="overall-rating-block text-center">
                            @php
                              if(!isset($overallrating)){
                                $overallrating = 0;
                              }
                            @endphp
                            <h1>{{ round($overallrating,1) }}</h1>
                            <div class="overall-rating-title">{{ __('staticwords.OverallRating') }}</div>
                            <div class="rating">
                                                 @php
                                                      $review_t = 0;
                                                      $price_t = 0;
                                                      $value_t = 0;
                                                      $sub_total = 0;
                                                      $sub_total = 0;
                                                      $reviews2 = App\UserReview::where('pro_id', $pro->id)->where('status', '1')->get();
                                                      @endphp @if(!empty($reviews2[0]))
                                                      @php
                                                      $count = App\UserReview::where('pro_id', $pro->id)->count();
                                                      foreach ($reviews2 as $review) {
                                                        $review_t = $review->price * 5;
                                                        $price_t = $review->price * 5;
                                                        $value_t = $review->value * 5;
                                                        $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                      }
                                                      $count = ($count * 3) * 5;
                                                      $rat = $sub_total / $count;
                                                      $ratings_var2 = ($rat * 100) / 5;
                                                    @endphp

                                                   
                                                      <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span></div>
                                                    

                                                     @else
                                                     <div class="text-center">
                                                       {{'No Rating'}}
                                                     </div>
                                                     @endif
                            </div>
                            <div class="total-review">{{$count =  count($mainproreviews)}} Ratings & {{$reviewcount}} reviews</div>
                          </div>
                          <div>
                            <div class="stat-levels">
                                <label>{{ __('staticwords.Quality') }}</label>
                                <div class="stat-1 stat-bar">
                                  <span class="stat-bar-rating" role="stat-bar" style="width: {{ $qualityprogress }}%;">{{ $qualityprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Price') }}</label>
                                <div class="stat-2 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-one" role="stat-bar" style="width: {{ $priceprogress }}%;">{{ $priceprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Value') }}</label>
                                <div class="stat-3 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-two" role="stat-bar" style="width: {{ $valueprogress }}%;">{{ $valueprogress }}%</span>
                                </div>
                            </div>
                          </div>
                          @if($overallrating>3.9)
                            <div class="overall-rating-block satisfied-customer-block text-center">
                              <h3>100%</h3>
                              <div class="overall-rating-title">{{ __('staticwords.SatisfiedCustomer') }}</div>
                              <p>{{ __('staticwords.customerText') }}</p>
                            </div>
                          @endif
                        </div>
                      </div>

                       <div class="col-md-9">
                          <!-- All reviews will show here-->
                          @foreach($mainproreviews as $review)

                             @if($review->status == "1")
                              <div class="row">

                                  <div class="col-md-2 col-xs-3">
                                    @if($review->users->image !='')
                                    <img src="{{ url('/images/user/'.$review->users->image) }}" alt="" class="img img-responsive img-fluid">
                                    @else
                                    <img v src="{{ Avatar::create($review->users->name)->toBase64() }}">
                                    @endif
                                  </div>



                                  <div class="col-md-10 col-xs-9">
                                    <p>
                                      <b><i>{{ $review->users->name }}</i></b>
                                      <?php

                                        $user_count = count([$review]);
                                        $user_sub_total = 0;
                                        $user_review_t = $review->price * 5;
                                        $user_price_t = $review->price * 5;
                                        $user_value_t = $review->value * 5;
                                        $user_sub_total = $user_sub_total + $user_review_t + $user_price_t + $user_value_t;

                                        $user_count = ($user_count * 3) * 5;
                                        $rat1 = $user_sub_total / $user_count;
                                        $ratings_var1 = ($rat1 * 100) / 5;

                                        ?>
                                    <div class="pull-left">
                                        <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var1; ?>%" class="star-ratings-sprite-rating"></span>
                                        </div>
                                    </div>

                                      <small class="pull-right">On {{ date('jS M Y',strtotime($review->created_at)) }}</small>
                                      <br>
                                      <span class="font-weight500">{{ $review->review }}</span>
                                    </p>
                                  </div>

                              </div>
                              <hr>
                          @endif

                        @endforeach
                                       <!--end-->
                       </div>
                     </div>
                    

                  @else
                     <h5>{{ __('staticwords.ratereviewPurchase') }}</h5>
                      <hr>
                      @php
                       if(!isset($overallrating)){
                          $overallrating = 0;
                       }
                      @endphp
                     <div class="row">
                      <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="overall-rating-main-block">
                          <div class="overall-rating-block text-center">
                            <h1>{{ round($overallrating,1) }}</h1>
                            <div class="overall-rating-title">{{ __('staticwords.OverallRating') }}</div>
                            <div class="rating">
                                                 @php
                                                      $review_t = 0;
                                                      $price_t = 0;
                                                      $value_t = 0;
                                                      $sub_total = 0;
                                                      $sub_total = 0;
                                                      $reviews2 = App\UserReview::where('pro_id', $pro->id)->where('status', '1')->get();
                                                      @endphp @if(!empty($reviews2[0]))
                                                      @php
                                                      $count = App\UserReview::where('pro_id', $pro->id)->count();
                                                      foreach ($reviews2 as $review) {
                                                        $review_t = $review->price * 5;
                                                        $price_t = $review->price * 5;
                                                        $value_t = $review->value * 5;
                                                        $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                      }
                                                      $count = ($count * 3) * 5;
                                                      $rat = $sub_total / $count;
                                                      $ratings_var2 = ($rat * 100) / 5;
                                                    @endphp

                                                   
                                                      <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span></div>
                                                    

                                                     @else
                                                     <div class="text-center">
                                                       {{'No Rating'}}
                                                     </div>
                                                     @endif
                            </div>
                            <div class="total-review">{{$count =  count($mainproreviews)}} Ratings & {{$reviewcount}} reviews</div>
                          </div>
                          <div>
                           <div class="stat-levels">
                                <label>{{ __('staticwords.Quality') }}</label>
                                <div class="stat-1 stat-bar">
                                  <span class="stat-bar-rating" role="stat-bar" style="width: {{ $qualityprogress }}%;">{{ $qualityprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Price') }}</label>
                                <div class="stat-2 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-one" role="stat-bar" style="width: {{ $priceprogress }}%;">{{ $priceprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Value') }}</label>
                                <div class="stat-3 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-two" role="stat-bar" style="width: {{ $valueprogress }}%;">{{ $valueprogress }}%</span>
                                </div>
                            </div>
                          </div>
                          @if($overallrating>3.9)
                            <div class="overall-rating-block satisfied-customer-block text-center">
                              <h3>100%</h3>
                              <div class="overall-rating-title">{{ __('staticwords.SatisfiedCustomer') }}</div>
                              <p>{{ __('staticwords.customerText') }}</p>
                            </div>
                          @endif
                        </div>
                      </div>

                       <div class="col-md-8 product-add-review">
                         <div class="review-table">
                            <div class="table-responsive">
                               <table class="table">
                                  <thead>
                                     <tr>
                                        <th class="cell-label">&nbsp;</th>
                                        <th>1 {{ __('star') }}</th>
                                        <th>2 {{ __('stars') }}</th>
                                        <th>3 {{ __('stars') }}</th>
                                        <th>4 {{ __('stars') }}</th>
                                        <th>5 {{ __('stars') }}</th>
                                     </tr>
                                  </thead>
                                  <form class="cnt-form" method="post" action="{{url('user_review/'.$pro->id)}}">
                                     {{csrf_field()}}
                                     <div class="required">{{$errors->first('quality')}}</div>
                                     <div class="required">{{$errors->first('Price')}}</div>
                                     <div class="required">{{$errors->first('Value')}}</div>
                                     <tbody>
                                        <tr>
                                           <td class="cell-label">{{ __('staticwords.Quality') }} <span class="required">*</span></td>
                                           <td><input type="radio" name="quality" class="radio" value="1"></td>
                                           <td><input type="radio" name="quality" class="radio" value="2"></td>
                                           <td><input type="radio" name="quality" class="radio" value="3"></td>
                                           <td><input type="radio" name="quality" class="radio" value="4"></td>
                                           <td><input type="radio" name="quality" class="radio" value="5"></td>
                                        </tr>
                                        <tr>
                                           <td class="cell-label">{{ __('staticwords.Price') }} <span class="required">*</span></td>
                                           <td><input type="radio" name="Price" class="radio" value="1"></td>
                                           <td><input type="radio" name="Price" class="radio" value="2"></td>
                                           <td><input type="radio" name="Price" class="radio" value="3"></td>
                                           <td><input type="radio" name="Price" class="radio" value="4"></td>
                                           <td><input type="radio" name="Price" class="radio" value="5"></td>
                                        </tr>
                                        <tr>
                                           <td class="cell-label">{{ __('staticwords.Value') }} <span class="required">*</span></td>
                                           <td><input type="radio" name="Value" class="radio" value="1"></td>
                                           <td><input type="radio" name="Value" class="radio" value="2"></td>
                                           <td><input type="radio" name="Value" class="radio" value="3"></td>
                                           <td><input type="radio" name="Value" class="radio" value="4"></td>
                                           <td><input type="radio" name="Value" class="radio" value="5"></td>
                                        </tr>
                                     </tbody>
                               </table>
                               <!-- /.table .table-bordered -->
                            </div>
                            <!-- /.table-responsive -->
                         </div>
                         <!-- /.review-table -->
                         <div class="review-form">
                         <div class="form-container">
                           <div class="row">
                           <div class="col-sm-6">
                           <div class="form-group">
                           <input type="hidden" class="form-control txt" id="exampleInputName" name="name" value="
                              @if(isset($auth)) {{$auth->id}} @endif" placeholder="">
                           <div class="text-red">{{$errors->first('name')}}</div>
                           </div>
                           </div>
                           <div class="col-md-12">
                           <div class="form-group">
                           <label class="margin-left15" for="exampleInputReview">{{ __('staticwords.Review') }} :</label>
                           <textarea class="form-control text-rev" name="review" id="exampleInputReview" rows="5" cols="50" placeholder=""></textarea>
                           </div>
                           </div>
                           </div><!-- /.row -->
                           <div class="action text-right">
                           <button class="btn btn-primary btn-upper">{{ __('staticwords.SUBMITREVIEW') }}</button>
                           </div><!-- /.action -->
                         </form><!-- /.cnt-form -->
                         </div><!-- /.form-container -->
                         </div><!-- /.review-form -->
                       </div>
                     </div>
                      <!-- /.product-add-review -->
                    <h5>{{ __('staticwords.recReviews') }}</h5>

                    <hr>
                    
                    @if(count($mainproreviews)>0)
                        @foreach($mainproreviews as $review)

                             @if($review->status == "1")
                              <div class="row">

                                  <div class="col-md-2">
                                    @if($review->users->image !='')
                                    <img src="{{ url('/images/user/'.$review->users->image) }}" alt="" class="img img-responsive img-fluid">
                                    @else
                                    <img class="img img-responsive img-fluid" src="{{ Avatar::create($review->users->name)->toBase64() }}">
                                    @endif
                                  </div>

                                  <div class="col-md-10">
                                    <p>
                                      <b><i>{{ $review->users->name }}</i></b>
                                      <?php

                                        $user_count = count([$review]);
                                        $user_sub_total = 0;
                                        $user_review_t = $review->price * 5;
                                        $user_price_t = $review->price * 5;
                                        $user_value_t = $review->value * 5;
                                        $user_sub_total = $user_sub_total + $user_review_t + $user_price_t + $user_value_t;

                                        $user_count = ($user_count * 3) * 5;
                                        $rat1 = $user_sub_total / $user_count;
                                        $ratings_var1 = ($rat1 * 100) / 5;

                                        ?>
                                    <div class="pull-left">
                                        <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var1; ?>%" class="star-ratings-sprite-rating"></span>
                                        </div>
                                    </div>

                                      <small class="pull-right">{{ __('On') }} {{ date('jS M Y',strtotime($review->created_at)) }}</small>
                                      <br>
                                      <span class="font-weight500">{{ $review->review }}</span>
                                    </p>
                                  </div>

                              </div>
                              <hr>
                            @endif

                        @endforeach
                    @else
                      <h5><i class="fa fa-star"></i> {{ __('staticwords.ratetext') }}</h5>
                    @endif

                  @endif
                @else
                  <h5>{{ __('staticwords.purchaseProText') }}</h5>
                  <hr>
                  <h5>{{ __('staticwords.recReviews') }}</h5>
                  <hr>
                   @if(count($mainproreviews)>0)

                   @if(!isset($overallrating))
                       @php
                          $overallrating = 0;
                       @endphp
                  @endif
                      <div class="row">
                    
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="overall-rating-main-block">
                          <div class="overall-rating-block text-center">
                            <h1>{{ round($overallrating,1) }}</h1>
                            <div class="overall-rating-title">{{ __('staticwords.OverallRating') }}</div>
                            <div class="rating">
                                                 @php
                                                      $review_t = 0;
                                                      $price_t = 0;
                                                      $value_t = 0;
                                                      $sub_total = 0;
                                                      $sub_total = 0;
                                                      $reviews2 = App\UserReview::where('pro_id', $pro->id)->where('status', '1')->get();
                                                      @endphp @if(!empty($reviews2[0]))
                                                      @php
                                                      $count = App\UserReview::where('pro_id', $pro->id)->count();
                                                      foreach ($reviews2 as $review) {
                                                        $review_t = $review->price * 5;
                                                        $price_t = $review->price * 5;
                                                        $value_t = $review->value * 5;
                                                        $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                      }
                                                      $count = ($count * 3) * 5;
                                                      $rat = $sub_total / $count;
                                                      $ratings_var2 = ($rat * 100) / 5;
                                                    @endphp

                                                   
                                                      <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span></div>
                                                    

                                                     @else
                                                     <div class="text-center">
                                                       {{ __('No Rating') }}
                                                     </div>
                                                     @endif
                            </div>
                            <div class="total-review">{{$count =  count($mainproreviews)}} Ratings & {{$reviewcount}} reviews</div>
                          </div>
                          <div>
                            <div class="stat-levels">
                                <label>{{ __('staticwords.Quality') }}</label>
                                <div class="stat-1 stat-bar">
                                  <span class="stat-bar-rating" role="stat-bar" style="width: {{ $qualityprogress }}%;">{{ $qualityprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Price') }}</label>
                                <div class="stat-2 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-one" role="stat-bar" style="width: {{ $priceprogress }}%;">{{ $priceprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Value') }}</label>
                                <div class="stat-3 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-two" role="stat-bar" style="width: {{ $valueprogress }}%;">{{ $valueprogress }}%</span>
                                </div>
                            </div>
                          </div>
                          @if($overallrating>3.9)
                            <div class="overall-rating-block satisfied-customer-block text-center">
                              <h3>100%</h3>
                              <div class="overall-rating-title">{{ __('staticwords.SatisfiedCustomer') }}</div>
                              <p>{{ __('staticwords.customerText') }}</p>
                            </div>
                          @endif
                        </div>
                    </div>

                     <div class="col-md-9">
                       @foreach($mainproreviews as $review)

                         @if($review->status == "1")
                          <div class="row">

                              <div class="col-md-2">
                                @if($review->users->image !='')
                                <img src="{{ url('/images/user/'.$review->users->image) }}" alt="" class="img img-responsive img-fluid">
                                @else
                                <img class="img img-responsive img-fluid" src="{{ Avatar::create($review->users->name)->toBase64() }}">
                                @endif
                              </div>

                              <div class="col-md-10">
                                <p>
                                  <b><i>{{ $review->users->name }}</i></b>
                                  <?php

                                    $user_count = count([$review]);
                                    $user_sub_total = 0;
                                    $user_review_t = $review->price * 5;
                                    $user_price_t = $review->price * 5;
                                    $user_value_t = $review->value * 5;
                                    $user_sub_total = $user_sub_total + $user_review_t + $user_price_t + $user_value_t;

                                    $user_count = ($user_count * 3) * 5;
                                    $rat1 = $user_sub_total / $user_count;
                                    $ratings_var1 = ($rat1 * 100) / 5;

                                    ?>
                                <div class="pull-left">
                                    <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var1; ?>%" class="star-ratings-sprite-rating"></span>
                                    </div>
                                </div>

                                  <small class="pull-right">{{ __('On') }} {{ date('jS M Y',strtotime($review->created_at)) }}</small>
                                  <br>
                                  <span class="font-weight500">{{ $review->review }}</span>
                                </p>
                              </div>

                          </div>
                          <hr>
                        @endif

                    @endforeach
                     </div>
                   </div>
                   @else
                      <h5><i class="fa fa-star"></i> {{ __('staticwords.ratetext') }}</h5><hr>
                   @endif
                @endif
          
         @else
          <h5>{{ __('staticwords.Please') }} <a href="{{ route('login') }}">{{ __('staticwords.Login') }}</a> {{ __('staticwords.toratethisproduct') }}</h5>

          @if(count($mainproreviews)>0)
            <hr>
            <h5>{{ __('staticwords.recReviews') }}</h5>

            <hr>
              <div class="row">
                     
                     <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="overall-rating-main-block">
                          <div class="overall-rating-block text-center">
                            <h1>{{ round($overallrating,1) }}</h1>
                            <div class="overall-rating-title">{{ __('staticwords.OverallRating') }}</div>
                            <div class="rating">
                                                 @php
                                                      $review_t = 0;
                                                      $price_t = 0;
                                                      $value_t = 0;
                                                      $sub_total = 0;
                                                      $sub_total = 0;
                                                      $reviews2 = App\UserReview::where('pro_id', $pro->id)->where('status', '1')->get();
                                                      @endphp @if(!empty($reviews2[0]))
                                                      @php
                                                      $count = App\UserReview::where('pro_id', $pro->id)->count();
                                                      foreach ($reviews2 as $review) {
                                                        $review_t = $review->price * 5;
                                                        $price_t = $review->price * 5;
                                                        $value_t = $review->value * 5;
                                                        $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                      }
                                                      $count = ($count * 3) * 5;
                                                      $rat = $sub_total / $count;
                                                      $ratings_var2 = ($rat * 100) / 5;
                                                    @endphp

                                                   
                                                      <div class="star-ratings-sprite">
                                                        <span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span>
                                                      </div>
                                                    

                                                     @else
                                                     <div class="text-center">
                                                       {{ __('No Rating') }}
                                                     </div>
                                                     @endif
                            </div>
                            <div class="total-review">{{$count =  count($mainproreviews)}} {{ __('Ratings &') }} {{$reviewcount}} {{ __('reviews') }}</div>
                          </div>
                          <div>
                           <div class="stat-levels">
                                <label>{{ __('staticwords.Quality') }}</label>
                                <div class="stat-1 stat-bar">
                                  <span class="stat-bar-rating" role="stat-bar" style="width: {{ $qualityprogress }}%;">{{ $qualityprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Price') }}</label>
                                <div class="stat-2 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-one" role="stat-bar" style="width: {{ $priceprogress }}%;">{{ $priceprogress }}%</span>
                                </div>
                                <label>{{ __('staticwords.Value') }}</label>
                                <div class="stat-3 stat-bar">
                                  <span class="stat-bar-rating stat-bar-rating-two" role="stat-bar" style="width: {{ $valueprogress }}%;">{{ $valueprogress }}%</span>
                                </div>
                            </div>
                          </div>
                          @if($overallrating>3.9)
                            <div class="overall-rating-block satisfied-customer-block text-center">
                              <h3>100%</h3>
                              <div class="overall-rating-title">{{ __('staticwords.SatisfiedCustomer') }}</div>
                              <p>{{ __('staticwords.customerText') }}</p>
                            </div>
                          @endif
                        </div>
                    </div>

                     <div class="col-md-9">
                       @foreach($mainproreviews as $review)

                         @if($review->status == "1")
                          <div class="row">

                              <div class="col-md-2">
                                @if($review->users->image !='')
                                <img src="{{ url('/images/user/'.$review->users->image) }}" alt="" class="img img-responsive img-fluid">
                                @else
                                <img class="img img-responsive img-fluid" src="{{ Avatar::create($review->users->name)->toBase64() }}">
                                @endif
                              </div>

                              <div class="col-md-10">
                                <p>
                                  <b><i>{{ $review->users->name }}</i></b>
                                  <?php

                                    $user_count = count([$review]);
                                    $user_sub_total = 0;
                                    $user_review_t = $review->price * 5;
                                    $user_price_t = $review->price * 5;
                                    $user_value_t = $review->value * 5;
                                    $user_sub_total = $user_sub_total + $user_review_t + $user_price_t + $user_value_t;

                                    $user_count = ($user_count * 3) * 5;
                                    $rat1 = $user_sub_total / $user_count;
                                    $ratings_var1 = ($rat1 * 100) / 5;

                                    ?>
                                <div class="pull-left">
                                    <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var1; ?>%" class="star-ratings-sprite-rating"></span>
                                    </div>
                                </div>

                                  <small class="pull-right">{{ __('On') }} {{ date('jS M Y',strtotime($review->created_at)) }}</small>
                                  <br>
                                  <span class="font-weight500">{{ $review->review }}</span>
                                </p>
                              </div>

                          </div>
                        @endif

                    @endforeach
                     </div>
                   </div>
          @endif
         @endauth
</div>
<!-- =======================review-end ================== -->
<div class="comment-block">
 
  <ul class="nav nav-pills" id="pills-tab" role="tablist">
    <li class="nav-item">
      <a data-toggle="tab" href="#comments" class="nav-link active width-50"><i class="fa fa-comments-o"></i> <span id="countComment">{{ count($pro->comments) }}</span> {{ __('staticwords.Comments') }}</a>
    </li>
    <li class="nav-item">
      <a data-toggle="tab" href="#post-comments" class="nav-link width-50">{{ __('staticwords.PostComments') }}</a>
    </li>
  </ul>
  <hr>
  <div class="tab-content">
    <div id="comments" class="tab-pane fade in show active">
      <h3><i class="fa fa-commenting-o" aria-hidden="true"></i> {{ __('staticwords.RecentComments') }}</h3>
          <br>
          <div class="appendComment">
            @foreach($pro->comments->sortByDesc('id')->take(5) as $comment)
            
              @if($comment->approved == 1)
                  <div class="row">
                        <div class="col-md-2 col-sm-2">
                          <img title="{{ $comment->name }}" width="60px" height="60px" src="{{ Avatar::create($comment->name)->toBase64() }}" class="img-rounded img-responsive img-fluid"/>
                        </div>

                        <div class="col-md-10 col-sm-10 outer-bottom-xs">
                          <div class="blog-sub-comments inner-bottom-xs">
                            <h5>{{ $comment->name }}</h5>
                            <span class="review-action pull-right">
                              {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                            </span>
                            <p>{{ $comment->comment }}</p>
                          </div>
                        </div>
                  </div>

              @endif   
            @endforeach
            @if(count($pro->comments)>5)
            <div align="center" class="remove-row">
                <button data-proid="{{ $pro->id }}" data-id="{{ $comment->id }}" class="btn-more btn btn-info btn-sm" >{{ __('staticwords.LoadMore') }}</button>
            </div>
            @endif
          </div>
    </div>
    <div id="post-comments" class="tab-pane fade">
        
          <div class="blog-review wow fadeInUp">
            <div class=" ">
             <h3>{{ __('staticwords.LeaveAComment') }}:</h3>
                 <form id="comment_us">
                    {{csrf_field()}}

                    <div class="form-group">
                       <label>{{ __('staticwords.Name') }}: <span class="required">*</span></label>
                       <input required="" type="text" name="send" class="form-control col-md-7 col-xs-12">
                       <input type="hidden" name="id" value="{{$pro->id}}">
                       <span class="text-red">{{$errors->first('name')}}</span>
                    </div>
                    <p></p>
                    <br>
                    <div class="form-group">
                       <label>{{ __('staticwords.Email') }}: <span class="required">*</span></label>
                      <input required="" type="email" name="email" class="form-control col-md-7 col-xs-12"> <span class="colorred">{{$errors->first('email')}}</span>
                    </div>
                    <p></p>
                    <br>
                   <div class="form-group">
                      <label>{{ __('staticwords.Comment') }}: <span class="required">*</span></label>
                      <textarea required="" name="comment" rows="5" cols="50" class="form-control" placeholder="{{ __('Please Enter Your Comment') }}"></textarea>
                      <span>{{$errors->first('comment')}}</span>
                   </div>
                 <p></p>
                 <br>
                  <div class="form-group">
                    <button type="submit" id="submit-form" class="btn btn-primary">{{ __('staticwords.Submit') }}</button>
                  </div>

                  </form>
              </div>
            </div>
    </div>
  </div>
</div>
<!-- =======================comment-end ================== -->
</div>
<!-- ======================== ====================== small-screen tab end ============================================== -->
<!-- ==============================================    ============================================== -->     
<!-- ============================================== UPSELL PRODUCTS ============================================== -->
@if(isset($pro->relsetting))
<div class="card">

    
       <div class="card-header bg-white">
          <h5 class="card-title">{{ __('staticwords.RelatedProducts') }}</h5>
       </div>

       <div class="card-body">
         <section class="section-random section new-arriavls related-products-block">
      <div class="owl-carousel home-owl-carousel custom-carousel owl-theme outer-top-xs">
    @if($pro->relsetting->status == '1')   


        
        <!-- product show manually -->
      @if(isset($pro->relproduct))
        @foreach($pro->relproduct->related_pro as $relpro)
            @php
              $relproduct = App\Product::find($relpro);
            @endphp
            
            @if(isset($relproduct))
                @foreach($relproduct->subvariants as $orivar)
          
                    @if($orivar->def == '1')
                    
                        @php 
                        $var_name_count = count($orivar['main_attr_id']);
                      
                        $name = array();
                        $var_name;
                          $newarr = array();
                          for($i = 0; $i<$var_name_count; $i++){
                            $var_id =$orivar['main_attr_id'][$i];
                            $var_name[$i] = $orivar['main_attr_value'][$var_id];
                              
                              $name[$i] = App\ProductAttributes::where('id',$var_id)->first();
                              
                          }


                        try{
                            $url = url('details').'/'.$relproduct->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                        }catch(Exception $e)
                        {
                          $url = url('details').'/'.$relproduct->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                        }
                      @endphp

                  <div class="item item-carousel">
                    <div class="products">
                      <div class="product">

                       <div class="product-image">
                        <div class="image {{ $orivar->stock ==0 ? "pro-img-box" : ""}}"> 
                              
                                 <a href="{{$url}}" title="{{$relproduct->name}}">
                                 
                                  @if(count($relproduct->subvariants)>0)

                                  @if(isset($orivar->variantimages['image2']))
                                   <img class="ankit {{ $orivar->stock ==0 ? "filterdimage" : ""}}" src="{{url('/variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}" alt="{{$relproduct->name}}">
                                   <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}} hover-image" src="{{url('/variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt=""/>
                                  @endif

                                  @else
                                  <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $relproduct->name }}" src="{{url('/images/no-image.png')}}" alt="No Image"/>
                                  
                                  @endif 
                           
                                                         
                          </a>
                          </div>

                            @if($orivar->stock == 0)
                             <h5 align="center" class="oottext">Out of stock</h5>
                            @endif

                             @if($orivar->stock != 0 && $orivar->products->selling_start_at != null && $orivar->products->selling_start_at >= date('Y-m-d H:i:s'))
                              <h5 align="center" class="oottext2">Coming Soon !</h5>
                            @endif
                          <!-- /.image -->
                          
                          @if($relproduct->featured=="1")
                            <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                            @elseif($pro->offer_price=="1")
                            <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
                            @else
                            <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
                          @endif
                        </div>
                        <!-- /.product-image -->
                        
                        <div class="product-info text-left">
                          <h3 class="name"><a href="{{ $url }}" title="{{$orivar->products->name}}">{{substr($relproduct->name, 0, 20)}}{{strlen($relproduct->name)>20 ? '...' : ""}}</a></h3>
                            <?php 
                            $reviews = App\UserReview::where('pro_id',$orivar->products->id)->where('status','1')->get();
                            ?> @if(!empty($reviews[0]))<?php
                            $review_t = 0;
                            $price_t = 0;
                            $value_t = 0;
                            $sub_total = 0;
                            $count =  App\UserReview::where('pro_id',$orivar->products->id)->count();
                              foreach($reviews as $review){
                              $review_t = $review->price*5;
                              $price_t = $review->price*5;
                              $value_t = $review->value*5;
                              $sub_total = $sub_total + $review_t + $price_t + $value_t;
                              }
                              $count = ($count*3) * 5;
                              $rat = $sub_total/$count;
                              $ratings_var = ($rat*100)/5;
                              ?>
                           
                          <div class="pull-left">
                            <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span></div>
                          </div>
                 
                          
                           @else
                            <div class="no-rating">{{ __('No Rating') }}</div>
                            @endif                               
                          <div class="description"></div>
                          <div class="product-price"> <span class="price">
                        
                           @if($price_login == '0' || Auth::check())
                              @php
                              $convert_price = 0;
                              $show_price = 0;
                              
                              $commision_setting = App\CommissionSetting::first();

                              if($commision_setting->type == "flat"){

                                 $commission_amount = $commision_setting->rate;
                                if($commision_setting->p_type == 'f'){
                                
                                  if($relproduct->tax_r !=''){

                                  $cit =$commission_amount*$relproduct->tax_r/100;
                                  $totalprice = $relproduct->vender_price+$orivar->price+$commission_amount+$cit;
                                  $totalsaleprice = $relproduct->vender_offer_price + $cit + $orivar->price + $commission_amount;

                                  if($relproduct->vender_offer_price == 0){
                                    $show_price = $totalprice;
                                  }else{
                                    $totalsaleprice;
                                    $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                    $show_price = $totalprice;
                                  }


                                  }else{

                                  $totalprice = $relproduct->vender_price+$orivar->price+$commission_amount;
                                  $totalsaleprice = $relproduct->vender_offer_price + $orivar->price + $commission_amount;

                                  if($relproduct->vender_offer_price == 0){
                                    $show_price = $totalprice;
                                  }else{
                                    $totalsaleprice;
                                    $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                    $show_price = $totalprice;
                                  }

                                }

                                   
                                }else{

                                  $totalprice = ($relproduct->vender_price+$orivar->price)*$commission_amount;

                                  $totalsaleprice = ($relproduct->vender_offer_price+$orivar->price)*$commission_amount;

                                  $buyerprice = ($relproduct->vender_price+$orivar->price)+($totalprice/100);

                                  $buyersaleprice = ($relproduct->vender_offer_price+$orivar->price)+($totalsaleprice/100);

                                 
                                    if($relproduct->vender_offer_price ==0){
                                      $show_price =  round($buyerprice,2);
                                    }else{
                                       round($buyersaleprice,2);
                                     
                                      $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                      $show_price = $buyerprice;
                                    }
                                 

                                }
                              }else{
                                
                              $comm = App\Commission::where('category_id',$relproduct->category_id)->first();
                           if(isset($comm)){
                             if($comm->type=='f'){
                               
                               if($relproduct->tax_r != ''){

                                    $cit = $comm->rate*$relproduct->tax_r/100;

                                    $price = $relproduct->vender_price + $comm->rate + $orivar->price + $cit;

                                    if($relproduct->vender_offer_price != null){
                                      $offer =  $relproduct->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                    }else{
                                      $offer =  $relproduct->vender_offer_price;
                                    }

                                    if($relproduct->vender_offer_price == 0 || $relproduct->vender_offer_price == null){
                                      $show_price = $price;
                                    }else{

                                    $convert_price = $offer;
                                    $show_price = $price;
                                    }

                                    }else{


                                    $price = $relproduct->vender_price + $comm->rate + $orivar->price;

                                    if($relproduct->vender_offer_price != null){
                                      $offer =  $relproduct->vender_offer_price + $comm->rate + $orivar->price;
                                    }else{
                                      $offer =  $relproduct->vender_offer_price;
                                    }

                                    if($relproduct->vender_offer_price == 0 || $relproduct->vender_offer_price == null){
                                      $show_price = $price;
                                    }else{

                                      $convert_price = $offer;
                                      $show_price = $price;
                                    
                                    }

                                }

                            }
                            else{

                                  $commission_amount = $comm->rate;

                                  $totalprice = ($relproduct->vender_price+$orivar->price)*$commission_amount;

                                  $totalsaleprice = ($relproduct->vender_offer_price+$orivar->price)*$commission_amount;

                                  $buyerprice = ($relproduct->vender_price+$orivar->price)+($totalprice/100);

                                  $buyersaleprice = ($relproduct->vender_offer_price+$orivar->price)+($totalsaleprice/100);

                                 
                                    if($relproduct->vender_offer_price == 0){
                                       $show_price = round($buyerprice,2);
                                    }else{
                                      $convert_price =  round($buyersaleprice,2);
                                      
                                      $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                      $show_price = round($buyerprice,2);
                                    }
                                 
                                 
                                  
                            }
                         }
                            }
                          $convert_price_form = $convert_price;
                          $show_price_form = $show_price;
                          $convert_price = $convert_price*$conversion_rate;
                          $show_price = $show_price*$conversion_rate;
                          
                            @endphp
                            
                            @if(Session::has('currency'))
                              @if($convert_price == 0 || $convert_price == 'null')
                                <span class="price"><i class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                              @else
                                <span class="price"><i class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                                <span class="price-before-discount"><i class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>

                              @endif
                            
                              

                            @endif

                              @endif
                             </div>
                          <!-- /.product-price --> 
                        </div>
                        <!-- /.product-info -->
                        @if($orivar->products->selling_start_at != null && $orivar->products->selling_start_at >= date('Y-m-d H:i:s'))
                        @elseif($orivar->stock == 0)
                        @else
                           <div class="cart clearfix animate-effect">
                            <div class="action">
                              <ul class="list-unstyled">                             
                                <li id="addCart" class="lnk wishlist">

                                <form method="POST" action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price_form, 'varofferprice' => $convert_price_form ,'qty' =>$orivar->min_order_qty])}}">
                                  {{ csrf_field() }}
                                  <button title="{{ __('Add to Cart') }}" type="submit" class="addtocartcus btn">
                                    <i class="fa fa-shopping-cart"></i>
                                  </button>
                                </form>
                                

                               </li>   

                              @auth
                              @if(Auth::user()->wishlist->count()<1)
                                <li class="lnk wishlist"> 

                                <a mainid="{{ $orivar->id }}" title="{{ __('Add to wishlist') }}" class="add-to-cart addtowish cursor-pointer" data-add="{{url('AddToWishList/'.$orivar->id)}}" title="{{ __('Add to wishlist') }}"> <i class="icon fa fa-heart"></i> 
                                </a> 

                                </li>
                              @else

                                @php
                                  $ifinwishlist = App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                                @endphp

                                  @if(!empty($ifinwishlist)) 
                                       <li class="lnk wishlist active">
                                        <a mainid="{{ $orivar->id }}"  title="{{ __('Remove From wishlist') }}" class="color000 cursor-pointer add-to-cart removeFrmWish active" data-remove="{{url('removeWishList/'.$orivar->id)}}" title="Wishlist"> <i class="icon fa fa-heart"></i> </a> 
                                      </li>
                                  @else
                                      <li class="lnk wishlist"> <a title="Add to wish list" mainid="{{ $orivar->id }}" class="add-to-cart addtowish cursor-pointer text-white" data-add="{{url('AddToWishList/'.$orivar->id)}}" title="Wishlist"> <i class="activeOne icon fa fa-heart"></i> </a></li>
                                  @endif

                                @endif
                              @endauth

                                <li class="lnk"> <a class="add-to-cart" href="{{route('compare.product',$orivar->products->id)}}" title="Compare"> <i class="fa fa-signal" aria-hidden="true"></i> </a> </li>
                              </ul>
                            </div>
                            <!-- /.action --> 
                          </div>
                        @endif
                        <!-- /.cart --> 
                      </div>

                      <!-- /.product --> 
                      
                    </div>
                    <!-- /.products --> 
                  </div>

                    @endif

                @endforeach
            @endif

        @endforeach
      @endif

      @else

       @foreach($pro->subcategory->products as $relpro)
          @if(isset($pro->subcategory->products))
                @foreach($relpro->subvariants as $orivar)
          
                    @if($orivar->def == '1' && $pro->id != $orivar->products->id)
                    
                        @php 
                        $var_name_count = count($orivar['main_attr_id']);
                      
                        $name = array();
                        $var_name;
                          $newarr = array();
                          for($i = 0; $i<$var_name_count; $i++){
                            $var_id =$orivar['main_attr_id'][$i];
                            $var_name[$i] = $orivar['main_attr_value'][$var_id];
                              
                              $name[$i] = App\ProductAttributes::where('id',$var_id)->first();
                              
                          }


                        try{
                          $url = url('details').'/'.$relpro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                        }catch(Exception $e)
                        {
                          $url = url('details').'/'.$relpro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                        }
                      @endphp

                  <div class="item item-carousel">
                    <div class="products">
                      <div class="product">

                       <div class="product-image">
                        <div class="image {{ $orivar->stock ==0 ? "pro-img-box" : ""}}"> 
                              
                                 <a href="{{$url}}" title="{{$pro->name}}">
                                 
                                  @if(count($pro->subvariants)>0)

                                  @if(isset($orivar->variantimages['image2']))
                                   <img class="ankit {{ $orivar->stock ==0 ? "filterdimage" : ""}}" src="{{url('/variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}" alt="{{$pro->name}}">
                                   <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}} hover-image" src="{{url('/variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt=""/>
                                  @endif

                                  @else
                                  <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $pro->name }}" src="{{url('/images/no-image.png')}}" alt="No Image"/>
                                  
                                  @endif 

                           
                                                         
                          </a>
                          </div>

                            @if($orivar->stock == 0)
                             <h5 align="center" class="oottext">
                               {{ __('staticwords.Outofstock') }}
                             </h5>
                            @endif

                             @if($orivar->stock != 0 && $orivar->products->selling_start_at != null && $orivar->products->selling_start_at >= date('Y-m-d H:i:s'))
                              <h5 align="center" class="oottext2">
                                {{ __('staticwords.ComingSoon') }}
                              </h5>
                            @endif
                          <!-- /.image -->
                          
                          @if($pro->featured=="1")
                            <div class="tag hot"><span>
                              {{ __('staticwords.Hot') }}
                            </span></div>
                            @elseif($pro->offer_price=="1")
                            <div class="tag sale"><span>
                              {{ __('staticwords.Sale') }}
                            </span></div>
                            @else
                            <div class="tag new"><span>
                              {{ __('staticwords.New') }}
                            </span></div>
                          @endif
                        </div>
                        <!-- /.product-image -->
                        
                        <div class="product-info text-left">
                          <h3 class="name"><a href="{{ $url }}" title="{{$relpro->name}}">{{substr($relpro->name, 0, 20)}}{{strlen($relpro->name)>20 ? '...' : ""}}</a></h3>
                            <?php 
                            $reviews = App\UserReview::where('pro_id',$relpro->id)->where('status','1')->get();
                            ?> @if(!empty($reviews[0]))<?php
                            $review_t = 0;
                            $price_t = 0;
                            $value_t = 0;
                            $sub_total = 0;
                            $count =  App\UserReview::where('pro_id',$relpro->id)->count();
                              foreach($reviews as $review){
                              $review_t = $review->price*5;
                              $price_t = $review->price*5;
                              $value_t = $review->value*5;
                              $sub_total = $sub_total + $review_t + $price_t + $value_t;
                              }
                              $count = ($count*3) * 5;
                              $rat = $sub_total/$count;
                              $ratings_var = ($rat*100)/5;
                              ?>
                           
                          <div class="pull-left">
                            <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%" class="star-ratings-sprite-rating"></span></div>
                          </div>
                 
                          
                           @else
                            <div class="no-rating">{{ __('No Rating') }}</div>
                            @endif                               
                          <div class="description"></div>
                          <div class="product-price"> <span class="price">
                        
                           @if($price_login == '0' || Auth::check())
                              @php
                              $convert_price = 0;
                              $show_price = 0;
                              
                              $commision_setting = App\CommissionSetting::first();

                              if($commision_setting->type == "flat"){

                                 $commission_amount = $commision_setting->rate;
                                if($commision_setting->p_type == 'f'){
                                
                                  if($relpro->tax_r !=''){

                                    $cit =$commission_amount*$relpro->tax_r/100;
                                    $totalprice = $relpro->vender_price+$orivar->price+$commission_amount+$cit;
                                    $totalsaleprice = $relpro->vender_offer_price + $cit + $orivar->price + $commission_amount;

                                    if($relpro->vender_offer_price == 0){
                                      $show_price = $totalprice;
                                    }else{
                                      $totalsaleprice;
                                      $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                      $show_price = $totalprice;
                                    }


                                    }else{

                                    $totalprice = $relpro->vender_price+$orivar->price+$commission_amount;
                                    $totalsaleprice = $relpro->vender_offer_price + $orivar->price + $commission_amount;

                                    if($relpro->vender_offer_price == 0){
                                      $show_price = $totalprice;
                                    }else{
                                      $totalsaleprice;
                                      $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                      $show_price = $totalprice;
                                    }

                                    }

                                   
                                }else{

                                  $totalprice = ($relpro->vender_price+$orivar->price)*$commission_amount;

                                  $totalsaleprice = ($relpro->vender_offer_price+$orivar->price)*$commission_amount;

                                  $buyerprice = ($relpro->vender_price+$orivar->price)+($totalprice/100);

                                  $buyersaleprice = ($relpro->vender_offer_price+$orivar->price)+($totalsaleprice/100);

                                 
                                    if($relpro->vender_offer_price ==0){
                                      $show_price =  round($buyerprice,2);
                                    }else{
                                       round($buyersaleprice,2);
                                     
                                      $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                      $show_price = $buyerprice;
                                    }
                                 

                                }
                              }else{
                                
                              $comm = App\Commission::where('category_id',$relpro->category_id)->first();
                           if(isset($comm)){
                             if($comm->type=='f'){
                               
                                if($relpro->tax_r != ''){

                                    $cit = $comm->rate*$relpro->tax_r/100;

                                    $price = $relpro->vender_price + $comm->rate + $orivar->price + $cit;

                                    if($relpro->vender_offer_price != null){
                                      $offer =  $relpro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                    }else{
                                      $offer =  $relpro->vender_offer_price;
                                    }

                                    if($relpro->vender_offer_price == 0 || $relpro->vender_offer_price == null){
                                      $show_price = $price;
                                    }else{

                                    $convert_price = $offer;
                                    $show_price = $price;
                                    }

                                    }else{


                                    $price = $relpro->vender_price + $comm->rate + $orivar->price;

                                    if($relpro->vender_offer_price != null){
                                      $offer =  $relpro->vender_offer_price + $comm->rate + $orivar->price;
                                    }else{
                                      $offer =  $relpro->vender_offer_price;
                                    }

                                    if($relpro->vender_offer_price == 0 || $relpro->vender_offer_price == null){
                                      $show_price = $price;
                                    }else{

                                      $convert_price = $offer;
                                      $show_price = $price;

                                    }

                                    }

                                
                            }
                            else{

                                  $commission_amount = $comm->rate;

                                  $totalprice = ($relpro->vender_price+$orivar->price)*$commission_amount;

                                  $totalsaleprice = ($relpro->vender_offer_price+$orivar->price)*$commission_amount;

                                  $buyerprice = ($relpro->vender_price+$orivar->price)+($totalprice/100);

                                  $buyersaleprice = ($relpro->vender_offer_price+$orivar->price)+($totalsaleprice/100);

                                 
                                    if($relpro->vender_offer_price == 0){
                                       $show_price = round($buyerprice,2);
                                    }else{
                                      $convert_price =  round($buyersaleprice,2);
                                      
                                      $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                      $show_price = round($buyerprice,2);
                                    }
                                 
                                 
                                  
                            }
                         }
                            }
                          $convert_price_form = $convert_price;
                          $show_price_form = $show_price;
                          $convert_price = $convert_price*$conversion_rate;
                          $show_price = $show_price*$conversion_rate;
                          
                            @endphp
                            
                            @if(Session::has('currency'))
                              @if($convert_price == 0 || $convert_price == 'null')
                                <span class="price"><i class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                              @else
                                <span class="price"><i class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                                <span class="price-before-discount"><i class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>

                              @endif
                            
                              

                            @endif

                              @endif
                             </div>
                          <!-- /.product-price --> 
                        </div>
                        <!-- /.product-info -->
                        @if($orivar->products->selling_start_at != null && $orivar->products->selling_start_at >= date('Y-m-d H:i:s'))
                        @elseif($orivar->stock == 0)
                        @else
                           <div class="cart clearfix animate-effect">
                            <div class="action">
                              <ul class="list-unstyled">                             
                                <li id="addCart" class="lnk wishlist">

                                @if($price_login != 1)
                                  <form method="POST" action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price_form, 'varofferprice' => $convert_price_form ,'qty' =>$orivar->min_order_qty])}}">
                                  {{ csrf_field() }}
                                  <button title="{{ __('Add to Cart') }}" type="submit" class="addtocartcus btn">
                                    <i class="fa fa-shopping-cart"></i>
                                  </button>
                                </form>
                                @endif
                                

                               </li>   

                              @auth
                              @if(Auth::user()->wishlist->count()<1)
                                <li class="lnk wishlist"> 

                                <a mainid="{{ $orivar->id }}" title="Add to wishlist" class="cursor-pointer add-to-cart addtowish" data-add="{{url('AddToWishList/'.$orivar->id)}}" title="{{ __('Add to wishlist') }}"> <i class="icon fa fa-heart"></i> 
                                </a> 

                                </li>
                              @else

                                @php
                                  $ifinwishlist = App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                                @endphp

                                  @if(!empty($ifinwishlist)) 
                                       <li class="lnk wishlist active">
                                        <a mainid="{{ $orivar->id }}"  title="{{ __('Remove From wishlist') }}" class="color000 cursor-pointer add-to-cart removeFrmWish active" data-remove="{{url('removeWishList/'.$orivar->id)}}" title="Wishlist"> <i class="icon fa fa-heart"></i> </a> 
                                      </li>
                                  @else
                                      <li class="lnk wishlist"> <a title="{{ __('Add to wish list') }}" mainid="{{ $orivar->id }}" class="add-to-cart addtowish text-white cursor-pointer" data-add="{{url('AddToWishList/'.$orivar->id)}}" title="Wishlist"> <i class="activeOne icon fa fa-heart"></i> </a></li>
                                  @endif

                                @endif
                              @endauth

                                <li class="lnk"> <a class="add-to-cart" href="{{route('compare.product',$relpro->id)}}" title="{{ __('Compare') }}"> <i class="fa fa-signal" aria-hidden="true"></i> </a> </li>
                              </ul>
                            </div>
                            <!-- /.action --> 
                          </div>
                        @endif
                        <!-- /.cart --> 
                      </div>

                      <!-- /.product --> 
                      
                    </div>
                    <!-- /.products --> 
                  </div>

                    @endif

                @endforeach
            @endif
       @endforeach
      @endif            
      </div>
                 <!-- /.home-owl-carousel --> 
    </section>
       </div>

</div>
     <br>  
@endif
<!-- ============================================== UPSELL PRODUCTS : END ============================================== -->
  
 
          <!-- ============================================== HOT DEALS ============================================== -->
      @if(isset($enable_hotdeal) && $enable_hotdeal->shop == "1")
      <div class='single-product sidebar body-content header-nav-smallscreen header-nav-smallscreen-one'>
        <div class="sidebar-module-container">
          <div class="sidebar-widget sidebar-widget-one hot-deals hot-deals-one outer-bottom-xs">
              <h3 class="section-title">{{ __('staticwords.Hotdeals') }}</h3>
              <div class="owl-carousel sidebar-carousel custom-carousel owl-theme outer-top-ss">
              
                @foreach($hotdeals as $value)
                
                @if(isset($value->pro))

                @foreach($value->pro->subvariants as $key=> $orivar)
                         @if($orivar->def ==1)




                          @php
                          $var_name_count = count($orivar['main_attr_id']);

                          $name;
                          $var_name;
                             $newarr = array();

                            for($i = 0; $i<$var_name_count; $i++){
                              $var_id =$orivar['main_attr_id'][$i];
                              $var_name[$i] = $orivar['main_attr_value'][$var_id];

                                $name[$i] = App\ProductAttributes::where('id',$var_id)->first();

                            }


                          try{
                            $url = url('details').'/'.$value->pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                          }catch(Exception $e)
                          {
                            $url = url('details').'/'.$value->pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                          }

                       @endphp

                          @if($date <= $value->end)
                            <div class="item hot-deals-item">
                              <div class="products">
                                <div class="hot-deal-wrapper">
                                 <div class="image">
                          <a href="{{$url}}" title="{{$value->pro->name}}">
                             @if(count($value->pro->subvariants)>0)

                                @if(isset($orivar->variantimages['image2']))
                                 <img src="{{ url('/variantimages/thumbnails/'.$orivar->variantimages['main_image']) }}" alt="{{$value->name}}">
                                 <img class="hover-image" src="{{url('/variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt=""/>
                                @endif

                                @else
                                <img title="{{ $value->name }}" src="{{url('/images/no-image.png')}}" alt="No Image"/>
                                @endif
                          </a>
                          </div>

                                  @if($value->pro->vender_offer_price != 0 || $value->pro->vender_offer_price != null)
                                   @php
                                      $getdisprice = $value->pro->vender_price - $value->pro->vender_offer_price;
                                      $gotdis = $getdisprice/$value->pro->vender_price;
                                      $offamount = $gotdis*100;
                                   @endphp
                                  <div class="sale-offer-tag"><span><?php echo Round($offamount) . "%"; ?><br>
                                    {{ __('off') }}</span>
                                  </div>

                                @endif
                                  <div class="countdown">
                                    <div class="timing-wrapper" data-startat="{{ $value->start }}" data-countdown="{{$value->end}}"></div>
                                  </div>
                                 </div>
                                <!-- /.hot-deal-wrapper -->

                                <div class="product-info text-left m-t-20">
                                  <h3 class="name"><b><a href="{{$url}}" title="{{$value->pro->name}}">{{$value->pro->name}}</a></b></h3>
                                          @php
                                          $review_t = 0;
                                          $price_t = 0;
                                          $value_t = 0;
                                          $sub_total = 0;
                                          $sub_total = 0;
                                          $reviews2 = App\UserReview::where('pro_id', $value->pro->id)->where('status', '1')->get();
                                          @endphp @if(!empty($reviews2[0]))
                                          @php
                                          $count = App\UserReview::where('pro_id', $value->pro->id)->count();
                                          foreach ($reviews2 as $review) {
                                            $review_t = $review->price * 5;
                                            $price_t = $review->price * 5;
                                            $value_t = $review->value * 5;
                                            $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                          }
                                          $count = ($count * 3) * 5;
                                          $rat = $sub_total / $count;
                                          $ratings_var2 = ($rat * 100) / 5;
                                          @endphp

                                          <div class="">
                                            <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var2; ?>%" class="star-ratings-sprite-rating"></span></div>
                                          </div>

                                           @else
                                           <div class="text-center">
                                             {{ __('No Rating') }}
                                           </div>
                                           @endif

                                           <div class="product-price"> <span class="price">

                                            @if($price_login == 0 || Auth::check())
                                               @php
                                               $convert_price = 0;
                                               $show_price = 0;

                                               $commision_setting = App\CommissionSetting::first();

                                               if($commision_setting->type == "flat"){

                                                  $commission_amount = $commision_setting->rate;
                                                 if($commision_setting->p_type == 'f'){

                                                   $totalprice = $value->pro->vender_price+$orivar->price+$commission_amount;
                                                   $totalsaleprice = $value->pro->vender_offer_price + $orivar->price + $commission_amount;

                                                    if($value->pro->vender_offer_price == 0){
                                                        $show_price = $totalprice;
                                                     }else{
                                                       $totalsaleprice;
                                                       $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                                       $show_price = $totalprice;
                                                     }


                                                 }else{

                                                   $totalprice = ($value->pro->vender_price+$orivar->price)*$commission_amount;

                                                   $totalsaleprice = ($value->pro->vender_offer_price+$orivar->price)*$commission_amount;

                                                   $buyerprice = ($value->pro->vender_price+$orivar->price)+($totalprice/100);

                                                   $buyersaleprice = ($value->pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                                     if($value->pro->vender_offer_price ==0){
                                                       $show_price =  round($buyerprice,2);
                                                     }else{
                                                        round($buyersaleprice,2);

                                                       $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                                       $show_price = $buyerprice;
                                                     }


                                                 }
                                               }else{

                                               $comm = App\Commission::where('category_id',$value->pro->category_id)->first();
                                            if(isset($comm)){
                                              if($comm->type=='f'){

                                                $price = $value->pro->vender_price + $comm->rate + $orivar->price;

                                                 if($value->pro->vender_offer_price != null){
                                                   $offer =  $value->pro->vender_offer_price + $comm->rate + $orivar->price;
                                                 }else{
                                                   $offer =  $value->pro->vender_offer_price;
                                                 }

                                                 if($value->pro->vender_offer_price == 0 || $value->pro->vender_offer_price == null){
                                                     $show_price = $price;
                                                 }else{

                                                   $convert_price = $offer;
                                                   $show_price = $price;
                                                 }


                                             }
                                             else{

                                                   $commission_amount = $comm->rate;

                                                   $totalprice = ($value->pro->vender_price+$orivar->price)*$commission_amount;

                                                   $totalsaleprice = ($value->pro->vender_offer_price+$orivar->price)*$commission_amount;

                                                   $buyerprice = ($value->pro->vender_price+$orivar->price)+($totalprice/100);

                                                   $buyersaleprice = ($value->pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                                     if($value->pro->vender_offer_price == 0){
                                                        $show_price = round($buyerprice,2);
                                                     }else{
                                                       $convert_price =  round($buyersaleprice,2);

                                                       $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                                       $show_price = round($buyerprice,2);
                                                     }



                                             }
                                          }
                                             }

                                             @endphp

                                             @if(Session::has('currency'))
                                               @if($convert_price == 0 || $convert_price == 'null')
                                                 <span class="price"><i class="{{session()->get('currency')['value']}}"></i>{{round($show_price*$conversion_rate,2)}}</span>
                                               @else

                                                 <span class="price"><i class="{{session()->get('currency')['value']}}"></i>{{round($convert_price*$conversion_rate,2)}}</span>

                                                 <span class="price-before-discount"><i class="{{session()->get('currency')['value']}}"></i>{{round($show_price*$conversion_rate,2)}}</span>


                                               @endif
                                             @endif

                                               @endif
                                              </div>

                                  <!-- /.product-price -->

                                </div>
                                <!-- /.product-info -->
                                
                                <div class="cart clearfix animate-effect">
                              <div class="action">
                                <ul class="list-unstyled">
                                @php
                                   $isInCart= App\Cart::where('variant_id',$orivar->id)->first();
                                   $in_session = 0;

                                   if(!empty(Session::has('cart'))){
                                      foreach (Session::get('cart') as $scart) {
                                       if($orivar->id == $scart['variantid']){
                                          $in_session = 1;
                                       }
                                     }
                                   }
                                   
                                   
                                @endphp
                                @if($value->pro->selling_start_at == '' || $value->pro->selling_start_at <= date("Y-m-d H:i:s"))
                                @if(!isset($isInCart) && $in_session == 0 && $orivar->stock>0)
                                  @if($price_login != 1)
                                     <form method="POST" action="{{route('add.cart',['id' => $value->pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                                                    {{ csrf_field() }}
                                  <li class="add-cart-button btn-group">
                                    <button class="btn btn-primary icon" data-toggle="dropdown" type="button"> <i class="fa fa-shopping-cart"></i> </button>
                                    <button class="btn btn-primary cart-btn" type="submit">Add to cart</button>
                                  </li>
                                </form>
                                  @endif
                                @else
                                @if($orivar->stock>0 && $in_session == 1)
                                  <li class="add-cart-button btn-group">
                                    
                                      <button class="btn btn-primary icon" data-toggle="dropdown" type="button"> <i class="fa fa-check"></i> </button>
                                      <button onclick="window.location='{{ url('/cart') }}'" class="btn btn-primary cart-btn" type="button">Deal in Cart</button>
                                  
                                  </li>
                                @endif
                                  @if($orivar->stock==0)

                                  <h5 class="required" align="center">
                                    {{ __('staticwords.Outofstock') }}
                                  </h5>
                                
                                  @endif
                                @endif
                               @else
                                  <h5>
                                    {{ __('staticwords.ComingSoon') }}
                                  </h5>
                                @endif
                                  
                                </ul>
                              </div>
                              <!-- /.action --> 
                          </div>



                              </div>
                            </div>


                          @endif
                        @endif
                        @endforeach
                        @endif
                        @endforeach


               </div>
                <!-- /.sidebar-widget -->
          </div>
        </div>
      </div>
      @endif
<!-- ============================================== HOT DEALS: END ============================================== -->

      </div><!-- /.col -->
    <!-- ============================================== BRANDS CAROUSEL ============================================== -->
<!-- ============================================== BRANDS CAROUSEL : END ============================================== -->  </div><!-- /.container -->
  


</div><!-- /.body-content -->

<!-- ============================================================= FOOTER ============================================================= -->

        <!-- ============================================== INFO BOXES ============================================== -->
    <!-- Report Product Modal -->
<div class="modal fade" id="reportproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="myModalLabel">{{ __('staticwords.ReportProduct') }} {{ $pro->name }}</h5>
      </div>

      <div class="modal-body">
        <form action="{{ route('rep.pro',$pro->id) }}" method="POST">
          {{ csrf_field() }}
          <div class="form group">
            <label>{{ __('staticwords.Subject') }}: <span class="text-red">*</span></label>
            <input required type="text" name="title" class="form-control" placeholder="{{ __('staticwords.Whyyoureportingtheprdouctentertitle') }}">
          </div>
          <br>
          <div class="form-group">
            <label>{{ __('staticwords.Email') }}: <span class="text-red">*</span></label>
            <input name="email" required type="email" class="form-control" name="email" placeholder="{{ __('staticwords.Enteryouremailaddress') }}">
          </div>

          <div class="form-group">
            <label>{{ __('staticwords.Description') }}: <span class="text-red">*</span></label>
            <textarea required class="form-control" placeholder="{{ __('staticwords.Briefdescriptionofyourissue') }}" name="des" id="" cols="30" rows="10"></textarea>
          </div>

          <div class="form-group">
            <button class="btn btn-md btn-primary">{{ __('staticwords.SUBMITFORREVIEW') }}</button>
          </div>
        </form>
      </div>
     
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="notifyMe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-sm modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="float-right close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h6 class="modal-title" id="exampleModalLabel">Notify me</h6>
        
      </div>
      <div class="modal-body">
          <form action="" method="POST" class="notifyForm">
            @csrf
            <p class="help-block text-dark">
              Please enter your email to get notified
            </p>
             <div class="form-group">
                <label>Email: <span class="text-red">*</span></label>
                <input name="email" type="email" class="form-control" placeholder="enter your email" required>
             </div>

             <div class="form-group">
               <button type="submit" class="btn btn-md btn-primary">Submit</button>
             </div>
          </form>
      </div>
     
    </div>
  </div>
</div>


@endsection

  @section('script')
  <script src="{{ url('js/share.js') }}"></script>
  @include('front.detailpagescript')
  <script>var baseUrl = "<?= url('/') ?>";</script>
  <script src="{{ url('js/detailpage.js') }}"></script>



  {{$pro->created_at}}
  <script>
 
    // Set the date we're counting down to

    var d = new Date();
  // d.setMinutes(d.getMinutes() + 3);
    d=Date.parse("{{$pro->created_at}}");
    
   
    var countDownDate = new Date(d).getTime();
     
    // Update the count down every 1 second
    var x = setInterval(function() {
    
      // Get today's date and time
      var now = new Date().getTime();
    
      
      // Find the distance between now and the count down date
      var distance = d - now;
    
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
      // Display the result in the element with id="demo"
      document.getElementById("demo").innerHTML = minutes + "m " + seconds + "s ";
    
      // If the count down is finished, write some text
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "EXPIRED";
      }
    }, 1000);
    </script>
@endsection