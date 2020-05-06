@php
    $tplConfig->page_meta_title = 'Trial Lesson';
@endphp
<div class="modal-page">

	<a href="{{  route('page', ['controller' => 'trial-lesson']) }}" class="action left"><i class="far fa-long-arrow-left"></i> Credentials</a>

	<div class="modal-page-steps sm">
		<ul class="steps">
			<li>
				<a href="{{  route('page', ['controller' => 'trial-lesson']) }}" class="tooltip" data-tooltip="Credentials">Credentials</a>
			</li>
			<li class="active">
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
			<!--<a href="/pages-website/home.php" class="logo"><img src="/library/images/logo-icon.svg"></a>-->
			<h1 class="modal-title">About you</h1>
			<p class="modal-subtitle">We use this information to customise your lessons</p>
		</header>

		<form action="{{  route('page', ['controller' => 'trial-lesson', 'id' => 3]) }}" method="">
			<div class="form-group">
				<input type="text">
				<label>Your Skype ID</label>
			</div>
			<div class="form-group">
				<select>
					<option></option>
					<option>Values here</option>
				</select>
				<label>Your current timezone</label>
			</div>
			<div class="form-group">
				<select>
					<option></option>
					<option>Values here</option>
				</select>
				<label>Your age</label>
			</div>
			<div class="form-group">
				<select>
					<option></option>
					<option>Values here</option>
				</select>
				<label>Why are you learning Spanish?</label>
			</div>
			<div class="form-group">
				<button class="button primary" type="submit">Next</button>
			</div>
		</form>

	</div>

</div>