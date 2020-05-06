<?php

namespace App;

use App\Notifications\MailResetPasswordToken;
use App\Services\Auth\UserType;
use App\Services\Auth\UserTypeTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements UserType
{
    use UserTypeTrait;

    const TYPE = 'usuario';

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    private $superadmin_role = 'super-admin';

    public function member()
    {
    	return $this->hasOne( Member::class, 'usuario_id' );
    }

	public function teacher()
	{
		return $this->hasOne( Teacher::class, 'usuario_id' );
	}

    public function group()
    {
	    return $this->hasOne( Group::class, 'usuario_id' );
    }

	public function group_history()
	{
		return $this->hasMany( GroupHistory::class, 'usuario_id' );
	}

	/**
	 * Send the password reset notification.
	 *
	 * @param  string  $token
	 * @return void
	 */
	public function sendPasswordResetNotification($token)
	{
		$area = 'teachers';

		if( !is_null( $this->member ) )
			$area = 'admin';
		elseif ( !is_null( $this->group ) )
			$area = 'group';

		$this->notify( new MailResetPasswordToken( $token, $area ) );
	}

	public function getInstance()
	{
		if( $this->member )         // Check member first to ensure Admin's tests in case if user has several types.
			return $this->member;
		elseif ( $this->teacher )
			return $this->teacher;
		elseif ( $this->group )
			return $this->group;

		return new Teacher; // FIXME: check if this does not create any conflict
	}

	public function getType()
	{
		return $this->getInstance()->getType();
	}

	public function getArea()
	{
		return $this->getInstance()->getArea();
	}
}
