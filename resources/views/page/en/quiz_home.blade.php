@php
	$tplConfig->page_meta_title = 'Free Learning Style Quiz';
	$tplConfig->page_meta_description = 'What is your learning style? Knowing this can be the key to learning a new language or anything else. Our free learning style quiz can point you in the right direction.';
@endphp

<main>
<section class="mb-lg">
	<div class="container">
		<header class="page-header position-centered">
			<h1 class="page-title">Free Learning Style Quiz</h1>
			<p class="page-subtitle">How do you learn best?</p>
		</header>
	</div>
</section>

<section>
	<div class="container md text-align-center">
		<p>Knowing your personal language learning style can be the <u>difference</u> between <b>reaching your goal</b> of learning another language (or anything) and <b>not</b>.  With that in mind, here we have created the <u>J-KAV&#8482; learning style quiz</u> to help you learn what works for you and what does not.  Just choose the language you want to learn to take the correct learning style quiz.</p>

		<hr>
		<h3>Language Specific <b>Learning Style</b> Quiz</h3>
		<p><i>If you want to find out what is the best learning style for you to learn a <u>specific language</u>, click on the language below to start the custom quiz for that language.</i></p>

		<a href="{{ route('page', ['controller' => 'quiz', 'id' => 'general']) }}" class="mb-xs button primary" title="What is the best way to learn for you?">General</a>

		@foreach($languageList as $l)
			<a href="{{ route('page', ['controller' => 'quiz', 'id' => strtolower($l->name)]) }}" class="mb-xs button secondary" title="What is the best way to learn {{ $l->name }} for you?">{{ $l->name }}</a>
		@endforeach

		<hr>
		<p>Based on the results of the Live Lingua J-KAV&#8482; quiz you will can find out which of the <b>48 different learning</b> styles best match you. The results will help you figure out which learning styles and techniques work best for you and which don't so you can take your learning into your own hands.  If you have any questions or suggestions, please don't hesitate to <a href="https://www.livelingua.com/contact-us.php" rel="nofollow">contact us</a>.</p>


	</div>
</section>
</main>