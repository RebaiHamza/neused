@extends("front/layout.master")
          @php
              $sellerac = App\Store::where('user_id','=', $user->id)->first();
          @endphp
@section('title',__('staticwords.MyOrders').' | ')
@section("body")

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-3">
            <div class="bg-white">
              <div class="user_header"><h5 class="user_m">• {{ __('staticwords.Hi!') }} {{$user->name}}</h5></div>
              <div align="center">
                 @if($user->image !="")
                <img src="{{url('images/user/'.$user->image)}}" class="user-photo"/>
                @else
                <img src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" class="user-photo"/>
                @endif
                  <h5>{{ $user->email }}</h5>
                <p>{{ __('staticwords.MemberSince') }}: {{ date('M jS Y',strtotime($user->created_at)) }}</p>
              </div>
                <br>
            </div>

<!-- ===================== full-screen navigation start======================= -->

      <div class="bg-white navigation-small-block">
          <div class="user_header">
            <h5 class="user_m">• {{ __('staticwords.UserNavigation') }}</h5>
          </div>
      <p></p>
       <div class="nav flex-column nav-pills" aria-orientation="vertical">
          <a class="nav-link padding15 {{ Nav::isRoute('user.profile') }}" href="{{ url('/profile') }}"> <i class="fa fa-user-circle" aria-hidden="true"></i> {{ __('staticwords.MyAccount') }}</a>
          
          <a class="nav-link padding15 {{ Nav::isRoute('user.order') }}" href="{{ url('/order') }}"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> {{ __('staticwords.MyOrders') }}</a>
          
          @if($wallet_system == 1)
            <a class="nav-link padding15 {{ Nav::isRoute('user.wallet.show') }}" href="{{ route('user.wallet.show') }}"><i class="fa fa-credit-card" aria-hidden="true"></i>
            {{ __('staticwords.MyWallet') }}
            </a>
          @endif

          <a class="nav-link padding15 {{ Nav::isRoute('failed.txn') }}" href="{{ route('failed.txn') }}"> <i class="fa fa-spinner" aria-hidden="true"></i> {{ __('staticwords.MyFailedTrancations') }}</a>

          <a class="nav-link padding15 {{ Nav::isRoute('user_t') }}"  href="{{ route('user_t') }}">&nbsp;<i class="fa fa-ticket" aria-hidden="true"></i> {{ __('staticwords.MyTickets') }}</a>

          <a class="nav-link padding15 {{ Nav::isRoute('get.address') }}" href="{{ route('get.address') }}"><i class="fa fa-list-alt" aria-hidden="true"></i> {{ __('staticwords.ManageAddress') }}</a>
      
          <a class="nav-link padding15 {{ Nav::isRoute('mybanklist') }}" href="{{ route('mybanklist') }}"> <i class="fa fa-cube" aria-hidden="true"></i> {{ __('staticwords.MyBankAccounts') }}</a>
      
          
         @php
            $genral = App\Genral::first();
          @endphp
          @if($genral->vendor_enable==1)
          @if(empty($sellerac) && Auth::user()->role_id != "a")
         
          <a class="nav-link padding15 {{ Nav::isRoute('applyforseller') }}" href="{{ route('applyforseller') }}"><i class="fa fa-address-card-o" aria-hidden="true"></i> {{ __('staticwords.ApplyforSellerAccount') }}</a>
          
          @elseif(Auth::user()->role_id != "a")
           <a class="nav-link padding15 {{ Nav::isRoute('seller.dboard') }}" href="{{ route('seller.dboard') }}"><i class="fa fa-address-card-o" aria-hidden="true"></i> {{ __('staticwords.SellerDashboard') }}</a>
          
          @endif
          @endif
          
          
            <a class="nav-link padding15" data-toggle="modal" href="#myModal"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('staticwords.ChangePassword') }}</a>
          

              <a class="nav-link padding15" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-power-off" aria-hidden="true"></i>  {{ __('Sign out?') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="display-none">
                                        @csrf
                                    </form>
<br>
        </div>
        </div>
            
        

 <!-- ===================== full-screen navigation end ======================= -->      
        
  <!-- =========================small screen navigation start ============================ -->
