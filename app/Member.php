<?php

namespace App;

use App\Services\Auth\UserType;
use App\Services\Auth\UserTypeTrait;
use Illuminate\Database\Eloquent\Model;

class Member extends Model implements UserType
{
	use UserTypeTrait;

	const UPDATED_AT = 'lastUpdated';
	const CREATED_AT = null;

	const TYPE = 'admin';

	const SUPERADMIN_ROLE = 'super-admin';
	const ADMIN_ROLE = 'admin';
	const MARKETER_ROLE = 'marketer';
	const HRADMIN_ROLE = 'hr-admin';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'userID';

	protected $guard = 'admin';

    public function usuario()
    {
    	return $this->belongsTo( User::class );
    }

	public function getType()
	{
		return static::TYPE;
	}

	public function hasSuperAdminRole()
	{
		return $this->member AND $this->member->exists() AND $this->superadmin_role == $this->member->userType;
	}

	public function getProfileImage()
	{
		return $this->image;
	}
}
