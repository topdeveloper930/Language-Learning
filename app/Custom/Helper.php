<?php


namespace App\Custom;


use Carbon\Carbon;

class Helper {
	const MORNING   = 1;
	const AFTERNOON = 2;
	const EVENING   = 3;
	const NIGHT     = 4;

	static $languages = [
		'english' => 'en', 'spanish' => 'es', 'french' => 'fr', 'german' => 'de', 'portuguese' => 'pt', 'italian' => 'it',
		'russian' => 'ru', 'arabic' => 'ar', 'chinese' => 'zh', 'japanese' => 'ja', 'korean' => 'ko'
	];

	public static function getPartOfDay( $tz = 'America/New_York' )
	{
		$t = Carbon::now( $tz )->format( 'H' );

		if ( $t >= 18 )
			return static::EVENING;
		elseif ( $t >= 12 )
			return static::AFTERNOON;
		else
			return static::MORNING;
	}

	public static function getLanguageCode( $language )
	{
		$language = strtolower( $language );

		if ( strlen( $language ) == 2 )
			return $language;

		return isset( static::$languages[ $language ] )
			? static::$languages[ $language ]
			: 'en';
	}

	public static function getGreeting( $name = '', $language = 'english',  $tz = 'America/New_York' )
	{
		$dayTime = static::getPartOfDay( $tz );

		$tplt = ( static::EVENING == $dayTime )
			? 'greeting.good_evening'
			: (( static::AFTERNOON == $dayTime )
				? 'greeting.good_afternoon'
				: 'greeting.good_morning');

		return trans_choice( $tplt, intval(!empty($name)), ['name' => $name], static::getLanguageCode( $language ));
	}
}