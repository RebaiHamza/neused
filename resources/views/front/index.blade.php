@php
$current_date = date('Y-m-d H:i:s');
@endphp
@extends("front.layout.master")
@section("body")
<div class="body-content outer-top-vs " id="top-banner-and-menu">
  <div class="container-fluid">
    <div class="row no-gutters">
      <!-- ============================================== SIDEBAR ============================================== -->
      @if(session()->has("getmsg"))
      <div class="animated fadeInDown col-md-offset-4 col-md-6 alert alert-primary alert-block">
        <button type="button" clacomss="close" data-dismiss="alert">×</button>
        <i class="fa fa-check-circle-o" aria-hidden="true"></i> {{ session()->get("getmsg") }}

      </div>
      @elseif(session()->has("failure"))
      <div class="animated fadeInDown col-md-offset-4 col-md-6 alert alert-error alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{ session()->get("failure") }}
      </div>
      <?php Session::forget('failure');?>
      @endif
      @include('front.layout.navbar')

      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10">
        <div class="main-content homebanner-holder">
          @php
          $getads = App\Adv::where('position','=','beforeslider')->where('status','=',1)->get();
          @endphp

          <!-- Advertisement -->
          @if(isset($getads))
          @foreach($getads as $adv)
          @if($adv->layout == 'Single image layout')
          <a href="
                        @if($adv->cat_id1 != '')
                         @include('front.adv.caturl')
                        @elseif($adv->pro_id1 != '')
                          @include('front.adv.pro')
                        @else
                          {{ $adv->url1 }}
                        @endif" title="Click to visit">
            <img class="img-fluid advertisement-img-one" src="{{ url('images/adv/'.$adv->image1) }}"
              alt="{{ $adv->image1 }}">
          </a>

          @endif

          @if($adv->layout == 'Three Image Layout')
          <div class="row">
            <div class="col-md-4  homebanner-img">
              <a href="
                        @if($adv->cat_id1 != '')
                         @include('front.adv.caturl')
                        @elseif($adv->pro_id1 != '')
                          @include('front.adv.pro')
                        @else
                          {{ $adv->url1 }}
                        @endif" title="Click to visit">
                <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image1) }}"
                  alt="{{ $adv->image1 }}">
              </a>
            </div>
            <div class="col-md-4 homebanner-img">
              <a href="
                        @if($adv->cat_id2 != '')
                           @include('front.adv.caturl2')
                        @elseif($adv->pro_id2 != '')
                           @include('front.adv.pro2')
                        @else
                          {{ $adv->url2 }}
                        @endif" title="Click to visit">
                <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image2) }}"
                  alt="{{ $adv->image2 }}">
              </a>
            </div>
            <div class="col-md-4">
              <a href="
                        @if($adv->cat_id3 != '')
                          @include('front.adv.caturl3')
                        @elseif($adv->pro_id3 != '')
                          @include('front.adv.pro3')
                        @else
                          {{ $adv->url3 }}
                        @endif" title="Click to visit">
                <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image3) }}"
                  alt="{{ $adv->image3 }}">
              </a>
            </div>
          </div>
          <br>
          @endif

          @if($adv->layout == 'Two equal image layout')
          <div class="row">
            <div class="col-md-6">
              <a href="
                            @if($adv->cat_id1 != '')
                             @include('front.adv.caturl')
                            @elseif($adv->pro_id1 != '')
                              @include('front.adv.pro')
                            @else
                              {{ $adv->url1 }}
                            @endif" title="Click to visit">
                <img class="img-fluid advertisement-img advertisement-img-one"
                  src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
              </a>
            </div>
            <div class="col-md-6">
              <a href="
                            @if($adv->cat_id2 != '')
                             @include('front.adv.caturl2')
                            @elseif($adv->pro_id2 != '')
                              @include('front.adv.pro2')
                            @else
                              {{ $adv->url2 }}
                            @endif" title="Click to visit">
                <img class="img-fluid advertisement-img advertisement-img-one"
                  src="{{ url('images/adv/'.$adv->image2) }}" alt="{{ $adv->image2 }}">
              </a>
            </div>
          </div>
          <br>
          @endif

          @if($adv->layout == 'Two non equal image layout')
          <div class="row">
            <div class="col-md-8">
              <a href="
                            @if($adv->cat_id1 != '')
                             @include('front.adv.caturl')
                            @elseif($adv->pro_id1 != '')
                              @include('front.adv.pro')
                            @else
                              {{ $adv->url1 }}
                            @endif" title="Click to visit">
                <img class="img-fluid advertisement-img advertisement-img-one advertisement-images-size-one"
                  src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
              </a>
            </div>
            <div class="col-md-4">
              <a href="
                              @if($adv->cat_id2 != '')
                               @include('front.adv.caturl2')
                              @elseif($adv->pro_id2 != '')
                                @include('front.adv.pro2')
                              @else
                                {{ $adv->url2 }}
                              @endif" title="Click to visit">
                <img class="img-fluid advertisement-images-size-one" src="{{ url('images/adv/'.$adv->image2) }}"
                  alt="{{ $adv->image2 }}">
              </a>
            </div>
          </div>
          <br><br>
          @endif
          @endforeach
          @endif
          <!--END -->
          <!-- ========================================== SECTION – HERO ========================================= -->
          <?php $home_slider = App\Widgetsetting::where('name','slider')->first(); ?>
          @if(!empty($home_slider))
          @if($home_slider->home=='1')
          <div id="hero">
            <div id="owl-main" class="owl-carousel owl-inner-nav owl-ui-sm">
              @foreach($slider as $sliders)

              @if($sliders->status == '1')
              <div class="item backgroundcover"
                style="background-image: url('{{ url('images/slider/'.$sliders->image) }}');">
                <div class="container-fluid">
                  <div class="caption bg-color vertical-center text-left">
                    <div class="slider-header fadeInDown-1"><span
                        style="color:{{ $sliders->subheadingcolor }}">{{ $sliders->heading }}</span></div>
                    <div class="big-text fadeInDown-1"> <span
                        style="color:{{ $sliders->headingtextcolor }}">{{ $sliders->topheading }}</span> </div>
                    <div class="excerpt fadeInDown-2 hidden-xs"> <span
                        style="color:{{ $sliders->moredesccolor }}">{{ $sliders->moredesc }}</span> </div>
                    @if($sliders->buttonname !='')
                    <div class="button-holder fadeInDown-3"> <a
                        style="background: {{ $sliders->btnbgcolor }};color:{{ $sliders->btntextcolor }}"
                        href="@if($sliders->category_id != '') @include('front.layout.slidercaturl') @elseif($sliders->child != '') @include('front.layout.slidersubcaturl') @elseif($sliders->grand_id !='')  @include('front.layout.sliderchildcaturl') @elseif($sliders->product_id !='') @include('front.layout.sliderpro') @else {{ $sliders->url }} @endif"
                        class="btn-lg btn btn-uppercase shop-now-button">{{ $sliders->buttonname }}</a> </div>
                    @endif
                  </div>
                  <!-- /.caption -->
                </div>
                <!-- /.container-fluid -->
              </div>
              @endif



              @endforeach

              <!-- /.item -->


              <!-- /.item -->

            </div>
            <!-- /.owl-carousel -->
          </div>
          @endif
          @endif



          <!-- ================================ SECTION – HERO : END ===============Advertisement before new product widget ================== -->

          @php
          $getads = App\Adv::where('position','=','abovenewproduct')->where('status','=',1)->get();
          @endphp

          <!-- Advertisement -->
          @if(isset($getads))
          <br>
          @foreach($getads as $adv)
          @if($adv->layout == 'Single image layout')
          <a href="
                        @if($adv->cat_id1 != '')
                         @include('front.adv.caturl')
                        @elseif($adv->pro_id1 != '')
                          @include('front.adv.pro')
                        @else
                          {{ $adv->url1 }}
                        @endif" title="{{ __('Click to visit') }}">
            <img class="img-fluid advertisement-img-one" src="{{ url('images/adv/'.$adv->image1) }}"
              alt="{{ $adv->image1 }}">
          </a>

          @endif

          @if($adv->layout == 'Three Image Layout')
          <div class="row">
            <div class="col-md-4">
              <a href="
                        @if($adv->cat_id1 != '')
                         @include('front.adv.caturl')
                        @elseif($adv->pro_id1 != '')
                          @include('front.adv.pro')
                        @else
                          {{ $adv->url1 }}
                        @endif" title="{{ __('Click to visit') }}">
                <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image1) }}"
                  alt="{{ $adv->image1 }}">
              </a>
            </div>
            <div class="col-md-4">
              <a href="
                        @if($adv->cat_id2 != '')
                           @include('front.adv.caturl2')
                        @elseif($adv->pro_id2 != '')
                           @include('front.adv.pro2')
                        @else
                          {{ $adv->url2 }}
                        @endif" title="{{ __('Click to visit') }}">
                <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image2) }}"
                  alt="{{ $adv->image2 }}">
              </a>
            </div>
            <div class="col-md-4">
              <a href="
                        @if($adv->cat_id3 != '')
                          @include('front.adv.caturl3')
                        @elseif($adv->pro_id3 != '')
                          @include('front.adv.pro3')
                        @else
                          {{ $adv->url3 }}
                        @endif" title="{{ __('Click to visit') }}">
                <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image3) }}"
                  alt="{{ $adv->image3 }}">
              </a>
            </div>
          </div>
          <br>
          @endif

          @if($adv->layout == 'Two equal image layout')
          <div class="row">
            <div class="col-md-6">
              <a href="
                            @if($adv->cat_id1 != '')
                             @include('front.adv.caturl')
                            @elseif($adv->pro_id1 != '')
                              @include('front.adv.pro')
                            @else
                              {{ $adv->url1 }}
                            @endif" title="{{ __('Click to visit') }}">
                <img class="img-fluid advertisement-img advertisement-img-one"
                  src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
              </a>
            </div>
            <div class="col-md-6">
              <a href="
                            @if($adv->cat_id2 != '')
                             @include('front.adv.caturl2')
                            @elseif($adv->pro_id2 != '')
                              @include('front.adv.pro2')
                            @else
                              {{ $adv->url2 }}
                            @endif" title="{{ __('Click to visit') }}">
                <img class="img-fluid advertisement-img advertisement-img-one"
                  src="{{ url('images/adv/'.$adv->image2) }}" alt="{{ $adv->image2 }}">
              </a>
            </div>
          </div>
          <br>
          @endif

          @if($adv->layout == 'Two non equal image layout')
          <div class="row">
            <div class="col-md-8">
              <a href="
                          @if($adv->cat_id1 != '')
                           @include('front.adv.caturl')
                          @elseif($adv->pro_id1 != '')
                            @include('front.adv.pro')
                          @else
                            {{ $adv->url1 }}
                          @endif" title="{{ __('Click to visit') }}">
                <img class="img-fluid advertisement-img advertisement-img-one advertisement-images-size-one"
                  src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
              </a>
            </div>
            <div class="col-md-4">
              <a href="
                            @if($adv->cat_id2 != '')
                             @include('front.adv.caturl2')
                            @elseif($adv->pro_id2 != '')
                              @include('front.adv.pro2')
                            @else
                              {{ $adv->url2 }}
                            @endif" title="{{ __('Click to visit') }}">
                <img class="img-fluid advertisement-images-size-one" src="{{ url('images/adv/'.$adv->image2) }}"
                  alt="{{ $adv->image2 }}">
              </a>
            </div>
          </div>
          <br>
          @endif
          @endforeach
          @endif
          <!--END -->

          <!-- ============================================== SCROLL TABS ============================================== -->
          @php
          $abcd = App\FrontCat::first();
          @endphp
          @if(isset($abcd) && $abcd->status == '1')
          <?php $parents = explode(",",$abcd->name);?>
          <div id="product-tabs-slider" class="scroll-tabs">
            <div class="more-info-tab clearfix ">
              <h3 class="new-product-title pull-left">{{ __('staticwords.newprods') }}</h3>
              <div class="scroller scroller-left"><i class="fa fa-angle-left"></i></div>
              <div class="scroller scroller-right"><i class="fa fa-angle-right"></i></div>
              <div class="wrapper">
                <ul class="nav nav-pills float-right guu" id="new-products-1">
                  <li class="nav-item"><a class="nav-link active" data-transition-type="backSlide" href="#all"
                      data-toggle="tab">{{ __('staticwords.All') }}</a></li>


                  @foreach($parents as $parent)
                  <?php $parentcats = App\Category::where('status','1')->where('id',$parent)->first(); ?>
                  @if(isset($parentcats))
                  <li class="nav-item"><a class="nav-link" data-transition-type="backSlide"
                      href="#pro{{$parentcats->id}}" data-toggle="tab"
                      title="{{$parentcats->title}}">{{$parentcats->title}}</a></li>
                  @endif
                  @endforeach

                </ul>
              </div>
              <!-- /.nav-tabs -->
            </div>
            <div class="tab-content outer-top-xs">
              <div class="tab-pane in show active" id="all">
                <div class="product-slider">

                  <div class="owl-carousel home-owl-carousel custom-carousel owl-theme">

                    <!-- /.item -->
                    @if(isset($products))
                    <?php $i = 0;
                          $countP = 0;
                         ?>
                    @foreach($products as $pro)

                    @if($genrals_settings->vendor_enable != 1)

                    @if($pro->vender['role_id'] == 'a')

                    @foreach($pro->subvariants as $key=> $orivar)

                    @if($orivar->def == 1)

                    @php
                    $var_name_count = count($orivar['main_attr_id']);

                    $name = array();
                    $var_name;
                    $newarr = array();
                    for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
                      $var_name[$i]=$orivar['main_attr_value'][$var_id];
                      $name[$i]=App\ProductAttributes::where('id',$var_id)->first();
                      }


                      try{
                      $url =
                      url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                      }catch(Exception $e)
                      {
                      $url = url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                      }
                      @endphp

                      <div class="item item-carousel">
                        <div class="products">
                          <div class="product">

                            <div class="product-image">
                              <div class="image {{ $orivar->stock ==0 ? "pro-img-box" : ""}}">

                                <a href="{{$url}}" title="{{$pro->name}}">

                                  @if(count($pro->subvariants)>0)

                                  @if(isset($orivar->variantimages['main_image']))
                                  <img class="ankit {{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                                    src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                                    alt="{{$pro->name}}">
                                  <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}} hover-image"
                                    src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}"
                                    alt="" />
                                  @endif

                                  @else
                                  <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $pro->name }}"
                                    src="{{url('images/no-image.png')}}" alt="No Image" />

                                  @endif


                                </a>
                              </div>

                              @if($orivar->stock == 0)
                              <h6 align="center" class="oottext"><span>{{ __('staticwords.Outofstock') }}</span></h6>
                              @endif

                              @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                              $orivar->products->selling_start_at >= $current_date)
                              <h6 align="center" class="oottext2"><span>{{ __('staticwords.ComingSoon') }}</span></h6>
                              @endif
                              <!-- /.image -->

                              @if($pro->featured=="1")
                              <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                              @elseif($pro->offer_price != "0")
                              <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
                              @else
                              <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
                              @endif
                            </div>
                            <!-- /.product-image -->

                            <div class="product-info text-left">
                              <h3 class="name"><a href="{{ $url }}"
                                  title="{{$pro->name}}">{{substr($pro->name, 0, 20)}}{{strlen($pro->name)>20 ? '...' : ""}}</a>
                              </h3>
                              <?php 
                                    $reviews = App\UserReview::where('pro_id',$pro->id)->where('status','1')->get();
                                    ?> @if(!empty($reviews[0]))<?php
                                    $review_t = 0;
                                    $price_t = 0;
                                    $value_t = 0;
                                    $sub_total = 0;
                                    $count =  App\UserReview::where('pro_id',$pro->id)->count();
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
                                <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                                    class="star-ratings-sprite-rating"></span></div>
                              </div>


                              @else
                              <div class="no-rating">{{'No Rating'}}</div>
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

                                  if($pro->tax_r !=''){

                                  $cit =$commission_amount*$pro->tax_r/100;
                                  $totalprice = $pro->vender_price+$orivar->price+$commission_amount+$cit;
                                  $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price +
                                  $commission_amount;

                                  if($pro->vender_offer_price == NULL){
                                  $show_price = $totalprice;
                                  }else{
                                  $totalsaleprice;
                                  $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                  $show_price = $totalprice;
                                  }


                                  }else{
                                  $totalprice = $pro->vender_price+$orivar->price+$commission_amount;
                                  $totalsaleprice = $pro->vender_offer_price + $orivar->price + $commission_amount;

                                  if($pro->vender_offer_price == NULL){
                                  $show_price = $totalprice;
                                  }else{
                                  $totalsaleprice;
                                  $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                  $show_price = $totalprice;
                                  }

                                  }

                                  }else{

                                  $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                  $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                  $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                  $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                  if($pro->vender_offer_price == NULL){
                                  $show_price = round($buyerprice,2);
                                  }else{
                                  round($buyersaleprice,2);

                                  $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                  $show_price = $buyerprice;
                                  }


                                  }
                                  }else{

                                  $comm = App\Commission::where('category_id',$pro->category_id)->first();
                                  if(isset($comm)){
                                  if($comm->type=='f'){

                                  if($pro->tax_r != ''){

                                  $cit = $comm->rate*$pro['tax_r']/100;

                                  $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                                  if($pro->vender_offer_price != null){
                                  $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                  }else{
                                  $offer = $pro->vender_offer_price;
                                  }

                                  if($pro->vender_offer_price == NULL){
                                  $show_price = $price;
                                  }else{

                                  $convert_price = $offer;
                                  $show_price = $price;
                                  }

                                  }else{


                                  $price = $pro->vender_price + $comm->rate + $orivar->price;

                                  if($pro->vender_offer_price != NULL){
                                  $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                                  }else{
                                  $offer = $pro->vender_offer_price;
                                  }

                                  if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                  $show_price = $price;
                                  }else{

                                  $convert_price = $offer;
                                  $show_price = $price;
                                  }

                                  }


                                  }
                                  else{

                                  $commission_amount = $comm->rate;

                                  $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                  $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                  $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                  $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                  if($pro->vender_offer_price == NULL){
                                  $show_price = round($buyerprice,2);
                                  }else{
                                  $convert_price = round($buyersaleprice,2);

                                  $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                  $show_price = round($buyerprice,2);
                                  }



                                  }
                                  }else{
                                  $commission_amount = 0;

                                  $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                  $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                  $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                  $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                  if($pro->vender_offer_price == NULL){
                                  $show_price = round($buyerprice,2);
                                  }else{
                                  $convert_price = round($buyersaleprice,2);

                                  $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                  $show_price = round($buyerprice,2);
                                  }
                                  }
                                  }
                                  $convert_price_form = $convert_price;
                                  $show_price_form = $show_price;
                                  $convert_price = $convert_price*$conversion_rate;
                                  $show_price = $show_price*$conversion_rate;

                                  @endphp


                                  @if($convert_price == 0 || $convert_price == 'null')
                                  <span class="price"><i
                                      class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                                  @else
                                  <span class="price"><i
                                      class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                                  <span class="price-before-discount"><i
                                      class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>

                                  @endif



                                  @endif
                              </div>
                              <!-- /.product-price -->
                            </div>
                            <!-- /.product-info -->
                            @if($orivar->products->selling_start_at != null && $orivar->products->selling_start_at >=
                            $current_date)
                            @elseif($orivar->stock < 1) @else <div class="cart clearfix animate-effect">
                              <div class="action">
                                <ul class="list-unstyled">
                                  <li id="addCart" class="lnk wishlist">

                                    @if($price_login != 1)
                                    <form method="POST"
                                      action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price_form, 'varofferprice' => $convert_price_form ,'qty' =>$orivar->min_order_qty])}}">
                                      {{ csrf_field() }}
                                      <button title="{{ __('Add to Cart') }}" type="submit" class="addtocartcus btn">
                                        <i class="fa fa-shopping-cart"></i>
                                      </button>
                                    </form>
                                    @endif


                                  </li>

                                  @auth
                                  @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                                    <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.AddToWishList') }}"
                                      class="cursor-pointer add-to-cart addtowish"
                                      data-add="{{url('AddToWishList/'.$orivar->id)}}" title="Add to wishlist"> <i
                                        class="icon fa fa-heart"></i>
                                    </a>

                                    </li>
                                    @else

                                    @php
                                    $ifinwishlist =
                                    App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                                    @endphp

                                    @if(!empty($ifinwishlist))
                                    <li class="lnk wishlist active">
                                      <a mainid="{{ $orivar->id }}" title="{{ __('RemoveFromWishlist') }}"
                                        class="cursor-pointer color000 add-to-cart removeFrmWish active"
                                        data-remove="{{url('removeWishList/'.$orivar->id)}}" title="Wishlist"> <i
                                          class="icon fa fa-heart"></i> </a>
                                    </li>
                                    @else
                                    <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                                        mainid="{{ $orivar->id }}"
                                        class="cursor-pointer text-white add-to-cart addtowish"
                                        data-add="{{url('AddToWishList/'.$orivar->id)}}" title="Wishlist"> <i
                                          class="activeOne icon fa fa-heart"></i> </a></li>
                                    @endif

                                    @endif
                                    @endauth

                                    <li class="lnk"> <a class="add-to-cart"
                                        href="{{route('compare.product',$orivar->products->id)}}"
                                        title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal"
                                          aria-hidden="true"></i> </a> </li>
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
                  @else

                  @foreach($pro->subvariants as $key=> $orivar)
                  @if($orivar->def == 1)

                  @php
                  $var_name_count = count($orivar['main_attr_id']);

                  $name = array();
                  $var_name;
                  $newarr = array();
                  for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
                    $var_name[$i]=$orivar['main_attr_value'][$var_id];
                    $name[$i]=App\ProductAttributes::where('id',$var_id)->first();

                    }


                    try{
                    $url =
                    url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                    }catch(Exception $e)
                    {
                    $url = url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                    }
                    @endphp

                    <div class="item item-carousel">
                      <div class="products">
                        <div class="product">

                          <div class="product-image">
                            <div class="image {{ $orivar->stock ==0 ? "pro-img-box" : ""}}">

                              <a href="{{$url}}" title="{{$pro->name}}">

                                @if(count($pro->subvariants)>0)

                                <img class="ankit {{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                                  src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                                  alt="{{$pro->name}}">
                                <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}} hover-image"
                                  src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}"
                                  alt="" />

                                @else
                                <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $pro->name }}"
                                  src="{{url('images/no-image.png')}}" alt="No Image" />

                                @endif


                              </a>
                            </div>

                            @if($orivar->stock == 0)
                            <h6 align="center" class="oottext"><span>{{ __('staticwords.Outofstock') }}</span></h6>
                            @endif

                            @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                            $orivar->products->selling_start_at >= $current_date)
                            <h6 align="center" class="oottext2"><span>{{ __('staticwords.ComingSoon') }}</span></h6>
                            @endif
                            <!-- /.image -->

                            @if($pro->featured=="1")
                            <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                            @elseif($pro->offer_price != "0")
                            <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
                            @else
                            <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
                            @endif
                          </div>
                          <!-- /.product-image -->

                          <div class="product-info text-left">
                            <h3 class="name"><a href="{{ $url }}"
                                title="{{$pro->name}}">{{substr($pro->name, 0, 20)}}{{strlen($pro->name)>20 ? '...' : ""}}</a>
                            </h3>
                            <?php 
                                    $reviews = App\UserReview::where('pro_id',$pro->id)->where('status','1')->get();
                                    ?> @if(!empty($reviews[0]))<?php
                                    $review_t = 0;
                                    $price_t = 0;
                                    $value_t = 0;
                                    $sub_total = 0;
                                    $count =  App\UserReview::where('pro_id',$pro->id)->count();
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
                              <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                                  class="star-ratings-sprite-rating"></span></div>
                            </div>


                            @else
                            <div class="no-rating">{{'No Rating'}}</div>
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

                                if($pro->tax_r !=''){

                                $cit =$commission_amount*$pro->tax_r/100;
                                $totalprice = $pro->vender_price+$orivar->price+$commission_amount+$cit;
                                $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price + $commission_amount;

                                if($pro->vender_offer_price == 0){
                                $show_price = $totalprice;
                                }else{
                                $totalsaleprice;
                                $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                $show_price = $totalprice;
                                }


                                }else{
                                $totalprice = $pro->vender_price+$orivar->price+$commission_amount;
                                $totalsaleprice = $pro->vender_offer_price + $orivar->price + $commission_amount;

                                if($pro->vender_offer_price == NULL){
                                $show_price = $totalprice;
                                }else{
                                $totalsaleprice;
                                $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                $show_price = $totalprice;
                                }

                                }

                                }else{

                                $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($pro->vender_offer_price == NULL){
                                $show_price = round($buyerprice,2);
                                }else{
                                round($buyersaleprice,2);

                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = $buyerprice;
                                }


                                }
                                }else{

                                $comm = App\Commission::where('category_id',$pro->category_id)->first();
                                if(isset($comm)){
                                if($comm->type=='f'){

                                if($pro->tax_r != ''){

                                $cit = $comm->rate*$pro['tax_r']/100;

                                $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                                if($pro->vender_offer_price != NULL){
                                $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                }else{
                                $offer = $pro->vender_offer_price;
                                }

                                if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                $show_price = $price;
                                }else{

                                $convert_price = $offer;
                                $show_price = $price;
                                }

                                }else{


                                $price = $pro->vender_price + $comm->rate + $orivar->price;

                                if($pro->vender_offer_price != NULL){
                                $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                                }else{
                                $offer = $pro->vender_offer_price;
                                }

                                if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                $show_price = $price;
                                }else{

                                $convert_price = $offer;
                                $show_price = $price;
                                }

                                }


                                }
                                else{

                                $commission_amount = $comm->rate;

                                $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($pro->vender_offer_price == 0){
                                $show_price = round($buyerprice,2);
                                }else{
                                $convert_price = round($buyersaleprice,2);

                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = round($buyerprice,2);
                                }



                                }
                                }else{
                                $commission_amount = 0;

                                $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($pro->vender_offer_price == 0){
                                $show_price = round($buyerprice,2);
                                }else{
                                $convert_price = round($buyersaleprice,2);

                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = round($buyerprice,2);
                                }

                                }

                                }
                                $convert_price_form = $convert_price;
                                $show_price_form = $show_price;
                                $convert_price = $convert_price*$conversion_rate;
                                $show_price = $show_price*$conversion_rate;

                                @endphp



                                @if($convert_price == 0 || $convert_price == 'null')
                                <span class="price"><i
                                    class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                                @else
                                <span class="price"><i
                                    class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                                <span class="price-before-discount"><i
                                    class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>

                                @endif


                                @endif
                            </div>
                            <!-- /.product-price -->
                          </div>
                          <!-- /.product-info -->
                          @if($orivar->products->selling_start_at != null && $orivar->products->selling_start_at >=
                          $current_date)
                          @elseif($orivar->stock < 1) @else <div class="cart clearfix animate-effect">
                            <div class="action">
                              <ul class="list-unstyled">
                                <li id="addCart" class="lnk wishlist">

                                  @if($price_login != 1)
                                  <form method="POST"
                                    action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price_form, 'varofferprice' => $convert_price_form ,'qty' =>$orivar->min_order_qty])}}">
                                    {{ csrf_field() }}
                                    <button title="{{ __('staticwords.AddtoCart') }}" type="submit"
                                      class="addtocartcus btn">
                                      <i class="fa fa-shopping-cart"></i>
                                    </button>
                                  </form>
                                  @endif


                                </li>

                                @auth
                                @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                                  <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.AddToWishList') }}"
                                    class="cursor-pointer add-to-cart addtowish"
                                    data-add="{{url('AddToWishList/'.$orivar->id)}}"
                                    title="{{ __('staticwords.AddToWishList') }}"> <i class="icon fa fa-heart"></i>
                                  </a>

                                  </li>
                                  @else

                                  @php
                                  $ifinwishlist =
                                  App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                                  @endphp

                                  @if(!empty($ifinwishlist))
                                  <li class="lnk wishlist active">
                                    <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.RemoveFromWishlist') }}"
                                      class="cursor-pointer color000 add-to-cart removeFrmWish active"
                                      data-remove="{{url('removeWishList/'.$orivar->id)}}" title="Wishlist"> <i
                                        class="icon fa fa-heart"></i> </a>
                                  </li>
                                  @else
                                  <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                                      mainid="{{ $orivar->id }}" class="cursor-pointer text-white add-to-cart addtowish"
                                      data-add="{{url('AddToWishList/'.$orivar->id)}}" title="Wishlist"> <i
                                        class="activeOne icon fa fa-heart"></i> </a></li>
                                  @endif

                                  @endif
                                  @endauth

                                  <li class="lnk"> <a class="add-to-cart"
                                      href="{{route('compare.product',$orivar->products->id)}}"
                                      title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal"
                                        aria-hidden="true"></i> </a> </li>
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

                <?php $i++; 
                         $countP++; ?>
                @endforeach
                @endisset
                <!-- /.item -->
              </div>
              <!-- /.home-owl-carousel -->
            </div>
            <!-- /.product-slider -->
          </div>
          <!-- /.tab-pane -->
          @php
          $abcd = App\FrontCat::first();
          $parents = explode(",",$abcd->name);
          @endphp
          @if(isset($abcd) && $abcd->status == '1')
          @foreach($parents as $parent)

          <?php $parentcats = App\Category::where('status','1')->where('id',$parent)->first();
                      ?>
          @if(isset($parentcats))
          <div class="tab-pane" id="pro{{ $parentcats->id }}">

            <div class="product-slider">

              <div class="owl-carousel home-owl-carousel custom-carousel owl-theme">

                @foreach(App\Product::where('category_id',$parentcats->id)->take(10)->get() as $pro)
                @if($genrals_settings->vendor_enable != 1)
                @if($pro->vender['role_id'] == 'a')
                @foreach($pro->subvariants as $key=> $orivar)
                @if($orivar->def ==1)

                @php
                $var_name_count = count($orivar['main_attr_id']);

                $name = array();
                $var_name;
                $newarr = array();
                for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
                  $var_name[$i]=$orivar['main_attr_value'][$var_id];
                  $name[$i]=App\ProductAttributes::where('id',$var_id)->first();

                  }


                  try{
                  $url =
                  url(url('details').'/').$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                  }catch(Exception $e)
                  {
                  $url = url(url('details').'/').$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                  }

                  @endphp
                  <div class="item item-carousel">
                    <div class="products">
                      <div class="product">
                        <div class="product-image">
                          <div class="image {{ $orivar->stock ==0 ? "pro-img-box" : ""}}">

                            <a href="{{$url}}" title="{{$pro->name}}">

                              @if(count($pro->subvariants)>0)

                              @if(isset($orivar->variantimages['main_image']))
                              <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                                src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                                alt="{{$pro->name}}">
                              <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}  hover-image"
                                src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}"
                                alt="" />
                              @endif

                              @else
                              <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $pro->name }}"
                                src="{{url('images/no-image.png')}}" alt="No Image" />

                              @endif



                            </a>
                          </div>
                          <!-- /.image -->

                          @if($orivar->stock == 0)
                          <h6 align="center" class="oottext"><span>{{ __('staticwords.Outofstock') }}</span></h6>
                          @endif

                          @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                          $orivar->products->selling_start_at >= $current_date)
                          <h6 align="center" class="oottext2"><span>{{ __('staticwords.ComingSoon') }}</span></h6>
                          @endif

                          @if($pro->featured=="1")
                          <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                          @elseif($pro->offer_price !="0")
                          <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
                          @else
                          <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
                          @endif
                        </div>
                        <!-- /.product-image -->

                        <div class="product-info text-left">
                          <h3 class="name"><a href="{{ $url }}"
                              title="{{$pro->name}}">{{substr($pro->name, 0, 20)}}{{strlen($pro->name)>20 ? '...' : ""}}</a>
                          </h3>
                          <?php 
                                  $review_t = 0;
                                  $price_t = 0;
                                  $value_t = 0;
                                  $sub_total = 0;
                                   $reviews = App\UserReview::where('pro_id',$pro->id)->where('status','1')->get();
                                  ?> @if(!empty($reviews[0]))<?php
                                  $count =  App\UserReview::where('pro_id',$pro->id)->count();
                                    foreach($reviews as $review){
                                      $review_t = $review->price*5;
                                      $price_t = $review->price*5;
                                      $value_t = $review->value*5;
                                      $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                    }
                                    $count = ($count*3) * 5;
                                    $rat = $sub_total/$count;
                                    $ratings_var = ($rat*100)/5;
                                    ?><div class="pull-left">
                            <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                                class="star-ratings-sprite-rating"></span></div>
                          </div>

                          @else
                          {{"No Rating"}}
                          @endif

                          <div class="product-price"> <span class="price">

                              <div class="product-price"> <span class="price">

                                  @if($price_login == 0 || Auth::check())
                                  @php
                                  $convert_price = 0;
                                  $show_price = 0;

                                  $commision_setting = App\CommissionSetting::first();

                                  if($commision_setting->type == "flat"){

                                  $commission_amount = $commision_setting->rate;
                                  if($commision_setting->p_type == 'f'){

                                  if($pro->tax_r !=''){

                                  $cit =$commission_amount*$pro->tax_r/100;
                                  $totalprice = $pro->vender_price+$orivar->price+$commission_amount+$cit;
                                  $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price +
                                  $commission_amount;

                                  if($pro->vender_offer_price == 0){
                                  $show_price = $totalprice;
                                  }else{
                                  $totalsaleprice;
                                  $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                  $show_price = $totalprice;
                                  }


                                  }else{
                                  $totalprice = $pro->vender_price+$orivar->price+$commission_amount;
                                  $totalsaleprice = $pro->vender_offer_price + $orivar->price + $commission_amount;

                                  if($pro->vender_offer_price == 0){
                                  $show_price = $totalprice;
                                  }else{
                                  $totalsaleprice;
                                  $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                  $show_price = $totalprice;
                                  }

                                  }


                                  }else{

                                  $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                  $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                  $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                  $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                  if($pro->vender_offer_price ==0){
                                  $show_price = round($buyerprice,2);
                                  }else{
                                  round($buyersaleprice,2);
                                  $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                  $show_price = $buyerprice;
                                  }


                                  }
                                  }else{

                                  $comm = App\Commission::where('category_id',$pro->category_id)->first();
                                  if(isset($comm)){
                                  if($comm->type=='f'){


                                  if($pro->tax_r != ''){

                                  $cit = $comm->rate*$pro['tax_r']/100;

                                  $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                                  if($pro->vender_offer_price != null){
                                  $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                  }else{
                                  $offer = $pro->vender_offer_price;
                                  }

                                  if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                  $show_price = $price;
                                  }else{

                                  $convert_price = $offer;
                                  $show_price = $price;
                                  }

                                  }else{


                                  $price = $pro->vender_price + $comm->rate + $orivar->price;

                                  if($pro->vender_offer_price != null){
                                  $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                                  }else{
                                  $offer = $pro->vender_offer_price;
                                  }

                                  if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                  $show_price = $price;
                                  }else{

                                  $convert_price = $offer;
                                  $show_price = $price;
                                  }

                                  }



                                  }
                                  else{

                                  $commission_amount = $comm->rate;

                                  $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                  $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                  $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                  $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                  if($pro->vender_offer_price == 0){
                                  $show_price = round($buyerprice,2);
                                  }else{
                                  round($buyersaleprice,2);

                                  $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                  $show_price = round($buyerprice,2);
                                  }



                                  }
                                  }else{
                                  $commission_amount = 0;

                                  $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                  $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                  $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                  $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                  if($pro->vender_offer_price == 0){
                                  $show_price = round($buyerprice,2);
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


                                  @if($convert_price == 0 || $convert_price == 'null')
                                  <span class="price"><i
                                      class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                                  @else

                                  <span class="price"><i
                                      class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                                  <span class="price-before-discount"><i
                                      class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>


                                  @endif


                                  @endif
                              </div>
                          </div>
                          <!-- /.product-price -->


                        </div>
                        <!-- /.product-info -->
                        @if($orivar->products->selling_start_at != null && $orivar->products->selling_start_at >=
                        $current_date)
                        @elseif($orivar->stock < 1) @else <div class="cart clearfix animate-effect">
                          <div class="action">
                            <ul class="list-unstyled">
                              <li class="lnk wishlist">
                                <form method="POST"
                                  action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                                  {{ csrf_field() }}
                                  <button title="{{ __('staticwords.AddtoCart') }}" type="submit"
                                    class="addtocartcus btn">
                                    <i class="fa fa-shopping-cart"></i>
                                  </button>
                                </form>
                              </li>

                              @auth
                              @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                                <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.AddToWishList') }}"
                                  class="add-to-cart addtowish" data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i
                                    class="icon fa fa-heart"></i>
                                </a>

                                </li>
                                @else

                                @php
                                $ifinwishlist =
                                App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                                @endphp



                                @if(!empty($ifinwishlist))
                                <li class="lnk wishlist active"> <a mainid="{{ $orivar->id }}"
                                    title="{{ __('staticwords.RemoveFromWishlist') }}"
                                    class="color000 add-to-cart removeFrmWish active"
                                    data-remove="{{url('removeWishList/'.$orivar->id)}}"
                                    title="{{ __('staticwords.Wishlist') }}"> <i class="icon fa fa-heart"></i> </a>
                                </li>
                                @else
                                <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                                    mainid="{{ $orivar->id }}" class="add-to-cart addtowish text-white"
                                    data-add="{{url('AddToWishList/'.$orivar->id)}}" title="Wishlist"> <i
                                      class="activeOne icon fa fa-heart"></i> </a></li>
                                @endif

                                @endif
                                @endauth

                                <li class="lnk"> <a class="add-to-cart"
                                    href="{{route('compare.product',$orivar->products->id)}}"
                                    title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal"
                                      aria-hidden="true"></i> </a> </li>
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
              @else
              @foreach($pro->subvariants as $key=> $orivar)
              @if($orivar->def ==1)

              @php
              $var_name_count = count($orivar['main_attr_id']);

              $name = array();
              $var_name;
              $newarr = array();
              for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
                $var_name[$i]=$orivar['main_attr_value'][$var_id];
                $name[$i]=App\ProductAttributes::where('id',$var_id)->first();

                }


                try{
                $url =
                url(url('details').'/').$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                }catch(Exception $e)
                {
                $url = url(url('details').'/').$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                }

                @endphp
                <div class="item item-carousel">
                  <div class="products">
                    <div class="product">
                      <div class="product-image">
                        <div class="image {{ $orivar->stock ==0 ? "pro-img-box" : ""}}">

                          <a href="{{$url}}" title="{{$pro->name}}">

                            @if(count($pro->subvariants)>0)

                            @if(isset($orivar->variantimages['main_image']))
                            <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                              src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                              alt="{{$pro->name}}">
                            <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}  hover-image"
                              src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt="" />
                            @endif

                            @else
                            <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $pro->name }}"
                              src="{{url('images/no-image.png')}}" alt="No Image" />

                            @endif



                          </a>
                        </div>
                        <!-- /.image -->

                        @if($orivar->stock == 0)
                        <h6 align="center" class="oottext"><span>{{ __('staticwords.Outofstock') }}</span></h6>
                        @endif

                        @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                        $orivar->products->selling_start_at >= $current_date)
                        <h6 align="center" class="oottext2"><span>{{ __('staticwords.ComingSoon') }}</span></h6>
                        @endif

                        @if($pro->featured=="1")
                        <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                        @elseif($pro->offer_price !="0")
                        <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
                        @else
                        <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
                        @endif
                      </div>
                      <!-- /.product-image -->

                      <div class="product-info text-left">
                        <h3 class="name"><a href="{{ $url }}"
                            title="{{$pro->name}}">{{substr($pro->name, 0, 20)}}{{strlen($pro->name)>20 ? '...' : ""}}</a>
                        </h3>
                        <?php 
                                  $review_t = 0;
                                  $price_t = 0;
                                  $value_t = 0;
                                  $sub_total = 0;
                                   $reviews = App\UserReview::where('pro_id',$pro->id)->where('status','1')->get();
                                  ?> @if(!empty($reviews[0]))<?php
                                  $count =  App\UserReview::where('pro_id',$pro->id)->count();
                                    foreach($reviews as $review){
                                      $review_t = $review->price*5;
                                      $price_t = $review->price*5;
                                      $value_t = $review->value*5;
                                      $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                    }
                                    $count = ($count*3) * 5;
                                    $rat = $sub_total/$count;
                                    $ratings_var = ($rat*100)/5;
                                    ?><div class="pull-left">
                          <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                              class="star-ratings-sprite-rating"></span></div>
                        </div>

                        @else
                        {{ __("No Rating") }}
                        @endif

                        <div class="product-price"> <span class="price">

                            <div class="product-price"> <span class="price">

                                @if($price_login == 0 || Auth::check())
                                @php
                                $convert_price = 0;
                                $show_price = 0;

                                $commision_setting = App\CommissionSetting::first();

                                if($commision_setting->type == "flat"){

                                $commission_amount = $commision_setting->rate;
                                if($commision_setting->p_type == 'f'){

                                if($pro->tax_r !=''){

                                $cit =$commission_amount*$pro->tax_r/100;
                                $totalprice = $pro->vender_price+$orivar->price+$commission_amount+$cit;
                                $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price + $commission_amount;

                                if($pro->vender_offer_price == 0){
                                $show_price = $totalprice;
                                }else{
                                $totalsaleprice;
                                $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                $show_price = $totalprice;
                                }


                                }else{
                                $totalprice = $pro->vender_price+$orivar->price+$commission_amount;
                                $totalsaleprice = $pro->vender_offer_price + $orivar->price + $commission_amount;

                                if($pro->vender_offer_price == 0){
                                $show_price = $totalprice;
                                }else{
                                $totalsaleprice;
                                $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                $show_price = $totalprice;
                                }

                                }


                                }else{

                                $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($pro->vender_offer_price ==0){
                                $show_price = round($buyerprice,2);
                                }else{
                                round($buyersaleprice,2);
                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = $buyerprice;
                                }


                                }
                                }else{

                                $comm = App\Commission::where('category_id',$pro->category_id)->first();
                                if(isset($comm)){
                                if($comm->type=='f'){


                                if($pro->tax_r != ''){

                                $cit = $comm->rate*$pro['tax_r']/100;

                                $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                                if($pro->vender_offer_price != null){
                                $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                }else{
                                $offer = $pro->vender_offer_price;
                                }

                                if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                $show_price = $price;
                                }else{

                                $convert_price = $offer;
                                $show_price = $price;
                                }

                                }else{


                                $price = $pro->vender_price + $comm->rate + $orivar->price;

                                if($pro->vender_offer_price != null){
                                $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                                }else{
                                $offer = $pro->vender_offer_price;
                                }

                                if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                $show_price = $price;
                                }else{

                                $convert_price = $offer;
                                $show_price = $price;
                                }

                                }



                                }
                                else{

                                $commission_amount = $comm->rate;

                                $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($pro->vender_offer_price == 0){
                                $show_price = round($buyerprice,2);
                                }else{
                                round($buyersaleprice,2);

                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = round($buyerprice,2);
                                }



                                }
                                }else{
                                $commission_amount = 0;

                                $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($pro->vender_offer_price == 0){
                                $show_price = round($buyerprice,2);
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
                                <span class="price"><i
                                    class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                                @else

                                <span class="price"><i
                                    class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                                <span class="price-before-discount"><i
                                    class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>


                                @endif


                                @endif

                                @endif
                            </div>
                        </div>
                        <!-- /.product-price -->


                      </div>
                      <!-- /.product-info -->
                      @if($orivar->products->selling_start_at != null && $orivar->products->selling_start_at >=
                      $current_date)
                      @elseif($orivar->stock < 1) @else <div class="cart clearfix animate-effect">
                        <div class="action">
                          <ul class="list-unstyled">
                            <li class="lnk wishlist">
                              @if($price_login != 1)
                              <form method="POST"
                                action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                                {{ csrf_field() }}
                                <button title="{{ __('staticwords.AddtoCart') }}" type="submit"
                                  class="addtocartcus btn">
                                  <i class="fa fa-shopping-cart"></i>
                                </button>
                              </form>
                              @endif
                            </li>

                            @auth
                            @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                              <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.AddToWishList') }}"
                                class="add-to-cart addtowish" data-add="{{url('AddToWishList/'.$orivar->id)}}"
                                title="Add to wishlist"> <i class="icon fa fa-heart"></i>
                              </a>

                              </li>
                              @else

                              @php
                              $ifinwishlist =
                              App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                              @endphp



                              @if(!empty($ifinwishlist))
                              <li class="lnk wishlist active"> <a mainid="{{ $orivar->id }}"
                                  title="Remove From wishlist" class="color000 add-to-cart removeFrmWish active"
                                  data-remove="{{url('removeWishList/'.$orivar->id)}}" title="{{ __('Wishlist') }}"> <i
                                    class="icon fa fa-heart"></i> </a>
                              </li>
                              @else
                              <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                                  mainid="{{ $orivar->id }}" class="add-to-cart addtowish text-white"
                                  data-add="{{url('AddToWishList/'.$orivar->id)}}" title="Wishlist"> <i
                                    class="activeOne icon fa fa-heart"></i> </a></li>
                              @endif

                              @endif
                              @endauth

                              <li class="lnk"> <a class="add-to-cart"
                                  href="{{route('compare.product',$orivar->products->id)}}"
                                  title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal"
                                    aria-hidden="true"></i> </a> </li>
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
          </div>

        </div>

      </div>

      @endif

      @endforeach

      @endif
      <!-- /.tab-pane -->

    </div>
    <!-- /.tab-content -->
  </div>
  @endif

  <!-- ============================= small screen product-tab-slider : start ============================================== -->
  <!-- ==============================================  ============================================== -->

  <section id="home-product-tab" class="home-product-tab-main-block">
    <div class="container">
      <div class="row home-product-tab">
        <ul class="nav nav-tabs">
          <li class="nav-item tab-width"><a data-toggle="tab" class="nav-link active"
              href="#home">{{ __('staticwords.newprods') }}</a></li>
          <li class="nav-item tab-width"><a class="nav-link" data-toggle="tab"
              href="#menu1">{{ __('staticwords.tpc') }}</a></li>
          <li class="nav-item tab-width"><a class="nav-link" data-toggle="tab"
              href="#menu2">{{ __('staticwords.Featured') }}</a></li>
        </ul>
        <div class="tab-content">
          <div id="home" class="tab-pane fade in show active">
            <div class="new-product-block">
              <div class="container">
                <div class="row">
                  @if(isset($abcd) && $abcd->status == '1')
                  <?php $parents = explode(",",$abcd->name);?>
                  <div class="small-screen-scroll-tabs scroll-tabs outer-top-vs">
                    <div class="more-info-tab clearfix display-none">
                      <h3 class="new-product-title pull-left">New Products</h3>
                      <div class="scroller scroller-left"><i class="fa fa-angle-left"></i></div>
                      <div class="scroller scroller-right"><i class="fa fa-angle-right"></i></div>
                      <div class="wrapper">
                        <ul class="nav nav-tabs nav-tab-line pull-right list" id="new-products-1">
                          <li class="active"><a data-transition-type="backSlide" href="#all"
                              data-toggle="tab">{{ __('staticwords.All') }}</a></li>


                          @foreach($parents as $parent)
                          <?php $parentcats = App\Category::where('status','1')->where('id',$parent)->first(); ?>
                          @if(isset($parentcats))
                          <li><a data-transition-type="backSlide" href="#pro{{$parentcats->id}}" data-toggle="tab"
                              title="{{$parentcats->title}}">{{$parentcats->title}}</a></li>
                          @endif
                          @endforeach

                        </ul>
                      </div>
                      <!-- /.nav-tabs -->
                    </div>
                    <div class="tab-content outer-top-xs">
                      <div class="tab-pane in active" id="all">
                        <div class="product-slider">

                          <div class="product-slider-main-block">
                            <div class="row no-pad">

                              <!-- /.item -->
                              @if(isset($products))
                              <?php $i = 0;
                                                            $countP = 0;
                                                           ?>
                              @foreach($products as $pro)

                              @if($genrals_settings->vendor_enable != 1)

                              @if($pro->vender['role_id'] == 'a')
                              @foreach($pro->subvariants as $key=> $orivar)
                              @if($orivar->def == 1)

                              @php
                              $var_name_count = count($orivar['main_attr_id']);

                              $name;
                              $var_name;
                              $newarr = array();
                              for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
                                $var_name[$i]=$orivar['main_attr_value'][$var_id];
                                $name[$i]=App\ProductAttributes::where('id',$var_id)->first();

                                }


                                try{
                                $url =
                                url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                                }catch(Exception $e)
                                {
                                $url = url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                                }
                                @endphp

                                <div class="col-6">
                                  <div class="item item-carousel">
                                    <div class="products">
                                      <div class="product">

                                        <div class="product-image">
                                          <div class="image {{ $orivar->stock ==0 ? "pro_img-box" : ""}}">

                                            <a href="{{$url}}" title="{{$pro->name}}">

                                              @if(count($pro->subvariants)>0)

                                              @if(isset($orivar->variantimages['main_image']))
                                              <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                                                src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                                                alt="{{$pro->name}}">
                                              <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}} hover-image"
                                                src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}"
                                                alt="" />
                                              @endif

                                              @else
                                              <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                                                title="{{ $pro->name }}" src="{{url('images/no-image.png')}}"
                                                alt="No Image" />

                                              @endif

                                              @if($orivar->stock == 0)
                                              <h6 align="center" class="oottext">
                                                <span>{{ __('staticwords.Outofstock') }}</span></h6>
                                              @endif



                                              @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                                              $orivar->products->selling_start_at >= $current_date)
                                              <h6 align="center" class="oottext2">
                                                <span>{{ __('staticwords.ComingSoon') }}</span></h6>
                                              @endif

                                            </a>
                                          </div>
                                          <!-- /.image -->

                                          @if($pro->featured=="1")
                                          <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                                          @elseif($pro->offer_price !="0")
                                          <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
                                          @else
                                          <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
                                          @endif
                                        </div>
                                        <!-- /.product-image -->

                                        <div class="product-info text-left">
                                          <h3 class="name"><a href="{{ $url }}"
                                              title="{{$pro->name}}">{{ substr($pro->name,0,10) }}{{ strlen($pro->name)>10 ? "..." : "" }}</a>
                                          </h3>
                                          <?php 
                                                                              $reviews = App\UserReview::where('pro_id',$pro->id)->where('status','1')->get();
                                                                              ?> @if(!empty($reviews[0]))<?php
                                                                              $review_t = 0;
                                                                              $price_t = 0;
                                                                              $value_t = 0;
                                                                              $sub_total = 0;
                                                                              $count =  App\UserReview::where('pro_id',$pro->id)->count();
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
                                            <div class="star-ratings-sprite"><span
                                                style="width:<?php echo $ratings_var; ?>%"
                                                class="star-ratings-sprite-rating"></span></div>
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

                                              if($pro->tax_r !=''){

                                              $cit =$commission_amount*$pro->tax_r/100;
                                              $totalprice = $pro->vender_price+$orivar->price+$commission_amount+$cit;
                                              $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price +
                                              $commission_amount;

                                              if($pro->vender_offer_price == 0){
                                              $show_price = $totalprice;
                                              }else{
                                              $totalsaleprice;
                                              $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                              $show_price = $totalprice;
                                              }


                                              }else{
                                              $totalprice = $pro->vender_price+$orivar->price+$commission_amount;
                                              $totalsaleprice = $pro->vender_offer_price + $orivar->price +
                                              $commission_amount;

                                              if($pro->vender_offer_price == 0){
                                              $show_price = $totalprice;
                                              }else{
                                              $totalsaleprice;
                                              $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                              $show_price = $totalprice;
                                              }

                                              }

                                              }else{

                                              $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                              $totalsaleprice =
                                              ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                              $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                              $buyersaleprice =
                                              ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                              if($pro->vender_offer_price ==0){
                                              $show_price = round($buyerprice,2);
                                              }else{
                                              round($buyersaleprice,2);

                                              $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                              $show_price = $buyerprice;
                                              }


                                              }
                                              }else{

                                              $comm = App\Commission::where('category_id',$pro->category_id)->first();
                                              if(isset($comm)){
                                              if($comm->type=='f'){

                                              if($pro->tax_r != ''){

                                              $cit = $comm->rate*$pro['tax_r']/100;

                                              $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                                              if($pro->vender_offer_price != null){
                                              $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                              }else{
                                              $offer = $pro->vender_offer_price;
                                              }

                                              if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                              $show_price = $price;
                                              }else{

                                              $convert_price = $offer;
                                              $show_price = $price;
                                              }

                                              }else{


                                              $price = $pro->vender_price + $comm->rate + $orivar->price;

                                              if($pro->vender_offer_price != null){
                                              $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                                              }else{
                                              $offer = $pro->vender_offer_price;
                                              }

                                              if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                              $show_price = $price;
                                              }else{

                                              $convert_price = $offer;
                                              $show_price = $price;
                                              }

                                              }


                                              }
                                              else{

                                              $commission_amount = $comm->rate;

                                              $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                              $totalsaleprice =
                                              ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                              $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                              $buyersaleprice =
                                              ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                              if($pro->vender_offer_price == 0){
                                              $convert_price = round($buyerprice,2);
                                              }else{
                                              $convert_price = round($buyersaleprice,2);

                                              $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                              $show_price = round($buyerprice,2);
                                              }



                                              }
                                              }else{
                                              $commission_amount = 0;

                                              $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                              $totalsaleprice =
                                              ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                              $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                              $buyersaleprice =
                                              ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                              if($pro->vender_offer_price == 0){
                                              $convert_price = round($buyerprice,2);
                                              }else{
                                              $convert_price = round($buyersaleprice,2);

                                              $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                              $show_price = round($buyerprice,2);
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
                                              <span class="price"><i
                                                  class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                                              @else
                                              <span class="price"><i
                                                  class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                                              <span class="price-before-discount"><i
                                                  class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>

                                              @endif



                                              @endif

                                              @endif
                                          </div>
                                          <!-- /.product-price -->
                                        </div>
                                        <!-- /.product-info -->
                                        @if($orivar->products->selling_start_at != null &&
                                        $orivar->products->selling_start_at >= $current_date)
                                        @elseif($orivar->stock < 1) @else <div class="cart clearfix animate-effect">
                                          <div class="action">
                                            <ul class="list-unstyled">
                                              <li id="addCart" class="lnk wishlist">

                                                <form method="POST"
                                                  action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price_form, 'varofferprice' => $convert_price_form ,'qty' =>$orivar->min_order_qty])}}">
                                                  {{ csrf_field() }}
                                                  <button title="{{ __('staticwords.AddtoCart') }}" type="submit"
                                                    class="addtocartcus btn">
                                                    <i class="fa fa-shopping-cart"></i>
                                                  </button>
                                                </form>


                                              </li>

                                              @auth
                                              @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                                                <a mainid="{{ $orivar->id }}"
                                                  title="{{ __('staticwords.AddToWishList') }}"
                                                  class="cursor-pointer add-to-cart addtowish"
                                                  data-add="{{url('AddToWishList/'.$orivar->id)}}"
                                                  title="{{ __('staticwords.AddToWishList') }}"> <i
                                                    class="icon fa fa-heart"></i>
                                                </a>

                                                </li>
                                                @else

                                                @php
                                                $ifinwishlist =
                                                App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                                                @endphp

                                                @if(!empty($ifinwishlist))
                                                <li class="lnk wishlist active">
                                                  <a mainid="{{ $orivar->id }}"
                                                    title="{{ __('staticwords.RemoveFromWishlist') }}"
                                                    class="color000 cursor-pointer add-to-cart removeFrmWish active"
                                                    data-remove="{{url('removeWishList/'.$orivar->id)}}"
                                                    title="{{ __('staticwords.Wishlist') }}"> <i
                                                      class="icon fa fa-heart"></i> </a>
                                                </li>
                                                @else
                                                <li class="lnk wishlist"> <a
                                                    title="{{ __('staticwords.AddToWishList') }}"
                                                    mainid="{{ $orivar->id }}"
                                                    class="add-to-cart addtowish cursor-pointer text-white"
                                                    data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i
                                                      class="activeOne icon fa fa-heart"></i> </a></li>
                                                @endif

                                                @endif
                                                @endauth

                                                <li class="lnk"> <a class="add-to-cart"
                                                    href="{{route('compare.product',$orivar->products->id)}}"
                                                    title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal"
                                                      aria-hidden="true"></i> </a> </li>
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
                            </div>

                            @endif
                            @endforeach
                            @endif

                            @else
                            @foreach($pro->subvariants as $key=> $orivar)
                            @if($orivar->def == 1)

                            @php
                            $var_name_count = count($orivar['main_attr_id']);

                            $name;
                            $var_name;
                            $newarr = array();
                            for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
                              $var_name[$i]=$orivar['main_attr_value'][$var_id];
                              $name[$i]=App\ProductAttributes::where('id',$var_id)->first();

                              }


                              try{
                              $url =
                              url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                              }catch(Exception $e)
                              {
                              $url = url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                              }
                              @endphp

                              <div class="col-6">
                                <div class="item item-carousel">
                                  <div class="products">
                                    <div class="product">

                                      <div class="product-image">
                                        <div class="image {{ $orivar->stock ==0 ? "pro_img-box" : ""}}">

                                          <a href="{{$url}}" title="{{$pro->name}}">

                                            @if(count($pro->subvariants)>0)

                                            @if(isset($orivar->variantimages['main_image']))
                                            <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                                              src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                                              alt="{{$pro->name}}">
                                            <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}} hover-image"
                                              src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}"
                                              alt="" />
                                            @endif

                                            @else
                                            <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                                              title="{{ $pro->name }}" src="{{url('images/no-image.png')}}"
                                              alt="No Image" />

                                            @endif

                                            @if($orivar->stock == 0)
                                            <h6 align="center" class="oottext">
                                              <span>{{ __('staticwords.Outofstock') }}</span></h6>
                                            @endif



                                            @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                                            $orivar->products->selling_start_at >= $current_date)
                                            <h6 align="center" class="oottext2">
                                              <span>{{ __('staticwords.ComingSoon') }}</span></h6>
                                            @endif

                                          </a>
                                        </div>
                                        <!-- /.image -->

                                        @if($pro->featured=="1")
                                        <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                                        @elseif($pro->offer_price !="0")
                                        <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
                                        @else
                                        <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
                                        @endif
                                      </div>
                                      <!-- /.product-image -->

                                      <div class="product-info text-left">
                                        <h3 class="name"><a href="{{ $url }}"
                                            title="{{$pro->name}}">{{ substr($pro->name,0,10) }}{{ strlen($pro->name)>10 ? "..." : "" }}</a>
                                        </h3>
                                        <?php 
                                                                              $reviews = App\UserReview::where('pro_id',$pro->id)->where('status','1')->get();
                                                                              ?> @if(!empty($reviews[0]))<?php
                                                                              $review_t = 0;
                                                                              $price_t = 0;
                                                                              $value_t = 0;
                                                                              $sub_total = 0;
                                                                              $count =  App\UserReview::where('pro_id',$pro->id)->count();
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
                                          <div class="star-ratings-sprite"><span
                                              style="width:<?php echo $ratings_var; ?>%"
                                              class="star-ratings-sprite-rating"></span></div>
                                        </div>


                                        @else
                                        <div class="no-rating">{{'No Rating'}}</div>
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

                                            if($pro->tax_r !=''){

                                            $cit =$commission_amount*$pro->tax_r/100;
                                            $totalprice = $pro->vender_price+$orivar->price+$commission_amount+$cit;
                                            $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price +
                                            $commission_amount;

                                            if($pro->vender_offer_price == 0){
                                            $show_price = $totalprice;
                                            }else{
                                            $totalsaleprice;
                                            $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                            $show_price = $totalprice;
                                            }


                                            }else{
                                            $totalprice = $pro->vender_price+$orivar->price+$commission_amount;
                                            $totalsaleprice = $pro->vender_offer_price + $orivar->price +
                                            $commission_amount;

                                            if($pro->vender_offer_price == 0){
                                            $show_price = $totalprice;
                                            }else{
                                            $totalsaleprice;
                                            $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                            $show_price = $totalprice;
                                            }

                                            }

                                            }else{

                                            $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                            $totalsaleprice =
                                            ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                            $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                            $buyersaleprice =
                                            ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                            if($pro->vender_offer_price ==0){
                                            $show_price = round($buyerprice,2);
                                            }else{
                                            round($buyersaleprice,2);

                                            $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                            $show_price = $buyerprice;
                                            }


                                            }
                                            }else{

                                            $comm = App\Commission::where('category_id',$pro->category_id)->first();
                                            if(isset($comm)){
                                            if($comm->type=='f'){

                                            if($pro->tax_r != ''){

                                            $cit = $comm->rate*$pro['tax_r']/100;

                                            $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                                            if($pro->vender_offer_price != null){
                                            $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                            }else{
                                            $offer = $pro->vender_offer_price;
                                            }

                                            if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                            $show_price = $price;
                                            }else{

                                            $convert_price = $offer;
                                            $show_price = $price;
                                            }

                                            }else{


                                            $price = $pro->vender_price + $comm->rate + $orivar->price;

                                            if($pro->vender_offer_price != null){
                                            $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                                            }else{
                                            $offer = $pro->vender_offer_price;
                                            }

                                            if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                            $show_price = $price;
                                            }else{

                                            $convert_price = $offer;
                                            $show_price = $price;
                                            }

                                            }


                                            }
                                            else{

                                            $commission_amount = $comm->rate;

                                            $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                            $totalsaleprice =
                                            ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                            $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                            $buyersaleprice =
                                            ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                            if($pro->vender_offer_price == 0){
                                            $show_price = round($buyerprice,2);
                                            }else{
                                            $convert_price = round($buyersaleprice,2);

                                            $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                            $show_price = round($buyerprice,2);
                                            }



                                            }
                                            }else{
                                            $commission_amount = 0;

                                            $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                            $totalsaleprice =
                                            ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                            $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                            $buyersaleprice =
                                            ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                            if($pro->vender_offer_price == 0){
                                            $show_price = round($buyerprice,2);
                                            }else{
                                            $convert_price = round($buyersaleprice,2);

                                            $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                            $show_price = round($buyerprice,2);
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
                                            <span class="price"><i
                                                class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                                            @else
                                            <span class="price"><i
                                                class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                                            <span class="price-before-discount"><i
                                                class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>

                                            @endif



                                            @endif

                                            @endif
                                        </div>
                                        <!-- /.product-price -->
                                      </div>
                                      <!-- /.product-info -->
                                      @if($orivar->products->selling_start_at != null &&
                                      $orivar->products->selling_start_at >= $current_date)
                                      @elseif($orivar->stock < 1) @else <div class="cart clearfix animate-effect">
                                        <div class="action">
                                          <ul class="list-unstyled">
                                            <li id="addCart" class="lnk wishlist">

                                              @if($price_login != 1)
                                              <form method="POST"
                                                action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price_form, 'varofferprice' => $convert_price_form ,'qty' =>$orivar->min_order_qty])}}">
                                                {{ csrf_field() }}
                                                <button title="{{ __('staticwords.AddtoCart') }}" type="submit"
                                                  class="addtocartcus btn">
                                                  <i class="fa fa-shopping-cart"></i>
                                                </button>
                                              </form>
                                              @endif

                                            </li>

                                            @auth
                                            @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                                              <a mainid="{{ $orivar->id }}"
                                                title="{{ __('staticwords.AddToWishList') }}"
                                                class="cursor-pointer add-to-cart addtowish"
                                                data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i
                                                  class="icon fa fa-heart"></i>
                                              </a>

                                              </li>
                                              @else

                                              @php
                                              $ifinwishlist =
                                              App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                                              @endphp

                                              @if(!empty($ifinwishlist))
                                              <li class="lnk wishlist active">
                                                <a mainid="{{ $orivar->id }}"
                                                  title="{{ __('staticwords.RemoveFromWishlist') }}"
                                                  class="color000 cursor-pointer add-to-cart removeFrmWish active"
                                                  data-remove="{{url('removeWishList/'.$orivar->id)}}"> <i
                                                    class="icon fa fa-heart"></i> </a>
                                              </li>
                                              @else
                                              <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                                                  mainid="{{ $orivar->id }}"
                                                  class="add-to-cart addtowish cursor-pointer text-white"
                                                  data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i
                                                    class="activeOne icon fa fa-heart"></i> </a></li>
                                              @endif

                                              @endif
                                              @endauth

                                              <li class="lnk"> <a class="add-to-cart"
                                                  href="{{route('compare.product',$orivar->products->id)}}"
                                                  title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal"
                                                    aria-hidden="true"></i> </a> </li>
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
                          </div>

                          @endif
                          @endforeach
                          @endif


                          <?php $i++; 
                                                           $countP++; ?>
                          @endforeach
                          @endisset
                          <!-- /.item -->
                        </div>
                      </div>
                      <!-- /.home-owl-carousel -->
                    </div>
                    <!-- /.product-slider -->
                  </div>
                  <!-- /.tab-pane -->
                  <?php 
                                                            $abcd = App\FrontCat::first();
                                                            $parents = explode(",",$abcd->name);
                                                      ?>
                  @if(isset($abcd) && $abcd->status == '1')
                  @foreach($parents as $parent)

                  <?php $parentcats = App\Category::where('status','1')->where('id',$parent)->first();
                                                          ?>
                  @if(isset($parentcats))
                  <div class="tab-pane" id="pro{{ $parentcats->id }}">

                    <div class="product-slider">

                      <div class="owl-carousel home-owl-carousel custom-carousel owl-theme">
                        @foreach(App\Product::where('category_id',$parentcats->id)->take(10)->get() as $pro)
                        @if($genrals_settings->vendor_enable != 1)
                        @if($pro->vender['role_id'] == 'a')

                        @endif
                        @else
                        @foreach($pro->subvariants as $key=> $orivar)
                        @if($orivar->def ==1)

                        @php
                        $var_name_count = count($orivar['main_attr_id']);

                        $name;
                        $var_name;
                        $newarr = array();
                        for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
                          $var_name[$i]=$orivar['main_attr_value'][$var_id];
                          $name[$i]=App\ProductAttributes::where('id',$var_id)->first();

                          }


                          try{
                          $url =
                          url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                          }catch(Exception $e)
                          {
                          $url = url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                          }

                          @endphp
                          <div class="item item-carousel">
                            <div class="products">
                              <div class="product">
                                <div class="product-image">
                                  <div class="image {{ $orivar->stock ==0 ? "pro_img-box" : ""}}">

                                    <a href="{{$url}}" title="{{$pro->name}}">

                                      @if(count($pro->subvariants)>0)

                                      @if(isset($orivar->variantimages['main_image']))
                                      <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                                        src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                                        alt="{{$pro->name}}">
                                      <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}  hover-image"
                                        src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}"
                                        alt="" />
                                      @endif

                                      @else
                                      <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                                        title="{{ $pro->name }}" src="{{url('images/no-image.png')}}" alt="No Image" />

                                      @endif

                                      @if($orivar->stock == 0)
                                      <h6 align="center" class="oottext"><span>{{ __('staticwords.Outofstock') }}</span>
                                      </h6>
                                      @endif

                                      @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                                      $orivar->products->selling_start_at >= $current_date)
                                      <h6 align="center" class="oottext2">
                                        <span>{{ __('staticwords.ComingSoon') }}</span></h6>
                                      @endif

                                    </a>
                                  </div>
                                  <!-- /.image -->

                                  @if($pro->featured=="1")
                                  <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                                  @elseif($pro->offer_price !="0")
                                  <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
                                  @else
                                  <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
                                  @endif
                                </div>
                                <!-- /.product-image -->

                                <div class="product-info text-left">
                                  <h3 class="name"><a href="{{ $url }}"
                                      title="{{$pro->name}}">{{ substr($pro->name,0,10) }}{{ strlen($pro->name)>10 ? "..." : "" }}</a>
                                  </h3>
                                  <?php 
                                                                      $review_t = 0;
                                                                      $price_t = 0;
                                                                      $value_t = 0;
                                                                      $sub_total = 0;
                                                                       $reviews = App\UserReview::where('pro_id',$pro->id)->where('status','1')->get();
                                                                      ?> @if(!empty($reviews[0]))<?php
                                                                      $count =  App\UserReview::where('pro_id',$pro->id)->count();
                                                                        foreach($reviews as $review){
                                                                          $review_t = $review->price*5;
                                                                          $price_t = $review->price*5;
                                                                          $value_t = $review->value*5;
                                                                          $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                                        }
                                                                        $count = ($count*3) * 5;
                                                                        $rat = $sub_total/$count;
                                                                        $ratings_var = ($rat*100)/5;
                                                                        ?><div class="pull-left">
                                    <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                                        class="star-ratings-sprite-rating"></span></div>
                                  </div>
                                  <br><br>

                                  @else
                                  {{"No Rating"}}
                                  @endif

                                  <div class="margin-minus-top15 product-price"> <span class="price">

                                      <div class="product-price"> <span class="price">

                                          @if($price_login == 0 || Auth::check())
                                          @php
                                          $convert_price = 0;
                                          $show_price = 0;

                                          $commision_setting = App\CommissionSetting::first();

                                          if($commision_setting->type == "flat"){

                                          $commission_amount = $commision_setting->rate;
                                          if($commision_setting->p_type == 'f'){

                                          if($pro->tax_r !=''){

                                          $cit =$commission_amount*$pro->tax_r/100;
                                          $totalprice = $pro->vender_price+$orivar->price+$commission_amount+$cit;
                                          $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price +
                                          $commission_amount;

                                          if($pro->vender_offer_price == 0){
                                          $show_price = $totalprice;
                                          }else{
                                          $totalsaleprice;
                                          $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                          $show_price = $totalprice;
                                          }


                                          }else{
                                          $totalprice = $pro->vender_price+$orivar->price+$commission_amount;
                                          $totalsaleprice = $pro->vender_offer_price + $orivar->price +
                                          $commission_amount;

                                          if($pro->vender_offer_price == 0){
                                          $show_price = $totalprice;
                                          }else{
                                          $totalsaleprice;
                                          $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                          $show_price = $totalprice;
                                          }

                                          }


                                          }else{

                                          $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                          $totalsaleprice =
                                          ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                          $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                          $buyersaleprice =
                                          ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                          if($pro->vender_offer_price ==0){
                                          $show_price = round($buyerprice,2);
                                          }else{
                                          round($buyersaleprice,2);
                                          $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                          $show_price = $buyerprice;
                                          }


                                          }
                                          }else{

                                          $comm = App\Commission::where('category_id',$pro->category_id)->first();
                                          if(isset($comm)){
                                          if($comm->type=='f'){


                                          if($pro->tax_r != ''){

                                          $cit = $comm->rate*$pro['tax_r']/100;

                                          $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                                          if($pro->vender_offer_price != null){
                                          $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                          }else{
                                          $offer = $pro->vender_offer_price;
                                          }

                                          if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                          $show_price = $price;
                                          }else{

                                          $convert_price = $offer;
                                          $show_price = $price;
                                          }

                                          }else{


                                          $price = $pro->vender_price + $comm->rate + $orivar->price;

                                          if($pro->vender_offer_price != null){
                                          $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                                          }else{
                                          $offer = $pro->vender_offer_price;
                                          }

                                          if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                          $show_price = $price;
                                          }else{

                                          $convert_price = $offer;
                                          $show_price = $price;
                                          }

                                          }



                                          }
                                          else{

                                          $commission_amount = $comm->rate;

                                          $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                          $totalsaleprice =
                                          ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                          $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                          $buyersaleprice =
                                          ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                          if($pro->vender_offer_price == 0){
                                          $show_price = round($buyerprice,2);
                                          }else{
                                          round($buyersaleprice,2);

                                          $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                          $show_price = round($buyerprice,2);
                                          }



                                          }
                                          }else{
                                          $commission_amount = 0;

                                          $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                          $totalsaleprice =
                                          ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                          $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                          $buyersaleprice =
                                          ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                          if($pro->vender_offer_price == 0){
                                          $show_price = round($buyerprice,2);
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
                                          <span class="price"><i
                                              class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                                          @else

                                          <span class="price"><i
                                              class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                                          <span class="price-before-discount"><i
                                              class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>


                                          @endif

                                          @endif

                                          @endif
                                      </div>
                                  </div>
                                  <!-- /.product-price -->


                                </div>
                                <!-- /.product-info -->
                                @if($orivar->products->selling_start_at != null && $orivar->products->selling_start_at
                                >= $current_date)
                                @elseif($orivar->stock < 1) @else <div class="cart clearfix animate-effect">
                                  <div class="action">
                                    <ul class="list-unstyled">
                                      <li class="lnk wishlist">
                                        @if($price_login != 1)
                                        <form method="POST"
                                          action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                                          {{ csrf_field() }}
                                          <button title="{{ __('staticwords.AddtoCart') }}" type="submit"
                                            class="addtocartcus btn">
                                            <i class="fa fa-shopping-cart"></i>
                                          </button>
                                        </form>
                                        @endif
                                      </li>

                                      @auth
                                      @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                                        <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.AddToWishList') }}"
                                          class="add-to-cart addtowish"
                                          data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i
                                            class="icon fa fa-heart"></i>
                                        </a>

                                        </li>
                                        @else

                                        @php
                                        $ifinwishlist =
                                        App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                                        @endphp



                                        @if(!empty($ifinwishlist))
                                        <li class="lnk wishlist active"> <a mainid="{{ $orivar->id }}"
                                            title="{{ __('staticwords.RemoveFromWishlist') }}"
                                            class="color000 add-to-cart removeFrmWish active"
                                            data-remove="{{url('removeWishList/'.$orivar->id)}}"> <i
                                              class="icon fa fa-heart"></i> </a>
                                        </li>
                                        @else
                                        <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                                            mainid="{{ $orivar->id }}" class="text-white add-to-cart addtowish"
                                            data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i
                                              class="activeOne icon fa fa-heart"></i> </a></li>
                                        @endif

                                        @endif
                                        @endauth

                                        <li class="lnk"> <a class="add-to-cart"
                                            href="{{route('compare.product',$orivar->products->id)}}"
                                            title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal"
                                              aria-hidden="true"></i> </a> </li>
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
                    </div>

                  </div>

                </div>
                @endif


                @endforeach
                @endif
                <!-- /.tab-pane -->

              </div>
            </div>

            @endif
          </div>
        </div>
      </div>
    </div>
    <div id="menu1" class="tab-pane fade">
      <div class="top-category-block">
        @php
        $top_categories = App\CategorySlider::first();
        @endphp
        <!-- Top categories-->
        @if(isset($top_categories))
        @if($top_categories->status == 1)

        <div class="display-none top_cat_header">
          <h3 class="cat_title">{{ __('staticwords.tpc') }}</h3>
        </div>
        <br>

        <!--content-->
        @if($top_categories->category_ids !='')
        @foreach($top_categories->category_ids as $category)
        @php
        $category = App\Category::where('status','1')->where('id',$category)->first();
        @endphp
        @if(isset($category))
        <section class="section-random2 section new-arriavls">
          <h3 class="section-title">{{ $category->title }}</h3>
          <div class="row no-pad">
            @foreach($category->products->take($top_categories->pro_limit) as $pro)

            @if($pro->status == 1)
            @if($genrals_settings->vendor_enable != 1 )
            @if($pro->vender['role_id'] == 'a')
            @foreach($pro->subvariants as $orivar)
            @if($orivar->def == 1)
            @php
            $var_name_count = count($orivar['main_attr_id']);

            $name = array();
            $var_name;
            $newarr = array();
            for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
              $var_name[$i]=$orivar['main_attr_value'][$var_id]; $name[$i]=App\ProductAttributes::where('id',$var_id)->
              first();

              }


              try{
              $url =
              url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
              }catch(Exception $e)
              {
              $url = url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
              }

              @endphp
              <div class="col-6">
                <div class="item item-carousel">
                  <div class="products">
                    <div class="product">
                      <div class="product-image">
                        <div class="image {{ $orivar->stock ==0 ? "pro-img-box" : ""}}">

                          <a href="{{$url}}" title="{{$pro->name}}">

                            @if(count($pro->subvariants)>0)

                            @if(isset($orivar->variantimages['main_image']))
                            <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                              src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                              alt="{{$pro->name}}">
                            <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}  hover-image"
                              src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt="" />
                            @endif

                            @else
                            <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $pro->name }}"
                              src="{{url('images/no-image.png')}}" alt="No Image" />

                            @endif



                          </a>
                        </div>

                        @if($orivar->stock == 0)
                        <h6 align="center" class="oottext"><span>{{ __('staticwords.Outofstock') }}</span></h6>
                        @endif

                        @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                        $orivar->products->selling_start_at >= $current_date)
                        <h6 align="center" class="oottext2"><span>{{ __('staticwords.ComingSoon') }}</span></h6>
                        @endif
                        <!-- /.image -->

                        @if($pro->featured=="1")
                        <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                        @elseif($pro->offer_price !="")
                        <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
                        @else
                        <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
                        @endif
                      </div>
                      <!-- /.product-image -->

                      <div class="product-info text-left">
                        <h3 class="name"><a href="{{ $url }}"
                            title="{{$pro->name}}">{{substr($pro->name, 0, 20)}}{{strlen($pro->name)>20 ? '...' : ""}}</a>
                        </h3>
                        <?php 
                                                                      $review_t = 0;
                                                                      $price_t = 0;
                                                                      $value_t = 0;
                                                                      $sub_total = 0;
                                                                       $reviews = App\UserReview::where('pro_id',$pro->id)->where('status','1')->get();
                                                                      ?> @if(!empty($reviews[0]))<?php
                                                                      $count =  App\UserReview::where('pro_id',$pro->id)->count();
                                                                        foreach($reviews as $review){
                                                                          $review_t = $review->price*5;
                                                                          $price_t = $review->price*5;
                                                                          $value_t = $review->value*5;
                                                                          $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                                        }
                                                                        $count = ($count*3) * 5;
                                                                        $rat = $sub_total/$count;
                                                                        $ratings_var = ($rat*100)/5;
                                                                        ?><div class="pull-left">
                          <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                              class="star-ratings-sprite-rating"></span></div>
                        </div>

                        @else
                        {{"No Rating"}}
                        @endif

                        <div class="product-price"> <span class="price">

                            <div class="product-price"> <span class="price">

                                @if($price_login == 0 || Auth::check())
                                @php
                                $convert_price = 0;
                                $show_price = 0;

                                $commision_setting = App\CommissionSetting::first();

                                if($commision_setting->type == "flat"){

                                $commission_amount = $commision_setting->rate;
                                if($commision_setting->p_type == 'f'){


                                if($pro->tax_r !=''){

                                $cit =$commission_amount*$pro->tax_r/100;
                                $totalprice = $pro->vender_price+$orivar->price+$commission_amount+$cit;
                                $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price + $commission_amount;

                                if($pro->vender_offer_price == 0){
                                $show_price = $totalprice;
                                }else{
                                $totalsaleprice;
                                $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                $show_price = $totalprice;
                                }


                                }else{
                                $totalprice = $pro->vender_price+$orivar->price+$commission_amount;
                                $totalsaleprice = $pro->vender_offer_price + $orivar->price + $commission_amount;

                                if($pro->vender_offer_price == 0){
                                $show_price = $totalprice;
                                }else{
                                $totalsaleprice;
                                $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                                $show_price = $totalprice;
                                }

                                }


                                }else{

                                $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($pro->vender_offer_price ==0){
                                $show_price = round($buyerprice,2);
                                }else{
                                round($buyersaleprice,2);
                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = $buyerprice;
                                }


                                }
                                }else{

                                $comm = App\Commission::where('category_id',$pro->category_id)->first();
                                if(isset($comm)){
                                if($comm->type=='f'){

                                if($pro->tax_r != ''){

                                $cit = $comm->rate*$pro['tax_r']/100;

                                $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                                if($pro->vender_offer_price != null){
                                $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                                }else{
                                $offer = $pro->vender_offer_price;
                                }

                                if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                $show_price = $price;
                                }else{

                                $convert_price = $offer;
                                $show_price = $price;
                                }

                                }else{


                                $price = $pro->vender_price + $comm->rate + $orivar->price;

                                if($pro->vender_offer_price != null){
                                $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                                }else{
                                $offer = $pro->vender_offer_price;
                                }

                                if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                                $show_price = $price;
                                }else{

                                $convert_price = $offer;
                                $show_price = $price;
                                }

                                }
                                }
                                else{

                                $commission_amount = $comm->rate;

                                $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($pro->vender_offer_price == 0){
                                $show_price = round($buyerprice,2);
                                }else{
                                round($buyersaleprice,2);

                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = round($buyerprice,2);
                                }



                                }
                                }else{
                                $commission_amount = 0;

                                $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                                $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                                $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                                $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                                if($pro->vender_offer_price == 0){
                                $show_price = round($buyerprice,2);
                                }else{
                                round($buyersaleprice,2);

                                $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                                $show_price = round($buyerprice,2);
                                }
                                }
                                }

                                $show_price = $show_price*$conversion_rate;
                                $convert_price = $convert_price*$conversion_rate;

                                @endphp

                                @if(Session::has('currency'))
                                @if($convert_price == 0 || $convert_price == 'null')
                                <span class="price"><i
                                    class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                                @else

                                <span class="price"><i
                                    class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                                <span class="price-before-discount"><i
                                    class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>


                                @endif


                                @endif

                                @endif
                            </div>
                        </div>
                        <!-- /.product-price -->


                      </div>
                      <!-- /.product-info -->
                      @if($orivar->products->selling_start_at != null && $orivar->products->selling_start_at >=
                      $current_date)
                      @elseif($orivar->stock < 1) @else <div class="cart clearfix animate-effect">
                        <div class="action">
                          <ul class="list-unstyled">
                            <li class="lnk wishlist">
                              <form method="POST"
                                action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                                {{ csrf_field() }}
                                <button title="{{ __('staticwords.AddtoCart') }}" type="submit"
                                  class="addtocartcus btn">
                                  <i class="fa fa-shopping-cart"></i>
                                </button>
                              </form>
                            </li>

                            @auth
                            @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                              <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.AddToWishList') }}"
                                class="add-to-cart addtowish" data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i
                                  class="icon fa fa-heart"></i>
                              </a>

                              </li>
                              @else

                              @php
                              $ifinwishlist =
                              App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                              @endphp



                              @if(!empty($ifinwishlist))
                              <li class="lnk wishlist active"> <a mainid="{{ $orivar->id }}"
                                  title="{{ __('staticwords.RemoveFromWishlist') }}"
                                  class="color000 add-to-cart removeFrmWish active"
                                  data-remove="{{url('removeWishList/'.$orivar->id)}}" title="Wishlist"> <i
                                    class="icon fa fa-heart"></i> </a>
                              </li>
                              @else
                              <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                                  mainid="{{ $orivar->id }}" class="text-white add-to-cart addtowish"
                                  data-add="{{url('AddToWishList/'.$orivar->id)}}" title="Wishlist"> <i
                                    class="activeOne icon fa fa-heart"></i> </a></li>
                              @endif

                              @endif
                              @endauth

                              <li class="lnk"> <a class="add-to-cart"
                                  href="{{route('compare.product',$orivar->products->id)}}"
                                  title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal"
                                    aria-hidden="true"></i> </a> </li>
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
          </div>


          @endif
          @endforeach
          @endif
          @else
          @foreach($pro->subvariants as $orivar)
          @if($orivar->def == 1)
          @php
          $var_name_count = count($orivar['main_attr_id']);

          $name = array();
          $var_name;
          $newarr = array();
          for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
            $var_name[$i]=$orivar['main_attr_value'][$var_id]; $name[$i]=App\ProductAttributes::where('id',$var_id)->
            first();

            }


            try{
            $url =
            url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
            }catch(Exception $e)
            {
            $url = url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
            }

            @endphp
            <div class="col-6">
              <div class="item item-carousel">
                <div class="products">
                  <div class="product">
                    <div class="product-image">
                      <div class="image {{ $orivar->stock ==0 ? "pro-img-box" : ""}}">

                        <a href="{{$url}}" title="{{$pro->name}}">

                          @if(count($pro->subvariants)>0)

                          @if(isset($orivar->variantimages['main_image']))
                          <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                            src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                            alt="{{$pro->name}}">
                          <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}  hover-image"
                            src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt="" />
                          @endif

                          @else
                          <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $pro->name }}"
                            src="{{url('images/no-image.png')}}" alt="No Image" />

                          @endif



                        </a>
                      </div>

                      @if($orivar->stock == 0)
                      <h6 align="center" class="oottext"><span>{{ __('staticwords.Outofstock') }}</span></h6>
                      @endif

                      @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                      $orivar->products->selling_start_at >= $current_date)
                      <h6 align="center" class="oottext2"><span>{{ __('staticwords.ComingSoon') }}</span></h6>
                      @endif
                      <!-- /.image -->

                      @if($pro->featured=="1")
                      <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                      @elseif($pro->offer_price !="")
                      <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
                      @else
                      <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
                      @endif
                    </div>
                    <!-- /.product-image -->

                    <div class="product-info text-left">
                      <h3 class="name"><a href="{{ $url }}"
                          title="{{$pro->name}}">{{substr($pro->name, 0, 10)}}{{strlen($pro->name)>10 ? '...' : ""}}</a>
                      </h3>
                      <?php 
                                                                      $review_t = 0;
                                                                      $price_t = 0;
                                                                      $value_t = 0;
                                                                      $sub_total = 0;
                                                                       $reviews = App\UserReview::where('pro_id',$pro->id)->where('status','1')->get();
                                                                      ?> @if(!empty($reviews[0]))<?php
                                                                      $count =  App\UserReview::where('pro_id',$pro->id)->count();
                                                                        foreach($reviews as $review){
                                                                          $review_t = $review->price*5;
                                                                          $price_t = $review->price*5;
                                                                          $value_t = $review->value*5;
                                                                          $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                                        }
                                                                        $count = ($count*3) * 5;
                                                                        $rat = $sub_total/$count;
                                                                        $ratings_var = ($rat*100)/5;
                                                                        ?><div class="pull-left">
                        <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                            class="star-ratings-sprite-rating"></span></div>
                      </div>

                      @else
                      {{ __("No Rating") }}
                      @endif

                      <div class="product-price"> <span class="price">

                          <div class="product-price"> <span class="price">

                              @if($price_login == 0 || Auth::check())
                              @php
                              $convert_price = 0;
                              $show_price = 0;

                              $commision_setting = App\CommissionSetting::first();

                              if($commision_setting->type == "flat"){

                              $commission_amount = $commision_setting->rate;
                              if($commision_setting->p_type == 'f'){


                              if($pro->tax_r !=''){

                              $cit =$commission_amount*$pro->tax_r/100;
                              $totalprice = $pro->vender_price+$orivar->price+$commission_amount+$cit;
                              $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price + $commission_amount;

                              if($pro->vender_offer_price == 0){
                              $show_price = $totalprice;
                              }else{
                              $totalsaleprice;
                              $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                              $show_price = $totalprice;
                              }


                              }else{
                              $totalprice = $pro->vender_price+$orivar->price+$commission_amount;
                              $totalsaleprice = $pro->vender_offer_price + $orivar->price + $commission_amount;

                              if($pro->vender_offer_price == 0){
                              $show_price = $totalprice;
                              }else{
                              $totalsaleprice;
                              $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                              $show_price = $totalprice;
                              }

                              }


                              }else{

                              $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                              $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                              $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                              $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                              if($pro->vender_offer_price ==0){
                              $show_price = round($buyerprice,2);
                              }else{
                              round($buyersaleprice,2);
                              $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                              $show_price = $buyerprice;
                              }


                              }
                              }else{

                              $comm = App\Commission::where('category_id',$pro->category_id)->first();
                              if(isset($comm)){
                              if($comm->type=='f'){

                              if($pro->tax_r != ''){

                              $cit = $comm->rate*$pro['tax_r']/100;

                              $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                              if($pro->vender_offer_price != null){
                              $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                              }else{
                              $offer = $pro->vender_offer_price;
                              }

                              if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                              $show_price = $price;
                              }else{

                              $convert_price = $offer;
                              $show_price = $price;
                              }

                              }else{


                              $price = $pro->vender_price + $comm->rate + $orivar->price;

                              if($pro->vender_offer_price != null){
                              $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                              }else{
                              $offer = $pro->vender_offer_price;
                              }

                              if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                              $show_price = $price;
                              }else{

                              $convert_price = $offer;
                              $show_price = $price;
                              }

                              }
                              }
                              else{

                              $commission_amount = $comm->rate;

                              $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                              $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                              $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                              $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                              if($pro->vender_offer_price == 0){
                              $show_price = round($buyerprice,2);
                              }else{
                              round($buyersaleprice,2);

                              $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                              $show_price = round($buyerprice,2);
                              }



                              }
                              }else{
                              $commission_amount = 0;

                              $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                              $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                              $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                              $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                              if($pro->vender_offer_price == 0){
                              $show_price = round($buyerprice,2);
                              }else{
                              round($buyersaleprice,2);

                              $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                              $show_price = round($buyerprice,2);
                              }
                              }
                              }

                              $show_price = $show_price*$conversion_rate;
                              $convert_price = $convert_price*$conversion_rate;

                              @endphp

                              @if(Session::has('currency'))
                              @if($convert_price == 0 || $convert_price == 'null')
                              <span class="price"><i
                                  class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                              @else

                              <span class="price"><i
                                  class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                              <span class="price-before-discount"><i
                                  class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>


                              @endif


                              @endif

                              @endif
                          </div>
                      </div>
                      <!-- /.product-price -->


                    </div>
                    <!-- /.product-info -->
                    @if($orivar->products->selling_start_at != null && $orivar->products->selling_start_at >=
                    $current_date)
                    @elseif($orivar->stock < 1) @else <div class="cart clearfix animate-effect">
                      <div class="action">
                        <ul class="list-unstyled">
                          <li class="lnk wishlist">
                            @if($price_login != 1)
                            <form method="POST"
                              action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                              {{ csrf_field() }}
                              <button title="{{ __('staticwords.AddtoCart') }}" type="submit" class="addtocartcus btn">
                                <i class="fa fa-shopping-cart"></i>
                              </button>
                            </form>
                            @endif
                          </li>

                          @auth
                          @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                            <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.AddToWishList') }}"
                              class="add-to-cart addtowish" data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i
                                class="icon fa fa-heart"></i>
                            </a>

                            </li>
                            @else

                            @php
                            $ifinwishlist =
                            App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                            @endphp



                            @if(!empty($ifinwishlist))
                            <li class="lnk wishlist active"> <a mainid="{{ $orivar->id }}"
                                title="{{ __('staticwords.RemoveFromWishlist') }}"
                                class="color000 add-to-cart removeFrmWish active"
                                data-remove="{{url('removeWishList/'.$orivar->id)}}"> <i class="icon fa fa-heart"></i>
                              </a>
                            </li>
                            @else
                            <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                                mainid="{{ $orivar->id }}" class="text-white add-to-cart addtowish"
                                data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i
                                  class="activeOne icon fa fa-heart"></i> </a></li>
                            @endif

                            @endif
                            @endauth

                            <li class="lnk"> <a class="add-to-cart"
                                href="{{route('compare.product',$orivar->products->id)}}"
                                title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal" aria-hidden="true"></i>
                              </a> </li>
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
      </div>


      @endif
      @endforeach
      @endif
      @endif

      @endforeach
    </div>
    <!-- /.home-owl-carousel -->
  </section>
  @endif
  @endforeach
  @endif

  <!--end-->

  @endif
  @endif
