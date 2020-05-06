<?php


namespace App\Components\Subscription\Classes\ActiveCampaign;


class Field {
	const ID = 'id';
	const FIRST_NAME = 'first_name';
	const LAST_NAME = 'last_name';
	const EMAIL = 'email';
	const TAGS = 'tags';
	const ORGNAME = 'orgname';

	/**
	 * Custom fields
	 */
	const TITLE = 'field[%TITLE%,0]';
	const LANGUAGE = 'field[%LANGUAGE%,0]';
	const COURSE = 'field[%COURSE%,0]';
	const CITY = 'field[%CITY%,0]';
	const STATE = 'field[%STATE%,0]';
	const COUNTRY = 'field[%COUNTRY%,0]';
	const WHY_STUDY = 'field[%WHY_STUDY%,0]';
	const AGE = 'field[%AGE%,0]';
	const LEVEL = 'field[%LEVEL%,0]';
	const DATE_SUBSCRIBED = 'field[%DATE_SUBSCRIBED%,0]';
	const REFERRAL_CODE = 'field[%REFERRAL_CODE%,0]';
}