<div class="order-accordion navigation-full-screen">
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h5 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            <div class="user_header">
              <h5 class="user_m">• {{ __('staticwords.UserNavigation') }}</h5>
            </div>
          </a>
        </h5>
      </div>
      <div id="collapseOne" class="panel-collapse collapseOne collapse" role="tabpanel" aria-labelledby="headingOne">
          <div class="panel-body">
            <ul class="mnu_user nav-pills nav nav-stacked">
          <li class="{{ Nav::isRoute('user.profile') }}">
 
            <a  href="{{ url('/profile') }}"><i class="fa fa-user-circle" aria-hidden="true"></i> {{ __('staticwords.MyAccount') }}</a></li>
          
          <li class="{{ Nav::isRoute('user.order') }}"><a href="{{ url('/order') }}"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>
 {{ __('staticwords.MyOrders') }}</a></li>

  <li class="{{ Nav::isRoute('failed.txn') }}"><a href="{{ route('failed.txn') }}"><i class="fa fa-spinner" aria-hidden="true"></i> {{ __('staticwords.MyFailedTrancations') }}</a></li>

 <li class="{{ Nav::isRoute('user_t') }}"><a href="{{ route('user_t') }}"><i class="fa fa-envelope-square" aria-hidden="true"></i>

 {{ __('staticwords.MyTickets') }}</a></li>

 <li class="{{ Nav::isRoute('get.address') }}"> <a href="{{ route('get.address') }}"><i class="fa fa-list-alt" aria-hidden="true"></i>

        {{ __('staticwords.ManageAddress') }}</a>
      </li>

       <li class="{{ Nav::isRoute('mybanklist') }}"> <a href="{{ route('mybanklist') }}"><i class="fa fa-cube" aria-hidden="true"></i>

        {{ __('staticwords.MyBankAccounts') }}</a>
      </li>
          
         @php
            $genral = App\Genral::first();
          @endphp
          @if($genral->vendor_enable==1)
          @if(empty($sellerac) && Auth::user()->role_id != "a")
         
          <li><a href="{{ route('applyforseller') }}"><i class="fa fa-address-card-o" aria-hidden="true"></i>
            {{ __('staticwords.ApplyforSellerAccount') }}</a>
          </li>
          @elseif(Auth::user()->role_id != "a")
           <li><a href="{{ route('seller.dboard') }}"><i class="fa fa-address-card-o" aria-hidden="true"></i>
           {{ __('staticwords.SellerDashboard') }}</a>
          </li>
          @endif
          @endif
          
          <li>
            <a data-toggle="modal" href="#myModal"><i class="fa fa-eye" aria-hidden="true"></i>
 {{ __('staticwords.ChangePassword') }}</a>
          </li>
          
          <li>

<a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-power-off" aria-hidden="true"></i>  {{ __('Sign out?') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="display-none">
                                        @csrf
                                    </form>
</li>
<br>
        </ul>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- =========================small screen navigation end ============================ -->      
