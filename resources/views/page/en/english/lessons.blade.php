@php
	$tplConfig->page_meta_title = 'Lesson Page';
	$tplConfig->current_menu	= 'lessons';
@endphp

<section class="mb-xl">
	<div class="container">
		<header class="page-header position-centered">
			<h1 class="page-title">Standard Spanish Lessons</h1>
			<p class="page-subtitle">Your immersive learning experience starts here</p>
		</header>
	</div>
</section>

<section class="mb-xxl">
	<div class="container">
		<div class="columns text-align-center">
			<div class="column col-6 col-12-md">
				<div class="card">
					<img src="{{ asset( '/public/images/spanish_lessons_page_immersive_experience_bullet1.png' ) }}" alt="Ideal tutor">
					<h3>Our academic team connects you with your ideal tutor</h3>
					<p>Simply tell us about your reason for learning, your preferred accent, and your Spanish level. After a quick learning style quiz, we'll introduce you to your ideal tutor.</p>
				</div>
			</div>
			<div class="column col-6 col-12-md">
				<div class="card">
					<img src="{{ asset( '/public/images/spanish_lessons_page_immersive_experience_bullet2.png' ) }}" alt="Lesson structuring">
					<h3>Lessons are structured around how you learn best</h3>
					<p>Your personal tutor and our academic team will assess your current level and look at your goals, to design a personalized learning plan so you can reach them.</p>
				</div>
			</div>
			<div class="column col-6 col-12-md">
				<div class="card">
					<img src="{{ asset( '/public/images/spanish_lessons_page_immersive_experience_bullet3.png' ) }}" alt="Tailored feedback">
					<h3>Tailored feedback to guide you as you progress</h3>
					<p>In each lesson your tutor will guide you knowing how you learn more effectively. Then, provide feedback and direction on homework and additional materials to keep you engaged.</p>
				</div>
			</div>
			<div class="column col-6 col-12-md">
				<div class="card">
					<img src="{{ asset( '/public/images/spanish_lessons_page_immersive_experience_bullet4.png' ) }}" alt="Lesson scheduling">
					<h3>Find balance between learning and your daily to-dos</h3>
					<p>With Live Lingua you are in control of your learning schedule. Quickly and easily select times for lessons that are convenient for you from your student dashboard.</p>
				</div>
			</div>
		</div>
		<div class="cta-buttons text-align-center mt-xl">
			<a href="#" class="button primary">Try a 1-to-1 lesson free</a>
			<br>
			<span class="button-hint">No credit card required</span>
		</div>
	</div>
</section>

<section class="mb-xxl pv-xxl section-fill">
	<div class="container lg">
		<blockquote class="testimonial-feature">
			<p>"Thank you very much to my Spanish teacher for her patience and good teaching skills. She is the best teacher I have ever had." <cite>Tseng E. Photographer</cite></p>
		</blockquote>
	</div>
</section>

<section class="mb-xxl">
	<div class="container">
		<header class="section-header position-centered">
			<h2 class="section-title">Premium service at an affordable price</h2>
			<p class="section-subtitle">We know you are busy and your plans can change. That's why with Live Lingua, you only pay for lessons you need. No minimum purchase and no long-term commitment required.</p>
		</header>
	</div>
	<div class="container lg">
		<table class="table-bordered mb-lg">
			<tr>
				<th>Standard Lessons</td>
				<td>From $10.99 per hour</td>
				<td></td>
			</tr>
			<tr>
				<th>DELE &amp; ECELE Exam Lessons</td>
				<td>From $15.99 per hour</td>
				<td class="text-align-right"><a href="#" class="button display-block">Find out more</a></td>
			</tr>
			<tr>
				<th>Custom Lessons</th>
				<td>From $15.99 per hour</td>
				<td class="text-align-right"><a href="#" class="button display-block">Find out more</a></td>
			</tr>
			<tr>
				<th>Group Classes</th>
				<td>Contact us to find out more</td>
				<td class="text-align-right"><a href="#" class="button display-block">Contact us</a></td>
			</tr>
		</table>

		<div class="cta-buttons text-align-center">
			<a href="#" class="button secondary">See full pricing</a><br>
			<span class="button-hint">Cancel anytime</span>
		</div>
	</div>
</section>

<section class="mb-xxl">
	<div class="strip light">
		<div class="container">
			<img src="{{ asset( '/public/images/e360.jpg' ) }}" alt="Entrepreneur360™'s Classics" class="strip-image">
			<p>Live Lingua has been chosen as one of the 15 Entrepreneur360™'s "Classics” in 2015 (the only education company to win an award in this category), for the dedication and focus to a customer-centric business model and charitable work.</p>
		</div>
	</div>
</section>

<section class="mb-xxl">
	<div class="container">

		@include('layout.zedalabs.widgets.selected_teachers')

	</div>
</section>

<div class="container">
	<div class="cta-section primary pv-xl text-align-center">
		<div class="container lg">
			<h2 class="cta-title">Take your first step to finally feeling comfortable speaking Spanish</h2>
			<p class="cta-subtitle">Let's connect you with a hand-picked native-speaking tutor today.</p>
			<div class="cta-buttons">
				<a href="#" class="button primary">Try a 1-to-1 lesson free</a><br>
				<span class="button-hint">No credit card required</span>
			</div>
		</div>
	</div>
</div>
