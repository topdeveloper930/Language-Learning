<?php


namespace App\Traits;


use App\LoginLog;

trait Loggable {

	public function loginLogs()
	{
		return $this->hasMany( LoginLog::class, 'userID', $this->getKeyName() )
		            ->where('loginType', $this->getType());
	}

	public function logLogin()
	{
		$this->loginLogs()->create([
			'loginType' => $this->getType(),
			'userID'    => $this->getKey(),
			'browser'   => request()->header('User-Agent')
		]);
	}
}