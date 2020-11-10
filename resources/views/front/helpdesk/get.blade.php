@extends('front.layout.master')
@section('title',__('staticwords.HelpdeskandSupport').'- ')
@section('head-script')
<!-- TinyMCE Editor -->
<script src="{{ url('admin/plugins/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
@endsection
@section('body')
 <div class="container-fluid">
 	<br>
 	<div class="bg-white col-md-12">
 		<p>&nbsp;</p>
 		<h4><i class="fa fa-question-circle"></i> {{ __('staticwords.HelpdeskandSupport') }}</h4>
 		<hr>
		<form action="{{ route('hdesk.store') }}" method="POST" enctype="multipart/form-data">
			{{ csrf_field() }}
		<div class="row padding15">

			<div class="col-md-8">
				<div class="form-group">
			<label for="">{{ __('staticwords.Issue') }}: <span class="required">*</span></label>
			<input type="text" name="issue_title" class="@error('issue_title') is-invalid @enderror form-control">
			@error('issue_title')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
		   </div>
		<div class="form-group">
			<label for="">{{ __('staticwords.Image') }}: </label>
			<input type="file" name="image" class="@error('image') is-invalid @enderror form-control">
			@error('image')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
	   </div>
		<div class="form-group">
			<label for="">{{ __('staticwords.DescribeyourIssue') }}: <span class="required">*</span></label>
			<textarea name="issue" id="editor1" cols="30" rows="10" class="@error('issue') is-invalid @enderror form-control"></textarea>
			@error('title')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
		</div>	
			</div>

			
			
		</div>
			
			<button type="submit" class="margin-left15 btn btn-md btn-primary">
			<i class="fa fa-plus"></i> {{ __('staticwords.CreateTicket') }}
			</button>

			<br><br>
		
		

		</form>
 	</div>
 </div>
@endsection

@section('script')
	<script src="{{ url('js/formsubmit.js') }}"></script>
@endsection