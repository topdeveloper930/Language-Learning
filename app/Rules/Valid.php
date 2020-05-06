<?php


namespace App\Rules;

use Illuminate\Support\Traits\Macroable;

class Valid {
	use Macroable;

	public static function timezone( $attribute, $timezone )
	{
		return ( is_scalar( $timezone ) AND in_array( $timezone, \DateTimeZone::listIdentifiers() ) );
	}

	/**
	 * Checks through all validation methods to verify it is in a
	 * phone number format of some type
	 * @param  string  $value The phone number to check
	 * @return boolean        is it correct format?
	 */
	public static function phone($attribute, $value)
	{
		return static::isE123($value)
		       OR static::isE164($value)
		       OR static::isNANP($value)
		       OR static::isDigits($value);
	}

	/**
	 * Format example 5555555555, 15555555555
	 * @param  [type]  $value [description]
	 * @return boolean        [description]
	 */
	public static function isDigits($value)
	{
		$conditions = [];
		$conditions[] = strlen($value) >= 10;
		$conditions[] = strlen($value) <= 16;
		$conditions[] = preg_match("/[^\d]/i", $value) === 0;
		return (bool) array_product($conditions);
	}
	/**
	 * Format example +22 555 555 1234, (607) 555 1234, (022607) 555 1234
	 * @param $value
	 * @return bool
	 */
	public static function isE123($value)
	{
		return preg_match('/^(?:\((\+?\d+)?\)|\+?\d+) ?\d*(-?\d{2,3} ?){0,4}$/', $value) === 1;
	}
	/**
	 * Format example +15555555555
	 * @param  string  $value The phone number to check
	 * @return boolean        is it correct format?
	 */
	public static function isE164($value)
	{
		$conditions = [];
		$conditions[] = strpos($value, "+") === 0;
		$conditions[] = strlen($value) >= 9;
		$conditions[] = strlen($value) <= 16;
		$conditions[] = preg_match("/[^\d+]/i", $value) === 0;
		return (bool) array_product($conditions);
	}
	/**
	 * Format examples: (555) 555-5555, 1 (555) 555-5555, 1-555-555-5555, 555-555-5555, 1 555 555-5555
	 * https://en.wikipedia.org/wiki/National_conventions_for_writing_telephone_numbers#United_States.2C_Canada.2C_and_other_NANP_countries
	 * @param  string  $value The phone number to check
	 * @return boolean        is it correct format?
	 */
	public static function isNANP($value)
	{
		$conditions = [];
		$conditions[] = preg_match("/^(?:\+1|1)?\s?-?\(?\d{3}\)?(\s|-)?\d{3}-\d{4}$/i", $value) > 0;
		return (bool) array_product($conditions);
	}
}