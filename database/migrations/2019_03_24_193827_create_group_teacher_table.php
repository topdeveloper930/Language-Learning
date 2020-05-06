<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('group_teacher', function (Blueprint $table) {
		    $table->unsignedInteger('group_id');
		    $table->unsignedInteger('teacher_id');
		    $table->primary(['group_id', 'teacher_id']);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('group_teacher');
    }
}