</div>

       
        
        

       
         
         <div class="col-lg-9 my-order-one main-content">

            <div class="bg-white2">

                <h5 class="user_m2">{{ __('staticwords.MyOrders') }} ({{ count($orders) }})</h5>
                <hr>
              @if(count($orders)>0)
              @foreach($orders as $order)
                @php
                  if($order->discount != 0){
                    if($order->distype == 'category'){

                      $findCoupon = App\Coupan::where('code','=',$order->coupon)->first();
                      $catarray = collect();
                      foreach ($order->invoices as $key => $os) {
                        
                        if($os->variant->products->category_id == $findCoupon->cat_id){
                            
                            $catarray->push($os);
                                       
                        }

                      }

                    }
                  }
                @endphp
                <div class="panel panel-default">

                  <div class="panel-heading">
                    <a href="{{  route('user.view.order',$order->order_id) }}" title="Order {{ $ord_postfix }}{{ $order->order_id }}" href="#" class="btn btn-primary">
                      {{ $ord_postfix }}{{ $order->order_id }}
                    </a>

                    <span class="pull-right">
                      <b>{{ __('Transcation ID') }}:</b> {{ $order->transaction_id }}
                      <br>
                      <b>{{ __('Payment Method') }}:</b> {{ $order->payment_method }}
                    </span>
                  </div>

                  <div class="panel-body">
                        @php
                          $x = count($order->invoices);
                          if(isset($order->invoices[0])){
                              $firstarray = array($order->invoices[0]);
                          }
                          
                          $morearray = array();
                          $counter = 0;

                          foreach ($order->invoices as $value) {
                            if($counter++ >0 ){
                               array_push($morearray, $value);
                            }
                          }

                          $morecount = count($morearray);
                        @endphp

                        @foreach($firstarray as $o)

                          @php
                                $orivar = App\AddSubVariant::withTrashed()->withTrashed()->findorfail($o->variant_id);

                                $varcount = count($orivar->main_attr_value);
                                $i=0;
                                $var_name_count = count($orivar['main_attr_id']);
                                unset($name);
                                $name = array();
                                $var_name;

                                  $newarr = array();
                                  for($i = 0; $i<$var_name_count; $i++){
                                    $var_id =$orivar['main_attr_id'][$i];
                                    $var_name[$i] = $orivar['main_attr_value'][$var_id];
                                     
                                      $name[$i] = App\ProductAttributes::where('id',$var_id)->first();
                                      
                                  }


                                try{
                                  $url = url('details').'/'.$orivar->products->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                                }catch(Exception $e)
                                {
                                  $url = url('details').'/'.$orivar->products->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                                }

                            @endphp
                          
                          <div class="row rowbox no-pad">
                            <div class="col-lg-1 col-md-2 col-sm-3 col-4 ">
                                <center><img class="pro-img2" src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}" alt="product name"/></center>
                            </div>

                            <div class="col-lg-4 col-md-3 col-sm-3 col-7 click-view-one">
                              <a target="_blank" title="Click to view" href="{{ url($url) }}"><b>{{substr($orivar->products->name, 0, 30)}}{{strlen($orivar->products->name)>30 ? '...' : ""}}</b>

                                  <small>
                                  (@foreach($orivar->main_attr_value as $key=> $orivars)
                                            <?php $i++; ?>

                                                @php
                                                  $getattrname = App\ProductAttributes::where('id',$key)->first()->attr_name;
                                                  $getvarvalue = App\ProductValues::where('id',$orivars)->first();
                                                @endphp

                                                @if($i < $varcount)
                                                  @if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null)
                                                    @if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour")
                                                    {{ $getvarvalue->values }},
                                                    @else
                                                    {{ $getvarvalue->values }}{{ $getvarvalue->unit_value }},
                                                    @endif
                                                  @else
                                                    {{ $getvarvalue->values }},
                                                  @endif
                                                @else
                                                  @if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null)
                                                  @if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour")
                                                  {{ $getvarvalue->values }}
                                                  @else
                                                    {{ $getvarvalue->values }}{{ $getvarvalue->unit_value }}
                                                    @endif
                                                  @else
                                                    {{ $getvarvalue->values }}
                                                  @endif
                                                @endif
                                            @endforeach
                                            )

                                    </small>
                              </a>
                              <br>
                              <small><b>{{ __('staticwords.SoldBy') }}:</b> {{$orivar->products->store->name}}</small>
                              <br>
                              <small><b>{{ __('Qty') }}:</b> {{$o->qty}}</small>
                              <br>
                              @if($o->status == 'delivered')
                              <span class="badge badge-pill font-weight-normal badge-success">{{ ucfirst($o->status) }}</span>
                            @elseif($o->status == 'processed')
                              <span class="badge badge-pill font-weight-normal badge-info">{{ ucfirst($o->status) }}</span>
                            @elseif($o->status == 'shipped')
                              <span class="badge badge-pill font-weight-normal badge-primary">{{ ucfirst($o->status) }}</span>
                            @elseif($o->status == 'return_request')
                              <span class="badge badge-pill font-weight-normal badge-warning">
                                {{ __('Return Request') }}
                              </span>
                            @elseif($o->status == 'returned')
                              <span class="badge badge-pill font-weight-normal badge-danger">
                                {{ __('Returned') }}
                              </span>
                            @elseif($o->status == 'refunded')
                              <span class="badge badge-pill font-weight-normal badge-success">
                                {{ __('Refunded') }}
                              </span>
                            @elseif($o->status == 'cancel_request')
                              <span class="badge badge-pill font-weight-normal badge-warning">
                                {{ __('Cancelation Request') }}
                              </span>
                            @elseif($o->status == 'canceled')
                              <span class="badge badge-pill font-weight-normal badge-danger">
                                {{ __('Canceled') }}
                              </span>
                            @elseif($o->status == 'Refund Pending')
                             <span class="badge badge-pill font-weight-normal badge-success">
                               {{ __('Refund in progress') }}
                             </span>
                            @elseif($o->status == 'ret_ref')
                              <span class="badge badge-pill font-weight-normal badge-primary">
                                {{ __('Returned & Refunded') }}
                              </span>
                            @else
                              <span class="badge badge-pill font-weight-normal badge-secondary">{{ ucfirst($o->status) }}</span>
                            @endif
                            </div>

                            <div class="m-8 col-md-4 offset-sm-2 col-sm-3 offset-2 col-6 m-8-no applied-block">
                              <b>

                                <i class="{{ $order->paid_in }}"></i>
                                 @if($o->order->discount !=0)
                                  
                                  @if($o->order->distype == 'product')

                                    
                                        @if($o->discount != 0)
                                          {{ round(($o->qty*$o->price+$o->tax_amount+$o->shipping)-$o->order->discount,2) }}
                                          <small class="couponbox"><b>{{ $order->coupon }}</b> {{ __('applied') }}</small>
                                        @else
                                          
                                          {{ round($o->qty*$o->price+$o->tax_amount+$o->shipping,2) }}

                                        @endif

                                  

                                  @elseif($o->order->distype == 'category')
                                      
                                      @if($o->discount != 0)
                                         {{ round(($o->qty*$o->price+$o->tax_amount+$o->shipping)-$o->discount,2) }}
                                          <br>
                                           <small class="couponbox"><b>{{ $order->coupon }}</b> {{ __('applied') }}</small>
                                      @else
                                          {{ round($o->qty*$o->price+$o->tax_amount+$o->shipping,2) }}
                                      @endif

                                  @elseif($o->order->distype == 'cart')

                                  {{ round(($o->qty*$o->price+$o->tax_amount+$o->shipping)-$o->discount,2) }}
                                    <small class="couponbox"><b>{{ $order->coupon }}</b> {{ __('applied') }}</small>
                                  @endif

                                @else
                                    {{ round($o->qty*$o->price+$o->tax_amount+$o->shipping,2) }}
                                @endif 
                           
                              </b>
                              <br>
                              <small>({{ __('Incl. of Tax & Shipping') }})</small>
                              <br>
                               
                            </div>

                            
                          </div>


                          @endforeach
                    
                    @if($order->invoices->count()>1)
                    <div align="center">
                      <a class="cursor-pointer" title="{{ __('View') }} {{ $morecount }} {{ __('more order') }}" id="moretext{{ $firstarray[0]->order->order_id }}" onclick="showMore('{{ $firstarray[0]->order->order_id }}')">+{{ $morecount }} {{ __('More') }}...</a>
                      
                    </div>

                     

                        <div class="display-none" id="expandThis{{ $firstarray[0]->order->order_id }}">
                          @foreach($morearray as $o)

                          @php
                              $orivar = App\AddSubVariant::withTrashed()->findorfail($o->variant_id);

                              $varcount = count($orivar->main_attr_value);
                              $i=0;
                              $var_name_count = count($orivar['main_attr_id']);
                              unset($name);
                              $name = array();
                              $var_name;

                                $newarr = array();
                                for($i = 0; $i<$var_name_count; $i++){
                                  $var_id =$orivar['main_attr_id'][$i];
                                  $var_name[$i] = $orivar['main_attr_value'][$var_id];
                                   
                                    $name[$i] = App\ProductAttributes::where('id',$var_id)->first();
                                    
                                }


                              try{
                                $url = url('details').'/'.$orivar->products->id.'?'.$name[0]['attr_name'].'='.$var_name[0].'&'.$name[1]['attr_name'].'='.$var_name[1];
                              }catch(Exception $e)
                              {
                                $url = url('details').'/'.$orivar->products->id.'?'.$name[0]['attr_name'].'='.$var_name[0];
                              }

                          @endphp
                          <br>
                             <div class="rowbox row no-pad">
                               <div class="col-lg-1 col-md-2 col-sm-3 col-4">
                                 <center><img class="pro-img2" src="{{url('variantimages/thumbnails/'.$orivar->variantimages['main_image'])}}" alt=""/></center>
                               </div>

                               <div class="col-lg-4 col-md-3 col-sm-3 col-7 click-view-one">
                                 <a target="_blank" title="Click to view" href="{{ url($url) }}"><b>{{substr($orivar->products->name, 0, 20)}}{{strlen($orivar->products->name)>20 ? '...' : ""}}</b>

                                  <small>
                                  (@foreach($orivar->main_attr_value as $key=> $orivars)
                                            <?php $i++; ?>

                                                @php
                                                  $getattrname = App\ProductAttributes::where('id',$key)->first()->attr_name;
                                                  $getvarvalue = App\ProductValues::where('id',$orivars)->first();
                                                @endphp

                                                @if($i < $varcount)
                                                  @if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null)
                                                    @if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour")
                                                    {{ $getvarvalue->values }},
                                                    @else
                                                    {{ $getvarvalue->values }}{{ $getvarvalue->unit_value }},
                                                    @endif
                                                  @else
                                                    {{ $getvarvalue->values }},
                                                  @endif
                                                @else
                                                  @if(strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null)
                                                    @if($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour")
                                                  {{ $getvarvalue->values }}
                                                  @else
                                                    {{ $getvarvalue->values }}{{ $getvarvalue->unit_value }}
                                                    @endif
                                                  @else
                                                    {{ $getvarvalue->values }}
                                                  @endif
                                                @endif
                                            @endforeach
                                            )

                                    </small>
                                  </a>
                                  <br>
                                  <small><b>{{ __('staticwords.SoldBy') }}:</b> {{$orivar->products->store->name}}</small>
                                  <br>
                                  <small><b>{{ __('Qty') }}:</b> {{$o->qty}}</small>
                                  <br>
                                  @if($o->status == 'delivered')
                                    <span class="badge badge-pill font-weight-normal badge-success">{{ ucfirst($o->status) }}</span>
                                  @elseif($o->status == 'processed')
                                    <span class="badge badge-pill font-weight-normal badge-info">{{ ucfirst($o->status) }}</span>
                                  @elseif($o->status == 'shipped')
                                    <span class="badge badge-pill font-weight-normal badge-primary">{{ ucfirst($o->status) }}</span>
                                  @elseif($o->status == 'return_request')
                                    <span class="badge badge-pill font-weight-normal badge-warning">Return Request</span>
                                  @elseif($o->status == 'returned')
                                    <span class="badge badge-pill font-weight-normal badge-danger">Returned</span>
                                  @elseif($o->status == 'refunded')
                                    <span class="badge badge-pill font-weight-normal badge-success">Refunded</span>
                                  @elseif($o->status == 'cancel_request')
                                    <span class="badge badge-pill font-weight-normal badge-warning">Cancelation Request</span>
                                  @elseif($o->status == 'canceled')
                                    <span class="badge badge-pill font-weight-normal badge-danger">Canceled</span>
                                  @elseif($o->status == 'Refund Pending')
                                   <span class="badge badge-pill font-weight-normal badge-success">Refund in progress</span>
                                  @elseif($o->status == 'ret_ref')
                                    <span class="badge badge-pill font-weight-normal badge-primary">Returned & Refunded</span>
                                  @else
                                    <span class="badge badge-pill font-weight-normal badge-secondary">{{ ucfirst($o->status) }}</span>
                                  @endif
                               </div>

                               <div class="m-8 col-md-4 offset-sm-2 col-sm-3 offset-2 col-6 m-8-no applied-block">
                                 <b><i class="{{ $order->paid_in }}"></i>
                                 @if($o->order->discount !=0)
                                  
                                  @if($o->order->distype == 'product')

                                        @if($o->discount != 0)

                                          {{ round(($o->qty*$o->price+$o->tax_amount+$o->shipping)-$o->discount,2) }}
                                          <small class="couponbox"><b>{{ $order->coupon }}</b> {{ __('applied') }}</small>

                                        @else
                                          
                                          {{ round($o->qty*$o->price+$o->tax_amount+$o->shipping,2) }}

                                        @endif

                                   

                                  @elseif($o->order->distype == 'category')
                                      
                                      @if($o->discount != 0)
                                          
                                          {{ round(($o->qty*$o->price+$o->tax_amount+$o->shipping)-$o->discount,2) }}
                                          <br>
                                           <small class="couponbox"><b>{{ $order->coupon }}</b> {{ __('applied') }}</small>
                                      @else
                                          {{ round($o->qty*$o->price+$o->tax_amount+$o->shipping,2) }}
                                      @endif
                                      
                                  @elseif($o->order->distype == 'cart')

                                    {{ round(($o->qty*$o->price+$o->tax_amount+$o->shipping)-$o->discount,2) }}
                                    <small class="couponbox"><b>{{ $order->coupon }}</b> {{ __('applied') }}</small>
                                  @endif

                                @else
                                    {{ round($o->qty*$o->price+$o->tax_amount+$o->shipping,2) }}
                                @endif  </b>
                                 <br>
                                  <small>({{ __('Incl. of Tax & Shipping') }})</small>
                                  
                               </div>

                             </div>
                          @endforeach
                        </div>
                          
                       <div align="center">
                      <a class="display-none font-weight500" title="{{ __('Show Less') }}" onclick="showLess('{{ $firstarray[0]->order->order_id }}')" id="showless{{ $firstarray[0]->order->order_id }}">
                        {{ __('Show Less') }}
                      </a>
                      <p></p>
                    </div>
                     
                      @endif
                     
                     </div>

                  <div class="panel-footer">
                    <b>{{ __('Order date') }}:</b> {{ date('d-m-Y',strtotime($order->created_at)) }}
                    <span class="pull-right">
                      <b>{{ __('Total') }} :</b> 
                      <i class="{{ $order->paid_in }}"></i>
                    
                        {{ number_format((float)$order->order_total , 2, '.', '')}}
                      | 
                      <b>{{ __('Handing Charges') }}:</b> 
                      <i class="{{ $order->paid_in }}"></i>{{ $order->handlingcharge }} | 
                      <b>{{ __('Order Total') }}:</b>
                     
                      <i class="{{ $order->paid_in }}"></i> {{ number_format((float)$order->order_total+$order->handlingcharge, 2, '.', '')}}
                     
                    </span>
                    
                    
                  </div>

                </div>

              @endforeach

              <div class="mx-auto width200px">
                {{$orders->links()}}
              </div>

              @else
                  <h3>{{ __('staticwords.ShoppingText') }}</h3>
                  <div align="center">
                    <img title="{{ __('staticwords.ShoppingText') }}" src="{{ url('images/noorder.jpg') }}" alt="no-order.jpg" width="70%">
                  </div>
              @endif
          
            </div>
        
        </div>


    </div>
    
