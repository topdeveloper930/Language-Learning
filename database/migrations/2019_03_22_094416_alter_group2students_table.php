<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGroup2studentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group2students', function (Blueprint $table) {
            $table->renameColumn('studentsID', 'studentID')->change();
            $table->index('groupID');
	        $table->index('studentID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group2students', function (Blueprint $table) {
	        $table->dropIndex('studentID');
	        $table->dropIndex('groupID');
	        $table->renameColumn('studentID', 'studentsID')->change();
        });
    }
}
