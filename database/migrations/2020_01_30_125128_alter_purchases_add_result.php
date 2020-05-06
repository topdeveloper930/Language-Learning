<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurchasesAddResult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('purchases', function (Blueprint $table) {
		    $table->renameColumn( 'error', 'result' )->change();
	    });

	    Schema::table('purchases', function (Blueprint $table) {
		    $table->string( 'error', 512 )
		          ->nullable()
		          ->after( 'result' )
		          ->comment( 'A reason of the denial to show to the user.' );
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('purchases', function (Blueprint $table) {
		    $table->dropColumn( 'error' );
	    });

	    Schema::table('purchases', function (Blueprint $table) {
		    $table->renameColumn( 'result', 'error' )->change();
	    });
    }
}
