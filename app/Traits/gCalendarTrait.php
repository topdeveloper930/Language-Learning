<?php


namespace App\Traits;


use App\CalendarExternal;

trait gCalendarTrait {


	public function gcalendar()
	{
		return $this->hasOne(CalendarExternal::class, 'user_id', $this->primaryKey )
		            ->where([
			            [ 'user_type', $this->getType() ],
			            [ 'provider', 'google' ]
		            ]);
	}

	public function getToken()
	{
		if( !$this->gcalendar OR !$this->gcalendar->access_token )
			return '';

		$tkn = json_decode( $this->gcalendar->access_token );

		return $tkn->access_token;
	}

	public function hasToken()
	{
		return ( bool ) $this->getToken();
	}

	public function link_gcalendar( $calendar_id, $access_token )
	{
		$gCal = new \App\CalendarExternal([
			'provider' => 'google',
			'user_type' => $this->getType(),
			'provider_cal_id' => $calendar_id,
			'access_token' => $access_token
		]);

		$this->gcalendar()->save( $gCal );
	}
}