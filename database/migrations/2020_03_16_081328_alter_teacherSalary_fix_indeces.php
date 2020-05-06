<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTeacherSalaryFixIndeces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('teacherSalary', function (Blueprint $table) {
		    $table->dropIndex('teacherSalaryID');

		    $table->index(['teacherID']);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('teacherSalary', function (Blueprint $table) {
		    $table->dropIndex(['teacherID']);

		    $table->index('teacherSalaryID', 'teacherSalaryID');
	    });
    }
}
