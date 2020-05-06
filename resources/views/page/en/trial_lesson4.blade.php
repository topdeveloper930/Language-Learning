@php
    $tplConfig->page_meta_title = 'Trial Lesson';
@endphp
<div class="modal-page">

	<a href="{{  route('page', ['controller' => 'trial-lesson', 'id' => 3]) }}" class="action left"><i class="far fa-long-arrow-left"></i> Your lesson</a>

	<div class="modal-page-steps sm">
		<ul class="steps">
			<li>
				<a href="{{  route('page', ['controller' => 'trial-lesson']) }}" class="tooltip" data-tooltip="Credentials">Credentials</a>
			</li>
			<li>
				<a href="{{  route('page', ['controller' => 'trial-lesson', 'id' => 2]) }}" class="tooltip" data-tooltip="About you">About you</a>
			</li>
			<li>
				<a href="{{  route('page', ['controller' => 'trial-lesson', 'id' => 3]) }}" class="tooltip" data-tooltip="Your lesson">Your lesson</a>
			</li>
			<li class="active">
				<a href="{{  route('page', ['controller' => 'trial-lesson', 'id' => 4]) }}" class="tooltip" data-tooltip="Learning style">Learning style</a>
			</li>
		</ul>
	</div>

	<div class="modal-page-box sm">

		<header>
			<!--<a href="/pages-website/home.php" class="logo"><img src="/library/images/logo-icon.svg"></a>-->
			<h1 class="modal-title">Learning style (1 of 2)</h1>
			<p class="modal-subtitle">We'd like to know more about how you learn best.</p>
		</header>

		<form action="{{  route('page', ['controller' => 'trial-lesson', 'id' => 8]) }}" method="">
			<div class="form-group">
				<div class="range-labels">
					<label>Time alone</label>
					<span>Bores you</span>
					<span>Is vital to you</span>
				</div>
				<input type="range" min="0" max="10" value="">
			</div>
			<div class="form-group">
				<div class="range-labels">
					<label>You tend to plan</label>
					<span>Far ahead</span>
					<span>At the last minute</span>
				</div>
				<input type="range" min="0" max="10" value="">
			</div>
			<div class="form-group">
				<div class="range-labels">
					<label>Time alone</label>
					<span>Bores you</span>
					<span>Is vital to you</span>
				</div>
				<input type="range" min="0" max="10" value="">
			</div>
			<div class="form-group">
				<div class="range-labels">
					<label>You find yelling to be</label>
					<span>Difficult</span>
					<span>Natural</span>
				</div>
				<input type="range" min="0" max="10" value="">
			</div>
			<div class="form-group">
				<div class="range-labels">
					<label>You learn best</label>
					<span>Alone</span>
					<span>In groups</span>
				</div>
				<input type="range" min="0" max="10" value="">
			</div>
			<div class="form-group">
				<div class="range-labels">
					<label>You are a</label>
					<span>Free thinker</span>
					<span>Logical thinker</span>
				</div>
				<input type="range" min="0" max="10" value="">
			</div>
			<div class="form-group">
				<button class="button primary" type="submit">Next</button>
			</div>
		</form>

	</div>

</div>