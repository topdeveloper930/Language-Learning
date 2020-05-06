@php
    $trialLink = route( 'page', [ 'controller' => 'trial-lesson' ] );
    $tutorsLink = route( 'page', [ 'controller' => 'tutors' ] );
    $tplConfig->page_meta_title = 'Home page';
@endphp

	<section class="hero mb-xxl">
		<div class="container">
			<div class="columns align-center">
				<div class="column col-6 col-12-md">
					<h1>Finally speak Spanish comfortably</h1>
					<p>Let us hand-pick your certified, native-speaking, personal tutor and get immersed in the language - from the comfort of home.</p>
					<div class="cta-buttons">
						<a href="#" class="button primary">Try a 1-to-1 lesson free</a>
						<span class="button-hint">No credit card required</span>
					</div>
				</div>
				<div class="column col-5 hide-md">
					<img src="{{ asset('public/images/homepage_main_headline.png') }}" alt="">
				</div>
			</div>
		</div>
	</section>

	<div class="container">
		<div class="brand-list mb-xxl">
			<img src="{{ asset('public/images/brand-list.png') }}" alt="">
		</div>
	</div>

	<section class="mb-xxl pv-xxl section-fill">
		<div class="container">
			<div class="columns align-center">
				<div class="column col-5 col-12-md">
					<header class="section-header mv-none">
						<h2 class="section-title lg">Want to learn or improve your Spanish?</h2>
						<p class="section-subtitle">What if you no longer had to:</p>
						<!-- <a href="#" class="section-button button secondary">Spanish lessons <i class="far fa-angle-right"></i></a> -->
					</header>
				</div>
				<div class="column col-7 col-12-md">
					<div class="card card-sm mb-sm shadow">
						<img src="{{ asset('public/images/homepage_icon_get_to_know_your_teacher.png') }}" alt="Get to know your teacher" class="card-icon">
						<p><b>Pay sky-high private tutor rates</b></p>
					</div>
					<div class="card card-sm mb-sm shadow">
						<img src="{{ asset('public/images/homepage_icon_personalize_learning_experience.png') }}" alt="Personalized learning experience" class="card-icon">
						<p><b>Move as fast as the slowest person in a group class</b></p>
					</div>
					<div class="card card-sm mb-sm shadow">
						<img src="{{ asset('public/images/homepage_icon_lessons_around_life_schedule.png') }}" alt="Individual style and learning program" class="card-icon">
						<p><b>Make travel plans for every single lesson</b></p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="mb-xxl">
		<div class="container">
			<header class="section-header align-center">
				<h2 class="section-title lg">A personal guide in your <span class="text-ribbon">journey to another culture</span></h2>
			</header>
			<div class="columns justify-space-between">
				<div class="column col-5 col-12-md text-size-lg">
					<p>A tour guide needs to know what you want to experience and how to get you there. Similarly, your Spanish tutor needs to know the best and the quickest way for you to learn.</p>
					<p>Our class coordinators can match you with the perfect teacher - your "language learning guide". Get the undivided attention of a certified Spanish tutor and the same level of interaction you'd have by being in the room with them.</p>
					<p>We have native tutors from 8 Spanish speaking countries. Learn all the nuances of the language. Ask them about the culture and bring your lessons to life.</p>
				</div>
				<div class="column col-7 col-12-md">
					<img src="{{ asset('public/images/homepage_illustration_friendly_native_speaking_tutors.png') }}" alt="A personal guide in your journey to another culture" style="max-width:500px;margin-left:auto;margin-right:auto;width:100%;">
				</div>
			</div>
			<div class="cta-buttons align-center mt-xxl">
				<a href="#" class="button secondary">Meet our tutors</a>
				<a href="#" class="button primary">Try a 1-to-1 lesson free</a>
				<span class="button-hint">No credit card required</span>
			</div>
		</div>
	</section>

	<section class="mb-xxl pv-xxl section-fill">
		<div class="slider-container">
			<div class="slider container lg">
				<blockquote class="testimonial-feature">
					<p>"My teacher knows how to push me just enough to keep me learning but no so much that I get discouraged. So far it has been a wonderful experience." <cite><span class="text-ribbon">John A. - Judge</span></cite></p>
				</blockquote>
				<blockquote class="testimonial-feature">
					<p>"I change teachers every 3-4 months to get exposed to new teaching styles and I have not had a bad teacher yet." <cite><span class="text-ribbon">Paula P. - College Professor</span></cite></p>
				</blockquote>
				<blockquote class="testimonial-feature">
					<p>"I felt like I was really part of a school and that the people actually cared about me and whether I learned Spanish or not." <cite><span class="text-ribbon">Gina S. - Store Manager</span></cite></p>
				</blockquote>
			</div>
		</div>
	</section>

	<section class="mb-xxl">
		<div class="container">
			<div class="columns">
				<div class="column col-5 col-12-md">
					<header class="section-header">
						<h2 class="section-title lg">Class begins when it suits you</h2>
						<p class="section-subtitle">Fit learning into your busy schedule.</p>
						<a href="#" class="section-button button secondary">Spanish lessons <i class="far fa-angle-right"></i></a>
					</header>
				</div>
				<div class="column col-7 col-12-md">
					<div class="card card-sm mb-sm shadow">
						<img src="{{ asset('public/images/homepage_icon_get_to_know_your_teacher.png') }}" alt="Get to know your teacher" class="card-icon">
						<p>No generic baseline evaluation. We're interested in finding out what you'd like to learn about, why you want to be able to speak Spanish and even what your learning style is.</p>
					</div>
					<div class="card card-sm mb-sm shadow">
						<img src="{{ asset('public/images/homepage_icon_personalize_learning_experience.png') }}" alt="Personalized learning experience" class="card-icon">
						<p>Once we've connected you with your ideal tutor, you'll get your personalized learning program. See progress after every single lesson, move at a pace you're comfortable with.</p>
					</div>
					<div class="card card-sm mb-sm shadow">
						<img src="{{ asset('public/images/homepage_icon_learning_program.png') }}" alt="Individual style and learning program" class="card-icon">
						<p>Whether you want to be challenged and pushed to progress, or you need patience and understanding, your lessons will always follow your learning style and individual needs.</p>
					</div>
					<div class="card card-sm mb-sm shadow">
						<img src="{{ asset('public/images/homepage_icon_lessons_around_life_schedule.png') }}" alt="Fit lessons in to your schedule" class="card-icon">
						<p>Balance your workload with other commitments and learn Spanish from your laptop, computer, phone or tablet.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="mb-xxl">
		<div class="container">
			<header class="section-header align-center">
				<h2 class="section-title">Your journey to comfortably speaking Spanish starts in just <span class="text-ribbon">4 simple steps</span></h2>
			</header>
			<div class="columns text-align-center mv-xl">
				<div class="column col-3 col-6-md col-12-sm">
					<div class="tile">
						<img src="{{ asset('public/images/homepage_illustration_tell_us_about_yourself.png') }}" alt="Tell us about yourself" class="tile-image">
						<h3 class="h4">Tell us about yourself</h3>
						<p>Let us know about your learning style, what your current Spanish level is and your goals for learning.</p>
					</div>
				</div>
				<div class="column col-3 col-6-md col-12-sm">
					<div class="tile">
						<img src="{{ asset('public/images/homepage_illustration_get_expertly_matched.png') }}" alt="Get expertly matched" class="tile-image">
						<h3 class="h4">Get expertly matched</h3>
						<p>Our class coordinator will pair you up with your perfect tutor based on your profile and preferences.</p>
					</div>
				</div>
				<div class="column col-3 col-6-md col-12-sm">
					<div class="tile">
						<img src="{{ asset('public/images/homepage_illustration_30_min_trial_lesson.png') }}" alt="Take a free 30 min trial lessons" class="tile-image">
						<h3 class="h4">Take a free 30 min trial lesson</h3>
						<p>Get familiar with your tutor and experience what it's like to learn with Live Lingua.</p>
					</div>
				</div>
				<div class="column col-3 col-6-md col-12-sm">
					<div class="tile">
						<img src="{{ asset('public/images/homepage_illustration_convenient_time.png') }}" alt="Schedule future lessons" class="tile-image">
						<h3 class="h4">Schedule future lessons</h3>
						<p>Use our online calendar to pick dates and times that suit you for future lessons.</p>
					</div>
				</div>
			</div>
			<div class="cta-buttons align-center mt-xl">
				<a href="#" class="button secondary">Meet our tutors</a>
				<a href="#" class="button primary">Try a 1-to-1 lesson free</a>
				<span class="button-hint">No credit card required</span>
			</div>
		</div>
	</section>

	<section class="mb-xxl pv-xxl section-fill">
		<div class="container lg">
			<div class="columns">
				<div class="column col-12 col-12-md">
					<blockquote class="testimonial-feature">
						<p>"My Spanish teacher has been great. I have been taking Spanish classes for 2 years now and I just took my first trip to Mexico and was able to talk to everybody." <cite><span class="text-ribbon">James L. - Lawyer</span></cite></p>
					</blockquote>
				</div>
				<!-- <div class="column col-4 col-12-md">
					<blockquote class="testimonial-feature">
						<p>"I had lived in Central America with my parents when I was a child, and used to speak Spanish but as I grew up I forgot. Now I am feeling comfortable speaking again." <cite><span class="text-ribbon">Slovhenia D. - Business</span></cite></p>
					</blockquote>
				</div>
				<div class="column col-4 col-12-md">
					<blockquote class="testimonial-feature">
						<p>"I work with a lot of clients and co-workers in Mexico and have always wanted to be able to speak Spanish with them. Live Lingua has helped make that dream a reality." <cite><span class="text-ribbon">Jeremy S. - Head of Sales</span></cite></p>
					</blockquote>
				</div> -->
			</div>
		</div>
	</section>

	<section class="mb-xxl">
		<div class="container">
			<div class="columns justify-space-between align-center">
				<div class="column col-4 col-12-md">
					<div class="section-header mb-none">
						<img class="section-title-image" src="{{ asset('public/images/homepage_illustration_the_immersion_experience.png') }}" alt="The immersion experience">
						<h2 class="section-title lg">Like learning in a Spanish-speaking country</h2>
						<a href="#" class="button secondary section-button">Free resources &amp; courses <i class="far fa-angle-right"></i></a>
					</div>
				</div>
				<div class="column col-7 col-12-md text-size-lg">
					<p>If you don't live in a Spanish-speaking country, you probably know how hard it can be to get to the point where you can speak the language naturally. Especially if you're new to it.</p>
					<p>But maybe you know Spanish vocabulary, and you just can't seem to piece it together without the translator on your phone. An oh, those hand gestures...</p>
					<p>What if on the other hand you take your classes, feel great about your Spanish, then come back to your everyday life, only to realize that if you actually don't use it, you lose it.</p>
					<p>Our goal is for you to experience what it's like to immerse yourself in the Spanish language.</strong> Not only during your lessons, but in your day-to-day. Keeping you engaged and consistently refreshing what you learn, with additional reading resources and audio courses.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="mb-xxl">
		<div class="container">
			<div class="columns image-content-banner">
				<div class="column col-12 col-12-md">
					<img src="https://placehold.it/2800x1250">
				</div>
			</div>
			<div class="columns image-content-overlay">
				<div class="column col-6 col-12-md">
					<div class="section-header">
						<h2 class="section-title">Learning a language brings people together</h2>
						<a href="{{ route('page', ['controller' => 'our-story']) }}" class="section-button button secondary">About us <i class="far fa-angle-right"></i></a>
					</div>
				</div>
				<div class="column col-6 col-12-md text-size-lg mb-lg">
					<p>Laura was a qualified language teacher from Mexico, and Ray was a Peace Corps member originally from the Philippines.</p>
					<p>They co-founded Live Lingua from a first-hand experience of how much language brings people together.</p>
				</div>
				<p class="text-size-lg">What today is Live Lingua, started as a brick & mortar Spanish school in Mexico. Our students were naturally immersed in the language and culture, every single day. Now, we've redesigned it as a Spanish school online, so we're able to bring the language immersion experience to you.</p>
			</div>
		</div>
	</section>

	<div class="container">
		<div class="cta-section primary pv-xl text-align-center">
			<div class="container lg">
				<h2 class="cta-title">Take your first step to <span class="text-ribbon">finally</span> feeling comfortable speaking Spanish</h2>
				<p class="cta-subtitle">Let's connect you with a hand-picked native-speaking tutor today.</p>
				<div class="cta-buttons">
					<a href="#" class="button primary">Try a 1-to-1 lesson free</a><br>
					<span class="button-hint">No credit card required</span>
				</div>
			</div>
		</div>
	</div>