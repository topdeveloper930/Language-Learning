<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
	const CREATED_AT = null;
	const UPDATED_AT = 'updated_on';

	protected $casts = [
		'value' => 'array',
	];

	public function lastEditedBy()
	{
		return $this->belongsTo( Member::class, 'updated_by', 'userID' );
	}

	/**
	 * Get overridden configs with defaults.
	 *
	 * @param  array  $value
	 * @return array
	 */
	public function getOverriddenValue()
	{
		return array_merge( config( $this->name, [] ), (array)$this->value );
	}

	public static function overriddenValuesByName( $name )
	{
		$defaults = config( $name, [] );
		$overrides = static::where( 'name', $name )->first();

		return ( $overrides )
			? array_merge( $defaults, $overrides->value )
			: $defaults;
	}
}
