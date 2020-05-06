<?php


namespace App\Services\Auth;


use App\Member;
use App\Traits\Loggable;
use App\Traits\SocialNetworkAuth;
use Illuminate\Notifications\Notifiable;

trait UserTypeTrait {
	use Notifiable, Loggable, SocialNetworkAuth;

	public function getType()
	{
		return static::TYPE;
	}

	public function getInstance()
	{
		return $this;
	}

	public function getPrimaryKey()
	{
		return $this->getInstance()->getKey();
	}

	public function getPK()
	{
		return $this->getPrimaryKey();
	}

	public function getTitle()
	{
		return $this->getInstance()->title;
	}

	public function getFirstName()
	{
		return $this->getInstance()->firstName;
	}

	public function getLastName()
	{
		return $this->getInstance()->lastName;
	}

	public function getProfileImage()
	{
		return $this->getInstance()->profileImage;
	}

	public function getEmail()
	{
		return ( $this->email ) ? $this->email : $this->getInstance()->email;
	}

	public function getArea()
	{
		return ( 'teacher' == static::TYPE OR 'student' == static::TYPE )
			? str_plural( static::TYPE )
			: static::TYPE;
	}

	public function fullName()
	{
		return trim( sprintf( '%s %s', $this->getFirstName(), $this->getLastName() ) );
	}

	public function accost()
	{
		return trim( sprintf( '%s %s', $this->getTitle(), $this->fullName() ) );
	}

	public function isMember()
	{
		return Member::TYPE == $this->getInstance()->getType();
	}

	public function isSuperAdmin()
	{
		return $this->isMember() AND Member::SUPERADMIN_ROLE == $this->getInstance()->userType;
	}

	public function hasAdminPermit()
	{
		return ( $this->isMember()
		         AND ( Member::SUPERADMIN_ROLE == $this->getInstance()->userType
		           OR Member::ADMIN_ROLE == $this->getInstance()->userType )
		);
	}

	public function isTheUser( $role, $id = null )
	{
		return $role == $this->getInstance()->getType()
		       AND ( is_null( $id ) OR $id == $this->getPrimaryKey() );
	}

	public function routeNotificationForMail( $notification = null )
	{
		return $this->getEmail();
	}
}