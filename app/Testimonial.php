<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'testimonialID';

	public $timestamps = false;
}
