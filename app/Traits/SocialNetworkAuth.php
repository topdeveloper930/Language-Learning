<?php


namespace App\Traits;


use App\SnAccount;

trait SocialNetworkAuth {

	public function snAccounts()
	{
		return $this->morphMany(SnAccount::class, 'user' );
	}

	public function googleAccount()
	{
		return $this->snAccounts()->where('provider', 'google')->first();
	}

	public function facebookAccount()
	{
		return $this->snAccounts()->where('provider', 'facebook')->first();
	}

}