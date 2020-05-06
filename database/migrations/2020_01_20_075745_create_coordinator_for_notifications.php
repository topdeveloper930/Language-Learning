<?php

use Illuminate\Database\Migrations\Migration;

class CreateCoordinatorForNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	if( !\App\User::where('email', config( 'legacy.globals.mainEmail' ))->count() ) {
		    factory(\App\User::class)->create([
			    'email'          => config( 'legacy.globals.mainEmail' ),
			    'remember_token' => null
		    ]);
	    }
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 * @throws Exception
	 */
    public function down()
    {
	    \App\User::where('email', config( 'legacy.globals.mainEmail' ))->first()->delete();
    }
}