</div>

<!-- Change password Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ __('staticwords.ChangePassword') }} ?</h5>
      </div>
      <div class="modal-body">
        <form id="form1" action="{{ route('pass.update',$user->id) }}" method="POST">
          {{ csrf_field() }}

          <div class="form-group eyeCy">
           
              
          <label class="font-weight-bold" for="confirm">Old Password:</label>
          <input required="" type="password" class="form-control @error('old_password') is-invalid @enderror" placeholder="Enter old password" name="old_password" id="old_password" />
          
          <span toggle="#old_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

          @error('old_password')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>



          <div class="form-group eyeCy">
         

            
               <label class="font-weight-bold" for="password">{{ __('staticwords.EnterPassword') }}:</label>
                <input minlength="8" required="" id="password" min="6" max="255" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" name="password" />
              
               <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            
             @error('password')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         
          
          </div>

          
          
          <div class="form-group eyeCy">
           
              
                <label class="font-weight-bold" for="confirm">{{ __('staticwords.ConfirmPassword') }}:</label>
          <input minlength="8" required="" id="confirm_password" type="password" class="form-control" placeholder="Re-enter password for confirmation" name="password_confirmation"/>
          
          <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

           <p id="message"></p>
          </div>
          

          <button @if(env('DEMO_LOCK') == 0) type="submit" @else disabled="disabled" title="{{ __('This action is disabled in demo !') }}" @endif id="test" class="btn btn-md btn-success"><i class="fa fa-save"></i> {{ __('staticwords.SaveChanges') }}</button>
          <button id="btn_reset" data-dismiss="modal" class="btn btn-danger btn-md" type="reset">X {{ __('staticwords.Cancel') }}</button>
        </form>
        
      </div>
      
    </div>
  </div>
</div>
   
@endsection
@section('script')

  <script src="{{ url('js/userorder.js') }}"></script>

@endsection