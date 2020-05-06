<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStudentsTableAddPasswordColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('students', function (Blueprint $table) {
		    if (!Schema::hasColumn('students', 'password')) {
			    $table->string( 'password', 255 )->nullable()->after('email');
		    }
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('students', function (Blueprint $table) {
		    $table->dropColumn(['password']);
	    });
    }
}
