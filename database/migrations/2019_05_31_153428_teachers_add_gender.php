<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TeachersAddGender extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('teachers', function (Blueprint $table) {
		    $table->enum('gender', ['male', 'female'])->nullable();
	    });

	    DB::unprepared("
			UPDATE `teachers` 
			SET `gender` = CASE 
						        WHEN `title` = 'Mrs.' OR `title` = 'Ms.' OR `teacherID` = 118 OR `teacherID` = 503  THEN 'female' 
						        ELSE 'male'
						    END
		");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('teachers', function (Blueprint $table) {
		    $table->dropColumn(['gender']);
	    });
    }
}
