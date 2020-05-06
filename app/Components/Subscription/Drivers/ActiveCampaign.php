<?php


namespace App\Components\Subscription\Drivers;


use App\Components\Subscription\Classes\ActiveCampaign\Group;
use App\Components\Subscription\Classes\ActiveCampaign\Status;
use papajin\ActiveCampaign\AC\Contact;

class ActiveCampaign extends Driver {

	protected $id;

	protected $subscriber;

	public function setSubscriber( $idOrEmail )
	{
		$this->id = ( filter_var( $idOrEmail, FILTER_VALIDATE_EMAIL ) )
			? $this->getContactByEmail( $idOrEmail )->id
			: $idOrEmail;

		$this->subscriber = null;

		return $this;
	}

	public function subscribeToList( $list )
	{
		$this->setGroup( $list, Status::ACTIVE );

		return $this;
	}

	public function unsubscribeFromList( $list )
	{
		$this->setGroup( $list, Status::UNSUBSCRIBED );

		return $this;
	}

	public function setTrialCompletedIfNotActive( $idOrEmail = null )
	{
		!$idOrEmail OR $this->setSubscriber( $idOrEmail );

		if( !$this->isInList( 'ACTIVE_STUDENTS' ) )
			$this->subscribeToList( 'TRIAL_COMPLETED' );

		return $this;
	}

	public function subscriber()
	{
		if ( $this->id AND ( !$this->subscriber OR $this->id != $this->subscriber->contact->id ) )
			$this->subscriber = $this->getACContact()->show( $this->id );

		return $this->subscriber;
	}

	public function isInList( $list )
	{
		$list = Group::getGroupId( $list );

		return $this->subscriber()
		       AND count($this->subscriber->contactLists)
		           AND !empty(current(array_filter($this->subscriber->contactLists, function( $element ) use( $list ) {
				            return $element->list == $list AND $element->status == Status::ACTIVE;
					})));
	}

	private function getContact( $id )
	{
		return $this->getACContact()->show( $id );
	}

	private function getContactByEmail( $email )
	{
		$contacts = $this->getACContact()->index(['email' => $email]);

		return ( (int) $contacts->meta->total > 0 )
			? $contacts->contacts[0]
			: null;
	}

	private function getACContact()
	{
		if( !$this->api instanceof Contact )
			$this->api = Contact::instance( $this->config['url'], $this->config['key']);

		return $this->api;
	}

	private function setGroup( $list, $status )
	{
		$this->getACContact()->updateListStatus( Group::getGroupId( $list ), $this->id, $status);
	}
}