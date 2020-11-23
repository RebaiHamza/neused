@extends("admin/layouts.master")
@section('title',"Social Login Setting |")
@section("body")
<div class="box">
	<div class="box-header">

	</div>

	<div class="box-body">
		<div class="row">


			<div class="col-md-6">

				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-facebook"></i> Facebook Login Setting</h3>
					</div>
					<div class="panel-body">
						<form action="{{ route('sl.fb') }}" method="POST">
							{{ csrf_field() }}

							<label for="">Client ID:</label>
							<input type="text" placeholder="enter client ID" class="form-control"
								name="FACEBOOK_CLIENT_ID" value="{{ $env_files['FACEBOOK_CLIENT_ID'] }}">
							<br>

							<div class="form-group eyeCy">

								<label for="">Client Secret Key:</label>
								<input type="password" placeholder="enter secret key" class="form-control"
									id="fb_secret" name="FACEBOOK_CLIENT_SECRET"
									value="{{ $env_files['FACEBOOK_CLIENT_SECRET'] }}">

								<span toggle="#fb_secret"
									class="inline-flex fa fa-fw fa-eye field-icon toggle-password2"></span>

							</div>
							<label for="">Callback URL:</label>
							<div class="input-group">
								<input value="{{ route('social.login.callback','facebook') }}" type="text"
									placeholder="https://yoursite.com/public/login/facebook/callback"
									name="FB_CALLBACK_URL" value="{{ $env_files['FB_CALLBACK_URL'] }}"
									class="callback-url form-control">
								<span class="input-group-addon" id="basic-addon2">
									<button title="Copy" type="button" class="copy btn btn-xs btn-default">
										<i class="fa fa-clipboard" aria-hidden="true"></i>
									</button>
								</span>
							</div>
							<small class="text-muted">
								<i class="fa fa-question-circle"></i> Copy the callback url and paste in your app
							</small>
							<br><br>
							<label for=""><i class="fa fa-facebook-square"></i> Enable Facebook Login: </label>
							&nbsp;&nbsp;
							<input {{ $setting->fb_login_enable==1 ? "checked" : "" }} class="tgl tgl-skewed"
								id="fb_enable" type="checkbox" />
							<label class="tgl-btn" data-tg-off="Disable" data-tg-on="Enable" for="fb_enable"></label>
							<input type="hidden" id="fb_login_enable" value="{{ $setting->fb_login_enable }}"
								name="fb_enable">
								<br>
							<button @if(env('DEMO_LOCK')==0) type="submit" @else disabled
								title="This action is disabled in demo !" @endif class="btn btn-md btn-primary"><i
									class="fa fa-save"></i> Save Setting</button>





						</form>

					</div>
				</div>

			</div>


			<div class="col-md-6">

				<div class="panel panel-warning">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-google-plus"></i> Google Login Setting</h3>
					</div>
					<div class="panel-body">
						<form action="{{ route('sl.gl') }}" method="POST">
							{{ csrf_field() }}

							<label for="">Client ID:</label>
							<input name="GOOGLE_CLIENT_ID" type="text" placeholder="enter client ID"
								class="form-control" value="{{ $env_files['GOOGLE_CLIENT_ID'] }}">
							<br>

							<div class="eyeCy">

								<label for="">Client Secret Key:</label>
								<input type="password" name="GOOGLE_CLIENT_SECRET"
									value="{{ $env_files['GOOGLE_CLIENT_SECRET'] }}" placeholder="enter secret key"
									class="form-control" id="gsecret">

								<span toggle="#gsecret"
									class="inline-flex fa fa-fw fa-eye field-icon toggle-password2"></span>

							</div>

							<br>
							<label for="">Callback URL:</label>
							<div class="input-group">
								<input  type="text"
									placeholder="https://yoursite.com/login/public/google/callback"
									name="GOOGLE_CALLBACK_URL" value="{{ route('social.login.callback','google') }}"
									class="callback-url form-control">
								<span class="input-group-addon" id="basic-addon2">
									<button title="Copy" type="button" class="copy btn btn-xs btn-default">
										<i class="fa fa-clipboard" aria-hidden="true"></i>
									</button>
								</span>
							</div>
							<small class="text-muted">
								<i class="fa fa-question-circle"></i> Copy the callback url and paste in your app
							</small>
							<br><br>
							<label for=""><i class="fa fa-google-plus-square"></i> Enable Google Login: </label>
							&nbsp;&nbsp;
							<input {{ $setting->google_login_enable==1 ? "checked" : "" }} class="tgl tgl-skewed"
								id="ggl_enable" type="checkbox" />
							<label class="tgl-btn" data-tg-off="Disable" data-tg-on="Enable" for="ggl_enable"></label>
							<input type="hidden" id="google_login_enable" value="{{ $setting->google_login_enable }}"
								name="google_enable">
							<br>
							<button @if(env('DEMO_LOCK')==0) type="submit" @else disabled
								title="This action is disabled in demo !" @endif class="btn btn-md btn-primary"><i
									class="fa fa-save"></i> Save Setting</button>

						</form>
					</div>
				</div>

			</div>

			<div class="col-md-6">
				<div class="panel panel-warning" style="border-color: #00ACEE">
					<div class="panel-heading" style="background-color: #00ACEE">
						<h3 class="panel-title" style="color: aliceblue"><i class="fa fa-twitter"></i> Twitter Login Setting</h3>
					</div>
					<div class="panel-body">
						<form action="{{ route('sl.tw','twitter') }}" method="POST">
							{{ csrf_field() }}
		
							<label for="">Client ID:</label>
							<input type="text" placeholder="enter client ID" class="form-control"
								name="TWITTER_API_KEY" value="{{ env('TWITTER_API_KEY') }}">
							<br>
		
							<div class="form-group eyeCy">
		
								<label for="">Client Secret Key:</label>
								<input type="password" placeholder="enter secret key" class="form-control"
									id="tw_secret" name="TWITTER_SECRET_KEY"
									value="{{ env('TWITTER_SECRET_KEY') }}">
		
								<span toggle="#tw_secret"
									class="inline-flex fa fa-fw fa-eye field-icon toggle-password2"></span>
		
							</div>
							<label for="">Callback URL:</label>
							<div class="input-group">
								<input value="{{ route('social.login.callback','twitter') }}" type="text"
									placeholder="https://yoursite.com/public/login/twitter/callback"
									name="FB_CALLBACK_URL" value="{{ env('FB_CALLBACK_URL') }}"
									class="callback-url form-control">
								<span class="input-group-addon" id="basic-addon2">
									<button title="Copy" type="button" class="copy btn btn-xs btn-default">
										<i class="fa fa-clipboard" aria-hidden="true"></i>
									</button>
								</span>
							</div>
							<small class="text-muted">
								<i class="fa fa-question-circle"></i> Copy the callback url and paste in your app
							</small>
							<br><br>
							<div class="form-group">
								<label for=""><i class="fa fa-twitter"></i> Enable Twitter Login: </label>
								<br>
								<label class="switch">
									<input id="twitter_enable" type="checkbox" name="twitter_enable"
										{{ $setting->twitter_login_enable==1 ? "checked" : "" }}>
									<span class="knob"></span>
								</label>
							</div>
							<button @if(env('DEMO_LOCK')==0) type="submit" @else disabled
								title="This action is disabled in demo !" @endif class="btn btn-md btn-primary"><i
									class="fa fa-save"></i> Save Setting
							</button>
							<br><br>
						</form>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>


@endsection
@section('custom-script')
<script>
	$('.copy').on('click', function () {

		var copyText = $(this).closest('.input-group').find('.callback-url');
		copyText.select();
		//copyText.setSelectionRange(0, 99999); /*For mobile devices*/

		/* Copy the text inside the text field */
		document.execCommand("copy");
	});
</script>
@endsection