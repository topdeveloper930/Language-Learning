<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use Payment;

class WebhookController extends APIController
{
	public function __invoke( $gateway )
	{
		Payment::gateway( $gateway )->webhook();

		return response('');
	}
}
