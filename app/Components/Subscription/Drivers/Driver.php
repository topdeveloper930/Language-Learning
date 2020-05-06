<?php


namespace App\Components\Subscription\Drivers;


use App\Components\Subscription\Contracts\Subscription;
use App\Traits\ArrayObjectAccess;

abstract class Driver implements Subscription {

	use ArrayObjectAccess;

	protected $api;

	/**
	 * Driver configs
	 *
	 * @var array|null
	 */
	protected $config;

	public function __construct( $config = null )
	{
		$this->config  = $config;
	}

	public function withApi( $api )
	{
		$new_inst = clone $this;

		$new_inst->api = $api;

		return $new_inst;
	}
}