</div>
</div>
<div id="menu2" class="tab-pane fade">
  <section class="section new-arriavls feature-product-main-block">
    <h3 class="section-title display-none">
      {{ __('staticwords.Featured') }}
    </h3>
    <div class="feature-product-dtl">
      <div class="row no-pad">
        @foreach($featureds as $featured)
        @foreach($featured->subvariants as $key=> $orivar)
        @if($orivar->def ==1)

        @php
        $var_name_count = count($orivar['main_attr_id']);

        $name;
        $var_name;
        $newarr = array();
        for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
          $var_name[$i]=$orivar['main_attr_value'][$var_id]; // echo($orivar['main_attr_id'][$i]);
          $name[$i]=App\ProductAttributes::where('id',$var_id)->first();

          }


          try{
          $url =
          url('details').'/'.$featured->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
          }catch(Exception $e)
          {
          $url = url('details').'/'.$featured->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
          }

          @endphp
          <div class="col-6">
            <div class="item item-carousel">
              <div class="products">
                <div class="product">
                  <div class="product-image">
                    <div class="image {{ $orivar->stock ==0 ? "pro_img-box" : ""}}">

                      <a href="{{$url}}" title="{{$featured->name}}">

                        @if(count($featured->subvariants)>0)

                        @if(isset($orivar->variantimages['main_image']))
                        <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                          src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                          alt="{{$featured->name}}">
                        <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}  hover-image"
                          src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt="" />
                        @endif

                        @else
                        <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $featured->name }}"
                          src="{{url('images/no-image.png')}}" alt="No Image" />

                        @endif

                        @if($orivar->stock == 0)
                        <h6 align="center" class="oottext"><span>{{ __('staticwords.Outofstock') }}</span></h6>
                        @endif

                        @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                        $orivar->products->selling_start_at >= $current_date)
                        <h6 align="center" class="oottext2"><span>{{ __('staticwords.ComingSoon') }}</span></h6>
                        @endif

                      </a>
                    </div>
                    <!-- /.image -->

                    @if($featured->feature == '1')
                    <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                    @elseif($featured->offer_price !='0')
                    <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span>
                    </div>
                    @else
                    <div class="tag sale"><span>{{ __('staticwords.New') }}</span>
                    </div>
                    @endif
                  </div>


                  <!-- /.product-image -->

                  <div class="product-info text-left">
                    <h3 class="name"><a
                        href="{{$url}}">{{ substr($featured->name,0,10) }}{{ strlen($featured->name)>10 ? "..." : "" }}</a>
                    </h3>
                    <?php 
                                                        $review_t = 0;
                                                        $price_t = 0;
                                                        $value_t = 0;
                                                        $sub_total = 0;
                                                        $reviews = App\UserReview::where('pro_id',$featured->id)->where('status','1')->get();
                                                        ?> @if(!empty($reviews[0]))<?php
                                                        $count =  App\UserReview::where('pro_id',$featured->id)->count();
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
                      <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                          class="star-ratings-sprite-rating"></span></div>
                    </div>

                    @else
                    {{ __('No Rating') }}
                    @endif
                    <div class="description"></div>


                    <!-- Product-price -->

                    <div class="product-price"> <span class="price">

                        <div class="product-price"> <span class="price">

                            @if($price_login == 0 || Auth::check())

                            @php
                            $convert_price = 0;
                            $show_price = 0;

                            $commision_setting = App\CommissionSetting::first();

                            if($commision_setting->type == "flat"){

                            $commission_amount = $commision_setting->rate;
                            if($commision_setting->p_type == 'f'){

                            if($featured->tax_r !=''){

                            $cit =$commission_amount*$featured->tax_r/100;
                            $totalprice = $featured->vender_price+$orivar->price+$commission_amount+$cit;
                            $totalsaleprice = $featured->vender_offer_price + $cit + $orivar->price +
                            $commission_amount;

                            if($featured->vender_offer_price == 0){
                            $show_price = $totalprice;
                            }else{
                            $totalsaleprice;
                            $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                            $show_price = $totalprice;
                            }


                            }else{

                            $totalprice = $featured->vender_price+$orivar->price+$commission_amount;
                            $totalsaleprice = $featured->vender_offer_price + $orivar->price + $commission_amount;

                            if($featured->vender_offer_price == 0){
                            $show_price = $totalprice;
                            }else{
                            $totalsaleprice;
                            $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                            $show_price = $totalprice;
                            }

                            }


                            }else{

                            $totalprice = ($featured->vender_price+$orivar->price)*$commission_amount;

                            $totalsaleprice = ($featured->vender_offer_price+$orivar->price)*$commission_amount;

                            $buyerprice = ($featured->vender_price+$orivar->price)+($totalprice/100);

                            $buyersaleprice = ($featured->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                            if($featured->vender_offer_price ==0){
                            $show_price = round($buyerprice,2);
                            }else{
                            round($buyersaleprice,2);

                            $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                            $show_price = $buyerprice;
                            }


                            }
                            }else{

                            $comm = App\Commission::where('category_id',$featured->category_id)->first();
                            if(isset($comm)){
                            if($comm->type=='f'){



                            if($featured->tax_r != ''){

                            $cit = $comm->rate*$featured->tax_r/100;

                            $price = $featured->vender_price + $comm->rate + $orivar->price + $cit;

                            if($featured->vender_offer_price != null){
                            $offer = $featured->vender_offer_price + $comm->rate + $orivar->price + $cit;
                            }else{
                            $offer = $featured->vender_offer_price;
                            }

                            if($featured->vender_offer_price == 0 || $featured->vender_offer_price == null){
                            $show_price = $price;
                            }else{

                            $convert_price = $offer;
                            $show_price = $price;
                            }

                            }else{


                            $price = $featured->vender_price + $comm->rate + $orivar->price;

                            if($featured->vender_offer_price != null){
                            $offer = $featured->vender_offer_price + $comm->rate + $orivar->price;
                            }else{
                            $offer = $featured->vender_offer_price;
                            }

                            if($featured->vender_offer_price == 0 || $featured->vender_offer_price == null){
                            $show_price = $price;
                            }else{

                            $convert_price = $offer;
                            $show_price = $price;
                            }

                            }




                            }
                            else{

                            $commission_amount = $comm->rate;

                            $totalprice = ($featured->vender_price+$orivar->price)*$commission_amount;

                            $totalsaleprice = ($featured->vender_offer_price+$orivar->price)*$commission_amount;

                            $buyerprice = ($featured->vender_price+$orivar->price)+($totalprice/100);

                            $buyersaleprice = ($featured->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                            if($featured->vender_offer_price == 0){
                            $show_price = round($buyerprice,2);
                            }else{
                            round($buyersaleprice,2);

                            $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                            $show_price = round($buyerprice,2);
                            }



                            }
                            }else{
                            $commission_amount = 0;

                            $totalprice = ($featured->vender_price+$orivar->price)*$commission_amount;

                            $totalsaleprice = ($featured->vender_offer_price+$orivar->price)*$commission_amount;

                            $buyerprice = ($featured->vender_price+$orivar->price)+($totalprice/100);

                            $buyersaleprice = ($featured->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                            if($featured->vender_offer_price == 0){
                            $show_price = round($buyerprice,2);
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
                            <span class="price"><i
                                class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                            @else

                            <span class="price"><i
                                class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                            <span class="price-before-discount"><i
                                class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>


                            @endif
                            @endif

                            @endif
                        </div>

                    </div>
                    <!-- /.product-price -->

                  </div>
                  @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                  $orivar->products->selling_start_at >= $current_date)
                  @elseif($orivar->stock < 1) @else <!-- /.product-info -->
                    <div class="cart clearfix animate-effect">
                      <div class="action">
                        <ul class="list-unstyled">
                          @if(!empty($auth))
                          <?php $cart_table = App\Cart::where('user_id',$auth->id)->where('pro_id',$featured->id)->first(); ?>@endif
                          @if(empty($cart_table))
                          <li id="addCart" class="lnk wishlist">

                            @if($price_login != 1)
                            <form method="POST"
                              action="{{route('add.cart',['id' => $featured->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                              {{ csrf_field() }}
                              <button title="{{ __('staticwords.AddtoCart') }}" type="submit" class="addtocartcus btn">
                                <i class="fa fa-shopping-cart"></i>
                              </button>
                            </form>
                            @endif

                          </li>
                          @else
                          <li id="addCart" class="lnk wishlist"> <a class="add-to-cart"
                              href="{{url('remove_table_cart/'.$orivar->id)}}" title="{{ __('Remove Cart') }}"> <i
                                class="icon fa fa-times"></i> </a> </li>
                          @endif

                          @auth
                          @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                            <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.AddToWishList') }}"
                              class="cursor-pointer add-to-cart addtowish"
                              data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i class="icon fa fa-heart"></i>
                            </a>

                            </li>
                            @else

                            @php
                            $ifinwishlist =
                            App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                            @endphp

                            @if(!empty($ifinwishlist))
                            <li class="lnk wishlist active">
                              <a mainid="{{ $orivar->id }}" title="{{ __('RemoveFromWishlist') }}"
                                class="color000 cursor-pointer add-to-cart removeFrmWish active"
                                data-remove="{{url('removeWishList/'.$orivar->id)}}" title="Wishlist"> <i
                                  class="icon fa fa-heart"></i> </a>
                            </li>
                            @else
                            <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                                mainid="{{ $orivar->id }}" class="text-white cursor-pointer add-to-cart addtowish"
                                data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i
                                  class="activeOne icon fa fa-heart"></i> </a></li>
                            @endif

                            @endif
                            @endauth
                            <li class="lnk"> <a class="add-to-cart" href="{{route('compare.product',$featured->id)}}"
                                title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal" aria-hidden="true"></i>
                              </a> </li>
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
          </div>
          <!-- /.item -->
          @endif
          @endforeach
          @endforeach
      </div>
    </div>
    <!-- /.home-owl-carousel -->
  </section>
</div>
</div>
</div>
</div>
</section>

<!-- ============================================== small screen product-tab-slider : END ============================================== -->
<!-- ==============================================  ============================================== -->
<!-- /.scroll-tabs -->
<!-- ============================================== SCROLL TABS : END ============================================== -->
<!-- ============================================== WIDE PRODUCTS ============================================== -->
<div class="wide-banners-full-screen">
  <?php $home_slider = App\Widgetsetting::where('name','banner')->first(); ?>
  @if(!empty($home_slider))

  @if($home_slider->home=='1')
  <div class="wide-banners">
    <div class="row">

      @foreach($advs as $adv)

      @if($adv->width==0 && $adv->position==1)

      <div class="col-md-4 col-sm-4">
        @if($adv->link_by=='cat')

        @endif
        <div class="wide-banner cnt-strip">
          <a
            href="@if($adv->link_by=='cat') @include('front.advcaturl') @elseif($adv->link_by=='pro') @include('front.advvarurl') @else{{$adv->url}}@endif">
            <div class="image"> <img class="img-fluid" src="{{url('images/adv/'.$adv->image)}}" alt=""> </div>
          </a>
        </div>
        <!-- /.wide-banner -->
      </div>

      @endif
      @endforeach
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  @endif
  @endif
  <!-- /.wide-banners -->
</div>

<!-- ============================================== WIDE PRODUCTS : END ============================================== -->

@php
$getads = App\Adv::where('position','=','abovetopcategory')->where('status','=',1)->get();
@endphp

<!-- Advertisement before top categories-->
@if(isset($getads))
@foreach($getads as $adv)
@if($adv->layout == 'Single image layout')
<a href="
                        @if($adv->cat_id1 != '')
                         @include('front.adv.caturl')
                        @elseif($adv->pro_id1 != '')
                          @include('front.adv.pro')
                        @else
                          {{ $adv->url1 }}
                        @endif" title="Click to visit">
  <img class="img-fluid advertisement-img advertisement-img-one" src="{{ url('images/adv/'.$adv->image1) }}"
    alt="{{ $adv->image1 }}">
</a>

@endif

@if($adv->layout == 'Three Image Layout')
<div class="row">
  <div class="col-md-4">
    <a href="
                        @if($adv->cat_id1 != '')
                         @include('front.adv.caturl')
                        @elseif($adv->pro_id1 != '')
                          @include('front.adv.pro')
                        @else
                          {{ $adv->url1 }}
                        @endif" title="Click to visit">
      <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                        @if($adv->cat_id2 != '')
                           @include('front.adv.caturl2')
                        @elseif($adv->pro_id2 != '')
                           @include('front.adv.pro2')
                        @else
                          {{ $adv->url2 }}
                        @endif" title="Click to visit">
      <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image2) }}" alt="{{ $adv->image2 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                        @if($adv->cat_id3 != '')
                          @include('front.adv.caturl3')
                        @elseif($adv->pro_id3 != '')
                          @include('front.adv.pro3')
                        @else
                          {{ $adv->url3 }}
                        @endif" title="Click to visit">
      <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image3) }}" alt="{{ $adv->image3 }}">
    </a>
  </div>
</div>
<br>
@endif

@if($adv->layout == 'Two equal image layout')
<div class="row">
  <div class="col-md-6">
    <a href="
                            @if($adv->cat_id1 != '')
                             @include('front.adv.caturl')
                            @elseif($adv->pro_id1 != '')
                              @include('front.adv.pro')
                            @else
                              {{ $adv->url1 }}
                            @endif" title="Click to visit">
      <img class="img-fluid advertisement-img-one advertisement-img" src="{{ url('images/adv/'.$adv->image1) }}"
        alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-6">
    <a href="
                            @if($adv->cat_id2 != '')
                             @include('front.adv.caturl2')
                            @elseif($adv->pro_id2 != '')
                              @include('front.adv.pro2')
                            @else
                              {{ $adv->url2 }}
                            @endif" title="Click to visit">
      <img class="img-fluid advertisement-img-one " src="{{ url('images/adv/'.$adv->image2) }}"
        alt="{{ $adv->image2 }}">
    </a>
  </div>
</div>
<br>
@endif

@if($adv->layout == 'Two non equal image layout')
<div class="row">
  <div class="col-md-8">
    <a href="
                          @if($adv->cat_id1 != '')
                           @include('front.adv.caturl')
                          @elseif($adv->pro_id1 != '')
                            @include('front.adv.pro')
                          @else
                            {{ $adv->url1 }}
                          @endif" title="Click to visit">
      <img class="img-fluid advertisement-img advertisement-img-one advertisement-images-size-one"
        src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                            @if($adv->cat_id2 != '')
                             @include('front.adv.caturl2')
                            @elseif($adv->pro_id2 != '')
                              @include('front.adv.pro2')
                            @else
                              {{ $adv->url2 }}
                            @endif" title="Click to visit">
      <img class="img-fluid advertisement-images-size-one" src="{{ url('images/adv/'.$adv->image2) }}"
        alt="{{ $adv->image2 }}">
    </a>
  </div>
</div>
<br>
@endif
@endforeach
@endif
<!--END -->

<div class="feature-product-block">
  @php
  $top_categories = App\CategorySlider::first();
  @endphp
  <!-- Top categories-->
  @if(isset($top_categories))
  @if($top_categories->status == 1)



  <div class="top_cat_header">
    <h3 class="cat_title">{{ __('staticwords.tpc') }}</h3>
  </div>
  <br>

  <!--content-->
  @if($top_categories->category_ids != '')
  @foreach($top_categories->category_ids as $category)
  @php
  $category = App\Category::where('status','1')->where('id',$category)->first();
  @endphp
  @if(isset($category))
  <section class="section-random2 section new-arriavls">
    <h3 class="section-title">{{ $category->title }}</h3>
    <div class="owl-carousel home-owl-carousel custom-carousel owl-theme outer-top-xs">
      @foreach($category->products->take($top_categories->pro_limit) as $pro)
      @if($pro->status == 1)


      @if($genrals_settings->vendor_enable != 1)
      @if($pro->vender['role_id'] == 'a')
      @foreach($pro->subvariants as $orivar)
      @if($orivar->def == 1)
      @php
      $var_name_count = count($orivar['main_attr_id']);

      $name = array();
      $var_name;
      $newarr = array();
      for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
        $var_name[$i]=$orivar['main_attr_value'][$var_id]; $name[$i]=App\ProductAttributes::where('id',$var_id)->
        first();

        }


        try{
        $url =
        url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
        }catch(Exception $e)
        {
        $url = url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
        }

        @endphp

        <div class="item item-carousel">
          <div class="products">
            <div class="product">
              <div class="product-image">
                <div class="image {{ $orivar->stock ==0 ? "pro-img-box" : ""}}">

                  <a href="{{$url}}" title="{{$pro->name}}">

                    @if(count($pro->subvariants)>0)

                    @if(isset($orivar->variantimages['main_image']))
                    <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                      src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                      alt="{{$pro->name}}">
                    <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}  hover-image"
                      src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt="" />
                    @endif

                    @else
                    <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $pro->name }}"
                      src="{{url('images/no-image.png')}}" alt="No Image" />

                    @endif



                  </a>
                </div>

                @if($orivar->stock == 0)
                <h6 align="center" class="oottext"><span>{{ __('staticwords.Outofstock') }}</span></h6>
                @endif

                @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
                $orivar->products->selling_start_at >= $current_date)
                <h6 align="center" class="oottext2"><span>{{ __('staticwords.ComingSoon') }}</span></h6>
                @endif
                <!-- /.image -->

                @if($pro->featured=="1")
                <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
                @elseif($pro->offer_price !="0")
                <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
                @else
                <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
                @endif
              </div>
              <!-- /.product-image -->

              <div class="product-info text-left">
                <h3 class="name"><a href="{{ $url }}"
                    title="{{$pro->name}}">{{substr($pro->name, 0, 20)}}{{strlen($pro->name)>20 ? '...' : ""}}</a></h3>
                <?php 
                                              $review_t = 0;
                                              $price_t = 0;
                                              $value_t = 0;
                                              $sub_total = 0;
                                               $reviews = App\UserReview::where('pro_id',$pro->id)->where('status','1')->get();
                                              ?> @if(!empty($reviews[0]))<?php
                                              $count =  App\UserReview::where('pro_id',$pro->id)->count();
                                                foreach($reviews as $review){
                                                  $review_t = $review->price*5;
                                                  $price_t = $review->price*5;
                                                  $value_t = $review->value*5;
                                                  $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                }
                                                $count = ($count*3) * 5;
                                                $rat = $sub_total/$count;
                                                $ratings_var = ($rat*100)/5;
                                                ?><div class="pull-left">
                  <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                      class="star-ratings-sprite-rating"></span></div>
                </div>

                @else
                {{ __("No Rating") }}
                @endif

                <div class="product-price"> <span class="price">

                    <div class="product-price"> <span class="price">

                        @if($price_login == 0 || Auth::check())
                        @php
                        $convert_price = 0;
                        $show_price = 0;

                        $commision_setting = App\CommissionSetting::first();

                        if($commision_setting->type == "flat"){

                        $commission_amount = $commision_setting->rate;
                        if($commision_setting->p_type == 'f'){

                        if($pro->tax_r !=''){

                        $cit =$commission_amount*$pro->tax_r/100;
                        $totalprice = $pro->vender_price+$orivar->price+$commission_amount+$cit;
                        $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price + $commission_amount;

                        if($pro->vender_offer_price == 0){
                        $show_price = $totalprice;
                        }else{
                        $totalsaleprice;
                        $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                        $show_price = $totalprice;
                        }


                        }else{
                        $totalprice = $pro->vender_price+$orivar->price+$commission_amount;
                        $totalsaleprice = $pro->vender_offer_price + $orivar->price + $commission_amount;

                        if($pro->vender_offer_price == 0){
                        $show_price = $totalprice;
                        }else{
                        $totalsaleprice;
                        $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                        $show_price = $totalprice;
                        }

                        }


                        }else{

                        $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                        $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                        $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                        $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                        if($pro->vender_offer_price ==0){
                        $show_price = round($buyerprice,2);
                        }else{
                        round($buyersaleprice,2);
                        $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                        $show_price = $buyerprice;
                        }


                        }
                        }else{

                        $comm = App\Commission::where('category_id',$pro->category_id)->first();
                        if(isset($comm)){
                        if($comm->type=='f'){


                        if($pro->tax_r != ''){

                        $cit = $comm->rate*$pro['tax_r']/100;

                        $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                        if($pro->vender_offer_price != null){
                        $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                        }else{
                        $offer = $pro->vender_offer_price;
                        }

                        if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                        $show_price = $price;
                        }else{

                        $convert_price = $offer;
                        $show_price = $price;
                        }

                        }else{


                        $price = $pro->vender_price + $comm->rate + $orivar->price;

                        if($pro->vender_offer_price != null){
                        $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                        }else{
                        $offer = $pro->vender_offer_price;
                        }

                        if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                        $show_price = $price;
                        }else{

                        $convert_price = $offer;
                        $show_price = $price;
                        }

                        }




                        }
                        else{

                        $commission_amount = $comm->rate;

                        $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                        $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                        $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                        $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                        if($pro->vender_offer_price == 0){
                        $show_price = round($buyerprice,2);
                        }else{
                        round($buyersaleprice,2);

                        $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                        $show_price = round($buyerprice,2);
                        }



                        }
                        }else{
                        $commission_amount = 0;

                        $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                        $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                        $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                        $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                        if($pro->vender_offer_price == 0){
                        $show_price = round($buyerprice,2);
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
                        <span class="price"><i
                            class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                        @else

                        <span class="price"><i
                            class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                        <span class="price-before-discount"><i
                            class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>


                        @endif

                        @endif

                        @endif
                    </div>
                </div>
                <!-- /.product-price -->


              </div>
              <!-- /.product-info -->
              @if($orivar->products->selling_start_at != null && $orivar->products->selling_start_at >= $current_date)
              @elseif($orivar->stock < 1) @else <div class="cart clearfix animate-effect">
                <div class="action">
                  <ul class="list-unstyled">
                    <li class="lnk wishlist">
                      <form method="POST"
                        action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                        {{ csrf_field() }}
                        <button title="{{ __('staticwords.AddtoCart') }}" type="submit" class="addtocartcus btn">
                          <i class="fa fa-shopping-cart"></i>
                        </button>
                      </form>
                    </li>

                    @auth
                    @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                      <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.AddToWishList') }}"
                        class="add-to-cart addtowish" data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i
                          class="icon fa fa-heart"></i>
                      </a>

                      </li>
                      @else

                      @php
                      $ifinwishlist =
                      App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                      @endphp



                      @if(!empty($ifinwishlist))
                      <li class="lnk wishlist active"> <a mainid="{{ $orivar->id }}"
                          title="{{ __('staticwords.RemoveFromWishlist') }}"
                          class="color000 add-to-cart removeFrmWish active"
                          data-remove="{{url('removeWishList/'.$orivar->id)}}"> <i class="icon fa fa-heart"></i> </a>
                      </li>
                      @else
                      <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                          mainid="{{ $orivar->id }}" class="add-to-cart addtowish text-white"
                          data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i class="activeOne icon fa fa-heart"></i>
                        </a></li>
                      @endif

                      @endif
                      @endauth

                      <li class="lnk"> <a class="add-to-cart" href="{{route('compare.product',$orivar->products->id)}}"
                          title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal" aria-hidden="true"></i> </a>
                      </li>
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
    @else
    @foreach($pro->subvariants as $orivar)
    @if($orivar->def == 1)
    @php
    $var_name_count = count($orivar['main_attr_id']);

    $name = array();
    $var_name;
    $newarr = array();
    for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
      $var_name[$i]=$orivar['main_attr_value'][$var_id]; $name[$i]=App\ProductAttributes::where('id',$var_id)->first();

      }


      try{
      $url =
      url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
      }catch(Exception $e)
      {
      $url = url('details').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
      }

      @endphp

      <div class="item item-carousel">
        <div class="products">
          <div class="product">
            <div class="product-image">
              <div class="image {{ $orivar->stock ==0 ? "pro-img-box" : ""}}">

                <a href="{{$url}}" title="{{$pro->name}}">

                  @if(count($pro->subvariants)>0)

                  @if(isset($orivar->variantimages['main_image']))
                  <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                    src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                    alt="{{$pro->name}}">
                  <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}  hover-image"
                    src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt="" />
                  @endif

                  @else
                  <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $pro->name }}"
                    src="{{url('images/no-image.png')}}" alt="No Image" />

                  @endif



                </a>
              </div>

              @if($orivar->stock == 0)
              <h6 align="center" class="oottext"><span>{{ __('staticwords.Outofstock') }}</span></h6>
              @endif

              @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
              $orivar->products->selling_start_at >= $current_date)
              <h6 align="center" class="oottext2"><span>{{ __('staticwords.ComingSoon') }}</span></h6>
              @endif
              <!-- /.image -->

              @if($pro->featured=="1")
              <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
              @elseif($pro->offer_price !="0")
              <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
              @else
              <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
              @endif
            </div>
            <!-- /.product-image -->

            <div class="product-info text-left">
              <h3 class="name"><a href="{{ $url }}"
                  title="{{$pro->name}}">{{substr($pro->name, 0, 20)}}{{strlen($pro->name)>20 ? '...' : ""}}</a></h3>
              <?php 
                                              $review_t = 0;
                                              $price_t = 0;
                                              $value_t = 0;
                                              $sub_total = 0;
                                               $reviews = App\UserReview::where('pro_id',$pro->id)->where('status','1')->get();
                                              ?> @if(!empty($reviews[0]))<?php
                                              $count =  App\UserReview::where('pro_id',$pro->id)->count();
                                                foreach($reviews as $review){
                                                  $review_t = $review->price*5;
                                                  $price_t = $review->price*5;
                                                  $value_t = $review->value*5;
                                                  $sub_total = $sub_total + $review_t + $price_t + $value_t;
                                                }
                                                $count = ($count*3) * 5;
                                                $rat = $sub_total/$count;
                                                $ratings_var = ($rat*100)/5;
                                                ?><div class="pull-left">
                <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                    class="star-ratings-sprite-rating"></span></div>
              </div>

              @else
              {{ __("No Rating") }}
              @endif

              <div class="product-price"> <span class="price">

                  <div class="product-price"> <span class="price">

                      @if($price_login == 0 || Auth::check())
                      @php
                      $convert_price = 0;
                      $show_price = 0;

                      $commision_setting = App\CommissionSetting::first();

                      if($commision_setting->type == "flat"){

                      $commission_amount = $commision_setting->rate;
                      if($commision_setting->p_type == 'f'){

                      if($pro->tax_r !=''){

                      $cit =$commission_amount*$pro->tax_r/100;
                      $totalprice = $pro->vender_price+$orivar->price+$commission_amount+$cit;
                      $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price + $commission_amount;

                      if($pro->vender_offer_price == 0){
                      $show_price = $totalprice;
                      }else{
                      $totalsaleprice;
                      $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                      $show_price = $totalprice;
                      }


                      }else{
                      $totalprice = $pro->vender_price+$orivar->price+$commission_amount;
                      $totalsaleprice = $pro->vender_offer_price + $orivar->price + $commission_amount;

                      if($pro->vender_offer_price == 0){
                      $show_price = $totalprice;
                      }else{
                      $totalsaleprice;
                      $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                      $show_price = $totalprice;
                      }

                      }


                      }else{

                      $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                      $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                      $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                      $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                      if($pro->vender_offer_price ==0){
                      $show_price = round($buyerprice,2);
                      }else{
                      round($buyersaleprice,2);
                      $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                      $show_price = $buyerprice;
                      }


                      }
                      }else{

                      $comm = App\Commission::where('category_id',$pro->category_id)->first();
                      if(isset($comm)){
                      if($comm->type=='f'){


                      if($pro->tax_r != ''){

                      $cit = $comm->rate*$pro['tax_r']/100;

                      $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                      if($pro->vender_offer_price != null){
                      $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                      }else{
                      $offer = $pro->vender_offer_price;
                      }

                      if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                      $show_price = $price;
                      }else{

                      $convert_price = $offer;
                      $show_price = $price;
                      }

                      }else{


                      $price = $pro->vender_price + $comm->rate + $orivar->price;

                      if($pro->vender_offer_price != null){
                      $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                      }else{
                      $offer = $pro->vender_offer_price;
                      }

                      if($pro->vender_offer_price == 0 || $pro->vender_offer_price == null){
                      $show_price = $price;
                      }else{

                      $convert_price = $offer;
                      $show_price = $price;
                      }

                      }




                      }
                      else{

                      $commission_amount = $comm->rate;

                      $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                      $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                      $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                      $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                      if($pro->vender_offer_price == 0){
                      $show_price = round($buyerprice,2);
                      }else{
                      round($buyersaleprice,2);

                      $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                      $show_price = round($buyerprice,2);
                      }



                      }
                      }else{
                      $commission_amount = 0;

                      $totalprice = ($pro->vender_price+$orivar->price)*$commission_amount;

                      $totalsaleprice = ($pro->vender_offer_price+$orivar->price)*$commission_amount;

                      $buyerprice = ($pro->vender_price+$orivar->price)+($totalprice/100);

                      $buyersaleprice = ($pro->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                      if($pro->vender_offer_price == 0){
                      $show_price = round($buyerprice,2);
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
                      <span class="price"><i
                          class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                      @else

                      <span class="price"><i
                          class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                      <span class="price-before-discount"><i
                          class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>


                      @endif

                      @endif

                      @endif
                  </div>
              </div>
              <!-- /.product-price -->


            </div>
            <!-- /.product-info -->
            @if($orivar->products->selling_start_at != null && $orivar->products->selling_start_at >= $current_date)
            @elseif($orivar->stock < 1) @else <div class="cart clearfix animate-effect">
              <div class="action">
                <ul class="list-unstyled">
                  <li class="lnk wishlist">
                    @if($price_login != 1)
                    <form method="POST"
                      action="{{route('add.cart',['id' => $pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                      {{ csrf_field() }}
                      <button title="{{ __('staticwords.AddtoCart') }}" type="submit" class="addtocartcus btn">
                        <i class="fa fa-shopping-cart"></i>
                      </button>
                    </form>
                    @endif
                  </li>

                  @auth
                  @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                    <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.AddToWishList') }}"
                      class="add-to-cart addtowish" data-add="{{url('AddToWishList/'.$orivar->id)}}"
                      title="Add to wishlist"> <i class="icon fa fa-heart"></i>
                    </a>

                    </li>
                    @else

                    @php
                    $ifinwishlist =
                    App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                    @endphp



                    @if(!empty($ifinwishlist))
                    <li class="lnk wishlist active"> <a mainid="{{ $orivar->id }}"
                        title="{{ __('staticwords.RemoveFromWishlist') }}"
                        class="color000 add-to-cart removeFrmWish active"
                        data-remove="{{url('removeWishList/'.$orivar->id)}}" title="Wishlist"> <i
                          class="icon fa fa-heart"></i> </a>
                    </li>
                    @else
                    <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                        mainid="{{ $orivar->id }}" class="add-to-cart addtowish text-white"
                        data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i class="activeOne icon fa fa-heart"></i>
                      </a></li>
                    @endif

                    @endif
                    @endauth

                    <li class="lnk"> <a class="add-to-cart" href="{{route('compare.product',$orivar->products->id)}}"
                        title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal" aria-hidden="true"></i> </a>
                    </li>
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
@endif
@endforeach
</div>
<!-- /.home-owl-carousel -->
</section>

@endif
@endforeach
@endif

<!--end-->

@endif
@endif
</div>




<!-- /.sidebar-widget -->
<!-- ============================================== BEST SELLER : END ================================ Advertisement before blog ============== -->

@php
$getads = App\Adv::where('position','=','abovelatestblog')->where('status','=',1)->get();
@endphp

<!-- Advertisement -->
@if(isset($getads))
@foreach($getads as $adv)
@if($adv->layout == 'Single image layout')
<a href="
                @if($adv->cat_id1 != '')
                 @include('front.adv.caturl')
                @elseif($adv->pro_id1 != '')
                  @include('front.adv.pro')
                @else
                  {{ $adv->url1 }}
                @endif" title="{{ __('Click to visit') }}">
  <img class="img-fluid advertisement-img advertisement-img-one" src="{{ url('images/adv/'.$adv->image1) }}"
    alt="{{ $adv->image1 }}">
</a>

@endif

@if($adv->layout == 'Three Image Layout')
<div class="row">
  <div class="col-md-4">
    <a href="
                @if($adv->cat_id1 != '')
                 @include('front.adv.caturl')
                @elseif($adv->pro_id1 != '')
                  @include('front.adv.pro')
                @else
                  {{ $adv->url1 }}
                @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                @if($adv->cat_id2 != '')
                   @include('front.adv.caturl2')
                @elseif($adv->pro_id2 != '')
                   @include('front.adv.pro2')
                @else
                  {{ $adv->url2 }}
                @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image2) }}" alt="{{ $adv->image2 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                @if($adv->cat_id3 != '')
                  @include('front.adv.caturl3')
                @elseif($adv->pro_id3 != '')
                  @include('front.adv.pro3')
                @else
                  {{ $adv->url3 }}
                @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid" src="{{ url('images/adv/'.$adv->image3) }}" alt="{{ $adv->image3 }}">
    </a>
  </div>
</div>
<br>
@endif

@if($adv->layout == 'Two equal image layout')
<div class="row">
  <div class="col-md-6">
    <a href="
                    @if($adv->cat_id1 != '')
                     @include('front.adv.caturl')
                    @elseif($adv->pro_id1 != '')
                      @include('front.adv.pro')
                    @else
                      {{ $adv->url1 }}
                    @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img advertisement-img-one" src="{{ url('images/adv/'.$adv->image1) }}"
        alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-6">
    <a href="
                    @if($adv->cat_id2 != '')
                     @include('front.adv.caturl2')
                    @elseif($adv->pro_id2 != '')
                      @include('front.adv.pro2')
                    @else
                      {{ $adv->url2 }}
                    @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img-one" src="{{ url('images/adv/'.$adv->image2) }}" alt="{{ $adv->image2 }}">
    </a>
  </div>
</div>
<br>
@endif

@if($adv->layout == 'Two non equal image layout')
<div class="row">
  <div class="col-md-8">
    <a href="
                  @if($adv->cat_id1 != '')
                   @include('front.adv.caturl')
                  @elseif($adv->pro_id1 != '')
                    @include('front.adv.pro')
                  @else
                    {{ $adv->url1 }}
                  @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img advertisement-img-one advertisement-images-size-one"
        src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                    @if($adv->cat_id2 != '')
                     @include('front.adv.caturl2')
                    @elseif($adv->pro_id2 != '')
                      @include('front.adv.pro2')
                    @else
                      {{ $adv->url2 }}
                    @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-images-size-one" src="{{ url('images/adv/'.$adv->image2) }}"
        alt="{{ $adv->image2 }}">
    </a>
  </div>
</div>
<br>
@endif
@endforeach
@endif
<!--END -->

<!-- ============================================== BLOG SLIDER ============================================== -->
@if(count($blogs)>0)
<section class="section latest-blog outer-bottom-vs">
  <a title="View all posts" href="{{ route('front.blog.index') }}"
    class="pull-right btn btn-md btn-info">{{ __('staticwords.vall') }}</a>
  <h3 class="section-title">{{ __('staticwords.lfromblog') }}</h3>

  <div class="blog-slider-container outer-top-xs">
    <div class="owl-carousel blog-slider custom-carousel">
      @foreach($blogs as $blog)
      <div class="item">
        <div class="blog-post">
          <div class="blog-post-image">
            <div class="image"><a title="{{$blog->heading}}" href="{{ route('front.blog.show',$blog->slug) }}"><img
                  src="{{url('images/blog/'.$blog->image)}}" alt=""></a> </div>
          </div>
          <!-- /.blog-post-image -->

          <div class="blog-post-info text-left">
            <h3 class="name"><a href="{{ route('front.blog.show',$blog->slug) }}">{{$blog->heading}}</a></h3>

            <span class="info">{{ __('staticwords.by') }} {{$blog->user}} &nbsp;|&nbsp;
              {{$blog->created_at->format('d/m/Y')}} | {{ read_time($blog->des ) }}</span>
            <p class="text"> {{substr(strip_tags($blog->des),0,50)}}{{strlen(strip_tags($blog->des))>50 ? "..." : ""}}
            </p>
          </div>
          <!-- /.blog-post-info -->

        </div>
        <!-- /.blog-post -->
      </div>
      <!-- /.item -->
      @endforeach
    </div>
    <!-- /.owl-carousel -->
  </div>
  <!-- /.blog-slider-container -->
</section>
@endif
<!-- /.section -->
<!-- ============================================== BLOG SLIDER : END =========================Advertisement before featured product ===================== -->

@php
$getads = App\Adv::where('position','=','abovefeaturedproduct')->where('status','=',1)->get();
@endphp

<!-- Advertisement -->
@if(isset($getads))
@foreach($getads as $adv)
@if($adv->layout == 'Single image layout')
<a href="
                @if($adv->cat_id1 != '')
                 @include('front.adv.caturl')
                @elseif($adv->pro_id1 != '')
                  @include('front.adv.pro')
                @else
                  {{ $adv->url1 }}
                @endif" title="Click to visit">
  <img class="img-fluid advertisement-img advertisement-img-one" src="{{ url('images/adv/'.$adv->image1) }}"
    alt="{{ $adv->image1 }}">
</a>

@endif

@if($adv->layout == 'Three Image Layout')
<div class="row">
  <div class="col-md-4">
    <a href="
                @if($adv->cat_id1 != '')
                 @include('front.adv.caturl')
                @elseif($adv->pro_id1 != '')
                  @include('front.adv.pro')
                @else
                  {{ $adv->url1 }}
                @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                @if($adv->cat_id2 != '')
                   @include('front.adv.caturl2')
                @elseif($adv->pro_id2 != '')
                   @include('front.adv.pro2')
                @else
                  {{ $adv->url2 }}
                @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image2) }}" alt="{{ $adv->image2 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                @if($adv->cat_id3 != '')
                  @include('front.adv.caturl3')
                @elseif($adv->pro_id3 != '')
                  @include('front.adv.pro3')
                @else
                  {{ $adv->url3 }}
                @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid" src="{{ url('images/adv/'.$adv->image3) }}" alt="{{ $adv->image3 }}">
    </a>
  </div>
</div>
<br>
@endif

@if($adv->layout == 'Two equal image layout')
<div class="row">
  <div class="col-md-6">
    <a href="
                    @if($adv->cat_id1 != '')
                     @include('front.adv.caturl')
                    @elseif($adv->pro_id1 != '')
                      @include('front.adv.pro')
                    @else
                      {{ $adv->url1 }}
                    @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img advertisement-img-one" src="{{ url('images/adv/'.$adv->image1) }}"
        alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-6">
    <a href="
                    @if($adv->cat_id2 != '')
                     @include('front.adv.caturl2')
                    @elseif($adv->pro_id2 != '')
                      @include('front.adv.pro2')
                    @else
                      {{ $adv->url2 }}
                    @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img-one" src="{{ url('images/adv/'.$adv->image2) }}" alt="{{ $adv->image2 }}">
    </a>
  </div>
</div>
<br>
@endif

@if($adv->layout == 'Two non equal image layout')
<div class="row">
  <div class="col-md-8">
    <a href="
                  @if($adv->cat_id1 != '')
                   @include('front.adv.caturl')
                  @elseif($adv->pro_id1 != '')
                    @include('front.adv.pro')
                  @else
                    {{ $adv->url1 }}
                  @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img advertisement-img-one advertisement-images-size-one"
        src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                    @if($adv->cat_id2 != '')
                     @include('front.adv.caturl2')
                    @elseif($adv->pro_id2 != '')
                      @include('front.adv.pro2')
                    @else
                      {{ $adv->url2 }}
                    @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-images-size-one" src="{{ url('images/adv/'.$adv->image2) }}"
        alt="{{ $adv->image2 }}">
    </a>
  </div>
</div>
<br>
@endif
@endforeach
@endif
<!--END -->

<!-- ============================================== FEATURED PRODUCTS ============================================== -->
@if(count($featureds)>0)
<section class="section new-arriavls feature-product-block">
  <h3 class="section-title">{{ __('staticwords.fpro') }}</h3>
  <div class="owl-carousel home-owl-carousel custom-carousel owl-theme outer-top-xs">

    @foreach($featureds as $featured)
    @foreach($featured->subvariants as $key=> $orivar)
    @if($orivar->def ==1)

    @php
    $var_name_count = count($orivar['main_attr_id']);

    $name=array();
    $var_name;
    $newarr = array();
    for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
      $var_name[$i]=$orivar['main_attr_value'][$var_id]; // echo($orivar['main_attr_id'][$i]);
      $name[$i]=App\ProductAttributes::where('id',$var_id)->first();

      }


      try{
      $url =
      url('details').'/'.$featured->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
      }catch(Exception $e)
      {
      $url = url('details').'/'.$featured->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
      }

      @endphp
      <div class="item item-carousel">
        <div class="products">
          <div class="product">
            <div class="product-image">
              <div class="image {{ $orivar->stock ==0 ? "pro-img-box" : ""}}">

                <a href="{{$url}}" title="{{$featured->name}}">

                  @if(count($featured->subvariants)>0)

                  @if(isset($orivar->variantimages['main_image']))
                  <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}"
                    src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                    alt="{{$featured->name}}">
                  <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}  hover-image"
                    src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt="" />
                  @endif

                  @else
                  <img class="{{ $orivar->stock ==0 ? "filterdimage" : ""}}" title="{{ $featured->name }}"
                    src="{{url('images/no-image.png')}}" alt="No Image" />

                  @endif



                </a>
              </div>

              @if($orivar->stock == 0)
              <h6 align="center" class="oottext"><span>{{ __('staticwords.Outofstock') }}</span></h6>
              @endif

              @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
              $orivar->products->selling_start_at >= $current_date)
              <h6 align="center" class="oottext2"><span>{{ __('staticwords.ComingSoon') }}</span></h6>
              @endif
              <!-- /.image -->



              @if($featured->featured=="1")
              <div class="tag hot"><span>{{ __('staticwords.Hot') }}</span></div>
              @elseif($featured->offer_price!="0")
              <div class="tag sale"><span>{{ __('staticwords.Sale') }}</span></div>
              @else
              <div class="tag new"><span>{{ __('staticwords.New') }}</span></div>
              @endif
            </div>


            <!-- /.product-image -->

            <div class="product-info text-left">
              <h3 class="name"><a
                  href="{{$url}}">{{substr($featured->name, 0, 20)}}{{strlen($featured->name)>20 ? '...' : ""}}</a></h3>
              <?php 
                            $review_t = 0;
                            $price_t = 0;
                            $value_t = 0;
                            $sub_total = 0;
                            $reviews = App\UserReview::where('pro_id',$featured->id)->where('status','1')->get();
                            ?> @if(!empty($reviews[0]))<?php
                            $count =  App\UserReview::where('pro_id',$featured->id)->count();
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
                <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                    class="star-ratings-sprite-rating"></span></div>
              </div>

              @else
              {{__('No Rating')}}
              @endif
              <div class="description"></div>


              <!-- Product-price -->

              <div class="product-price"> <span class="price">

                  <div class="product-price"> <span class="price">

                      @if($price_login == 0 || Auth::check())
                      @php
                      $convert_price = 0;
                      $show_price = 0;

                      $commision_setting = App\CommissionSetting::first();

                      if($commision_setting->type == "flat"){

                      $commission_amount = $commision_setting->rate;
                      if($commision_setting->p_type == 'f'){

                      if($featured->tax_r !=''){

                      $cit =$commission_amount*$featured->tax_r/100;
                      $totalprice = $featured->vender_price+$orivar->price+$commission_amount+$cit;
                      $totalsaleprice = $featured->vender_offer_price + $cit + $orivar->price + $commission_amount;

                      if($featured->vender_offer_price == 0){
                      $show_price = $totalprice;
                      }else{
                      $totalsaleprice;
                      $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                      $show_price = $totalprice;
                      }


                      }else{

                      $totalprice = $featured->vender_price+$orivar->price+$commission_amount;
                      $totalsaleprice = $featured->vender_offer_price + $orivar->price + $commission_amount;

                      if($featured->vender_offer_price == 0){
                      $show_price = $totalprice;
                      }else{
                      $totalsaleprice;
                      $convert_price = $totalsaleprice =='' ? $totalprice:$totalsaleprice;
                      $show_price = $totalprice;
                      }

                      }


                      }else{

                      $totalprice = ($featured->vender_price+$orivar->price)*$commission_amount;

                      $totalsaleprice = ($featured->vender_offer_price+$orivar->price)*$commission_amount;

                      $buyerprice = ($featured->vender_price+$orivar->price)+($totalprice/100);

                      $buyersaleprice = ($featured->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                      if($featured->vender_offer_price ==0){
                      $show_price = round($buyerprice,2);
                      }else{
                      round($buyersaleprice,2);

                      $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                      $show_price = $buyerprice;
                      }


                      }
                      }else{

                      $comm = App\Commission::where('category_id',$featured->category_id)->first();
                      if(isset($comm)){
                      if($comm->type=='f'){



                      if($featured->tax_r != ''){

                      $cit = $comm->rate*$featured->tax_r/100;

                      $price = $featured->vender_price + $comm->rate + $orivar->price + $cit;

                      if($featured->vender_offer_price != null){
                      $offer = $featured->vender_offer_price + $comm->rate + $orivar->price + $cit;
                      }else{
                      $offer = $featured->vender_offer_price;
                      }

                      if($featured->vender_offer_price == 0 || $featured->vender_offer_price == null){
                      $show_price = $price;
                      }else{

                      $convert_price = $offer;
                      $show_price = $price;
                      }

                      }else{


                      $price = $featured->vender_price + $comm->rate + $orivar->price;

                      if($featured->vender_offer_price != null){
                      $offer = $featured->vender_offer_price + $comm->rate + $orivar->price;
                      }else{
                      $offer = $featured->vender_offer_price;
                      }

                      if($featured->vender_offer_price == 0 || $featured->vender_offer_price == null){
                      $show_price = $price;
                      }else{

                      $convert_price = $offer;
                      $show_price = $price;
                      }

                      }




                      }
                      else{

                      $commission_amount = $comm->rate;

                      $totalprice = ($featured->vender_price+$orivar->price)*$commission_amount;

                      $totalsaleprice = ($featured->vender_offer_price+$orivar->price)*$commission_amount;

                      $buyerprice = ($featured->vender_price+$orivar->price)+($totalprice/100);

                      $buyersaleprice = ($featured->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                      if($featured->vender_offer_price == 0){
                      $show_price = round($buyerprice,2);
                      }else{
                      round($buyersaleprice,2);

                      $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                      $show_price = round($buyerprice,2);
                      }



                      }
                      }else{
                      $commission_amount = 0;

                      $totalprice = ($featured->vender_price+$orivar->price)*$commission_amount;

                      $totalsaleprice = ($featured->vender_offer_price+$orivar->price)*$commission_amount;

                      $buyerprice = ($featured->vender_price+$orivar->price)+($totalprice/100);

                      $buyersaleprice = ($featured->vender_offer_price+$orivar->price)+($totalsaleprice/100);


                      if($featured->vender_offer_price == 0){
                      $show_price = round($buyerprice,2);
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
                      <span class="price"><i
                          class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>
                      @else

                      <span class="price"><i
                          class="{{session()->get('currency')['value']}}"></i>{{round($convert_price,2)}}</span>

                      <span class="price-before-discount"><i
                          class="{{session()->get('currency')['value']}}"></i>{{round($show_price,2)}}</span>


                      @endif
                      @endif

                      @endif
                  </div>

              </div>
              <!-- /.product-price -->

            </div>
            @if($orivar->stock != 0 && $orivar->products->selling_start_at != null &&
            $orivar->products->selling_start_at >= $current_date)
            @elseif($orivar->stock < 1) @else <!-- /.product-info -->
              <div class="cart clearfix animate-effect">
                <div class="action">
                  <ul class="list-unstyled">
                    @if(!empty($auth))
                    <?php $cart_table = App\Cart::where('user_id',$auth->id)->where('pro_id',$featured->id)->first(); ?>@endif
                    @if(empty($cart_table))
                    <li id="addCart" class="lnk wishlist">

                      @if($price_login != 1)
                      <form method="POST"
                        action="{{route('add.cart',['id' => $featured->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                        {{ csrf_field() }}
                        <button title="{{ __('staticwords.AddtoCart') }}" type="submit" class="addtocartcus btn">
                          <i class="fa fa-shopping-cart"></i>
                        </button>
                      </form>
                      @endif

                    </li>
                    @else
                    <li id="addCart" class="lnk wishlist"> <a class="add-to-cart"
                        href="{{url('remove_table_cart/'.$orivar->id)}}" title="{{ __('Remove Cart') }}"> <i
                          class="icon fa fa-times"></i> </a> </li>
                    @endif

                    @auth
                    @if(Auth::user()->wishlist->count()<1) <li class="lnk wishlist">

                      <a mainid="{{ $orivar->id }}" class="cursor-pointer add-to-cart addtowish"
                        data-add="{{url('AddToWishList/'.$orivar->id)}}" title="{{ __('staticwords.AddToWishList') }}">
                        <i class="icon fa fa-heart"></i>
                      </a>

                      </li>
                      @else

                      @php
                      $ifinwishlist =
                      App\Wishlist::where('user_id',Auth::user()->id)->where('pro_id',$orivar->id)->first();
                      @endphp

                      @if(!empty($ifinwishlist))
                      <li class="lnk wishlist active">
                        <a mainid="{{ $orivar->id }}" title="{{ __('staticwords.RemoveFromWishlist') }}"
                          class="add-to-cart removeFrmWish active color000 cursor-pointer"
                          data-remove="{{url('removeWishList/'.$orivar->id)}}"> <i class="icon fa fa-heart"></i> </a>
                      </li>
                      @else
                      <li class="lnk wishlist"> <a title="{{ __('staticwords.AddToWishList') }}"
                          mainid="{{ $orivar->id }}" class="add-to-cart addtowish cursor-pointer text-white"
                          data-add="{{url('AddToWishList/'.$orivar->id)}}"> <i class="activeOne icon fa fa-heart"></i>
                        </a></li>
                      @endif

                      @endif
                      @endauth
                      <li class="lnk"> <a class="add-to-cart" href="{{route('compare.product',$orivar->products->id)}}"
                          title="{{ __('staticwords.Compare') }}"> <i class="fa fa-signal" aria-hidden="true"></i> </a>
                      </li>
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
      <!-- /.item -->
      @endif
      @endforeach
      @endforeach
  </div>
  <!-- /.home-owl-carousel -->
</section>
@endif
<!-- /.section -->
<!-- ============================================== FEATURED PRODUCTS : END =================Advertisement after featured product ============================= -->

@php
$getads = App\Adv::where('position','=','afterfeaturedproduct')->where('status','=',1)->get();
@endphp

<!-- Advertisement -->
@if(isset($getads))
@foreach($getads as $adv)
@if($adv->layout == 'Single image layout')
<a href="
                @if($adv->cat_id1 != '')
                 @include('front.adv.caturl')
                @elseif($adv->pro_id1 != '')
                  @include('front.adv.pro')
                @else
                  {{ $adv->url1 }}
                @endif" title="Click to visit">
  <img class="img-fluid single-image-layout advertisement-img advertisement-img-one"
    src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
</a>

@endif

@if($adv->layout == 'Three Image Layout')
<div class="row">
  <div class="col-md-4">
    <a href="
                @if($adv->cat_id1 != '')
                 @include('front.adv.caturl')
                @elseif($adv->pro_id1 != '')
                  @include('front.adv.pro')
                @else
                  {{ $adv->url1 }}
                @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                @if($adv->cat_id2 != '')
                   @include('front.adv.caturl2')
                @elseif($adv->pro_id2 != '')
                   @include('front.adv.pro2')
                @else
                  {{ $adv->url2 }}
                @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img" src="{{ url('images/adv/'.$adv->image2) }}" alt="{{ $adv->image2 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                @if($adv->cat_id3 != '')
                  @include('front.adv.caturl3')
                @elseif($adv->pro_id3 != '')
                  @include('front.adv.pro3')
                @else
                  {{ $adv->url3 }}
                @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid" src="{{ url('images/adv/'.$adv->image3) }}" alt="{{ $adv->image3 }}">
    </a>
  </div>
</div>
<br>
@endif

@if($adv->layout == 'Two equal image layout')
<div class="row">
  <div class="col-md-6">
    <a href="
                    @if($adv->cat_id1 != '')
                     @include('front.adv.caturl')
                    @elseif($adv->pro_id1 != '')
                      @include('front.adv.pro')
                    @else
                      {{ $adv->url1 }}
                    @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img-one advertisement-img" src="{{ url('images/adv/'.$adv->image1) }}"
        alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-6">
    <a href="
                    @if($adv->cat_id2 != '')
                     @include('front.adv.caturl2')
                    @elseif($adv->pro_id2 != '')
                      @include('front.adv.pro2')
                    @else
                      {{ $adv->url2 }}
                    @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img-one" src="{{ url('images/adv/'.$adv->image2) }}" alt="{{ $adv->image2 }}">
    </a>
  </div>
</div>
<br>
@endif

@if($adv->layout == 'Two non equal image layout')
<div class="row">
  <div class="col-md-8">
    <a href="
                  @if($adv->cat_id1 != '')
                   @include('front.adv.caturl')
                  @elseif($adv->pro_id1 != '')
                    @include('front.adv.pro')
                  @else
                    {{ $adv->url1 }}
                  @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-img advertisement-img-one advertisement-images-size-one"
        src="{{ url('images/adv/'.$adv->image1) }}" alt="{{ $adv->image1 }}">
    </a>
  </div>
  <div class="col-md-4">
    <a href="
                    @if($adv->cat_id2 != '')
                     @include('front.adv.caturl2')
                    @elseif($adv->pro_id2 != '')
                      @include('front.adv.pro2')
                    @else
                      {{ $adv->url2 }}
                    @endif" title="{{ __('Click to visit') }}">
      <img class="img-fluid advertisement-images-size-one" src="{{ url('images/adv/'.$adv->image2) }}"
        alt="{{ $adv->image2 }}">
    </a>
  </div>
</div>
<br>
@endif
@endforeach
@endif
<!--END -->
</div>

</div>
<!-- /.homebanner-holder -->
<!-- ============================================== CONTENT : END ============================================== -->
</div>
<!-- /.row -->
<!-- ============================================== BRANDS CAROUSEL ============================================== -->




<!-- /.logo-slider -->
<!-- ============================================== BRANDS CAROUSEL : END ============================================== -->
</div>
<!-- /.container -->
</div>
<!-- /#top-banner-and-menu -->

<!-- ============================================== INFO BOXES ============================================== -->

<!-- /.info-boxes -->
<!-- ============================================== INFO BOXES : END ============================================== -->

@endsection
@section('script')

<script>
  var baseUrl = "<?= url('/') ?>";
</script>
<script src="{{ url('js/wishlist.min.js') }}"></script>
@endsection