@php
    $tplConfig->page_meta_title = 'Faq Page';
@endphp

<section class="mb-xl">
	<div class="container">
		<header class="page-header position-centered">
			<h1 class="page-title">Have a question?</h1>
			<p class="page-subtitle">See if we have the right answer here.</p>
		</header>
	</div>
</section>

<section class="mb-md">
	<div class="container">
		<form class="page-search mb-md" method="GET" action="">
			<input type="text" name="search" placeholder="Search" value="" class="">
			<input type="hidden" name="orderby" value="">
			<input type="hidden" name="order" value="DESC">
			<!-- control page number here -->
			<input type="hidden" name="pg" value="">
		</form>
	</div>
</section>

<div class="hidden-menu left" id="faq-categories">
	<nav class="nav-menu">
		<span class="nav-label">My question is for</span>
		<a href="#" class="active">Students</a>
		<a href="#">Teachers</a>
		<span class="nav-label">Topics</span>
		<a href="#" class="active">All topics</a>
		<a href="#">General info</a>
		<a href="#">Lessons</a>
		<a href="#">Costs and payments</a>
		<a href="#">Credits and hours</a>
		<a href="#">Cancellations and refunds</a>
		<a href="#">Teacher info</a>
		<a href="#">Gift cards</a>
		<a href="#">Live Lingua Project</a>
		<a href="#">Password help</a>
		<a href="#">Scheduling classes</a>
		<a href="#">Getting in touch</a>
	</nav>
</div>

<section class="mb-xxl">
	<div class="container">
		<a href="#faq-categories" class="button secondary show-md mb-lg">Categories</a>
		<div class="columns">
			<aside class="column col-3 col-12-sm sticky-sidebar hide-md">
				<nav class="nav-menu">
					<span class="nav-label">My question is for</span>
					<a href="#" class="active">Students</a>
					<a href="#" class="">Teachers</a>
					<span class="nav-label">Topics</span>
					<a href="#" class="active">All topics</a>
					<a href="#">General info</a>
					<a href="#">Lessons</a>
					<a href="#">Costs and payments</a>
					<a href="#">Credits and hours</a>
					<a href="#">Cancellations and refunds</a>
					<a href="#">Teacher info</a>
					<a href="#">Gift cards</a>
					<a href="#">Live Lingua Project</a>
					<a href="#">Password help</a>
					<a href="#">Scheduling classes</a>
					<a href="#">Getting in touch</a>
				</nav>
			</aside>
			<div class="column col-9 col-12-sm">
				<div class="accordion faq">
					<a href="javascript:void(0);" class="accordion-title">Are there any countries that Live Lingua cannot work with?</a>
					<div class="accordion-content">
						<p>Unfortunately, we do not accept payments from students in Iran. Additionally, Skype functionality has been restricted in the UAE, so classes from there are not possible to be taught.</p>
					</div>
				</div>
				<div class="accordion faq">
					<a href="javascript:void(0);" class="accordion-title">I want to study more than one language at the same time. Is this recommended?</a>
					<div class="accordion-content">
						<p>While we do not recommend studying two languages at the same time as most people tend to get their wires crossed, we are happy to make it work for you if it is part of your language learning goals. If you do learn more than one language, we find that people experience less confusion when they study unrelated languages like Spanish and Japanese or Italian and Chinese.</p>
						<p>We also find that if you already speak two or more languages, then you may not have as many problems since your mind is already wired to differentiate between languages. But, of course, it always depends on the student.</p>
					</div>
				</div>
				<div class="accordion faq">
					<a href="javascript:void(0);" class="accordion-title">How long do the lessons usually last?</a>
					<div class="accordion-content">
						<p>While the free trial classes usually last 30 minutes, each paid class can be as long or as short as you want, permitting that the teacher has approved the time and can accommodate your request. Generally, though, lessons are 60-minutes-long.</p>
					</div>
				</div>
				<div class="accordion faq">
					<a href="javascript:void(0);" class="accordion-title">What if I'm running late for my lesson? How long will my teacher wait for me on Skype?</a>
					<div class="accordion-content">
						<p>Your teacher is required to wait 15 minutes from the start time of the class for you to connect. If, after 15 minutes, you have not connected to Skype to start the class, your teacher will treat the class as having been taken (charging you in full) if it is a paid class. For a trial class, they will mark you as a No Show.</p>
					</div>
				</div>
				<div class="accordion faq">
					<a href="javascript:void(0);" class="accordion-title">What if my teacher and I are having connectivity issues?</a>
					<div class="accordion-content">
						<p>If a class gets cancelled due to technical problems out of anyone's control such as weather, power outage, etc., the class will be made up. If the connectivity issue happens after a part of the class takes place, say 30 minutes, then the amount of class time taken will be charged and the rest will be made up at a later time.</p>
					</div>
				</div>
				<div class="accordion faq">
					<a href="javascript:void(0);" class="accordion-title">How much should I expect to pay each month for classes?</a>
					<div class="accordion-content">
						<p>As Live Lingua charges per hour, the cost per month depends on how many hours you'd like to purchase as well as which course you sign up for. The more hours you purchase together, the cheaper each hour gets. You can find a full breakdown of our hourly costs for each language and course here.</p>
					</div>
				</div>
				<div class="accordion faq">
					<a href="javascript:void(0);" class="accordion-title">If I want to share my class with an additional student, how much more does the class cost per person?</a>
					<div class="accordion-content">
						<p>With each extra person, the class cost increases by 50 percent. For example, if one person pays $14 USD per hour, then the second person would pay $7 USD per hour, totaling the class cost to $21 USD per hour.</p>
					</div>
				</div>
				<div class="accordion faq">
					<a href="javascript:void(0);" class="accordion-title">How are payments made?</a>
					<div class="accordion-content">
						<p>Payments can be made via credit card, PayPal, wire transfer or US dollar check.</p>
					</div>
				</div>
				<div class="accordion faq">
					<a href="javascript:void(0);" class="accordion-title">How do I pay for classes?</a>
					<div class="accordion-content">
						<p>Enrolling in additional lessons is easy. Just log into your Live Lingua Student dashboard here. Once you get in, click on the ‘Purchase Lesson' button and follow the instructions to pay through our secure online portal.</p>
					</div>
				</div>
				<div class="accordion faq">
					<a href="javascript:void(0);" class="accordion-title">Can I divide my credits between two or more teachers?</a>
					<div class="accordion-content">
						<p>Of course! Your credits are yours to do with however you wish.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="mb-xxl text-align-center">
	<div class="container">
		<h3 class="mb-md">Couldn't find the answer you were looking for?</h3>
		<a href="#" class="button secondary">Contact us</a>
	</div>
</section>

<div class="container">
	<div class="cta-section primary pv-xl text-align-center">
		<div class="container lg">
			<h2 class="cta-title">Found your answer and ready to start learning?</h2>
			<p class="cta-subtitle">Connect with your hand-picked certified tutor now.</p>
			<div class="cta-buttons">
				<a href="#" class="button primary">Try a 1-to-1 lesson free</a><br>
				<span class="button-hint">No credit card required</span>
			</div>
		</div>
	</div>
</div>