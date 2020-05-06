<?php


namespace App\Components\Subscription;


use App\Components\Subscription\Drivers\ActiveCampaign;
use Illuminate\Support\Manager;

class SubscriptionManager extends Manager {
	/**
	 * Get a driver instance.
	 *
	 * @param  string|null  $name
	 * @return mixed
	 */
	public function gateway( $name = null )
	{
		return $this->driver( $name );
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultDriver()
	{
		return $this->app['config']['subscription.default'];
	}

	public function createActiveCampaignDriver()
	{
		return new ActiveCampaign(config('api.activecampaign'));
	}
}