@php
	$tplConfig->page_meta_title = 'I am a ' . $shareContent . ' learner | ' . trim(ucfirst($result->language) . ' Language Learning Style Quiz | Live Lingua');
	$index = 0;
@endphp

@if(View::exists("page.$lang.style." . strtolower($result -> style) ))
	@include("page.$lang.style." . strtolower($result -> style) ))
@else
	@includeIf("page.en.style." . strtolower($result -> style) ))
@endif
	

<div class="container">
	<div class="cta-section secondary pv-xl text-align-center mb-xxl">
		<div class="container lg">
			<h2 class="cta-title">Curious about your own learning style?</h2>
			<div class="cta-buttons">
				<a href="{{ route('page', ['controller' => 'quiz']) }}" class="button primary">Take the quiz</a><br>
			</div>
		</div>
	</div>
</div>

<section class="mb-xxl">
	<div class="container md">
		<h2>Your learning style distribution</h2>

		<img src="https://placehold.it/1000x750" class="mv-lg">
		@foreach($styles as $style => $score)
		<div class="accordion lg {{ !$index++ ? 'active' : '' }}">
			<a href="#" class="accordion-title">{{ $index . ' - ' . $style }}</a>
			<div class="accordion-content">
				<p>@lang("quiz.$style.description")</p>
				<ul>
					@foreach(__("quiz.$style.bullets") as $bullet)
					<li>{!!  $bullet  !!}</li>
					@endforeach
				</ul>
			</div>
		</div>
		@endforeach
	</div>
</section>

<section class="mb-xxl">
	<div class="container md">
		<h2>Share your result</h2>

		<img src="https://placehold.it/1000x400" class="mt-lg">
	</div>
</section>

<div class="container">
	<div class="cta-section primary pv-xl text-align-center mb-xxl">
		<div class="container lg">
			<h2 class="cta-title">Take it to the next level!</h2>
			<p class="cta-subtitle">Now that you know your learning style, take your to the next level by getting paired with the perfect tutor for your unique learning style.</p>
			<div class="cta-buttons">
				<a href="#" class="button primary">Try a 1-to-1 lesson free</a><br>
				<span class="button-hint">No credit card required</span>
			</div>
		</div>
	</div>
</div>

<section>
	<div class="container md">
		<div class="notification mb-lg"><p><strong>Live Lingua Comment Policy</strong> - Please read our <a href="{{ route('page', ['controller' => 'privacy-policy']) }}" target="_blank" rel="noopener noreferrer" class="policy-link">Comment Policy</a> before commenting.</p></div>

		<img src="https://placehold.it/1000x400">

	</div>
</section>