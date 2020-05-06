<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Http\Requests\API\ActiveCampaignRequest;

class ActiveCampaignContactController extends APIController
{
	public function index( ActiveCampaignRequest $request )
	{
		return response('here we go');
	}


	/**
	 * Show available credits of the student for the course.
	 *
	 * @param  ActiveCampaignRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function show( ActiveCampaignRequest $request )
	{
		return response( 'show method' );
	}
}
