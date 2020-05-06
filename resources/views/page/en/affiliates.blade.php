@php
    $tplConfig->page_meta_title = 'Affilliates Page';
@endphp

<section class="mb-xl">
	<div class="container">
		<header class="page-header position-centered">
			<h1 class="page-title">Add a link. Earn money.</h1>
			<p class="page-subtitle">Live Lingua is an award-winning immersive online language school - you can find out more <a href="{{ route('page', ['controller' => 'our-story']) }}">about us here</a>.</p>
		</header>
	</div>
</section>

<section>
	<div class="container md">
		<p>We're looking for top bloggers and website owners to join our affiliate program. We're growing our affiliate program to include top bloggers, publications, and websites that are interested in working alongside us to grow.</p>

		<p>We'll provide you with links, banners, and ongoing support to ensure making money is easy and turnkey as possible – and we'll work to become a trusted affiliate partner and reliable source of income for your blog in the process.</p>

		<h2>10-15% Monthly Commissions</h2>

		<p>We have a fantastic program which offers monthly recurring commissions ranging from 10-15%, for the entire time a customer buys from us.  This means that when a customer signs up through your affiliate link, you make a cut of the profits every month for as long as they are a student.</p>

		<p>If you're interested in talking through this some more, we're happy to answer any questions you have or provide more information. We'll get the ball rolling whenever you're ready. We customize our approach for each individual affiliate based on his/her needs.</p>

		<p>Simply contact us by completing the short form below and we can get started.</p>

		<hr>

		<form action="{{ route('page', ['controller' => 'affiliates']) }}" id="email-form" method="post">
			{{ csrf_field() }}
			<div class="form-row">
				<div class="columns">
					<div class="column col-6 col-12-md">
						<label>Your name</label>
						<input type="text" name="user_name" maxlength="150" required>
					</div>
					<div class="column col-6 col-12-md">
						<label>Your email</label>
						<input type="email" name="user_email" maxlength="255" required>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="columns">
					<div class="column col-12">
						<label>How will you be marketing? (website, podcasts, etc)</label>
						<textarea name="note" required maxlength="500" minlength="20"></textarea>
					</div>
				</div>
			</div>
			<div class="form-row">
				<button class="button primary" type="submit">Send</button>
			</div>
		</form>

	</div>
</section>