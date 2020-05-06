<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVisitlogsAddIsBanned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('visitlogs', function( Blueprint $table )
	    {
		    $table->boolean('is_banned')->default(0)->after('longitude');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('visitlogs', function( Blueprint $table )
	    {
		    $table->dropColumn('is_banned');
	    });
    }
}
