<?php

use App\Member;
use Illuminate\Database\Migrations\Migration;

class CreateSystemUser extends Migration
{
	private $email;
	public function __construct()
	{
		$this->email = config( 'app.system_user_email' );

		if( !$this->email OR !filter_var( $this->email, FILTER_VALIDATE_EMAIL ) )
			throw new \InvalidArgumentException( 'No correct system_user_email value provided in config/app.php' );
	}

	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	if( \App\User::where('email', $this->email )->count() )
    		return;

    	$sys_user = factory( \App\User::class )->create([
		    'email'          => $this->email,
		    'password'       => bcrypt( str_random() ),
		    'remember_token' => null,
		    'api_token'      => str_random( 10 )
	    ]);

    	\App\Member::unguard();

    	\App\Member::create([
    		'usuario_id' => $sys_user->id,
		    'password' => $sys_user->password,
		    'firstName' => 'System',
		    'lastName' => 'User',
		    'email' => $sys_user->email,
		    'userType' => Member::SUPERADMIN_ROLE,
		    'userCode' => 'vt8FR7DRd08ihrCb9ellaaF7rIgjNlTi',
		    'image' => 'img/profiles/no-profile-image.jpg',
		    'complete' => 63,
		    'mailingList' => 1,
		    'premium' => 0,
		    'active' => 1
	    ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sys_user = \App\User::where('email', $this->email )->first();

        if( !$sys_user->exists() ) return;;

        if( $sys_user->member )
	        $sys_user->member->delete();

        $sys_user->delete();
    }
}
