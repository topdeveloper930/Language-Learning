@php
    $tplConfig->page_meta_title = 'Trial Lesson';
@endphp

<div class="modal-page">

	<a href="/" class="action left"><i class="far fa-long-arrow-left"></i> Home</a>

	<div class="modal-page-steps sm">
		<ul class="steps">
			<li class="active">
				<a href="{{  route('page', ['controller' => 'trial-lesson']) }}" class="tooltip" data-tooltip="Credentials">Credentials</a>
			</li>
			<li>
				<a href="{{  route('page', ['controller' => 'trial-lesson', 'id' => 2]) }}" class="tooltip" data-tooltip="About you">About you</a>
			</li>
			<li>
				<a href="{{  route('page', ['controller' => 'trial-lesson', 'id' => 3]) }}" class="tooltip" data-tooltip="Your lesson">Your lesson</a>
			</li>
			<li>
				<a href="{{  route('page', ['controller' => 'trial-lesson', 'id' => 4]) }}" class="tooltip" data-tooltip="Learning style">Learning style</a>
			</li>
		</ul>
	</div>

	<div class="modal-page-box sm">

		<header>
			<!-- <a href="/pages-website/home.php" class="logo"><img src="/library/images/logo-icon.svg"></a> -->
			<h1 class="modal-title">Credentials</h1>
			<p class="modal-subtitle">Let us know how you'd like to log in</p>
		</header>

		<form action="{{  route('page', ['controller' => 'trial-lesson', 'id' => 2]) }}" method="">
			<div class="form-group">
				<input type="text">
				<label>Full name</label>
			</div>
			<div class="form-group">
				<input type="email">
				<label for="password">Your email</label>
			</div>
			<div class="form-group">
				<label class="mt-md">
					<input type="checkbox" class="terms-of-service-required"> Agree to the <a href="{{  route('page', ['controller' => 'terms-conditions']) }}" target="_blank">terms of service</a>
				</label>
			</div>
			<div class="form-group">
				<button class="button primary" type="submit">Try a lesson free</button>
			</div>
			<div class="form-group">
				<p>Register with your social media account:</p>
				<div class="columns mt-xs">
					<div class="column">
						<a href="#" class="button facebook">Facebook</a>
					</div>
					<div class="column">
						<a href="#" class="button google">Google</a>
					</div>
				</div>
			</div>
		</form>

	</div>
</div>

