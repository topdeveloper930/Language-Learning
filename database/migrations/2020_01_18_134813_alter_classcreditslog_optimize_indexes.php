<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClasscreditslogOptimizeIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('classCreditsLog', function (Blueprint $table) {
		    $table->dropIndex('classCreditsLog');

		    $table->string( 'notes', 1000 )->nullable()->change();

		    $table->index([ 'studentID' ]);
		    $table->index([ 'transactionID' ]);
		    $table->index([ 'course', 'numStudents' ]);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('classCreditsLog', function (Blueprint $table) {
		    $table->index( 'classCreditsLogID', 'classCreditsLog' );
	    });
    }
}
