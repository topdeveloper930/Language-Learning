@php
    $tplConfig->page_meta_title = 'Trial Lesson';
@endphp

<div class="modal-page">

	<div class="modal-page-box sm">

		<header>
			<!--<a href="/pages-website/home.php" class="logo"><img src="/library/images/logo-icon.svg"></a>-->
			<h1 class="modal-title h3">Last step</h1>
			<p class="modal-subtitle">Tell us a little more about why you're learning Spanish.</p>
		</header>

		<form action="{{  route('page', ['controller' => 'trial-lesson', 'id' => 7]) }}" method="">
			<div class="form-group">
				<textarea maxlength="1000" rows="7"></textarea>
			</div>
			<div class="form-group">
				<button class="button primary" type="submit">Submit</button>
			</div>
		</form>

	</div>

</div>