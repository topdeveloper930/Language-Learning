@php
    $tplConfig->page_meta_title = 'Trial Lesson';
@endphp

<div class="modal-page">

	<div class="modal-page-box sm">

		<header>
			<!--<a href="/pages-website/home.php" class="logo"><img src="/library/images/logo-icon.svg"></a>-->
			<h1 class="modal-title h3">Matching in progress</h1>
			<p class="modal-subtitle">One moment, we're using your answers to match you with your perfect Spanish Teacher.</p>
		</header>

		<i class="fad fa-spinner-third fa-spin register-submission"></i>

	</div>

</div>

<script>
	window.setTimeout(function(){
		window.location.href = "/pages-dashboard/student-schedule-class.php";
	}, 5000);
</script>