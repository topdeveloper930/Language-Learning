<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecreateSnAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('sn_accounts', function ( Blueprint $table ) {
		    $table->dropIfExists();
	    });

	    Schema::create('sn_accounts', function (Blueprint $table) {
		    $table->increments('id' );
		    $table->string( 'provider', 45 );
		    $table->string( 'provider_uid', 128 );
		    $table->morphs( 'user' );
		    $table->timestamp( 'created_at' )->nullable();
		    $table->index(['provider', 'provider_uid']);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('sn_accounts', function ( Blueprint $table ) {
		    $table->dropIfExists();
	    });

	    $sql = "
		    CREATE TABLE `sn_accounts` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`student_id` INT(10) UNSIGNED NOT NULL COMMENT 'ID of the student (foreign key to students table)',
				`provider` VARCHAR(45) NOT NULL COMMENT 'Name of the authentication API provider: facebook, google etc.',
				`provider_uid` VARCHAR(128) NOT NULL COMMENT 'ID of the user in the provider\'s network',
				`email` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Email address that user registered with in the network.',
				`create_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date and time, when the profile added',
				PRIMARY KEY (`id`),
				INDEX `NETWORK_ID` (`provider`, `provider_uid`),
				INDEX `STUDENT_ID_idx` (`student_id`),
				INDEX `EMAIL_PROVIDER` (`email`, `provider`)
			)
			COMMENT='Table for storing third parties (social networks) authentication accounts.'
			COLLATE='utf8_general_ci'
			ENGINE=MyISAM
			;
	    ";

	    \Illuminate\Support\Facades\DB::statement( $sql );
    }
}
