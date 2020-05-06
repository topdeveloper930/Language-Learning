<?php

namespace App\Providers;

use App\GiftCardLog;
use App\Observers\GiftCardLogObserver;
use App\Observers\ReferralCreditObserver;
use App\Observers\TransactionObserver;
use App\ReferralCredit;
use App\Transaction;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		GiftCardLog::observe( GiftCardLogObserver::class );
		ReferralCredit::observe( ReferralCreditObserver::class );
		Transaction::observe( TransactionObserver::class );
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('Payment', function ($app) {
			return new \App\Components\Payment\PaymentManager( $app );
		});
	}
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['Payment'];
	}
}
