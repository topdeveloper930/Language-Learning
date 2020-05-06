<?php


namespace App\Traits;


trait InteractsWithGCalendar {
	/**
	 * @var \Google_Http_Batch
	 */
	protected $batch;
	/**
	 * @var \Google_Http_Batch
	 */
	protected $alt_batch;

	/**
	 * @var \Google_Service_Calendar used for batch requests only.
	 */
	protected $g_service;

	/**
	 * @var array
	 */
	protected $results = [];

	protected $processed_events = [];

	protected $needs_new_run = false;

	protected $needs_sync = 0;

	public function getGCalendarService()
	{
		$client = $this->getClient();

		if ( !$client instanceof \Google_Client )
			throw new \RuntimeException( 'Cannot obtain an instance of Google_Client' );

		return new \Google_Service_Calendar( $client );
	}

	/**
	 * @param array|null $config
	 * @return \Google_Client
	 */
	public function getClient( $config = null )
	{
		$config OR $config = config( 'google' );

		$client = new \Google_Client(array_get($config, 'config', []));

		// set application name
		$client->setApplicationName(array_get($config, 'application_name', ''));

		// set oauth2 configs
		$client->setClientId(array_get($config, 'client_id', ''));
		$client->setClientSecret(array_get($config, 'client_secret', ''));
		$client->setRedirectUri(array_get($config, 'redirect_uri', ''));
		$client->setScopes(array_get($config, 'scopes', []));
		$client->setAccessType(array_get($config, 'access_type', 'online'));
		$client->setApprovalPrompt(array_get($config, 'approval_prompt', 'auto'));

		return $this->refreshToken( $client );
	}

	public function flushResults()
	{
		$this->results = $this->processed_events = [];
	}

	public function resetGCalendarService()
	{
		$this->flushResults();
		$this->g_service = $this->batch = $this->alt_batch = null;
		$this->needs_sync = $this->needs_new_run = 0;
	}


	protected function makeBatch()
	{
		if( $this->batch instanceof \Google_Http_Batch )
			return;

		$this->g_service->getClient()->setUseBatch( true );

		$this->batch = $this->g_service->createBatch();
		$this->alt_batch = $this->g_service->createBatch();
	}

	protected function bulkAction( $events, $action, $flush_results = false )
	{
		$counter = 0;

		$batch_max_requests = config( 'google.batch_max_requests', 50 );

		$this->g_service = $this->getGCalendarService();

		$this->makeBatch();

		foreach ( $events as $event ) {
			// Ensure event properly instantiated and not already in the batch.
			if( empty( $event ) OR isset( $this->processed_events[ $event[ 'id' ] ] ) ) continue;

			$event instanceof \Google_Service_Calendar_Event OR $event = $this->getGoogleEvent( $event );

			if( $counter AND !$counter % $batch_max_requests ){
				$this->execute();
				$this->makeBatch();
			}

			$this->batch->add( $this->prepareRequest( $event, $action ), $event->getId() );
			$counter++;
		}

		$this->execute( $flush_results );

		$this->g_service = null;

		return $this->needs_sync;
	}

	/**
	 * @param \Google_Service_Calendar_Event $event
	 * @param $action
	 * @return \Psr\Http\Message\RequestInterface
	 */
	protected function prepareRequest( $event, $action )
	{
		$this->processed_events[ $event->getId() ]  = [ 'action' => $action, 'data' => $event ];

		$params = ( 'insert' == $action )
			? [ $this->getCalendarId(), $event ]
			: [ $this->getCalendarId(), $event->getId() ];

		if( 'patch' === $action )
			array_push( $params, $event );

		return call_user_func_array( [ $this->g_service->events, $action ], $params );
	}

