<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobApplicant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('jobApplicant', function ( Blueprint $table ) {
	    	$table->string( 'firstName', 150 )->change();
		    $table->string( 'lastName', 150 )->change();
		    $table->string( 'email', 150 )->change();
		    $table->string( 'skype', 150 )->change();
		    $table->smallInteger( 'accent', false, true )->nullable()->change();
		    $table->string( 'agesTaught', 512 )->default('')->change();
		    $table->string( 'degreeName', 512 )->default('')->change();
		    $table->string( 'certName', 512 )->default('')->change();
		    $table->string( 'originCity', 150 )->change();
		    $table->string( 'residenceCity', 150 )->change();
		    $table->string( 'referrer', 150 )->default('')->change();
		    $table->string( 'photoFileID' )->nullable()->change();
		    $table->string( 'resumeFileID' )->nullable()->change();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
