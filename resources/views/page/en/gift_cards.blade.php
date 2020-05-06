@php
    $tplConfig->page_meta_title = 'Gift Cards Page';
@endphp
<main id="root" v-cloak>
<section class="mb-lg">
	<div class="container md">
		<header class="page-header position-centered">
			<h1 class="page-title">Give the gift of language</h1>
			<p class="page-subtitle">Gift cards give students credit to use with any Spanish program they choose.</p>
		</header>
	</div>
</section>

<section>
	<div class="container md">

		<p><strong>Group gift card purchases:</strong> If you are purchasing gift cards on behalf of a company and wish to purchase 20 gift cards or more, please contact us directly via our Contact Us page. One of our staff will get back to your right away.</p>

		<hr>

		<form action="{{ route('page', ['controller' => 'gift-card-checkout']) }}" id="" method="post">
			{{ csrf_field() }}
			<input type="hidden" name="discountAmount" id="discountAmount" value="{{ $discountInfo['discountAmount'] }}">
			<fieldset>
				<legend>Gift card</legend>
				<div class="form-group">
					<label for="creditAmount">Amount <a href="#modal-language" class="badge">?</a></label>
					<select id="creditAmount" name="creditAmount">
						<option value="25">$25{{ $discountInfo['discount'] ? sprintf(" (pay only %s US)", 25 * $discountInfo['discountAmount']) : ''}}</option>
						<option value="50" selected="">$50{{ $discountInfo['discount'] ? sprintf(" (pay only %s US)", 50 * $discountInfo['discountAmount']) : ''}}</option>
						<option value="100">$100{{ $discountInfo['discount'] ? sprintf(" (pay only %s US)", 100 * $discountInfo['discountAmount']) : ''}}</option>
						<option value="150">$150{{ $discountInfo['discount'] ? sprintf(" (pay only %s US)", 150 * $discountInfo['discountAmount']) : ''}}</option>
						<option value="200">$200{{ $discountInfo['discount'] ? sprintf(" (pay only %s US)", 200 * $discountInfo['discountAmount']) : ''}}</option>
						<option value="300">$300{{ $discountInfo['discount'] ? sprintf(" (pay only %s US)", 300 * $discountInfo['discountAmount']) : ''}}</option>
						<option value="400">$400{{ $discountInfo['discount'] ? sprintf(" (pay only %s US)", 400 * $discountInfo['discountAmount']) : ''}}</option>
						<option value="500">$500{{ $discountInfo['discount'] ? sprintf(" (pay only %s US)", 500 * $discountInfo['discountAmount']) : ''}}</option>
						<option value="1000">$1000{{ $discountInfo['discount'] ? sprintf(" (pay only %s US)", 1000 * $discountInfo['discountAmount']) : ''}}</option>
					</select>
				</div>
			</fieldset>

			<fieldset>
				<legend>Your details</legend>
				<div class="columns">
					<div class="column col-2 col-4-md col-12-sm">
						<div class="form-group">
							<label>Title</label>
							<select id="purchaser_sex" name="purchaser_sex">
								<option value="Mr.">Mr.<option>
								<option value="">-</option>
								<option value="Mr.">Mr.</option>
								<option value="Ms.">Ms.</option>
								<option value="Mrs.">Mrs.</option>
								<option value="Dr.">Dr.</option>
								<option value="Prof.">Prof.</option>
							</select>
						</div>
					</div>
					<div class="column col-5 col-4-md col-12-sm">
						<div class="form-group">
							<label>First name</label>
							<input type="text" value="Tom" id="purchaser_firstName" name="purchaser_firstName" required>
						</div>
					</div>
					<div class="column col-5 col-4-md col-12-sm">
						<div class="form-group">
							<label>Last name</label>
							<input type="text" value="Green" id="purchaser_lastName" name="purchaser_lastName" required>
						</div>
					</div>
					<div class="column col-12">
						<div class="form-group">
							<label>Email</label>
							<input type="email" value="email@example.com" id="purchaser_email" name="purchaser_email" required>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>Receipient details</legend>
				<div class="columns">
					<div class="column col-2 col-4-md col-12-sm">
						<div class="form-group">
							<label>Title</label>
							<select id="recipient_sex" name="recipient_sex">
								<option value="Mr.">Mr.<option>
								<option value="">-</option>
								<option value="Mr.">Mr.</option>
								<option value="Ms.">Ms.</option>
								<option value="Mrs.">Mrs.</option>
								<option value="Dr.">Dr.</option>
								<option value="Prof.">Prof.</option>
							</select>
						</div>
					</div>
					<div class="column col-5 col-4-md col-12-sm">
						<div class="form-group">
							<label>First name</label>
							<input type="text" value="Tom" id="recipient_firstName" name="recipient_firstName" required>
						</div>
					</div>
					<div class="column col-5 col-4-md col-12-sm">
						<div class="form-group">
							<label>Last name</label>
							<input type="text" value="Green" id="recipient_lastName" name="recipient_lastName" required>
						</div>
					</div>
					<div class="column col-12">
						<div class="form-group">
							<label>Email</label>
							<input type="email" value="email@example.com" id="recipient_email" name="recipient_email" required>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>Delivery options</legend>
				<div class="columns">
					<div class="column col-6">
						<div class="form-group">
							<label>How will it be delivered?</label>
							<select id="deliveryMethod" name="deliveryMethod" v-model="deliveryMethod">
								<option value="SendNow">Send now via email</option>
								<option value="SendLater">Email on a specific date:</option>
								<option value="Print">Print out gift card</option>
							</select>
						</div>
					</div>
					<div class="column col-6" id="deliveryDate_div" v-if="deliveryMethod == 'SendLater'">
						<div class="form-group">
							<label>Send Date</label>
							<input placeholder="mm/dd/yyyy" type="date" id="sendDate" name="sendDate"  min="{{ date("Y-m-d") }}" 
									 :required="deliveryMethod == 'SendLater'">
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>Customization</legend>
				<div class="columns">
					<div class="column col-12">
						<div class="form-group">
							<label>Gift card for:</label>
							<select id="giftCardCategory" name="giftCardCategory">
								<option value="">-</option>
								<option value="Birthday">Birthday</option>
								<option value="Anniversary">Anniversary</option>
								<option value="Valentines">Valentines Day</option>
								<option value="Retirement">Retirement</option>
								<option value="Wedding">Wedding</option>
								<option value="Fathers Day">Fathers Day</option>
								<option value="Mothers Day">Mothers Day</option>
								<option value="Christmas">Christmas</option>
								<option value="New Years">New Years</option>
								<option value="Other">Other</option>
							</select>
						</div>
					</div>
					<div class="column col-12">
						<div class="form-group">
							<label>Your personalized message:</label>
							<textarea id="comments" name="comments" rows="6"  class="form-control"></textarea>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<div class="form-group">
					<button class="button primary" type="submit">Checkout</button>
				</div>
				<div class="form-group">
					<p><small>By clicking on "Checkout" you confirm that you have read and agreed to the Live Lingua terms and conditions.</small></p>
				</div>
			</fieldset>
		</form>


	</div>
</section>
</main>