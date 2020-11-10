@extends('front.layout.master') 
@section('title',"$page->name | ") 
@section('body')
@if(isset($page))
<div class="container-fluid">
	<div class="row">
		<div class="blog-page width100">
			<div class="blog-post wow fadeInUp"> 
				<h1 class="text-dark">{{ $page->name }}</h1>
					<hr> 
				{!!  $page->des  !!} 
			</div>
		</div>
	</div>
</div>
@endif
@endsection