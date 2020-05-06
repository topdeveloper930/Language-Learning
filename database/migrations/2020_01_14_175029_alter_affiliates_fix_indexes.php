<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAffiliatesFixIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('affiliates', function (Blueprint $table) {
		    $table->dropIndex('affiliateID');

		    $table->string( 'companyName', 250 )->nullable()->change();
		    $table->string( 'street1', 100 )->nullable()->change();
		    $table->string( 'street2', 100 )->nullable()->change();
		    $table->string( 'city', 150 )->nullable()->change();
		    $table->string( 'state', 100 )->nullable()->change();
		    $table->string( 'country', 100 )->nullable()->change();
		    $table->string( 'postalCode', 100 )->nullable()->change();
		    $table->string( 'phone', 50 )->nullable()->change();
		    $table->string( 'url', 500 )->nullable()->change();
		    $table->decimal( 'commission', 5, 2 )->default(0)->change();
		    $table->boolean( 'termsConditions' )->default(0)->change();
		    $table->boolean( 'active' )->default(1)->change();

		    $table->unique([ 'affiliateCode' ]);
		    $table->index([ 'email' ]);
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
