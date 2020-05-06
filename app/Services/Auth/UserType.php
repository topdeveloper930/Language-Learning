<?php


namespace App\Services\Auth;


interface UserType {
	public function getInstance();
	public function getTitle();
	public function getFirstName();
	public function getLastName();
	public function getEmail();
	public function getType();
	public function getArea();
	public function getPrimaryKey();
	public function fullName();
	public function accost();
	public function getProfileImage();
	public function isMember();
	public function isSuperAdmin();
	public function hasAdminPermit();
	public function isTheUser( $role, $id = null );
}