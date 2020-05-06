@php
	$tplConfig->page_meta_title = 'Costs page';
	$tplConfig->current_menu	= 'costs';
@endphp
<section class="mb-xl">
	<div class="container">
		<header class="page-header position-centered">
			<h1 class="page-title">Private Spanish tutoring finally got affordable. And convenient.</h1>
			<p class="page-subtitle">See how much it is to learn by selecting your preferred program.</p>
		</header>
	</div>
</section>

<section class="mb-xl">
	<div class="container">
		<span class="section-label text-align-center">Select a program</span>
		<div class="columns pricing-selections align-stretch">
			<div class="column col-4 col-12-sm">
				<a href="#pricing" class="selected" data-program="Standard">
					<img src="{{ asset('public/images/pricing_page_standard.png') }}" alt="">
					<h4>Standard</h4>
					<p>Description</p>
				</a>
			</div>
			<div class="column col-4 col-12-sm">
				<a href="#pricing"  data-program="Exam">
					<img src="{{ asset('public/images/pricing_page_exam.png') }}" alt="">
					<h4>Exam</h4>
					<p>Description</p>
				</a>
			</div>
			<div class="column col-4 col-12-sm">
				<a href="#pricing"  data-program="Professional">
					<img src="{{ asset('public/images/pricing_page_specialized_alt.png') }}" alt="">
					<h4>Professional</h4>
					<p>Description</p>
				</a>
			</div>
		</div>
	</div>
</section>


<div class="modal-box lg" id="pricing-structure">
	<a href="#" class="modal-close fal fa-times"></a>
	<div class="modal-content">
		<nav class="tab-navigation">
			<a href="#pricing-standard" class="active">Standard</a>
			<a href="#pricing-dele">DELE</a>
			<a href="#pricing-ecele">ECELE</a>
			<a href="#pricing-professional">Professional</a>
			<a href="#pricing-specialized">Specliaized courses</a>
			<a href="#pricing-groups">Groups</a>
		</nav>
		<div class="tab-content">
			<div id="pricing-standard">
				<table>
					<tr>
						<th>1-9 hours</th>
						<td>$16.00</td>
					</tr>
					<tr>
						<th>10-19 hours</th>
						<td>$15.00</td>
					</tr>
					<tr>
						<th>20-29 hours</th>
						<td>$14.00</td>
					</tr>
					<tr>
						<th>30-39 hours</th>
						<td>$13.00</td>
					</tr>
					<tr>
						<th>40-49 hours</th>
						<td>$12.00</td>
					</tr>
					<tr>
						<th>50+ hours</th>
						<td>$10.99</td>
					</tr>
				</table>
			</div>
			<div id="pricing-dele">
			</div>
			<div id="pricing-ecele">
			</div>
			<div id="pricing-professional">
			</div>
			<div id="pricing-specialized">
			</div>
			<div id="pricing-groups">
				<p class="text-align-center">Looking for group classes? <a href="#">Contact us</a>.</p>
			</div>
		</div>
	</div>
</div>

<section class="mb-xxl" id="pricing">
	<div class="container md">
		<header class="section-header position-centered">
			<!-- <h2 class="section-title">Connect with your perfect tutor in a free trial lesson, then you can purchase more hours from your dashboard.</h2> -->
			<p class="section-subtitle">Connect with your perfect tutor in a <a href="#">free trial lesson</a>, then you can purchase more hours from your dashboard.</p>
		</header>
	</div>
	<div class="container md">
		<div class="pricing-slider">
			<div class="range-labels">
					<label>1 Hour</label>
					<span>1 Hour</span>
					<span>100 Hours</span>
				</div>
			<input type="range" class="pricing-slider" min="1" max="100" value="1" step="1">
			<div class="pricing-figure">$<span>0</span></div>
			<div class="pricing-calculations">
				<span class="pricing-hourly">$<span></span>  per hour <br><small class="savings"></small><br> <a href="#pricing-structure">See pricing structure</a></span>
			</div>
			<div class="cta-buttons">
				<a href="#" class="button primary">Take a free trial lesson</a><br>
				<span class="button-hint">No credit card required</span>
			</div>
		</div>
	</div>
</section>

<section class="mb-xxl pv-xxl section-fill">
	<div class="container lg">
		<div class="columns">
			<div class="column col-12 col-12-md">
				<blockquote class="testimonial-feature">
					<p>"It is like being able to take my Spanish classroom with me. I believe this is the future of language learning and Live Lingua is leading the way." <cite>Bronn A. - Tech Support</cite></p>
				</blockquote>
			</div>
			<!-- <div class="column col-4 col-12-md">
				<blockquote class="testimonial-feature">
					<p>"I love the ability to have Spanish tutoring without the need to commit to set hours each week. This gives me the flexibility I need to be able to commit to my studies." <cite>Erhan T. - Chef</cite></p>

				</blockquote>
			</div>
			<div class="column col-4 col-12-md">
				<blockquote class="testimonial-feature">
					<p>"I did not have time to spend hours in traffic everyday to get to a physical school and Live Lingua worked great for me. I have been with them for over 3 years and now speak Spanish very well." <cite>Joost V. - Web Developer</cite></p>
				<blockquote>
			</div> -->
		</div>
	</div>
