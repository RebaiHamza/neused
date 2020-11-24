  <!-- Left side column. contains the logo and sidebar -->
  <aside id="mainSidebar" class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          @if($userimage)
          <img src="{{url('images/user/'.$Uimage->image)}}" class="img-circle" alt="User Image">
          @else
         <img title="{{ Auth::user()->name }}" src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" />
          @endif
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

         <li id="dashboard" class="{{ Nav::isRoute('admin.main') }}">
            <a class="treeview" href="{{ route('admin.main') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            
          </a>
          </li>

         
            
         
              <li class="{{ Nav::isResource('users') }}"><a href="{{url('admin/users')}} "><i class="fa fa-users" aria-hidden="true"></i><span>All Customers</span></a></li>
             
             
            
       

         <li id="menum" class="{{ Nav::isResource('admin/menu') }}">
            <a class="treeview" href="{{ route('menu.index') }}">
           <i class="fa fa-window-restore" aria-hidden="true"></i> <span>Menu Management</span>
            
          </a>
          </li>

         <li class="treeview {{ Nav::isRoute('get.store.request') }} {{ Nav::isResource('stores') }}">
            <a href="#">
                <i class="fa fa-cart-plus" aria-hidden="true"></i> <span>Stores</span>
                <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>

              <ul class="treeview-menu">
                  
                   <li class="{{ Nav::isResource('stores') }}"><a href="{{url('admin/stores')}} "><i class="fa fa-circle-o"></i>Stores </a></li>
                    @php
                    $genral = App\Genral::first();
                  @endphp
                  @if($genral->vendor_enable==1)
                  <li class="{{ Nav::isRoute('get.store.request') }}"><a href="{{route('get.store.request')}} "><i class="fa fa-circle-o"></i>Stores Request</a></li>
                  @endif
              </ul>
         </li>

         <li id="prom" class="treeview {{ Nav::isResource('used-products') }} {{ Nav::isResource('ticket-products') }} {{ Nav::isResource('admin/return_policy') }} {{ Nav::isResource('brand') }} {{ Nav::isResource('coupan') }} {{ Nav::isResource('category') }} {{ Nav::isResource('subcategory') }} {{ Nav::isResource('grandcategory') }} {{ Nav::isResource('products') }} {{ Nav::isResource('unit') }} {{ Nav::isResource('special') }} {{ Nav::isRoute('attr.index') }} {{ Nav::isRoute('attr.add') }} {{ Nav::isRoute('opt.edit') }} {{ Nav::isRoute('pro.val') }} {{ Nav::isRoute('add.var') }} {{ Nav::isRoute('manage.stock') }} {{ Nav::isRoute('edit.var') }} {{ Nav::isRoute('pro.vars.all') }} {{ Nav::isRoute('import.page') }} {{ Nav::isRoute('requestedbrands.admin') }}">
          <a href="#">
           <i class="fa fa-shopping-basket" aria-hidden="true"></i> <span>Products Management</span>
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Nav::isResource('brand') }}"><a href="{{url('admin/brand')}} "><i class="fa fa-circle-o"></i>Brands</a></li>
            @if($genrals_settings->vendor_enable == 1)
            <li class="{{ Nav::isRoute('requestedbrands.admin') }}"><a href="{{route('requestedbrands.admin')}} "><i class="fa fa-circle-o"></i>Requested Brands 

              @php
                $brands = App\Brand::where('is_requested','=','1')->where('status','0')->orderBy('id','DESC')->count();
              @endphp
              
              @if($brands !=0)
                <span class="pull-right-container">
                  <small class="label pull-right bg-red">{{ $brands }}</small>
                </span>
              @endif

            </a></li>
            @endif
            <li class="treeview {{ Nav::isResource('category') }} {{ Nav::isResource('subcategory') }} {{ Nav::isResource('grandcategory') }}">
              <a href="#"><i class="fa fa-circle-o"></i>Categories
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{ Nav::isRoute('category.index') }} {{ Nav::isRoute('category.create') }} {{ Nav::isRoute('category.edit') }}" ><a href="{{url('admin/category')}}"><i class="fa fa-circle-o"></i>Categories</a></li>
                <li class="{{ Nav::isResource('subcategory') }}"><a href="{{url('admin/subcategory')}}"><i class="fa fa-circle-o"></i>Subcategories</a></li>
                <li class="{{ Nav::isResource('grandcategory') }}"><a href="{{url('admin/grandcategory')}}"><i class="fa fa-circle-o"></i>Childcategories</a></li>
              </ul>
            </li>

            <li class="treeview {{ Nav::isResource('products') }} {{ Nav::isRoute('requested.products') }}">
              <a href="#"><i class="fa fa-circle-o"></i>New Products
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{ Nav::isRoute('pro.vars.all') }} @if(Str::startsWith(Route::current()->getName(),'products.')) active @endif {{ Nav::isRoute('add.var') }} {{ Nav::isRoute('manage.stock') }} {{ Nav::isRoute('edit.var') }}"><a href="{{url('admin/products')}} "> <i class="fa fa-circle-o"></i>New Products</a></li>
                
                <li class="{{ Nav::isRoute('requested.products') }}"><a href="{{url('admin/requested/products')}}"><i class="fa fa-circle-o"></i>Requested Products</a></li>
              </ul>
            </li>

            <li class="treeview {{ Nav::isResource('used-products') }} {{ Nav::isRoute('requested.used-products') }}">
              <a href="#"><i class="fa fa-circle-o"></i>Used Products
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{ Nav::isRoute('used-products') }}"><a href="{{ url('admin/used-products') }} "> <i class="fa fa-circle-o"></i>Used Products</a></li>
                
                <li class="{{ Nav::isRoute('requested.used-products') }}"><a href="{{url('admin/requested/used-products')}}"><i class="fa fa-circle-o"></i>Requested used products</a></li>
              </ul>
            </li>

            <li class="treeview {{ Nav::isResource('ticket-products') }} {{ Nav::isRoute('requested.ticket-products') }}">
              <a href="#"><i class="fa fa-circle-o"></i>Tickets
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{ Nav::isRoute('ticket-products') }}"><a href="{{ url('admin/ticket-products') }} "> <i class="fa fa-circle-o"></i>Tickets</a></li>
                
                <li class="{{ Nav::isRoute('requested.ticket-products') }}"><a href="{{url('admin/requested/ticket-products')}}"><i class="fa fa-circle-o"></i>Requested Tickets</a></li>
              </ul>
            </li>

            <li class="treeview {{ Nav::isResource('bid-products') }} {{ Nav::isRoute('requested.bid-products') }}">
              <a href="#"><i class="fa fa-circle-o"></i>Bids
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{ Nav::isRoute('bid-products') }}"><a href="{{ url('admin/bid-products') }} "> <i class="fa fa-circle-o"></i>Bids</a></li>
                
                <li class="{{ Nav::isRoute('requested.bid-products') }}"><a href="{{url('admin/requested/bid-products')}}"><i class="fa fa-circle-o"></i>Requested Bids</a></li>
              </ul>
            </li>
            
            <li class="{{ Nav::isRoute('import.page') }}"><a href="{{ route('import.page') }}"><i class="fa fa-circle-o"></i>Import Products</a></li>
            <li class="{{ Nav::isRoute('pro.val') }} {{ Nav::isRoute('opt.edit') }} {{ Nav::isRoute('attr.add') }}{{ Nav::isRoute('attr.index') }}"><a href="{{route('attr.index')}} "> <i class="fa fa-circle-o"></i>Product Attributes </a></li>
            
             <li class="{{ Nav::isResource('coupan') }}"><a href="{{url('admin/coupan')}} "><i class="fa fa-circle-o"></i>Coupons</a></li>

             <li class="{{ Nav::isResource('admin/return_policy') }}"><a href="{{url('admin/return_policy')}} "><i class="fa fa-circle-o"></i>Return Policy Settings</a></li>
            
            <li class="{{ Nav::isResource('unit') }}"><a href="{{url('admin/unit') }}"><i class="fa fa-circle-o"></i>Units</a></li>

            <li class="{{ Nav::isResource('special') }}"><a href="{{ url('admin/special') }}"><i class="fa fa-circle-o"></i>Special Offers</a></li>
        </ul>
        </li>

        <li id="ordersm" class="treeview {{ Nav::isResource('admin.pending.orders') }} {{ Nav::isRoute('admin.can.order') }} {{ Nav::isRoute('return.order.show') }} {{ Nav::isRoute('return.order.detail') }} {{ Nav::isRoute('return.order.index') }} {{ Nav::isResource('order') }} {{ Nav::isResource('invoice') }}">
              
            <a href="#">
              <i class="fa fa-list-alt" aria-hidden="true"></i> <span>Orders & Invoices</span>
               <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
               </span>
             </a>

             <ul class="treeview-menu">

                <li class="{{ Nav::isResource('order') }}"><a href="{{route('order.index')}} "><i class="fa fa-circle-o"></i>All Orders </a></li>
                <li class="{{ Nav::isResource('admin.pending.orders') }}"><a href="{{route('admin.pending.orders')}} "><i class="fa fa-circle-o"></i>Pending Orders </a></li>
                 <li class="{{ Nav::isRoute('admin.can.order') }}"><a href="{{route('admin.can.order')}} "><i class="fa fa-circle-o"></i>Canceled Orders </a></li>

                <li class="{{ Nav::isRoute('return.order.index') }} {{ Nav::isRoute('return.order.show') }} {{ Nav::isRoute('return.order.detail') }}"><a href="{{route('return.order.index')}} "><i class="fa fa-circle-o"></i>Returned Orders </a></li>
                
                <li class="{{ Nav::isResource('invoice') }}"><a href="{{url('admin/invoice')}} "><i class="fa fa-circle-o"></i>Invoice Setting</a></li>

              </ul>
        </li>

         <li class="treeview {{ Nav::isRoute('review.index') }} {{ Nav::isRoute('r.ap') }}"> <a href="#">
            <i class="fa fa-star-half-o" aria-hidden="true"></i> <span>Reviews & Ratings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
        <ul class="treeview-menu">
         
         
            
                <li class="{{ Nav::isRoute('review.index') }}"><a href="{{url('admin/review')}}"><i class="fa fa-circle-o"></i>All Reviews</a></li>
                <li class="{{ Nav::isRoute('r.ap') }}"><a href="{{url('admin/review_approval')}}"><i class="fa fa-circle-o"></i>Reviews For Approval</a></li>
                
              
           
         
            
          </ul>
          </li>

        <li id="martools" class="treeview {{ Nav::isResource('admin/testimonial') }} {{ Nav::isResource('admin/adv') }} {{ Nav::isResource('admin/hotdeal') }} {{ Nav::isResource('admin/detailadvertise') }}">
          <a href="#">
            <i class="fa fa-line-chart" aria-hidden="true"></i><span>Marketing Tools</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>

           <ul class="treeview-menu">
            <li class="{{ Nav::isResource('admin/hotdeal') }}"><a href="{{url('admin/hotdeal')}}"><i class="fa fa-circle-o"></i>Hot Deals</a></li>
            <li class="{{ Nav::isResource('admin/detailadvertise') }}"><a href="{{url('admin/detailadvertise')}}"><i class="fa fa-circle-o"></i>Block Advertisments</a></li>
            <li class="{{ Nav::isResource('admin/adv') }}"><a href="{{url('admin/adv')}}"><i class="fa fa-circle-o"></i>Advertisements</a></li>
            <li class="{{ Nav::isResource('admin/testimonial') }}"><a href="{{url('admin/testimonial')}} "><i class="fa fa-circle-o"></i>Testimonials</a></li>
           </ul>
          
        </li>


        <li id="location" class="treeview {{ Nav::isRoute('country.list.pincode') }} {{ Nav::isRoute('admin.desti') }} {{ Nav::isRoute('country.index') }} {{ Nav::isRoute('state.index') }} {{ Nav::isRoute('city.index') }}">
          <a href="#">
            <i class="fa fa-map-marker" aria-hidden="true"></i> <span>Locations</span>
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         
           
              <ul class="treeview-menu">
                <li class="{{ Nav::isResource('country') }}"><a href="{{url('admin/country')}}"><i class="fa fa-circle-o"></i>Countries</a></li>
                <li class="{{ Nav::isResource('state') }}"><a href="{{url('admin/state')}}"><i class="fa fa-circle-o"></i>States</a></li>
                <li class="{{ Nav::isResource('city') }}"><a href="{{url('admin/city')}}"><i class="fa fa-circle-o"></i>Cities</a></li>
               <li class="{{ Nav::isRoute('country.list.pincode') }}{{ Nav::isRoute('admin.desti') }}"><a href="{{url('admin/destination')}}"><i class="fa fa-circle-o"></i>Delivery Locations</a></li>
              </ul>
          
            
       
        </li>

        <li id="shippingtax" class="treeview {{ Nav::isResource('admin/zone') }}  {{ Nav::isResource('shipping') }} {{ Nav::isResource('tax') }}">
            <a href="#">
                <i class="fa fa-fighter-jet" aria-hidden="true"></i> <span>Shipping & Taxes</span>
                <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>

              <ul class="treeview-menu">
                  <li class="{{  Nav::isResource('tax_class')  }}"><a href="{{url('admin/tax_class')}}"><i class="fa fa-circle-o"></i>Tax Classes</a></li>
                  <li class="{{ Nav::isRoute('tax.index') }}{{ Nav::isRoute('tax.edit') }}{{ Nav::isRoute('tax.create') }}"><a href="{{url('admin/tax')}}"><i class="fa fa-circle-o"></i>Tax Rates</a></li>
                   <li class="{{ Nav::isResource('admin/zone') }}"><a href="{{url('admin/zone')}}"><i class="fa fa-circle-o"></i>Zones</a></li>
                  <li class="{{ Nav::isResource('shipping') }}"><a href="{{url('admin/shipping')}}"><i class="fa fa-circle-o"></i>Shipping</a></li>
              </ul>
        </li>

        <li id="commission" class="treeview {{ Nav::isResource('admin/commission') }}">
          <a href="#">
            <i class="fa fa-pie-chart" aria-hidden="true"></i><span>Commissions</span>
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if($cms->type =='c')
              <li class="{{ Nav::isResource('admin/commission') }}"><a href="{{url('admin/commission')}} "><i class="fa fa-circle-o"></i>Commissions</a></li>
            @endif
            <li><a href="{{url('admin/commission_setting')}} "><i class="fa fa-circle-o"></i>Commission Setting</a></li>
          </ul>
         </li>

         @if($genrals_settings->vendor_enable == 1)

        <li class="treeview {{ Nav::isRoute('seller.payout.show.complete') }} {{ Nav::isRoute('seller.payouts.index') }} {{ Nav::isRoute('seller.payout.complete') }}">
          <a href="#">
            <i class="fa fa-slack" aria-hidden="true"></i><span>Seller Payouts</span>
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <li class="{{ Nav::isRoute('seller.payouts.index') }}"><a href="{{route('seller.payouts.index')}} "><i class="fa fa-circle-o"></i>Pending Payouts</a></li>

            <li class="{{ Nav::isRoute('seller.payout.show.complete') }} {{ Nav::isRoute('seller.payout.complete') }}"><a href="{{ route('seller.payout.complete') }}"><i class="fa fa-circle-o"></i>Completed Payouts</a></li>

          </ul>

        </li>

        @endif

 


 <li id="mscur" class="{{ Nav::isResource('admin/multiCurrency') }}"><a href="{{url('admin/multiCurrency')}} "><i class="fa fa-money"></i><span>Multiple Currencies</span></a></li>

 <li id="slider" class="treeview {{ Nav::isRoute('front.slider') }} {{ Nav::isResource('slider') }}">
    <a href="#">
      <i class="fa fa-sliders" aria-hidden="true"></i><span>Sliders</span>
      <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
     <li class="{{ Nav::isResource('slider') }}"><a href="{{url('admin/slider')}} "><i class="fa fa-circle-o"></i>Sliders</a></li>
     <li class="{{ Nav::isRoute('front.slider') }}">
        <a href="{{route('front.slider')}} "><i class="fa fa-circle-o" aria-hidden="true"></i><span>Category Sliders</span></a>
     </li>
    </ul>
