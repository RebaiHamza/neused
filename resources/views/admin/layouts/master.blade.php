<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') {{ $title }} - Admin</title>
  
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
   @php
      $g = App\Genral::first();
   @endphp

  <link rel="icon" href="{{url('images/genral/'.$fevicon)}}" type="image/gif" sizes="16x16">
  <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" type="text/css" href="{{ url('css/vendor/animate.min.css') }}">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="{{url('admin/vendor/dist/css/adminboot3.css')}}">
  <!-- Bootstrap Tour -->
  <link rel="stylesheet" href="{{url('css/vendor/bootstrap-tour.min.css')}}">
  <!-- Colorpicker -->
  <link rel="stylesheet" href="{{url('admin/vendor/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}">
   <!-- Fontawesome -->
  <link rel="stylesheet" href="{{url('css/font-awesome.min.css')}}">
   <!-- Iconpicker -->
  <link rel="stylesheet" href="{{ url('css/vendor/fontawesome-iconpicker.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{url('admin/vendor/Ionicons/css/ionicons.min.css')}}">
  <link rel="stylesheet" href="{{ url('admin/vendor/icon-font/icon-font.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{url('admin/vendor/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
   <!-- Select Stylesheets -->
  <link rel="stylesheet" href="{{url('front/vendor/css/bootstrap-select.min.css')}}">
  <link rel="stylesheet" href="{{ url('css/vendor/select2.min.css') }}" rel="stylesheet"  />
   <!-- Datepicker and toggle -->
  <link rel="stylesheet" href="{{ url('css/vendor/datepicker.css') }}">
  <link rel="stylesheet" href="{{ url('css/vendor/toggle.css') }}">
<!-- Admin Theme style -->
<link rel="stylesheet" href="{{url('admin/vendor/dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{url('admin/vendor/dist/css/skins/_all-skins.min.css')}}">
 <!-- Pace Preloader -->
<link rel="stylesheet" href="{{ url('css/vendor/pace.min.css') }}">
<link rel="stylesheet" href="{{ url('css/vendor/loading-bar.css') }}">
<link rel="stylesheet" href="{{ url('css/vendor/jquery.ui.plupload.css') }}">
<!-- Additional Admin Style-->
<link rel="stylesheet" href="{{ url('css/vendor/alert.css') }}">
<link rel="stylesheet" href="{{ url('css/vendor/jquery-ui.css') }}" />
<link rel="stylesheet" href="{{ url('admin/css/style.css') }}">
<!-- TinyMCE Editor -->
<script src="{{ url('admin/plugins/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

@yield('stylesheet')
</head>
  <!-- jQuery 3.5.4 -->
  <script src="{{ url('js/jquery.js') }}"></script>
  <!-- Pnotify -->
  <script src="{{ url('front/vendor/js/PNotify.js') }}"></script>
  <script src="{{url('front/vendor/js/PNotifyAnimate.js')}}"></script>
  <script src="{{url('front/vendor/js/PNotifyCallbacks.js')}}"></script>
  <script src="{{url('front/vendor/js/PNotifyButtons.js')}}"></script>
  <script src="{{url('front/vendor/js/PNotifyNonBlock.js')}}"></script>
  <script src="{{url('front/vendor/js/PNotifyMobile.js')}}"></script>
  <script src="{{url('front/vendor/js/PNotifyHistory.js')}}"></script>
  <script src="{{url('front/vendor/js/PNotifyDesktop.js')}}"></script>
  <script src="{{url('front/vendor/js/PNotifyConfirm.js')}}"></script>
  <script src="{{url('front/vendor/js/PNotifyStyleMaterial.js')}}"></script>
  <script src="{{url('front/vendor/js/PNotifyReference.js')}}"></script>
  <!-- Sweetalert -->
  <script src="{{ url('front/vendor/js/sweetalert.min.js') }}"></script>

  <script>
    var addedmsg = "<?=Session::get('added')?>";
    var updatedmsg = "<?=Session::get('updated')?>";
    var deletedmsg = "<?=Session::get('deleted')?>";
    var warningmsg = "<?=Session::get('warning')?>";
  </script>
  <!-- Custom alert -->
  <script src="{{ url('js/alert.js') }}"></script>
  
