<div class="col-12 col-sm-12 col-md-12 col-lg-12  col-xl-2 sidebar left-sidebar">
  <div class="side-content ">
    @php
    $price_login = App\Genral::first()->login;
    $enable_cat_widget = App\Widgetsetting::where('name','category')->first(); 
    $price_array = array();
    @endphp
    <!-- ================================== TOP NAVIGATION ================================== -->
    @if(!empty($home_slider))
    @if($enable_cat_widget->home=='1')
    <div class="side-menu animate-dropdown outer-bottom-xs header-nav-screen">
      <div class="head"><i class="icon fa fa-align-justify fa-fw"></i> {{ __('staticwords.Categories') }} </div>
      <nav class="megamenu-horizontal">

        <ul class="nav">
          @php 
              $price_array = array();
              if($genrals_settings->vendor_enable == 1){
                
                $pirmarycategories = App\Category::
                                     join('products', function ($join) {
                                        $join->on('products.category_id', '=', 'categories.id')->where('products.status','1');
                                     })
                                     ->leftjoin('users', function ($join) {
                                        $join->on('products.vender_id', '=', 'users.id')->where('users.status','1');
                                     })
                                     ->where('products.is_new',1)
                                     ->select('categories.*')->where('categories.status','=','1')->get();
                                     
              }else{
                
                $pirmarycategories = App\Category::
                                     join('products', function ($join) {
                                        $join->on('products.category_id', '=', 'categories.id')->where('products.status','1');
                                     })
                                     ->join('users', function ($join) {
                                        $join->on('products.vender_id', '=', 'users.id')->where('users.status','1')->where('users.role_id','!=','v');
                                     })
                                    ->orderBy('position','ASC')->select('categories.*')->where('categories.status','=','1')->get();
              }
          @endphp
          @foreach($pirmarycategories as $item)

          @if($item->products->count()>0)
          @foreach($item->products as $old)

          
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

          $comm = App\Commission::where('category_id',$orivar->products->category_id)->first();
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

            <a aria-expanded="false" title="{{$item->title}}" href="#childOpen{{ $item->id }}" data-toggle="collapse"
              class="cat-page collapsed">
              <i class="fa {{ $item->icon }}"></i>
              @if($price_login == 0 || Auth::check())

              <label class="cursor-pointer"
                onclick="categoryfilter('{{$item->id}}','','','{{ $startp }}','{{ $endp }}')">{{substr(strip_tags($item->title), 0, 20)}}{{strlen(strip_tags(
                $item->title))>20 ? '...' : ""}}</label>

              @else

              <label class="cursor-pointer">
                {{substr(strip_tags($item->title), 0, 20)}}{{strlen(strip_tags($item->title))>20 ? '...' : ""}}
              </label>

              @endif
            </a>


            <ul class="collapse" id="childOpen{{ $item->id }}">

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

              $comm = App\Commission::where('category_id',$orivar->products->category_id)->first();
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
              <li class="left-4 dropdown menu-item {{ $s->childcategory->count()>0 ? "" : "side-sub-list" }}">
                <a title="{{$s->title}}" data-toggle="collapse" href="#collapseExample{{ $s->id }}"
                  aria-expanded="false" aria-controls="collapseExample{{ $s->id }}"><i class="fa {{ $s->icon }}"></i>
                  @if($price_login == 0 || Auth::check())

                  <label class="cursor-pointer"
                    onclick="categoryfilter('{{$item->id}}','{{ $s->id }}','','{{ $startp }}','{{ $endp }}')">
                    {{substr(strip_tags($s->title), 0, 20)}}{{strlen(strip_tags($s->title))>20 ? '...' : ""}}
                  </label>

                  @else

                  <label class="cursor-pointer">
                    {{substr(strip_tags($s->title), 0, 20)}}{{strlen(strip_tags($s->title))>20 ? '...' : ""}}
                  </label>

                  @endif
                </a>

                <!-- child category loop -->

                @if($s->childcategory->count()>0)

                <ul class="collapse" id="collapseExample{{ $s->id }}">
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

                  $comm = App\Commission::where('category_id',$orivar->products->category_id)->first();
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
                  @if($old->vender->role_id == 'a')
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

                  $comm = App\Commission::where('category_id',$orivar->products->category_id)->first();
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
                        class="fa fa-angle-double-right" aria-hidden="true"></i>
                      {{substr(strip_tags($child->title), 0, 20)}}{{strlen(strip_tags($child->title))>20 ? '...' : ""}}
                    </a>
                    @else
                    <a class="cursor-pointer" title="{{ $child['title'] }}"><i class="fa fa-angle-double-right"
                        aria-hidden="true"></i>
                      {{substr(strip_tags($child->title), 0, 20)}}{{strlen(strip_tags($child->title))>20 ? '...' : ""}}</a>
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
      <!-- /.megamenu-horizontal -->
    </div>
    @endif
    @endif

    <!-- /.side-menu -->
    <!-- ================================== TOP NAVIGATION : END ================================== -->

    <!-- ============================================== HOT DEALS ============================================== -->
    @php
    $enable_hotdeal = App\Widgetsetting::where('name','hotdeals')->first();
    $date  =  date('Y-m-d h:i:s'); 
    if($genrals_settings->vendor_enable != 1){
      $hotdeals = App\Hotdeal::join('products','products.id','=','hotdeals.pro_id')->join('users','users.id','=','products.vender_id')->where('users.status','1')->where('products.status','=','1')->where('hotdeals.status','=','1')->where('users.role_id','!=','v')->get();
    }else{
      $hotdeals = App\Hotdeal::join('products','products.id','=','hotdeals.pro_id')->join('users','users.id','=','products.vender_id')->where('users.status','1')->where('products.status','=','1')->where('hotdeals.status','=','1')->get();
     
    }

    @endphp
    @if(isset($enable_hotdeal) && $enable_hotdeal->home == "1")
    <div class="sidebar-widget hot-deals outer-bottom-xs">
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

        $price = $value->pro->vender_price + $comm->rate+$orivar->price;
        $offer = $value->pro->vender_offer_price + $comm->rate+$orivar->price;

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

        for($i = 0; $i<$var_name_count; $i++){ $var_id=$orivar['main_attr_id'][$i];
          $var_name[$i]=$orivar['main_attr_value'][$var_id]; $name[$i]=App\ProductAttributes::where('id',$var_id)->
          first();

          }


          try{
          $url =
          url('details').'/'.$value->pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
          }catch(\Exception $e)
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
                      <img class="{{ $orivar->stock == 0 ? "filterdimage" : ""}}"
                        src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                        alt="{{$value->name}}">
                      <img class="{{ $orivar->stock == 0 ? "filterdimage" : ""}} hover-image"
                        src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}" alt="" />
                      @endif

                      @else
                      <img title="{{ $value->name }}" src="{{url('images/no-image.png')}}" alt="No Image" />
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
                    <div class="timing-wrapper" data-startat="{{ $value->start }}" data-countdown="{{$value->end}}">
                    </div>
                  </div>
                </div>
                <!-- /.hot-deal-wrapper -->

                <div class="product-info text-left m-t-20">
                  <h3 class="name"><b><a href="{{$url}}" title="{{$value->pro->name}}">{{$value->pro->name}}</a></b>
                  </h3>
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

                  <div class="text-center">
                    <div class="star-ratings-sprite"><span style="width:<?php echo $ratings_var; ?>%"
                        class="star-ratings-sprite-rating"></span></div>
                  </div>

                  @else
                  <div class="text-center">
                    {{'No Rating'}}
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
                      $show_price = round($buyerprice,2);
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
                      $offer = $value->pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                      }else{
                      $offer = $value->pro->vender_offer_price;
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
                      $offer = $value->pro->vender_offer_price + $comm->rate + $orivar->price;
                      }else{
                      $offer = $value->pro->vender_offer_price;
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
                      $convert_price = round($buyerprice,2);
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
                      <span class="price"><i
                          class="{{session()->get('currency')['value']}}"></i>{{round($show_price*$conversion_rate,2)}}</span>
                      @else

                      <span class="price"><i
                          class="{{session()->get('currency')['value']}}"></i>{{round($convert_price*$conversion_rate,2)}}</span>

                      <span class="price-before-discount"><i
                          class="{{session()->get('currency')['value']}}"></i>{{round($show_price*$conversion_rate,2)}}</span>


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
                        @if(!isset($isInCart) && $in_session==0 && $orivar->stock>0)
                        <form method="POST"
                          action="{{route('add.cart',['id' => $value->pro->id ,'variantid' =>$orivar->id, 'varprice' => $show_price, 'varofferprice' => $convert_price ,'qty' =>$orivar->min_order_qty])}}">
                          {{ csrf_field() }}
                          <li class="add-cart-button btn-group">
                            <button class="btn btn-primary icon" data-toggle="dropdown" type="button"> <i
                                class="fa fa-shopping-cart"></i> </button>
                            <button class="btn btn-primary cart-btn" type="submit">Add to cart</button>
                          </li>
                        </form>
                        @else
                        @if($orivar->stock>0 && $in_session == 1)
                        <li class="add-cart-button btn-group">

                          <button class="btn btn-primary icon" data-toggle="dropdown" type="button"> <i
                              class="fa fa-check"></i> </button>
                          <button onclick="window.location='{{ url('/cart') }}'" class="btn btn-primary cart-btn"
                            type="button">Deal in Cart</button>

                        </li>
                        @endif
                        @if($orivar->stock==0)

                        <h5 class="required" align="center"><span>{{ __('staticwords.Outofstock') }}</span></h5>

                        @endif
                        @endif
                        @else
                        <h5><span>{{ __('staticwords.ComingSoon') }}</span></h5>
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
    @php
    $enable_sp_offer = App\Widgetsetting::where('name','specialoffer')->first();

    if($genrals_settings->vendor_enable != 1){
      $specialoffers = App\SpecialOffer::join('products','products.id','=','special_offers.pro_id')->join('users','users.id','=','products.vender_id')->where('users.status','1')->where('products.status','=','1')->where('special_offers.status','=','1')->where('users.role_id','!=','v')->get();
    }else{
      $specialoffers = App\SpecialOffer::join('products','products.id','=','special_offers.pro_id')->join('users','users.id','=','products.vender_id')->where('users.status','1')->where('products.status','=','1')->where('special_offers.status','=','1')->get();
    }
    
    @endphp
    <!-- ============================================== HOT DEALS: END ============================================== -->

    <!-- ============================================== SPECIAL OFFER ============================================== -->
    @if(count($specialoffers)>0 && isset($enable_sp_offer) && $enable_sp_offer->home == "1")
    <div class="sidebar-widget outer-bottom-small">
      <h3 class="section-title">{{ __('staticwords.sp') }}</h3>
      <div class="sidebar-widget-body outer-top-xs">
        <div class="owl-carousel sidebar-carousel special-offer custom-carousel owl-theme outer-top-xs">
          @foreach($specialoffers as $offerproduct)
          @if(isset($offerproduct->pro) && isset($offerproduct->pro->subvariants))
          @foreach($offerproduct->pro->subvariants as $varkey=> $orivar)
          @if($orivar->def ==1)
          <div class="item">
            <div class="products special-product">
             
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
                url('details').'/'.$offerproduct['pro']['id'].'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                }catch(Exception $e)
                {
                $url = url('details').'/'.$offerproduct['pro']['id'].'?'.$name[0]['attr_name'].'='.$var_name[0];
                }

                @endphp
                <div class="product">
                  <div class="product-micro">
                    <div class="row product-micro-row">
                      <div class="col col-5">
                        <div class="product-image">
                          <div class="image">

                            <a href="{{$url}}" title="{{$offerproduct->pro->name}}">

                              @if(count($offerproduct->pro->subvariants)>0)

                              @if(isset($orivar->variantimages['image2']))
                              <img src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}"
                                alt="{{$offerproduct->pro->name}}">
                              <img class="hover-image"
                                src="{{url('variantimages/hoverthumbnail/'.$orivar->variantimages['image2'])}}"
                                alt="" />
                              @endif

                              @else
                              <img title="{{ $offerproduct->pro->name }}" src="{{url('images/no-image.png')}}"
                                alt="No Image" />
                              @endif

                            </a>

                          </div>
                          <!-- /.image -->

                        </div>
                        <!-- /.product-image -->
                      </div>
                      <!-- /.col -->
                      <div class="col col-6">
                        <div class="product-info">
                          <h3 class="name"><a href="{{$url}}"
                              title="{{ $offerproduct->pro->name }}">{{ $offerproduct->pro->name }}</a></h3>
                          <?php 
                                      $review_t = 0;
                                      $price_t = 0;
                                      $value_t = 0;
                                      $sub_total = 0;
                                      $sub_total = 0;
                                      $reviews = App\UserReview::where('pro_id',$offerproduct->pro->id)->where('status','1')->get();
                                      ?> @if(!empty($reviews[0]))<?php
                                      $count =  App\UserReview::where('pro_id',$offerproduct->pro->id)->count();
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
                          {{'No Rating'}}
                          @endif

                          <!-- /.product-price -->

                          <div class="product-price"> <span class="price">

                              @if($price_login==0 || Auth::user())
                              @php
                              $convert_price = 0;
                              $show_price = 0;

                              $commision_setting = App\CommissionSetting::first();

                              if($commision_setting->type == "flat"){

                              $commission_amount = $commision_setting->rate;
                              if($commision_setting->p_type == 'f'){



                              if($offerproduct->pro['tax_r'] !=''){



                              $cit =$commission_amount*$offerproduct->pro['tax_r']/100;

                              $totalprice = $offerproduct->pro['vender_price']+$orivar->price+$commission_amount+$cit;

                              if($offerproduct->pro['vender_offer_price'] != 0){

                              $totalsaleprice = $offerproduct->pro['vender_offer_price'] + $orivar->price +
                              $commission_amount+$cit;

                              }


                              if($offerproduct->pro['vender_offer_price'] == 0){
                              $show_price = $totalprice;
                              }else{
                              $totalsaleprice;
                              $convert_price = $totalsaleprice;
                              $show_price = $totalprice;
                              }

                              }else{

                              $totalprice = $offerproduct->pro['vender_price']+$orivar->price+$commission_amount;
                              $totalsaleprice = $offerproduct->pro['vender_offer_price'] + $orivar->price +
                              $commission_amount;

                              if($offerproduct->pro['vender_offer_price'] == 0){
                              $show_price = $totalprice;
                              }else{
                              $totalsaleprice;
                              $convert_price = $totalsaleprice==''?$totalprice:$totalsaleprice;
                              $show_price = $totalprice;
                              }

                              }



                              }else{

                              $totalprice = ($offerproduct->pro['vender_price']+$orivar->price)*$commission_amount;

                              $totalsaleprice =
                              ($offerproduct->pro['vender_offer_price']+$orivar->price)*$commission_amount;

                              $buyerprice = ($offerproduct->pro['vender_price']+$orivar->price)+($totalprice/100);

                              $buyersaleprice =
                              ($offerproduct->pro['vender_offer_price']+$orivar->price)+($totalsaleprice/100);


                              if($offerproduct->pro['vender_offer_price'] == 0){
                              $show_price = round($buyerprice,2);
                              }else{
                              round($buyersaleprice,2);
                              $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                              $show_price = $buyerprice;
                              }


                              }
                              }else{

                              $comm = App\Commission::where('category_id',$offerproduct->pro['category_id'])->first();
                              if(isset($comm)){

                              if($comm->type=='f'){

                              $price = $offerproduct->pro['vender_price'] + $comm->rate+$orivar->price;
                              $offer = $offerproduct->pro['vender_offer_price'] + $comm->rate+$orivar->price;


                              if($offerproduct->pro['vender_offer_price'] != null){
                              $offer = $offerproduct->pro['vender_offer_price'] + $comm->rate + $orivar->price;
                              }else{
                              $offer = $offerproduct->pro['vender_offer_price'];
                              }

                              if($offerproduct->pro['vender_offer_price'] == 0){
                              $show_price = $price;
                              }else{

                              $convert_price = $offer;
                              $show_price = $price;
                              }


                              }
                              else{

                              $commission_amount = $comm->rate;

                              $totalprice = ($offerproduct->pro['vender_price']+$orivar->price)*$commission_amount;

                              $totalsaleprice =
                              ($offerproduct->pro['vender_offer_price']+$orivar->price)*$commission_amount;

                              $buyerprice = ($offerproduct->pro['vender_price']+$orivar->price)+($totalprice/100);

                              $buyersaleprice =
                              ($offerproduct->pro['vender_offer_price']+$orivar->price)+($totalsaleprice/100);


                              if($offerproduct->pro['vender_offer_price'] ==0){
                              $show_price = round($buyerprice,2);
                              }else{
                              round($buyersaleprice,2);
                              $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                              $show_price = round($buyerprice,2);
                              }



                              }
                              }else{
                              $commission_amount = 0;

                              $totalprice = ($offerproduct->pro['vender_price']+$orivar->price)*$commission_amount;

                              $totalsaleprice =
                              ($offerproduct->pro['vender_offer_price']+$orivar->price)*$commission_amount;

                              $buyerprice = ($offerproduct->pro['vender_price']+$orivar->price)+($totalprice/100);

                              $buyersaleprice =
                              ($offerproduct->pro['vender_offer_price']+$orivar->price)+($totalsaleprice/100);


                              if($offerproduct->pro['vender_offer_price'] == 0){
                              $show_price = round($buyerprice,2);
                              }else{
                              round($buyersaleprice,2);
                              $convert_price = $buyersaleprice==''?$buyerprice:$buyersaleprice;
                              $show_price = round($buyerprice,2);
                              }
                              }
                              }

                              @endphp

                              @if(Session::has('currency'))

                              @if($convert_price == 0 || $convert_price == 'null')

                              <span class="price"><i
                                  class="{{session()->get('currency')['value']}}"></i>{{round($show_price*$conversion_rate,2)}}</span>
                              @else

                              <span class="price"><i
                                  class="{{session()->get('currency')['value']}}"></i>{{round($convert_price*$conversion_rate,2)}}</span>

                              <span class="price-before-discount"><i
                                  class="{{session()->get('currency')['value']}}"></i>{{round($show_price*$conversion_rate,2)}}</span>
                              @endif

                              @endif
                              @if($orivar->stock==0)
                              <h5 class="required"><span>{{ __('staticwords.Outofstock') }}</span></h5>
                              @endif
                              @endif
                          </div>

                        </div>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.product-micro-row -->
                  </div>

                  <!-- /.product-micro -->

                </div>
                
            </div>
          </div>
                @endif
                @endforeach
                @endif
          @endforeach
        </div>
      </div>
      <!-- /.sidebar-widget-body -->
    </div>
    @endif

    <!-- ============================================== SPECIAL DEALS : END ============================================== -->
    <!-- ============================================== NEWSLETTER ============================================== -->
    @php
    $enable_newsletter_widget = App\Widgetsetting::where('name','newsletter')->first();
    @endphp
    @if($enable_newsletter_widget->home == '1' && isset($enable_newsletter_widget))
    <div class="sidebar-widget newsletter outer-bottom-small header-nav-screen">
      <h3 class="section-title">{{ __('staticwords.nw') }}</h3>
      <div class="sidebar-widget-body outer-top-xs">
        <p>{{ __('staticwords.newsletterword') }}</p>
        <form method="post" action="{{url('newsletter')}}">
          {{csrf_field()}}
          <div class="form-group">
            <label class="sr-only" for="exampleInputEmail1">{{ __('staticwords.eaddress') }}<</label> <input
                type="email" name="email" class="form-control" id="exampleInputEmail1"
                placeholder="{{ __('staticwords.subscribeword') }}">
          </div>
          <button class="btn btn-primary">{{ __('staticwords.Subscribe') }}</button>
        </form>
      </div>
      <!-- /.sidebar-widget-body -->
    </div>
    @endif
    <!-- /.sidebar-widget -->
    <!-- ============================================== NEWSLETTER: END ============================================== -->

    <!-- ============================================== Testimonials============================================== -->
    @php
    $testimonials = App\Testimonial::where('status','1')->get();
    $enable_testimonial_widget = App\Widgetsetting::where('name','testimonial')->first();
    @endphp
    @if(count($testimonials)>0 && isset($enable_testimonial_widget) && $enable_testimonial_widget->home == '1')

    <div class="sidebar-widget outer-top-vs advertisement-testimonial">
      <div id="advertisement" class="advertisement">


        @foreach($testimonials as $value)
        @if($home_slider->home=='1')

        <div class="item">
          <div class="avatar">
            @if($value->image =='')
            <img title="{{ $value->name }}" src="{{ Avatar::create($value->name)->toBase64() }}" />
            @else

            <img src="{{url('images/testimonial/'.$value->image)}}" alt="{{ $value->name }}">

            @endif
          </div>
          <div class="testimonials"><em>"</em> {{strip_tags($value->des)}}<em>"</em></div>
          <div class="clients_author">{{ $value->name }}<span>{{ $value->post }}</span> </div>
          <!-- /.container-fluid -->
        </div>
        @endif
        @endforeach

        <!-- /.item -->

      </div>
      <!-- /.owl-carousel -->
    </div>
    @endif

    <!-- ============================================== Testimonials: END ============================================== -->
  </div>

</div>
<script>
  var crate = {!!json_encode(round($conversion_rate, 4)) !!};
  var exist = {!!json_encode(url('shop')) !!};
  var setstart = {!!json_encode(url('setstart')) !!};
</script>
<script src="{{url('js/frontlayout.min.js')}}"></script>
@include('front.layout.hotdealscript')