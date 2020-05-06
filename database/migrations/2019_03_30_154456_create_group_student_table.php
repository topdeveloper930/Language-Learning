<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('group_student', function (Blueprint $table) {
		    $table->unsignedInteger('group_id');
		    $table->unsignedInteger('student_id');
		    $table->primary(['group_id', 'student_id']);
	    });

	    Schema::dropIfExists('group2students');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('group_student');
	    // Restore legacy table (empty for the moment)
	    DB::unprepared(file_get_contents('database/sql/up/2019_03_30_restore_group2students_table.sql'));
    }
}