</section>



<section class="mb-xxl">
	<div class="container">
		<header class="section-header position-centered">
			<h2 class="section-title">All lessons come with</h2>
		</header>
		<div class="columns mb-xl align-stretch">
			<div class="column col-6 col-12-md">
				<div class="card card-sm shadow">
					<i class="fad fa-check-circle card-icon"></i>
					<div class="card-body">
						<h3 class="h5">Personalized lesson plans</h3>
						<p>Discuss your learning goals with your hand-picked tutor and they will develop a custom curriculum you can follow to achieve them easily.</p>
					</div>
				</div>
			</div>
			<div class="column col-6 col-12-md">
				<div class="card card-sm shadow">
					<i class="fad fa-check-circle card-icon"></i>
					<div class="card-body">
						<h3 class="h5">Regular progress reports</h3>
						<p>Your tutor will provide you with a personalized progress report after every 20 hours of lessons, so you can see how much you're improving.</p>
					</div>
				</div>
			</div>
			<div class="column col-6 col-12-md">
				<div class="card card-sm shadow">
					<i class="fad fa-check-circle card-icon"></i>
					<div class="card-body">
						<h3 class="h5">Schedule lessons when it suits you</h3>
						<p>With our online calendar that syncs with Google calendar, you can quickly and easily book lessons that fit in with your schedule.</p>
					</div>
				</div>
			</div>
			<div class="column col-6 col-12-md">
				<div class="card card-sm shadow">
					<i class="fad fa-check-circle card-icon"></i>
					<div class="card-body">
						<h3 class="h5">Flexible lesson duration</h3>
						<p>Choose how long you want your next lesson to be - 30 mins, 1 hour, 1.5 hours or 2 hours.</p>
					</div>
				</div>
			</div>
			<div class="column col-6 col-12-md">
				<div class="card card-sm shadow">
					<i class="fad fa-check-circle card-icon"></i>
					<div class="card-body">
						<h3 class="h5">Learning materials provided</h3>
						<p>As a Live Lingua student, you'll get access to learning materials provided to you by your tutor, at no extra charge.</p>
					</div>
				</div>
			</div>
			<div class="column col-6 col-12-md">
				<div class="card card-sm shadow">
					<i class="fad fa-check-circle card-icon"></i>
					<div class="card-body">
						<h3 class="h5">Your purchased lessons never expire</h3>
						<p>A break is much needed sometimes. Don't worry, all the hours of lessons you've purchased will still be available when you come back.</p>
					</div>
				</div>
			</div>
			<div class="column col-6 col-12-md">
				<div class="card card-sm shadow">
					<i class="fad fa-check-circle card-icon"></i>
					<div class="card-body">
						<h3 class="h5">Hands-on customer support</h3>
						<p>If you have any questions or require support in any area, then simply drop us an email or give us a call and we will quickly help you.</p>
					</div>
				</div>
			</div>
			<div class="column col-6 col-12-md">
				<div class="card card-sm shadow">
					<i class="fad fa-check-circle card-icon"></i>
					<div class="card-body">
						<h3 class="h5">Regular Spanish webinars</h3>
						<p>You will gain access to our education webinars, where you can get extra Spanish training from language learning experts.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container md">
		<div class="text-align-center">
			<h5>Don't forget!</h5>
			<p>You can always access the free audio courses and free reading resources available on the Live Lingua website anytime you want to brush up on your vocabulary or comprehension skills.</p>
		</div>
	</div>
</section>

<div class="container mb-xxl">
	<div class="cta-section secondary pv-xl text-align-center">
		<div class="container lg">
			<div class="image-fan mb-lg">
				<a href="#" target="_blank"><img src="{{ asset('public/images/stc/Karim.jpg') }}" alt=""></a>
				<a href="#" target="_blank"><img src="{{ asset('public/images/stc/Durga.jpg') }}" alt=""></a>
				<a href="#" target="_blank"><img src="{{ asset('public/images/stc/Ibrahima.jpg') }}" alt=""></a>
				<a href="#" target="_blank"><img src="{{ asset('public/images/stc/Kylliewyn.jpg') }}" alt=""></a>
			</div>
			<h2 class="cta-title">Learn Spanish and help a child in need</h2>
			<p class="cta-subtitle">When you learn with Live Lingua you enable us to sponsor 10 children through our <a href="#" target="_blank">program with Save the Children</a>. </p>
			<div class="cta-buttons">
				<a href="#" class="button primary">Try a 1-to-1 lesson free</a><br>
				<span class="button-hint">No credit card required</span>
			</div>
		</div>
	</div>
</div>

