@php
	$tplConfig->page_meta_title = 'Gift Cards Checkout Page';
	
		
		if($option['method'] == "SendNow")
			$option['methodText'] = __("gift_card.send_now_text");
		elseif($option['method'] == "SendLater")
			$option['methodText'] = __("gift_card.send_later_text"). " <br><b>".date("l M, jS, Y",strtotime($option['sendDate']))."</b>";
		else {
			# code...
			$option['methodText'] = __("gift_card.print_text");
		}

@endphp
@section('head')
    @parent
<style>
	.twitter-typeahead{
		width: 100%;
	}
	.twitter-typeahead .tt-menu{
		background-color: white;
		width: 100%;
	}
</style>


@endsection
<main id="root" v-cloak>
<section class="mb-lg">
	<div class="container">
		<header class="page-header position-centered">
			<h1 class="page-title">Gift card payment</h1>
		</header>
	</div>
</section>

<section>
	<div class="container md">

		<form action="{{ route('page', ['controller' => 'gift-cards-claim']) }}" method="post">
			{{ csrf_field() }}
			{{-- <div class="columns">
				<div class="column col-6 col-12-md">
					<h3>Credit card information</h3>
					<div class="form-group">
						<label>First name</label>
						<input type="text" required id="firstName" name="firstName">
					</div>
					<div class="form-group">
						<label>Last name</label>
						<input type="text" required id="lastName" name="lastName">
					</div>
					<div class="form-group">
						<div class="payment-cards">
							<i class="fab fa-cc-amex" :class="paymentType == 'amex' ? 'active' : ''"></i>
							<i class="fab fa-cc-discover" :class="paymentType == 'discover' ? 'active' : ''"></i>
							<i class="fab fa-cc-visa" :class="paymentType == 'visa' ? 'active' : ''"></i>
							<i class="fab fa-cc-mastercard" :class="paymentType == 'mastercard' ? 'active' : ''"></i>
						</div>
						<label>Credit card type</label>
						<select id="paymentType" name="paymentType" class="form-control" v-model="paymentType">
							<option value="">-</option>
							<option value="visa">Visa</option>
							<option value="mastercard">Mastercard</option>
							<option value="discover">Discover</option>
							<option value="amex">American Express</option>
						</select>
					</div>
					<div class="form-group">
						<label>Card number</label>
						<input type="text" name="creditCardNumber" id="creditCardNumber" minlength="13" placeholder="ex:1234567890123456" maxlength="19">
					</div>
					<div class="form-group">
						<label>Expiry date</label>
						<div class="columns">
							<div class="column col-6 col-12-md">
								<input type="number" placeholder="MM"  min=1 max=12 required>
							</div>
							<div class="column col-6 col-12-md">
								<input type="number" placeholder="YYYY" min={{ date("Y") }} max={{ date("Y") + 22 }} required>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>CVV code <a href="#cvc" class="badge">?</a></label>
						<input type="text" name="securityCode" id="securityCode" minlength="3" maxlength="4" required>
					</div>
				</div>
				<div class="column col-6 col-12-md">
					<h3>Billing address</h3>
					<div class="form-group">
						<label>Apt number (optional)</label>
						<input type="text" id="street2" name="street2">
					</div>
					<div class="form-group">
						<label>Street</label>
						<input type="text" id="street1" name="street1" required>
					</div>
					
					<div class="form-group">
						<label>Country</label>
						<input type="text" id="country" name="country"  v-model="country">
					</div>
					<div class="form-group">
						<label>States</label>
						<input type="text" id="state" name="state"   v-model="state">
					</div>
					<div class="form-group">
						<label>City</label>
						<input type="text" id="city" name="city" v-model="city">
					</div>

					
					
					<div class="form-group">
						<label>Zip / Postal code</label>
						<input type="text" id="zipCode" name="zipCode">
					</div>
				</div>
			</div> --}}

			<div class="columns mv-lg">
				<div class="column col-6">
					<p><strong>Gift card amount:</strong><br> ${{ $credit['amount'] . ".00" . ($credit['discountAmount'] <> 1 ? sprintf(" (You pay $%0.2f)", $credit['amount'] * $credit['discountAmount']):'') }}</p>
					<p><strong>Recipient:</strong><br> {{ $recipient['name'] . " (" . $recipient['email'] . ")" }}</p>
					<p><strong>Sender:</strong><br> {{ $purchaser['name'] . " (" . $purchaser['email'] . ")" }}</p>
				</div>
				<div class="column col-6">
					<p><strong>Send instructions:</strong><br> {!! $option['methodText'] !!}</p>
					<p><strong>Ocassion:</strong><br> {{ $giftCardCategory }}</p>
					<p><strong>Message:</strong><br> {{ $message }}</p>
				</div>
			</div>




			<h2 class="mb-md h3">@lang('student_purchase.payment')</h2>

                <div class="tab-navigation mb-md">
					<a href="#" class="active" @click.prevent="setMethod('stripe')" id="stripe_tab">@lang('student_purchase.stripe')</a>
                </div>








			<div class="mv-lg">
				<div class="gift-card-example">
					<span class="amount">${{ $credit['amount'] }}</span>
					<span class="key">J4JDDJ334525</span>
					<img src="{{ asset('public/images/live-lingua-gift-card-image.jpg')}}" alt="Gift card">
				</div>
			</div>

			<div class="form-pricing mt-lg mb-md">
				<span class="pricing-breakdown mv-md">
					Gift card amount: <span class="text-success">${{ $credit['amount'] . ".00" }}</span><br>
					@if($credit['discountAmount'] <> 1)
					{!! sprintf(" (You pay : <span>$%0.2f</span>)", $credit['amount'] * $credit['discountAmount']) !!}
					@endif
				</span>
				<span class="pricing-amount">{!! sprintf("$%0.2f", $credit['amount'] * $credit['discountAmount']) !!}</span>
				<small>To review the costs, please see the <a href="{{ route('page', ['controller' => 'costs'] ) }}">pricing page</a></small>
			</div>
			<div class="form-group text-align-center">
				<button type="submit" class="button primary" @click.prevent="sendPayment"><i v-if="loading" class="fal fa-spinner fa-spin"></i> @{{ submitText }}</button>
			</div>

			<div class="notification"><strong>Reviewing Gift Card:</strong> If you are not already a student with Live Lingua we will create a free account for you in our system upon completion of this purchase. Information on how to login to this account will be emailed to you once the transaction is complete. Using the login information in that email you can review & print your gift card purchase at any time.</div>
		</form>

	</div>
</section>
</main>