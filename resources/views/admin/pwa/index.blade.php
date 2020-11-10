@extends('admin.layouts.master')
@section('title','Progressive Web App Setting | ')
@section('body')
	<div class="box">
		<div class="box-header with-border">
			<div class="box-title">
				Progressive Web App Setting
			</div>		
		</div>

		<div class="box-body">
			<div class="nav-tabs-custom">

				  <!-- Nav tabs -->
				  <ul id="myTabs" class="nav nav-tabs" role="tablist">
				    
				    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">App Setting</a></li>
				    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Update Icons</a></li>
				    
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="home">

				    	<div class="callout callout-success">
				    		<i class="fa fa-info-circle"></i>
				    		 Progessive Web App Requirements
				    		 <ul>
				    		 	<li><b>HTTPS</b> must required in your domain (for enable contact your host provider for SSL configuration).</li>
				    		 	<li><b>Start URL</b> you put must be correct in below settings.</li>
				    		 	<li><b>All icons size</b> required and to be updated in Icon Settings.</li>
				    		 </ul>
				    	</div>

				    	<div class="callout callout-info">
				    		<p><i class="fa fa-info-circle"></i>
				    		 Setting is in json format lookout for commas (,) and update file carefully else PWA will not work.</p>
				    		 <p>Read more about <a target="_blank" href="https://developers.google.com/web/progressive-web-apps" title="Click to open">Progressive Web App</a> Here...</p>
				    	</div>

				    	<div class="callout callout-danger">
				    		<p><i class="fa fa-info-circle"></i> Important Notes
				    		<ul>
				    			<li>IF your domain content /public than in above Manifest setting <u>start_url</u> should be <b>https://yourdomain.com/public/?launcher=true</b> </li>
				    			<li>IF your domain doesn't content /public than in above Manifest setting <u>start_url</u> should be <b>/?launcher=true</b> </li>
				    			<li>IF your project is under <u>subdomain</u> and content /public than in above Manifest setting <u>start_url</u> should be:
									<ol type="i">
										<li><b>https://test.yourdomain.com/public/?launcher=true</b></li>
									</ol>
								IF Doesnt content /public after subdomain url than <u>start_url</u> should be:
									<ol type="i">
										<li><b>https://test.yourdomain.com/?launcher=true</b></li>
									</ol>
				    			</li>
				    		</ul>
				    	</div>

				    	<form action="{{ route('pwa.setting.update') }}" method="POST">
				    		@csrf
				    		<div class="form-group">
				    			<button type="submit" class="pull-right btn btn-md btn-flat btn-primary">
					    		<i class="fa fa-save"></i> Save Changes
					    	</button>
				    		</div>
				    		<br><br>
				    		<div class="form-group">
				    			<label>Manifest Setting:</label>
				    			<textarea name="app_setting" class="form-control" id="" cols="30" rows="20">{{ $setting }}</textarea>
				    		</div>
				    		<div class="form-group">
				    			<label>Service Worker Setting:</label>
				    			<textarea name="sw_setting" class="form-control" id="" cols="30" rows="20">{{ $sw }}</textarea>
				    		</div>
					    	<button type="submit" class="btn btn-md btn-flat btn-primary">
					    		<i class="fa fa-save"></i> Save Changes
					    	</button>
				    	</form>
				    </div>
				    <div role="tabpanel" class="tab-pane" id="profile">
				    
				    	<form action="{{ route('pwa.icons.update') }}" method="POST" enctype="multipart/form-data">
				    		@csrf
					    	<button type="submit" class="pull-right btn btn-md btn-flat btn-primary">
					    			<i class="fa fa-save"></i> Update Icons
					    	</button>
					    	<br><br>
				    		<div class="well">
				    			<div class="row">
				    				<div class="col-md-6">
				    					<div class="form-group">
						    				<label>Icon (36x36)</label>
						    				<input id="icon1" type="file" class="form-control" name="icon36">
						    			</div>
				    				</div>

				    				<div class="col-md-6">
				    					<img id="preview1" alt="preview" src="{{ url('/images/icons/icon36x36.png') }}">
				    				</div>
				    			</div>
				    		</div>

				    		<div class="well">
				    			<div class="row">
				    				<div class="col-md-6">
				    					<div class="form-group">
						    				<label>Icon (48x48)</label>
						    				<input id="icon2" type="file" class="form-control" name="icon48">
						    			</div>
				    				</div>

				    				<div class="col-md-6">
				    					<img id="preview2" alt="preview" src="{{ url('/images/icons/icon48x48.png') }}">
				    				</div>
				    			</div>
				    		</div>

				    		<div class="well">
				    			<div class="row">
				    				<div class="col-md-6">
				    					<div class="form-group">
						    				<label>Icon (72x72)</label>
						    				<input id="icon3" type="file" class="form-control" name="icon72">
						    			</div>
				    				</div>

				    				<div class="col-md-6">
				    					<img id="preview3" alt="preview" src="{{ url('/images/icons/icon72x72.png') }}">
				    				</div>
				    			</div>
				    		</div>

				    		<div class="well">
				    			<div class="row">
				    				<div class="col-md-6">
				    					<div class="form-group">
						    				<label>Icon (96x96)</label>
						    				<input id="icon4" type="file" class="form-control" name="icon96">
						    			</div>
				    				</div>

				    				<div class="col-md-6">
				    					<img id="preview4" alt="preview" src="{{ url('/images/icons/icon96x96.png') }}">
				    				</div>
				    			</div>
				    		</div>

				    		<div class="well">
				    			<div class="row">
				    				<div class="col-md-6">
				    					<div class="form-group">
						    				<label>Icon (144x144)</label>
						    				<input id="icon5" type="file" class="form-control" name="icon144">
						    			</div>
				    				</div>

				    				<div class="col-md-6">
				    					<img id="preview5" alt="preview" src="{{ url('/images/icons/icon144x144.png') }}">
				    				</div>
				    			</div>
				    		</div>

				    		<div class="well">
				    			<div class="row">
				    				<div class="col-md-6">
				    					<div class="form-group">
						    				<label>Icon (168x168)</label>
						    				<input id="icon6" type="file" class="form-control" name="icon168">
						    			</div>
				    				</div>

				    				<div class="col-md-6">
				    					<img id="preview6" alt="preview" src="{{ url('/images/icons/icon168x168.png') }}">
				    				</div>
				    			</div>
				    		</div>

				    		<div class="well">
				    			<div class="row">
				    				<div class="col-md-6">
				    					<div class="form-group">
						    				<label>Icon (192x192)</label>
						    				<input id="icon7" type="file" class="form-control" name="icon192">
						    			</div>
				    				</div>

				    				<div class="col-md-6">
				    					<img id="preview7" alt="preview" src="{{ url('/images/icons/icon192x192.png') }}">
				    				</div>
				    			</div>
				    		</div>

				    		<div class="well">
				    			<div class="row">
				    				<div class="col-md-6">
				    					<div class="form-group">
						    				<label>Icon (256x256)</label>
						    				<input id="icon8" type="file" class="form-control" name="icon256">
						    			</div>
				    				</div>

				    				<div class="col-md-6">
				    					<img id="preview8" alt="preview" src="{{ url('/images/icons/icon256x256.png') }}">
				    				</div>
				    			</div>
				    		</div>
				    		<div class="well">
				    			<div class="row">
				    				<div class="col-md-6">
				    					<div class="form-group">
						    				<label>Icon (512x512)</label>
						    				<input id="icon9" type="file" class="form-control" name="icon512">
						    			</div>
				    				</div>

				    				<div class="col-md-6">
				    					<img id="preview9" alt="preview" src="{{ url('/images/icons/icon512x512.png') }}">
				    				</div>
				    			</div>
				    		</div>
				    		<p></p>
							<div class="form-group">
								<button @if(env('DEMO_LOCK') == 0) type="submit" @else title="This action is disabled in demo !" disabled="disabled" @endif class="pull-left btn btn-md btn-flat btn-primary">
					    			<i class="fa fa-save"></i> Update Icons
					    		</button>
					    		<br>
							</div>
				    	</form>
				    </div>
				  </div>

			</div>
		</div>
	</div>
@endsection
@section('custom-script')
  <script src="{{ url('js/pwasetting.js') }}"></script>
@endsection