</li>
      
       <li class="{{ Nav::isResource('blog') }}"><a href="{{url('admin/blog')}}"><i class="fa fa-circle-o"></i>Blogs</a></li>

       <li class="{{ Nav::isRoute('blogrequests') }}"><a href="{{url('admin/blogrequests')}}"><i class="fa fa-circle-o"></i>Blog Requests</a></li>
 
       <li id="faqs" class="{{ Nav::isResource('faq') }}"><a href="{{url('admin/faq')}} "><i class="fa fa-question-circle-o" aria-hidden="true"></i><span>FAQ's</span></a></li>

       <li class="{{ Nav::isRoute('pwa.setting.index') }}"><a title="Progressive Web App Setting" href="{{route('pwa.setting.index')}} "><i class="fa fa-delicious" aria-hidden="true"></i><span>PWA Settings</span></a></li>

        <li id="sitesetting" class="treeview {{ Nav::isRoute('get.view.m.mode') }} {{ Nav::isRoute('customstyle') }} {{ Nav::isRoute('site.lang') }} {{ Nav::isResource('admin/abuse') }} {{ Nav::isResource('admin/bank_details') }} {{ Nav::isRoute('genral.index') }} {{ Nav::isRoute('mail.getset') }} {{ Nav::isRoute('gen.set') }} {{ Nav::isRoute('footer.index') }} {{ Nav::isResource('social') }} {{ Nav::isResource('page') }} {{ Nav::isRoute('seo.index') }} {{ Nav::isRoute('api.setApiView') }} {{ Nav::isRoute('get.paytm.setting') }} {{ Nav::isResource('page') }} {{ Nav::isRoute('admin.dash') }} {{ Nav::isRoute('static.trans')  }}">
          <a href="#">
            <i class="fa fa-cog" aria-hidden="true"></i><span>Site Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
          <ul class="treeview-menu">
            <li class="{{ Nav::isRoute('genral.index') }}"><a href="{{url('admin/genral')}}"><i class="fa fa-circle-o"></i>General Settings</a></li>
            <li class="{{ Nav::isRoute('static.trans')  }} {{ Nav::isRoute('site.lang') }}"><a href="{{route('site.lang')}}"><i class="fa fa-circle-o"></i>Site Languages</a></li>
            <li class="{{ Nav::isRoute('mail.getset') }}"><a href="{{url('admin/mail-settings')}}"><i class="fa fa-circle-o"></i>Mail Settings</a></li>
            <li class="{{ Nav::isRoute('gen.set') }}"><a href="{{route('gen.set')}}"><i class="fa fa-circle-o"></i>Social Login Settings</a></li>
            
            <li class="{{ Nav::isRoute('admin.dash') }}">
              <a href="{{ route('admin.dash') }}">
                <i class="fa fa-circle-o" aria-hidden="true"></i><span>Admin Dashboard Settings</span></a>
            </li>

            <li class="{{ Nav::isRoute('get.view.m.mode') }}">
              <a href="{{ route('get.view.m.mode') }}">
                <i class="fa fa-circle-o" aria-hidden="true"></i><span>Maintenance Mode</span></a>
            </li>

             <li class="{{ Nav::isRoute('customstyle') }}">
              <a href="{{ route('customstyle') }}">
                <i class="fa fa-circle-o" aria-hidden="true"></i><span>Custom Style and JS</span></a>
            </li>
            
            <li class="{{ Nav::isRoute('footer.index') }}"><a href="{{url('admin/footer')}} "><i class="fa fa-circle-o"></i>Footer Customizations</a></li>
            <li class="{{ Nav::isResource('social') }}"><a href="{{url('admin/social')}} "><i class="fa fa-circle-o"></i>Social Handler Settings</a></li>
            
            <li class="{{ Nav::isResource('admin/bank_details') }}"><a href="{{url('admin/bank_details')}} "><i class="fa fa-circle-o" aria-hidden="true"></i><span>Bank Details</span></a></li>
            <li class="{{ Nav::isResource('page') }}"><a href="{{url('admin/page')}}"><i class="fa fa-circle-o"></i>Pages</a></li>
            <li class="{{ Nav::isRoute('seo.index') }}"><a href="{{url('admin/seo')}} "><i class="fa fa-circle-o"></i>SEO</a></li>
              <li class="{{ Nav::isResource('admin/abuse') }}">
          <a href="{{ url('admin/abuse') }}">
           <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Abuse Word Settings</span></a>
        </li>
          </ul>
        </li>
        <li class="{{ Nav::isRoute('payment.gateway.settings') }}">
          
          <a href="{{ route('payment.gateway.settings') }}"><i class="fa fa-money"></i><span>Payment Gateway Settings</span></a>
            
        </li>

        <li class="{{ Nav::isRoute('widget.setting') }}">
          
          <a href="{{ route('widget.setting') }}"><i class="fa fa-gg"></i>Widgets Settings</span></a>
            
        </li>

       <li class="{{ Nav::isRoute('admin.wallet.settings') }}"><a href="{{ route('admin.wallet.settings') }}"><i class="fa fa-folder" aria-hidden="true"></i>Wallet Settings</a></li>
        
        <li id="ticket" class="{{ Nav::isRoute('tickets.admin') }} {{ Nav::isRoute('ticket.show') }}">
          <a href="{{ route('tickets.admin') }}">
            <i class="fa fa-bullhorn" aria-hidden="true"></i>
            <span>Support Tickets</span></a>
        </li>
        
         <li id="reppro" class="{{ Nav::isRoute('get.rep.pro') }}">
          <a href="{{ route('get.rep.pro') }}">
           <i class="fa fa-flag" aria-hidden="true"></i> <span>Reported Products</span></a>
        </li>
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>