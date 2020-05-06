<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearningQuizResult extends Model
{
	const CREATED_AT = 'createdate';
	const UPDATED_AT = null;

	const JUNGIAN_BASE_VALUE = 24;

	const JUNGIAN_IE = 30;
	const JUNGIAN_SN = 12;
	const JUNGIAN_FT = 30;
	const JUNGIAN_JP = 18;

	protected $table = 'learningQuizResults';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'language', 'ip', 'country', 'style', 'highest', 'visual', 'auditory', 'kinesthetic', 'createdate'
	];

	/**
	 * Get the ip.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public function getIpAttribute( $value )
	{
		return inet_ntop( $value );
	}

	/**
	 * Set the ip.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setIpAttribute( $value )
	{
		$this->attributes['ip'] = inet_pton( $value );
	}

	/**
	 * TODO
	 * @param array $answers
	 * @param string|null $language
	 * @param string|null $ip
	 * @param string|null $country
	 *
	 * @return LearningQuizResult
	 */
	public static function processAnswers( $answers, $language = null, $ip = null, $country = null )
	{
		$quiz = [
			'visual'      => 0,
			'auditory'    => 0,
			'kinesthetic' => 0
		];

		$styles = [
			'IE' => static::JUNGIAN_IE,
			'SN' => static::JUNGIAN_SN,
			'FT' => static::JUNGIAN_FT,
			'JP' => static::JUNGIAN_JP
		];

		foreach ($answers as $question) {
			switch ($question['distribution']) {
				case 'va':
					$quiz['visual'] += (10 - $question['answer']) * .3;
					$quiz['auditory'] += $question['answer'] * .3;
					break;
				case 'vk':
					$quiz['visual'] += (10 - $question['answer']) * .3;
					$quiz['kinesthetic'] += $question['answer'] * .3;
					break;
				case 'ak':
					$quiz['auditory'] += (10 - $question['answer']) * .3;
					$quiz['kinesthetic'] += $question['answer'] * .3;
					break;
				default:    //Jungian
					$styles[$question['distribution']] += $question['sign'] * $question['answer'] * 3/5;

			}
		}

		// Find key of the max value and ucfirst it.
		$quiz[ 'highest' ] = ucfirst( array_keys( $quiz, max( $quiz ) )[ 0 ] );

		$quiz['style'] = ( ( $styles['IE'] > static::JUNGIAN_BASE_VALUE ) ? 'E' : 'I' )
		                 . ( ( $styles['SN'] > static::JUNGIAN_BASE_VALUE ) ? 'N' : 'S' )
		                 . ( ( $styles['FT'] > static::JUNGIAN_BASE_VALUE ) ? 'T' : 'F' )
		                 . ( ( $styles['JP'] > static::JUNGIAN_BASE_VALUE ) ? 'P' : 'J' );

		$quiz[ 'language' ] = $language;

		$quiz[ 'ip' ] = $ip;

		$quiz[ 'country' ] = $country;

		return static::create($quiz);
	}
}
