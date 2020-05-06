<?php


namespace App\Components\Subscription\Contracts;


interface Subscription {
	/**
	 * @param int|string $idOrEmail id or email
	 *
	 * @return Subscription
	 */
	public function setSubscriber( $idOrEmail );

	/**
	 * @param string $list group designation that fits the const name
	 *
	 * @return Subscription
	 */
	public function subscribeToList( $list );

	/**
	 * @param string $list group designation that fits the const name
	 *
	 * @return Subscription
	 */
	public function unsubscribeFromList( $list );

	/**
	 * @param int|string|null $idOrEmail id or email. Defaults to null that means that current subscriber used.
	 * Subscribes the student to the TRIAL_COMPLETED group, if the student is not already in ACTIVE_STUDENTS group.
	 *
	 * @return Subscription
	 */
	public function setTrialCompletedIfNotActive( $idOrEmail = null );

	/**
	 * @param string $list group designation that fits the const name
	 *
	 * @return boolean
	 */
	public function isInList( $list );
}