<body class="hold-transition fixed skin-blue sidebar-mini pace-done">
  <div class="pace  pace-inactive transform-custom"><div class="pace-progress" data-progress-text="100%" data-progress="99">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div>
</div>
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/myadmin') }}" class="adminLogo logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">
        <img title="{{ config('app.name') }}" class="width-20" src="{{ url('images/genral/'.$genrals_settings->fevicon) }}" alt=""/>
      </span>
  
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"> <img title="{{ config('app.name') }}" class="width-20" src="{{ url('images/genral/'.$genrals_settings->fevicon) }}" alt=""/> {{$genrals_settings->project_name}}</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav id="mainNav" class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      
       

      <div class="navbar-custom-menu">
        


        <ul class="nav navbar-nav">
          
        <li id="stour">
            <a class="cursor-pointer" onclick="starttour()">
                <i class="fa fa-plane fa-1x" aria-hidden="true"></i> Setup Tour
            </a>
         </li>

          <li><a title="Visit site" href="{{ url('/') }}" target="_blank">Visit site <i class="fa fa-external-link" aria-hidden="true"></i></a></li>

          <li>
            <a title="Change language" href="#"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;</a>
          </li>
          <li>
            
           <select class="form-control langdropdown" onchange="changeLang()" name="" id="changed_lng">
                  @foreach(\DB::table('locales')->where('status','=',1)->get() as $lang)
                      <option {{ Session::get('changed_language') == $lang->lang_code ? "selected" : ""}} value="{{ $lang->lang_code }}">{{ $lang->name }}</option>
                  @endforeach
                </select>
          </li>
          

           <li class="dropdown notifications-menu">
            <a title="Tickets Notification" href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-ticket" aria-hidden="true"></i>
              @if(auth()->user()->unreadnotifications->where('n_type','=','ticket')->count())
              <span id="countNoti" class="label label-warning">
               
               {{ auth()->user()->unreadnotifications->where('n_type','=','ticket')->count() }}
              
             </span>
              @endif
            </a>
            <ul class="dropdown-menu">
              <li class="header">@if(auth()->user()->unreadnotifications->where('n_type','=','ticket')->count())
               {{ auth()->user()->unreadnotifications->where('n_type','=','ticket')->count() }} Ticket Request Recieved !
               @else
               <span class="text-center">No Notifications</span>
               @endif </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">

                   @if(auth()->user()->unreadnotifications->where('n_type','=','ticket')->count())
                  
                @foreach(auth()->user()->unreadNotifications->where('n_type','=','ticket') as $notification)
                   
                   <li>
                    
                   

                      <small class="padding-5 pull-right"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ date('jS M y',strtotime($notification->created_at)) }}</small>
                      
                      <a onclick="markread('{{ $notification->id }}')" href="{{ route('ticket.show',$notification->url) }}">
                        <b>{{ $notification->data['data'] }}</b>
                      </a>

                      

                   </li>
                  
                @endforeach

                 @endif
                 
                </ul>
              </li>
              @if(auth()->user()->unreadnotifications->count())
              <li class="footer"><a href="{{ route('mark_tkt_order') }}">Mark all as Read</a></li>
              @endif
            </ul>
          </li>
          
          <li class="dropdown notifications-menu">
            <a title="Order Notification" href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell"></i>
              @if(auth()->user()->unreadnotifications->where('n_type','=','order_v')->count())
              <span id="countNoti" class="label label-warning">
               
               {{ auth()->user()->unreadnotifications->where('n_type','=','order_v')->count() }}
              
             </span>
              @endif
            </a>
            <ul @if(auth()->user()->unreadnotifications->where('n_type','=','order_v')->count()>2) @endif class="scroll dropdown-menu">
              <li class="header">@if(auth()->user()->unreadnotifications->where('n_type','=','order_v')->count())
               You have {{ auth()->user()->unreadnotifications->where('n_type','=','order_v')->count() }} New Orders Notification!
               @else
               <span class="text-center">No Notifications</span>
               @endif </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">

                   @if(auth()->user()->unreadnotifications->where('n_type','=','order_v')->count())
                  
                @foreach(auth()->user()->unreadNotifications->where('n_type','=','order_v') as $notification)
                   
                   <li>
                    
                   
                     

                      <small class="padding-5 pull-right"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ date('jS M y',strtotime($notification->created_at)) }}</small>
                      
                      <a title="{{ $notification->data['data'] }}" onclick="markread('{{ $notification->id }}')" href="{{ url($notification->url) }}">
                        <i class="fa fa-shopping-basket" aria-hidden="true"></i> <b>#{{ $notification->data['data'] }}</b>
                      </a>

                     

                   </li>
                  
                @endforeach

                 @endif
                 
                </ul>
              </li>
              @if(auth()->user()->unreadnotifications->where('n_type','=','order_v')->count())
              <li class="footer"><a href="{{ route('mark_read_order') }}">Mark all as Read</a></li>
              @endif
            </ul>
          </li>

           <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a title="User Notifications" href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-users"></i>
              
              @if(auth()->user()->unreadnotifications->where('n_type','=','user')->count())
              <span class="label label-warning">
               
               {{ auth()->user()->unreadnotifications->where('n_type','=','user')->count() }}
              
             </span>
              @endif
             
            </a>

            <!-- FOR USER-->

            <ul class="dropdown-menu">
              <li class="header">

                @if(auth()->user()->unreadnotifications->where('n_type','=','user')->count())
               
               You have {{ auth()->user()->unreadnotifications->where('n_type','=','user')->count() }} Notifications
               @else
               <span class="text-center">No Notifications</span>
               @endif </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">

                   @if(auth()->user()->unreadnotifications->where('n_type','=','user')->count())
                  @foreach(auth()->user()->unreadNotifications->where('n_type','=','user') as $notification)
                 
                  <li>
                     <small class="padding-5 pull-right"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ date('jS M y',strtotime($notification->created_at)) }}</small>

                    <a onclick="markread('{{ $notification->id }}')" href="{{ url('admin/users/'.$notification->url.'/edit') }}">
                      <i class="fa fa-users text-aqua"></i> {{ $notification->data['data'] }}
                    
                    </a>
                  </li>
                  
                 @endforeach
                 @endif
                 
                </ul>
              </li>
              @if(auth()->user()->unreadnotifications->where('n_type','=','user')->count())
              <li class="footer"><a href="{{ route('mark_read_user') }}">Mark all as Read</a></li>
              @endif
            </ul>
          </li>

          @php
                  $userimage = @file_get_contents('images/user/'.Auth::user()->image);
          @endphp

          <!--END-->

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="@auth @if($userimage){{url('images/user/'.Auth::user()->image)}} @else {{ Avatar::create(Auth::user()->name)->toBase64() }} @endif" class="img-circle" alt="User Image" width="22px"> <span class="hidden-xs">Hi ! {{ Auth::user()->name }} @endauth</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                
                @if($userimage)
                <img src="{{url('images/user/'.Auth::user()->image)}}" class="img-circle" alt="User Image">
                @else
                <img title="{{ Auth::user()->name }}" src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" />
                @endif
                <p>
                  @auth {{ Auth::user()->name }} @endauth
                  <small>Member Since: {{ date('M jS Y',strtotime(Auth::user()->created_at)) }}</small>
                </p>
              </li>
              
              <!-- Menu Footer-->
              <li class="user-footer">

                <div class="row">

                  <div class="col-md-4">
                    <a title="Edit Your Profile" href="{{ url('admin/users/'.Auth::user()->id.'/edit') }}" class="btn btn-default btn-flat">Edit Profile</a>
                  </div>
                  @if(isset(Auth::user()->store) && Auth::user()->store->status == 1)
                    <div class="col-md-4">
                      <a title="Edit Your Store" href="{{ url('admin/stores/'.Auth::user()->store->id.'/edit') }}" class="btn btn-default btn-flat">Your Store</a>
                    </div>
                  @endif
                  <div class="col-md-4">

                     <a class="btn btn-default btn-flat" role="button" onclick="adminlogout()">
                      {{ __('Sign out') }}
                     </a>

                    <form action="{{ route('logout') }}" method="POST" class="adminlogout display-none">
                        {{ csrf_field() }}
                    </form>
                  </div>

                </div>
               

                
              </li>
            </ul>
          </li>
          <li>
            <a title="Logout?" class="pointer dropdown-item" onclick="adminlogout()">
              <i class="fa fa-power-off" aria-hidden="true"></i>
            </a>
            <form action="{{ route('logout') }}" method="POST" class="adminlogout display-none">
              {{ csrf_field() }}
            </form>
                                    
          </li>
        </ul>
      </div>
    </nav>
  </header>
  
  <div id="sidbarmenu">
    <!-- Sidebar Menu -->
      @include('admin.layouts.sidebar')
    <!-- END -->
  </div>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
   

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          
           <div class="row tile_count">
              @if($errors->any())
            <div class="alert alert-danger">
              <ul>
                  
                 @foreach($errors->all() as $error)

                  <li><h5>{{$error}}</h5></li>

                 @endforeach
                </ul>
            </div>
            

                @endif

           
         @if (Session::has('added'))
              <script>
                success();
              </script>
            @elseif (Session::has('updated'))
              <script>
                update();
              </script>
            @elseif (Session::has('deleted'))
              <script>
                deleted();
              </script>

              @elseif (Session::has('warning'))
              <script>
                warning();
              </script>
        @endif
        
       
         @yield('body')


          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer id="endfooter" class="main-footer">
    <div class="pull-right hidden-xs">
      
    </div>
    <strong>&copy; {{ date('Y') }} | {{ config('app.name') }} | {{$Copyright}}</strong>
    <span class="pull-right"><b>v {{ config('app.version') }}</b></span>
  </footer>

 
  
  <div class="control-sidebar-bg">
    
  </div>
