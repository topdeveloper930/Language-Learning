<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class BasePolicy
{
    use HandlesAuthorization;

	public function before( Authenticatable $user, $ability )
	{
		if ( $user->isSuperAdmin() )
			return true;
	}

	protected function reject()
	{
		return false;
	}
}
