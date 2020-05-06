<?php

namespace App;

use App\Traits\InteractsWithGCalendar;
use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class CalendarExternal extends Model implements Syncable
{
	use InteractsWithGCalendar;

	const EXTERNAL_EVENT_TMPLT = '[%s Calendar Event]';

    protected $table = 'calendar_external';

    protected $guarded = [];

	public $timestamps = false;

	/**
	 * Obtain Student or Teacher - owner of the calendar.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function owner()
	{
		return $this->belongsTo( ucfirst( $this->user_type ), 'user_id', $this->user_type . 'ID' );
	}

	public function events()
	{
		return $this->hasMany( Event::class, $this->user_type . 'ID', 'user_id' );
	}

	public function scheduledClasses()
	{
		return $this->events()
		            ->where( 'active', Event::ACTIVE )
		            ->where( 'entryType', 'Student' )
		            ->where( 'eventStart', '>', gmdate( 'Y-m-d H:i:s' ) )
					->get();
	}

	public function updateToken( $auth_code )
	{
		return $this->update([ 'access_token' => json_encode( $this->obtainToken( $auth_code ) ) ]);
	}

	public function createWithAuthToken( $data )
	{

		$data[ 'access_token' ] = json_encode( $this->obtainToken( $data[ 'authentication_code' ] ) );

		unset( $data[ 'authentication_code' ] );
		return $this->create( $data );
	}

	/**
	 * @param $service \Google_Service_Calendar
	 * @param $event \Google_Service_Calendar_Event
	 * @param $action string
	 *
	 * @throws \Exception
	 * @return \Google_Service_Calendar_Event|bool
	 */
	public function syncSingleEvent( $event, $action )
	{
		if( $this->access_token )
			return $this->singleEvent( $event, $action );

		return false;
	}

	public function studentSync()
	{
		$needs_sync = $this->bulkAction( $this->scheduledClasses(), 'insert', true );

		$this->update([ 'needs_sync' => $needs_sync ]);
	}

	public function teacherSync()
	{
		// TODO
	}

	/**
	 * Just entry point to call appropriate method.
	 * @param $service
	 */
	public function sync()
	{
		$this->{$this->user_type . 'Sync'}();
	}

	protected function getCalendarId()
	{
		return $this->provider_cal_id;
	}

	/**
	 * @param $client \Google_Client
	 *
	 * @return \Google_Client
	 * @throws \Exception
	 */
	protected function refreshToken( $client )
	{
		if( !$this->access_token )
			return $client;

		$client->setAccessToken( $this->access_token );

		try{
			if ( $client->isAccessTokenExpired() ) {
				$client->fetchAccessTokenWithRefreshToken();
				$this->update([ 'access_token' => json_encode( $client->getAccessToken() ) ]);
			}

			return $client;
		}
		catch ( \Exception $e ) {
			$this->update([ 'access_token' => '' ]);
			throw $e;
		}
	}

	/**
	 * @param Event $ev
	 *
	 * @return mixed
	 */
	protected function getGoogleEvent( $ev )
	{
		return $ev->asGoogleEvent( 'student' == $this->user_type );
	}

	protected function eraseToken()
	{
		$this->update([ 'access_token' => '', 'needs_sync' => 1 ]);
	}
}
