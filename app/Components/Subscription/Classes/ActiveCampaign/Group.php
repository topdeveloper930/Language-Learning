<?php


namespace App\Components\Subscription\Classes\ActiveCampaign;


class Group {

	const ALL = 5;

	/**
	 * Sales Funnel Groups
	 */
	const TRIAL_REQUEST = 2;
	const TRIAL_PENDING = 6;
	const TRIAL_MISSED = 7;
	const TRIAL_COMPLETED = 8;
	const ACTIVE_STUDENTS = 9;
	const TRIAL_ACTIVE = 9; // This is an alias of ACTIVE_STUDENTS
	const OUT_OF_HOURS = 50;
	const TRIAL_INACTIVE_CREDITS = 10;
	const TRIAL_INACTIVE_NO_CREDITS = 11;

	/**
	 * Language Groups
	 */
	const SPANISH = 12;
	const ENGLISH = 13;
	const FRENCH = 14;
	const GERMAN = 17;
	const ITALIAN = 15;
	const PORTUGUESE = 16;
	const JAPANESE = 19;
	const CHINESE = 20;
	const KOREAN = 21;
	const RUSSIAN = 18;
	const ARABIC = 22;

	public static function getGroupId( $group )
	{
		return is_integer( $group )
			? $group
			: constant(
				sprintf( 'App\Components\Subscription\Classes\ActiveCampaign\Group::%s', strtoupper( $group ) )
			);
	}
}