</div>
<!-- Bootstrap js -->
<script src="{{url('admin/vendor/dist/js/adminboot3.js')}}"></script>
<!-- Bootstrap Tour-->
<script src="{{ url('admin/vendor/dist/js/bootstrap-tour.min.js') }}"></script>
<!-- jquery UI js -->
<script src="{{ url('admin/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Iconpicker js -->
<script src="{{ url('front/vendor/js/fontawesome-iconpicker.js') }}"></script>
<!--select 2 js -->
<script src="{{ url('front/vendor/js/select2.min.js') }}"></script>
<!--colorpicker -->
<script src="{{ url('admin/vendor/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<!--jquery plUpload js -->
<script src="{{ url('front/vendor/js/plupload.full.min.js') }}"></script>

<script src="{{ url('front/vendor/js/jquery.ui.plupload.js') }}"></script>
<!-- Moment js -->
<script src="{{ url('front/vendor/js/moment-with-locales.js') }}"></script>
<!--Datepicker js -->
<script src="{{ url('front/vendor/js/datepicker.js') }}"></script>
<!-- DataTables -->
<script src="{{asset('front/vendor/js/datatables.min.js')}}" ></script>

<!-- SlimScroll -->
<script src="{{url('admin/vendor/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{url('admin/vendor/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE Theme JS -->
<script src="{{url('admin/vendor/dist/js/adminlte.min.js')}}"></script>
<!-- Pace js -->
<script src="{{ url('admin/plugins/pace/pace.min.js') }}"></script>
<!--Default admin js -->
<script>var baseUrl = "<?= url('/') ?>";</script>
<script src="{{ url('js/admin.js') }}"></script>
<script>var appname  = {!! json_encode(config('app.name')) !!}</script>
<script src="{{ url('js/adminmaster.js') }}"></script>
@yield('custom-script')
</body>
</html>
