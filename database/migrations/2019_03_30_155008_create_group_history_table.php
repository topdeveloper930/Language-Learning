<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('group_history', function (Blueprint $table) {
		    $table->increments('id');
		    $table->unsignedInteger('group_id')->comment('Foreign key to groups table');
		    $table->unsignedInteger('usuario_id')->comment('Foreign key to usuarios table');
		    $table->unsignedInteger('teacher_id')->nullable()->comment('Foreign key to teachers table. Null if student.');
		    $table->unsignedInteger('student_id')->nullable()->comment('Foreign key to students table. Null if teacher.');
		    $table->enum('action', ['attach', 'detach'])->default('attach');
		    $table->string('note', 512)->default('')->comment('Admins comment on the action.');
		    $table->timestamps();
		    $table->index('group_id');
		    $table->index('usuario_id');
		    $table->index('teacher_id');
		    $table->index('student_id');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('group_history');
    }
}
