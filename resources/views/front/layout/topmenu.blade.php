@foreach($menus->where('status','=','1')->orderBy('position','ASC')->get() as $menu)
	
		@if($menu->link_by == 'url')
		<li>
			<a href="{{ $menu->url }}">@if($menu->icon != NULL || $menu->icon != '') <i class="fa {{ $menu->icon }}"></i> @endif {{ $menu->title }}
				<span title="{{ $menu->tag_text }}" class="{{ $menu->menu_tag == 1 ? 'menu-label' : '' }} menu-label{{ $menu->id }} new_menu hidden-xs">{{substr(strip_tags($menu->tag_text), 0, 14)}}{{strlen(strip_tags(
                $menu->tag_text))>14 ? '...' : ""}}</span>
				<!-- If menu tag is on -->
				@if($menu->menu_tag == 1)
				
				<style>
	              .header-style-1 .header-nav .navbar-default .navbar-collapse .navbar-nav > li .menu-label{{ $menu->id }}.new_menu {
	                background: <?php echo $menu->tag_bg; ?>;
	              }
	              .header-style-1 .header-nav .navbar-default .navbar-collapse .navbar-nav > li .menu-label{{ $menu->id }}.new_menu::after {
	                border-color: <?php echo $menu->tag_bg ?> rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);
	              }
	              .header-style-1 .header-nav .navbar-default .navbar-collapse .navbar-nav > li .menu-label{{ $menu->id }} {
	                position: absolute;
	                text-transform: uppercase;
	                top: -10px;
	                display: inline;
	                padding: 4px 8px;
	                color: {{$menu->tag_color}};
	                font-size: 10px;
	                font-family:'Barlow', sans-serif;
	                right: 20px;
	                line-height: normal;
	                letter-spacing:1px;
	                border-radius:2px
	              }
	            </style>
			   
				
			  @endif

			  <!-- If menu tag end -->
	
			</a>
		</li>
			   
		@endif

		@if($menu->link_by == 'page')
		<li>
			<a href="{{ route('page.slug',$menu->gotopage->slug) }}">@if($menu->icon != NULL || $menu->icon != '') <i class="fa {{ $menu->icon }}"></i> @endif{{ $menu->title }}
			 <span title="{{ $menu->tag_text }}" class="{{ $menu->menu_tag == 1 ? 'menu-label' : '' }} menu-label{{ $menu->id }} new_menu hidden-xs">{{substr(strip_tags($menu->tag_text), 0, 14)}}{{strlen(strip_tags(
                $menu->tag_text))>14 ? '...' : ""}}</span>
			 <!-- If menu tag is on -->
				@if($menu->menu_tag == 1)
				
				<style>
	              .header-style-1 .header-nav .navbar-default .navbar-collapse .navbar-nav > li .menu-label{{ $menu->id }}.new_menu {
	                background: {{ $menu->tag_bg }};
	              }

	              .header-style-1 .header-nav .navbar-default .navbar-collapse .navbar-nav > li .menu-label{{ $menu->id }}.new_menu::after {
	                border-color: {{ $menu->tag_bg }} rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);
	              }

	              .header-style-1 .header-nav .navbar-default .navbar-collapse .navbar-nav > li .menu-label{{ $menu->id }} {
	                position: absolute;
	                text-transform: uppercase;
	                top: -10px;
	                display: inline;
	                padding: 4px 8px;
	                color: {{$menu->tag_color}};
	                font-size: 10px;
	                font-family:'Barlow', sans-serif;
	                right: 20px;
	                line-height: normal;
	                letter-spacing:1px;
	                border-radius:2px
	              }
	            </style>
			   
				
			  @endif

			  <!-- If menu tag end -->

			</a>

		</li>
		@endif

		@if($menu->link_by == 'cat')

			<li class="{{ $menu->show_cat_in_dropdown == 1 || $menu->show_child_in_dropdown == 1 ? 'mega-drop-down' : ''}}">

				<a href="@if($menu->cat_id != 0) <?php $category_id = $menu->cat_id ?> @include('front.layout.caturl') @else # @endif">@if($menu->icon != NULL || $menu->icon != '') <i class="fa {{ $menu->icon }}"></i> @endif {{ $menu->title }}
					<span title="{{ $menu->tag_text }}" class="{{ $menu->menu_tag == 1 ? 'menu-label' : '' }} menu-label{{ $menu->id }} new_menu hidden-xs">{{substr(strip_tags($menu->tag_text), 0, 14)}}{{strlen(strip_tags(
                $menu->tag_text))>14 ? '...' : ""}}</span>
					<!-- If menu tag is on -->
					@if($menu->menu_tag == 1)
					
					<style>
		              .header-style-1 .header-nav .navbar-default .navbar-collapse .navbar-nav > li .menu-label{{ $menu->id }}.new_menu {
		                background: <?php echo $menu->tag_bg; ?>;
		              }
		              .header-style-1 .header-nav .navbar-default .navbar-collapse .navbar-nav > li .menu-label{{ $menu->id }}.new_menu::after {
		                border-color: <?php echo $menu->tag_bg ?> rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);
		              }
		              .header-style-1 .header-nav .navbar-default .navbar-collapse .navbar-nav > li .menu-label{{ $menu->id }} {
		                position: absolute;
		                text-transform: uppercase;
		                top: -10px;
		                display: inline;
		                padding: 4px 8px;
		                color: {{$menu->tag_color}};
		                font-size: 10px;
		                font-family:'Barlow', sans-serif;
		                right: 20px;
		                line-height: normal;
		                letter-spacing:1px;
		                border-radius:2px
		              }
		            </style>
				   
					
				  @endif

				  <!-- If menu tag end -->
		
				</a>


	
				@if($menu->show_cat_in_dropdown == 1 && $menu->linked_parent != NULL)
					
					@php
						$catarray = array();

					    foreach ($menu->linked_parent as $key => $parent) {

					        $cat = App\Category::find($parent);
					       
					        if(isset($cat)){
					        	array_push($catarray, ["cat" => $cat->id]);
					        }

					        if(isset($menu->linked_child)){
					        	foreach ($menu->linked_child as $key => $child) {
					            
					            $subcat = App\Subcategory::find($child);

					            if(isset($subcat) && $subcat->parent_cat == $parent){
					               
					              $newdata =  array (
					               

					               	"sub" => $subcat->id,
					              
					                
					              );
					               array_push($catarray, $newdata);
					               $newdata2 =  array (
					             
					               	'detail' => strip_tags($subcat->description)
					                
					              );
								array_push($catarray, $newdata2);
					            }

					        }
					        }
					    }

					  
						$counter = 0;

						 $slCount = count($catarray)/18;
						 $whole = floor($slCount); 
						  $floor = fmod($slCount, 1);
						  if($floor > 0){
						  	$slCount = $whole + 1;
						  }else{
						  	$slCount = $whole;
						  }
						
						$last = -1;
					@endphp
					
					<div class="mega-menu">
						<div class="mega-menu-wrap">

							<div class="row">
								<div class="{{ $menu->show_image == 1 && $menu->bannerimage != '' && @file_get_contents(public_path().'/images/menu/'.$menu->bannerimage) ? 'col-md-9' : 'col-md-12' }}">
									<div class="row">
										
										@for($i=0;$i<$slCount;$i++)
											@php
									          	$counter = 0;
									         @endphp
										         <div class="col-md-3 {{ $i%2 == 0 ? '' : 'f3efef' }}">
										 		@foreach ($catarray as $key => $a) 
										 	
										        @if($counter < 18)
										        	@if($last <= $key)
											          @foreach ($a as $key2 => $b)

											           	@if($key2 == 'cat')
															<h4 class="maintitle mega-title">
															@php
																$cat = App\Category::find($b);
																$category_id = $b;
															@endphp
																<a class="text-dark" href="@include('front.layout.caturl')">
																	{{ $cat['title'] }}&nbsp;&nbsp;<i class="playicon fa fa-play" aria-hidden="true"></i>


																</a>
															</h4>
														@elseif($key2 == 'sub')

															@if($counter == 0)
																<br>
															@endif
																<ul class="w150 description">
																	<li>
																		@php
																			$sub = App\Subcategory::find($b);
																			$subcateid = $b;
																		@endphp
																		<a href="@include('front.layout.subcaturl')">{{ $sub->title }}</a>
																		
																	</li>
																</ul>

														@else
															@if($counter == 0)
																<br>
															@endif
															<span>
																{{substr(strip_tags($b), 0, 30)}}{{strlen(strip_tags($b))>30 ? '...' : ""}}
															</span>
															<br><br>
														@endif
														
											          @endforeach
											          @php
											          	$counter ++;
											          @endphp
											        @else
											        @endif
										        @else
										        	@php
										        		$last = $key;
										        	@endphp
										          @break
										        @endif
										
										        
										@endforeach
					  					</div>
					  					@endfor
										
										
									</div>
								</div>

								@if($menu->show_image == 1 && $menu->bannerimage != '' && @file_get_contents(public_path().'/images/menu/'.$menu->bannerimage))
								
									<div class="text-center col-md-3">
										<br>
											<a href="{{ $menu->img_link }}">
												<center>
													<img class="banner-img" src="{{ url('images/menu/'.$menu->bannerimage) }}">
												</center>
											</a>
									</div>

								@endif

								
							</div>
							
							
						</div>	
					</div>

				@endif

				@if($menu->show_child_in_dropdown == 1 && $menu->linked_parent != NULL)

					@php
						$catarray = array();

					    foreach ($menu->linked_parent as $key => $parent) {

					        $cat = App\Subcategory::find($parent);
					       
					        if(isset($cat)){
					        	array_push($catarray, ["cat" => $cat->id]);
					        }
					        
					        if(isset($menu->linked_child)){

						        foreach ($menu->linked_child as $key => $child) {
						            
						            $subcat = App\Grandcategory::find($child);

						            if(isset($subcat)){
						            	if($subcat->subcat_id  == $parent){
						               
							              $newdata =  array (
							               "sub" => $subcat->id,
							              
							                
							              );
							               array_push($catarray, $newdata);
							               $newdata2 =  array (
							             
							               'detail' => strip_tags($subcat->description)
							                
							              );
										array_push($catarray, $newdata2);
						            }
						            }

						        }
					    	}
					    }

					  
						$counter = 0;

						  $slCount = count($catarray)/18;
						  $whole = floor($slCount); 
						  $floor = fmod($slCount, 1);
						  if($floor > 0){
						  	$slCount = $whole + 1;
						  }else{
						  	$slCount = $whole;
						  }
						
						$last = -1;
					@endphp
					
					<div class="mega-menu">
						<div class="mega-menu-wrap">

							<div class="row">
								<div class="{{ $menu->show_image == 1 && $menu->bannerimage != '' && @file_get_contents(public_path().'/images/menu/'.$menu->bannerimage) ? 'col-md-9' : 'col-md-12' }}">
									<div class="row">
										
										@for($i=0;$i<$slCount;$i++)
											@php
									          	$counter = 0;
									         @endphp
										         <div class="col-md-3 {{ $i%2 == 0 ? '' : 'f3efef' }}">
										 		@foreach ($catarray as $key => $a) 
										 	
										        @if($counter < 18)
										        	@if($last <= $key)
											          @foreach ($a as $key2 => $b)
											           	@if($key2 == 'cat')
															<h4 class="maintitle mega-title">
																@php
																	$subcat = App\Subcategory::find($b);
																	$subcateid = $b;
																@endphp
																<a class="text-dark" href="@include('front.layout.subcaturl')">
																	{{ $subcat['title'] }} <i class="fa fa-angle-double-right" aria-hidden="true"></i>

																</a>
															</h4>
														@elseif($key2 == 'sub')

															@if($counter == 0)
																<br>
															@endif
																<ul class="w150 description">
																	<li>
																		@php
																			$childcat = App\Grandcategory::find($b);
																			$childid = $b;
																		@endphp
																		<a href="@include('front.layout.childcaturl')">{{ $childcat['title'] }}</a>
																		
																	</li>
																</ul>

														@else
															@if($counter == 0)
																<br>
															@endif
															<span>
																{{substr(strip_tags($b), 0, 30)}}{{strlen(strip_tags($b))>30 ? '...' : ""}}
															</span>
															<br><br>
														@endif
											          @endforeach
											          @php
											          	$counter ++;
											          @endphp
											        @else
											        @endif
										        @else
										        	@php
										        		$last = $key;
										        	@endphp
										          @break
										        @endif
										
										        
										@endforeach
					  					</div>
					  					@endfor
										
										
									</div>
								</div>

								@if($menu->show_image == 1 && $menu->bannerimage != '' && @file_get_contents(public_path().'/images/menu/'.$menu->bannerimage))
								
									<div class="col-md-3">
										<br>
											<a href="{{ $menu->img_link }}">
												<img class="banner-img" src="{{ url('images/menu/'.$menu->bannerimage) }}">
											</a>
									</div>

								@endif

								
							</div>
							
							
						</div>	
					</div>

				@endif
	            
			
			</li>
			   
		@endif

		
	
@endforeach