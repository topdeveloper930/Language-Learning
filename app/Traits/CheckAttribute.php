<?php


namespace App\Traits;


trait CheckAttribute {

	public function hasAttribute($attr)
	{
		return array_key_exists($attr, $this->attributes);
	}
}