	protected function execute( $flush_results = false )
	{
		if( ! $this->batch instanceof \Google_Http_Batch )
			return $this->results;

		try{
			$results = $this->batch->execute();
			$this->processResults( $results );

			if( $this->needs_new_run ) {
				$this->processResults( $this->alt_batch->execute(), false );
				$this->needs_new_run = false;
			}

			if( $flush_results ) {
				$ret_val = $this->results;
				$this->flushResults();

				return $ret_val;
			}

			return $this->results;
		}
		finally {
			$this->batch = $this->alt_batch = null;
		}
	}

	protected function processResults( $results, $rerun = true )
	{
		foreach ( $results as $id => $result ) {
			$id = str_replace( 'response-', '', $id );

			// In case of rerun true and create or update action with 409 or 404 error respectively
			// we need to update or create those failed events.
			if( $result instanceof \Google_Service_Exception ) {

				if( $rerun AND $this->isCreate( $id ) AND 409 == $result->getCode() ) {

					$request = $this->g_service->events->patch(
						$this->getCalendarId(),
						$id,
						$this->getEventFromPocessed( $id )
					);

					$this->alt_batch->add( $request, $id );
					$this->needs_new_run = true;
				}
				elseif( $rerun AND $this->isUpdate( $id ) AND 404 == $result->getCode() ) {

					$request = $this->g_service->events->insert(
						$this->getCalendarId(),
						$this->getEventFromPocessed( $id )
					);

					$this->alt_batch->add( $request, $id );
					$this->needs_new_run = true;
				}
				else {
					$this->results[ $id ] = [ 'success' => false, 'errors' => $result->getErrors() ];
					$this->needs_sync = 1;
				}
			}
			else {
				$this->results[ $id ] = [ 'success' => true ];
			}
		}
	}

	protected function getEventFromPocessed( $id )
	{
		return $this->processed_events[ $id ][ 'data' ];
	}

	protected function isDelete( $id )
	{
		return isset( $this->processed_events[ $id ] ) AND $this->processed_events[ $id ][ 'action' ] === 'delete';
	}

	protected function isCreate( $id )
	{
		return isset( $this->processed_events[ $id ] ) AND $this->processed_events[ $id ][ 'action' ] === 'insert';
	}

	protected function isUpdate( $id )
	{
		return isset( $this->processed_events[ $id ] ) AND $this->processed_events[ $id ][ 'action' ] === 'patch';
	}

	protected function singleEvent( $event, $action )
	{
		$service = $this->getGCalendarService();

		try{
			if( 'delete' == $action )
				return $service->events->delete( $this->getCalendarId(), $event->getId() );

			$res = $this->{$action . 'Event'}( $service, $event );

			return $res;
		}
		catch ( \Google_Service_Exception $exception ) {
			if( 400 == $exception->getCode() AND strpos( $exception->getMessage(), 'invalid_grant' ) !== false )
				$this->eraseToken();
		}

	}

	protected function insertEvent( $service, $event )
	{
		try {
			return $service->events->insert( $this->getCalendarId(), $event );
		}
		catch ( \Exception $e ) {
			// If code of error is 409, then the event already exists. Try to patch it.
			// Otherwise throw the exception farther up.
			if( 409 == $e->getCode() ) {
				$event->setStatus( 'confirmed' );
				return $this->patchEvent( $service, $event );
			}
			else {
				throw $e;
			}
		}
	}

	protected function patchEvent( $service, $event )
	{
		try {
			return $service->events->patch( $this->getCalendarId(), $event->getId(), $event );
		}
		catch ( \Exception $e ) {
			// If code of error is 404, then the event not found. Try to create it.
			// Otherwise throw the exception.
			if( 404 == $e->getCode() )
				return $this->insertEvent( $service, $event );
			else
				throw $e;
		}
	}

	protected function obtainToken( $auth_code )
	{
		$client = $this->getClient();
		$client->fetchAccessTokenWithAuthCode( $auth_code );

		return $client->getAccessToken();
	}

	/**
	 * Stub methods
	 */
	abstract protected function getCalendarId();

	abstract protected function refreshToken( $client );

	abstract protected function getGoogleEvent( $ev );

	abstract protected function eraseToken();
}