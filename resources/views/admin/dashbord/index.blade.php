  @extends("admin/layouts.master")
  @section('title','Admin Dashboard | ')
  @php
   $dashsetting = App\DashboardSetting::first();
  @endphp

  @section('title','Admin Dashboard |')
    @section("body")
        <div class="box">
           
           <div class="box-header with-border">

              <h3 class="box-title"><span class="lnr lnr-laptop"></span> Dashboard</h3>
              
            </div>

          <div class="box-body">

         
            
        <div class="db row">

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3>{{$user}}</h3>

                <p>Total Users</p>
              </div>
             
               <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="{{url('admin/users')}}" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3>{{$order}}<sup class="font-size-20"></sup></h3>

                <p>Total Orders</p>
              </div>
              <div class="icon">
               <i class="fa fa-area-chart" aria-hidden="true"></i>

              </div>
              <a href="{{url('admin/order')}}" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
       
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
                <h3>{{$totalcancelorder}}<sup class="font-size-20"></sup></h3>

                <p>Total Canceled Orders</p>
              </div>
              <div class="icon">
               <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>

              </div>
              <a href="{{url('admin/ord/canceled')}}" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
              
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3>{{ count($products) }}</h3>

                <p>Total Products</p>
              </div>

               <div class="icon">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
              </div>

             
              <a href="{{url('admin/products')}}" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3>{{$store}}</h3>

                <p>Total Stores</p>
              </div>
              <div class="icon">
               <i class="fa fa-home" aria-hidden="true"></i>
              </div>
              <a href="{{url('admin/stores')}}" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3>{{$category}}</h3>

                <p>Total Categories</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{url('admin/category')}}" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-blue">
              <div class="inner">
                <h3>{{$coupan}}</h3>

                <p>Total Coupons</p>
              </div>
              <div class="icon">
               <i class="fa fa-money" aria-hidden="true"></i>
              </div>
              <a href="{{url('admin/coupan')}}" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-blue">
              <div class="inner">
                <h3>{{$faqs}}</h3>

                <p>Total FAQ's</p>
              </div>
              <div class="icon">
               
               <i class="fa fa-question-circle-o" aria-hidden="true"></i>


              </div>
              <a href="{{url('admin/faq')}}" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          @if($genrals_settings->vendor_enable == 1)
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-olive">
              <div class="inner">
                <h3>{{ count($filterpayout) }}</h3>

                <p>Pending Payouts</p>
              </div>
              <div class="icon">
               
               <i class="fa fa-dollar" aria-hidden="true"></i>


              </div>
              <a href="{{ route('seller.payouts.index') }}" class="small-box-footer">
                See all <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          @endif

        </div>

         <div class="z-index99">

                {!! $orderchart->container() !!}
               
          </div>

          @if($dashsetting->lat_ord ==1)
            
              <div class="box box-solid">
        <div class="box-header with-border">
          
          <h4 class="box-title"><span class="ltorder lnr lnr-star"></span> Latest Orders</h4>
            
           <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
           </div>
          
        </div>
        
          <div class="ltorderbox box-body">
          <table class="table table-borderd table-responsive">
            <thead>
              <tr>
                <th>#</th>
                <th>Order ID</th>
                <th>Customer name</th>
                <th>Total Qty</th>
                <th>Total Price</th>
                <th>Order Date</th>
              </tr>
            </thead>

            <tbody>
              @foreach($latestorders as $key=> $order)
             
                <tr>
                  <td>{{$key+1}}</td>
                  <td><a title="View order" href="{{ route('show.order',$order->orderid) }}">#{{ $inv_cus->order_prefix.$order->orderid }}</a></td>
                  <td>{{ $order->customername }}</td>
                  <td>{{ $order->qty }}</td>
                  <td><i class="{{ $order->paid_in }}"></i>{{ $order->ordertotal }}</td>
                  <td>{{ date('d-M-Y',strtotime($order->created_at)) }}</td>
                  
                </tr>
              @endforeach
            </tbody>
          </table>
          
        

        </div>

        <div align="center" class="box-footer">
          <a class="link-text" href="{{ url('admin/order') }}"><i class="fa fa-eye"></i> View All Orders</a>
        </div>
        
      </div>
           
          @endif

          @if($genrals_settings->vendor_enable == 1)
            @if($dashsetting->rct_str==1)
           
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h4 class="box-title"><span class="lnr lnr-store"></span> Recent Store Requests</h4>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>

                <div class="ltorderbox box-body">
                  <table class="table table-borderd table-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Store Name</th>
                        <th>Buisness Email</th>
                        <th>Request By</th>
                      </tr>
                    </thead>

                    <tbody>
                    
                      @foreach($storerequest as $key => $store)
                      <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{ $store->name }}</td>
                        <td>{{ $store->email }}</td>
                        <td>{{ $store->owner }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                 
                </div>

                <div align="center" class="box-footer">
                   <a class="link-text" href="{{ url('admin/appliedform') }}"><i class="fa fa-eye"></i> View All Store Request</a>
                </div>
              </div>
            
            @endif
          @endif

    
          <!-- Social States -->
          @if($dashsetting->fb_wid ==1 || $dashsetting->tw_wid==1 || $dashsetting->insta_wid==1)
          <div class="box box-solid">
          <div class="box-header with-border">
              
              <h3><span class="lnr lnr-chart-bars"></span> Social States</h3>

          </div>

          <div class="margin-top-30 sst container-fluid">
          @php
            $connected = @fsockopen("www.facebook.com", 80); 
          @endphp
          <div class="row"> 
            @if($dashsetting->fb_wid ==1)
            <div class="col-md-4 col-lg-4">
              <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-facebook"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Page Likes</span>
                  
                  @if($dashsetting->fb_page_id != '' || $dashsetting->fb_page_token != '')
                     
                      @if($connected)
                         @php

                         $fb_page = "'".$dashsetting->fb_page_id."'"; 
                         $access_token = "'".$dashsetting->fb_page_token."'";
                         $url = 'https://graph.facebook.com/v3.2/'.$fb_page.'?fields=fan_count&access_token='.$access_token;
                         $curl = curl_init($url);
                         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   
                         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                         $result = curl_exec($curl);

                         curl_close($curl);
                         if($result) { // if valid json object
                                $result = json_decode($result); // decode json object
                                if(isset($result->fan_count)) { // get likes from the json object
                                   
                                   echo '<span class="info-box-number">'.$result->fan_count.'</span>';
                                }
                         }
                         else{
                            echo 'Page is not a valid FB Page';
                        }
                        @endphp
                      
                      @else
                         <p><b>Connection Problem !</b></p>
                      @endif
                  @else
                     <p><b>Set up your facebook page key in Admin Dashboard Setting !</b></p>
                  @endif
                  
                </div>
                <!-- /.info-box-content -->
              </div>
            </div>
          @endif

          @if($dashsetting->tw_wid==1)
            <div class="col-md-4 col-lg-4">
              <div class="info-box">
            <span class="info-box-icon bg-tw"><i class="fa fa-twitter"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Followers</span>
              @if($dashsetting->tw_username != '')
                 
                @if($connected)
                <?php 
                  
                  $data = @file_get_contents('https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names='.$dashsetting->tw_username); 
                  $parsed =  json_decode($data,true);
                  try{
                    $tw_followers =  $parsed[0]['followers_count'];
                  
                    echo '<span class="info-box-number">'.$tw_followers.'</span>';
                  }catch(\Exception $e){
                    echo '<span class="info-box-number">'.$e->getCode().' Invalid Username !</span>';
                  }
                ?>
                @else
                  <p><b>Connection Problem !</b></p>
                @endif
              @else
                <p><b>Set up Twitter username in Admin Dashboard Setting !</b></p>
              @endif
              
            </div>
            <!-- /.info-box-content -->
          </div>
            </div>
          @endif

          @if($dashsetting->insta_wid==1)

            <div class="col-md-4 col-lg-4">
              <div class="info-box">
            <span class="info-box-icon bg-insta"><i class="fa fa-instagram"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Followers</span>
                
                @if($dashsetting->inst_username !='')

                @if($connected)
                  <?php
                    $raw = @file_get_contents("https://www.instagram.com/$dashsetting->inst_username"); //
                    preg_match('/\"edge_followed_by\"\:\s?\{\"count\"\:\s?([0-9]+)/',$raw,$m);
                    
                    
                    try{
                      echo '<span class="info-box-number">'.$m[1].'</span>';
                    }catch(\Exception $e){
                      echo '<span class="info-box-number">'.$e->getCode().' Invalid Username !</span>';
                    }
                    
                  ?>
                @else
                     <p><b>Connection Problem !</b></p>
                @endif
                @else
                  <p><b>Set up Instagram username in <br>Admin Dashboard Setting !</b></p>
                @endif
             
            </div>
            <!-- /.info-box-content -->
          </div>
            </div>

            @endif
          </div>
            

            
          </div>
        </div>
          @endif
          <!-- Social States End -->
          <hr>
          <div class="row">
             
          

            @if($dashsetting->rct_pro ==1)
            <div class="col-md-4">
                <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title"><span class="lnr lnr-pushpin"></span> Recently Added Products</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body height335 overflow-y-scroll">
              <ul class="products-list product-list-in-box">
                
        
                @foreach($products->sortByDesc('id')->take($dashsetting->max_item_pro) as $pro)
                @foreach($pro->subvariants as $key=> $sub)
                @if($sub->def == 1)
                @php 
                $var_name_count = count($sub['main_attr_id']);
                $name = array();
                $var_name;
                $newarr = array();
                for($i = 0; $i<$var_name_count; $i++){
                  $var_id =$sub['main_attr_id'][$i];
                  $var_name[$i] = $sub['main_attr_value'][$var_id];
        
                  $name[$i] = App\ProductAttributes::where('id',$var_id)->first();
        
                }
        
        
                try{
                  $url = url('/details/').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                }catch(Exception $e)
                {
                  $url = url('/details/').'/'.$pro->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                }
        
                @endphp
        
                <li class="item">
                  <div class="product-img">
                    @if(count($pro->subvariants)>0)
                    <center>
                      <img class="pro-img2" src="{{ url('/variantimages/thumbnails/'.$sub->variantimages['main_image']) }}" alt="{{ $sub->variantimages['main_image'] }}" title="{{ $pro->name }}">
                    </center>
                    @endif
                  </div>
                  <div class="product-info">
                    <a href="{{ url($url) }}" class="product-title">{{ $pro->name }}
                      <span class="label label-success pull-right">@if($pro->vender_offer_price !=null)
                        {{ $pro->price_in }} {{ $pro->vender_offer_price+$sub->price }}
                        @else
                        {{ $pro->price_in }} {{ $pro->vender_price+$sub->price }}
                      @endif</span></a>
                      <span class="product-description">
                       {{ substr(strip_tags($pro->des),0,50)}}{{strlen(strip_tags($pro->des))>50 ? "..." : "" }}
                     </span>
                   </div>
                 </li>
        
                 @endif
                 @endforeach
                 @endforeach
                 <!-- /.item -->
               </ul>
             </div>
             
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="{{ url('admin/products') }}" class="link-text uppercase"><i class="fa fa-eye"></i> View All Products</a>
            </div>
            <!-- /.box-footer -->
          </div>
            </div>
            @endif

            @if($dashsetting->rct_cust ==1)
            <div class="col-md-4">
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h4 class="box-title"><span class="lnr lnr-users"></span> Recent Users</h4>
                 <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                 </div>
                </div>

                  <div class="pbox box-body">
                      
                    
                    
                      <ul class="users-list clearfix">
                        @foreach($users = App\User::where('role_id','!=','a')->orderBy('id','DESC')->take($dashsetting->max_item_cust)->get() as $user)
                        <li>
                          @if($user->image !="" || $user->image != null)
                            <img src="{{ url('images/user/'.$user->image)  }}" alt="">
                          @else
                           <img src="{{ url('images/user.png')  }}" alt="">
                          @endif
                         
                          <a class="users-list-name" href="#">{{ $user->name }}</a>
                          <span class="users-list-date">{{ date('Y-m-d',strtotime($user->created_at)) }}</span>
                        </li>
                        @endforeach
                     
                      </ul> 
                    
                    
                    
                  </div>

                  <div align="center" class="box-footer">
                    <a class="link-text" href="{{ url('admin/users') }}"><i class="fa fa-user"></i> View All Users</a>
                  </div>
                
              </div>
            </div>
            @endif

            <div class="col-md-4">
              <div class="box box-solid">
                <div class="box-body">
                  {!! $piechart->container() !!}
                </div>
              </div>
            </div>
  

          </div>

         
              

              <div class="box box-solid">
                <div class="box-body">
                  {!! $userchart->container() !!}
                </div>
              </div>
            
           

<!-- footer content -->
          </div>
        </div>
      

@endsection
@section('custom-script')
<script src="{{ url('front/vendor/js/Chart.min.js') }}" charset="utf-8"></script>
<script src="{{ url('front/vendor/js/highcharts.js') }}" charset="utf-8"></script>
 {!! $userchart->script() !!}
 {!! $piechart->script() !!}
 {!! $orderchart->script() !!}
@endsection