<section class="mb-xxl">
	<div class="container lg">
		<header class="section-header position-centered">
			<h2 class="section-title">FAQ</h2>
		</header>
		<div class="accordion faq">
			<a href="javascript:void(0);" class="accordion-title">Can I purchase lessons on this page?</a>
			<div class="accordion-content">
				<p>We want to ensure that you are connected with the perfect tutor before you commit to paying.</p>
				<p>Unlike other tutor directories, where you can purchase a block of lessons with a tutor to find that they aren't right for you.</p>
				<p>Once you've taken your free trial and we've matched you with the tutor who is right for your individual needs and preferences, you can purchase hours of lessons easily on the ‘Student Dashboard'.</p>
				<p>Or, if you want to try lessons with a different tutor or even multiple tutors at the same time, simply contact your Class Coordinator who will email you as soon as you sign up for your trial, and will be more than happy to help.</p>
			</div>
		</div>
		<div class="accordion faq">
			<a href="javascript:void(0);" class="accordion-title">What if I don't think my tutor is a good fit?</a>
			<div class="accordion-content">
				<p>No problem! We can match you up with a new tutor or you can search through our tutor database to find someone you'll be more suited to. Your purchased lesson hours can be used with any tutor. Some of our students change tutors every 3-6 months to get exposed to a variety of teaching styles and accents, so no need to feel guilty about changing!</p>
				<p>Also, if you weren't 100% on your trial lesson experience, then we'll arrange a second trial with a different tutor at no extra charge. Simply drop us an email and we'll be more than happy to help.</p>
			</div>
		</div>
		<div class="accordion faq">
			<a href="javascript:void(0);" class="accordion-title">What do I get for referring someone?</a>
			<div class="accordion-content">
				<p>Earn $15 in free lesson credit and the person you refer also gets 10% off their first set of lessons.</p>
				<p><strong>How it works;</strong></p>
				<p>When you sign-up to Live Lingua, you will notice on your Student Dashboard our ‘Referral' section. Here you will find your unique referral code as well as multiple ways to share the code with friends and family.</p>
				<p>You can then share the code through our system, on your social media or even through your personal email..</p>
				<p>They purchase lessons (1 hour minimum) and enter the unique referral code.</p>
				<p>They will get a 10% discount for that purchase and your account will instantly be  credited $15.  This amount will automatically be deducted from your next set of lessons.</p>
			</div>
		</div>
		<div class="accordion faq">
			<a href="javascript:void(0);" class="accordion-title">Will my tutor still be available if I take time off?</a>
			<div class="accordion-content">
				<p>We understand that some of our students have other commitments and sometimes need to take time out of their learning, which is why we offer lesson hours that never expire.</p>
				<p>When you're ready to start learning again, simply book a time with your tutor on your online calendar and they'll be more than happy to pick up where you left off!</p>
			</div>
		</div>
		<div class="accordion faq">
			<a href="javascript:void(0);" class="accordion-title">How long are the lessons?</a>
			<div class="accordion-content">
				<p>We offer lessons at a minimum of 30 minutes up to 2 hours. As long as your selected time falls on a 30-min time frame, then your lesson can be as long or as short as you need!</p>
			</div>
		</div>
		<div class="accordion faq">
			<a href="javascript:void(0);" class="accordion-title">How long will it take for me to become fluent?</a>
			<div class="accordion-content">
				<p>We don't want to give false promises to our customers, how long it will take usually depends on how committed you are to learning, what level you're at now and how you are learning.</p>
				<p>We have taught over 17000+ students and created the most fertile learning environment online with; 1-to-1 tutors, audio materials, reading materials and specialized courses. All to create a language immersion experience.</p>
				<p>Take a free trial lesson with one of our experienced and qualified tutors and they will give you their professional opinion!</p>
			</div>
		</div>
		<div class="accordion faq">
			<a href="javascript:void(0);" class="accordion-title">Can I cancel lessons?</a>
			<div class="accordion-content">
				<p>You may cancel up to 24 hours before the lesson starts at no charge.</p>
			</div>
		</div>
		<div class="accordion faq">
			<a href="javascript:void(0);" class="accordion-title">Can I take lessons in a group?</a>
			<div class="accordion-content">
				<p>Yes. If you have a group of friends, colleagues or family members who are at a similar level to you and share the same language learning goals, then you can arrange group lessons by contacting us.</p>
				<p>Your friends, colleagues or family will get a 50% discount on the lessons too! So if you pay the full price of an $11 lesson for example, then the other participants will only pay $5.50 to join the same lesson!</p>
			</div>
		</div>
		<div class="accordion faq">
			<a href="javascript:void(0);" class="accordion-title">Can I have multiple tutors at the same time?</a>
			<div class="accordion-content">
				<p>Yes. If your tutor is not available at the hours you need, or if you want to get exposed to different teaching techniques or accents, we can pair you with multiple tutors at the same time.  All tutors have access to the same class dashboard so they can coordinate the classes between each other. Simply contact our customer support.</p>
			</div>
		</div>
	</div>
</section>


<div class="container">
	<div class="brand-list mb-xxl">
		<img src="{{ asset('public/images/brand-list.png') }}" alt="">
	</div>
</div>

<div class="container">
	<section class="cta-section primary pv-xxl text-align-center">
		<div class="container lg">
			<h2 class="cta-title">Ready to begin? Get introduced to immersive learning for free</h2>
			<div class="cta-buttons">
				<a href="#" class="button primary">Try a 1-to-1 lesson free</a><br>
				<span class="button-hint">No credit card required</span>
			</div>
		</div>
	</section>
</div>
