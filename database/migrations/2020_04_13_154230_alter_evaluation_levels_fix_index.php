<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEvaluationLevelsFixIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('evaluationLevels', function (Blueprint $table) {
		    $table->dropIndex(['skill', 'level']);

		    $table->index(['skill', 'title']);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('evaluationLevels', function (Blueprint $table) {
		    $table->dropIndex(['skill', 'title']);

		    $table->index(['skill', 'level']);
	    });
    }
}
