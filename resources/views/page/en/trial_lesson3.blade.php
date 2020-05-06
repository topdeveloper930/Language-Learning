@php
    $tplConfig->page_meta_title = 'Trial Lesson';
@endphp

<div class="modal-box trial-class-availibility xl" id="date-time-picker">
	<div class="modal-header">
		<span class="modal-title">Date &amp; times you are available</span>
	</div>
	<div class="modal-content">
		<div class="columns">
			<div class="column col-7 col-12-lg">
				<div id="datepicker-trial-class"></div>
			</div>
			<div class="column col-5 col-12-lg text-align-left">
				<label>Add a time for this date (Timezone: PST)</label>
				<select class="timepicker">
					<option value="16:00">4:00pm</option>
					<option value="17:00">5:00pm</option>
					<option value="18:00">6:00pm</option>
					<option value="19:00">7:00pm</option>
					<option value="20:00">8:00pm</option>
				</select>
				<button class="button primary save-time">Save</button>
				<button class="button modal-close">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal-page">
	<a href="{{  route('page', ['controller' => 'trial-lesson', 'id' => 2]) }}" class="action left"><i class="far fa-long-arrow-left"></i> About you</a>
	<div class="modal-page-steps sm">
		<ul class="steps">
			<li>
				<a href="{{  route('page', ['controller' => 'trial-lesson']) }}" class="tooltip" data-tooltip="Credentials">Credentials</a>
			</li>
			<li>
				<a href="{{  route('page', ['controller' => 'trial-lesson', 'id' => 2]) }}" class="tooltip" data-tooltip="About you">About you</a>
			</li>
			<li class="active">
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
			<h1 class="modal-title">Your lesson</h1>
			<p class="modal-subtitle">Help us pick out your perfect free lessson</p>
		</header>
		<form action="{{  route('page', ['controller' => 'trial-lesson', 'id' => 4]) }}" method="">
			<div class="form-group">
				<div class="available-datetimes"></div>
				<a href="#date-time-picker" class="button display-block">Choose time &amp; date availability</a>
			</div>
			<div class="form-group">
				<select>
					<option></option>
					<option>Values here</option>
				</select>
				<label>Your current Spanish level</label>
			</div>
			<div class="form-group">
				<select>
					<option></option>
					<option>Values here</option>
				</select>
				<label>Your preferred Spanish accent</label>
			</div>
			<div class="form-group">
				<select>
					<option></option>
					<option>Values here</option>
				</select>
				<label>Teacher gender preference</label>
			</div>
			<div class="form-group">
				<button class="button primary" type="submit">Next</button>
			</div>
		</form>
	</div>
</div>