<?php


namespace App\Extensions;


use App\Student;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class StudentUserProvider extends EloquentUserProvider {

	public function retrieveById($identifier)
	{
		return Student::find( $identifier );
	}

	public function retrieveByCredentials(array $credentials)
	{
		$query = $this->createModel()->newQuery();
		return $query->where('email', $credentials['email'])->first();
	}

	public function retrieveAllByCredentials(array $credentials)
	{
		$query = $this->createModel()->newQuery();
		return $query->where('email', $credentials['email'])->get();
	}

	public function validateCredentials( Authenticatable $user, array $credentials )
	{
		return parent::validateCredentials( $user, $credentials ) OR $this->legacyValidateCredentials( $user, $credentials );
	}

	public function legacyValidateCredentials(Authenticatable $user, array $credentials)
	{
		return hash_equals( (string)$user->password128, crypt( $credentials[ 'password' ], $user->password128 ));
	}

	public function newPassHash( Authenticatable $user, array $credentials )
	{
		if( !$user->password )
			$user->saveNewPassHash( $this->hasher->make( $credentials[ 'password' ] ) );
	}
}