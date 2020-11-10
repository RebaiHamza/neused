@extends('front.layout.master')
@section('title',"FAQ's | ")
@section('body')
<div class="container-fluid">

	<div class="p-3 bg-white">
		<h4>{{ __('All FAQs') }}</h4>
		<hr>
		@if(count($faqs)>0)
			<div class="accordion" id="accordionExample">
			 
			 @foreach($faqs as $faq)
				<div class="card">
				    <div class="card-header" id="headingOne">
				      <h2 class="mb-0">
				        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#faq{{ $faq->id }}" aria-expanded="true" aria-controls="collapseOne">
				          <i class="fa fa-question-circle"></i> {{ $faq->que }}
				        </button>
				      </h2>
				    </div>

				    <div id="faq{{ $faq->id }}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
				      <div class="card-body">
				        <blockquote class="blockquote mb-0">
				        	{{ $faq->ans }}
				        </blockquote>
				      </div>
				    </div>
			  </div>
			  
			 @endforeach

			 <div class="mx-auto width200">
				{!! $faqs->links() !!}
			 </div>
			 
			</div>

	    @else
	     <div class="alert">
	     	<i class="fa fa-info-circle">
	     		<h6>{{ __('NO FAQ Created Yet !') }}</h6>
	     	</i>
	     </div>
		@endif
	</div>
	
</div>
@endsection