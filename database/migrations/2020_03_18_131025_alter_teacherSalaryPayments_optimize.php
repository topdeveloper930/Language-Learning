<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTeacherSalaryPaymentsOptimize extends Migration
{
    /**
     * Run the migrations.
     * !!! We cannot change hoursTaught, payPerHour and totalPay columns to FLOAT now,
     * because of backward compatibility with legacy code (admin).
     *
     * @return void
     */
    public function up()
    {
	    DB::statement("
				ALTER TABLE `teacherSalaryPayments`
				    MODIFY COLUMN `numStudents` 		TINYINT NULL,
				    MODIFY COLUMN `course`		 VARBINARY(255) NULL,
				    MODIFY COLUMN `hoursTaught`	  VARBINARY(60) NULL,
				    MODIFY COLUMN `payPerHour`	  VARBINARY(60) NOT NULL DEFAULT '0',
				    MODIFY COLUMN `totalPay`	  VARBINARY(60) NOT NULL DEFAULT '0',
				    MODIFY COLUMN `adminNotes`	VARBINARY(2000) NULL
		");

	    DB::statement("
				ALTER TABLE `teacherSalaryPayments`
				    MODIFY COLUMN `course`		 VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
				    MODIFY COLUMN `hoursTaught`	  VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
				    MODIFY COLUMN `payPerHour`	  VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
				    MODIFY COLUMN `totalPay`	  VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
				    MODIFY COLUMN `adminNotes`	VARCHAR(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
		");

	    DB::statement("
			ALTER TABLE `teacherSalaryPayments` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
	    ");

	    Schema::table('teacherSalaryPayments', function (Blueprint $table) {
		    $table->dropIndex('teacherSalaryPaymentsID');

		    $table->index(['teacherID']);
		    $table->index(['studentID']);
		    $table->index(['numStudents']);
		    $table->index(['course']);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('teacherSalaryPayments', function (Blueprint $table) {
		    $table->dropIndex(['teacherID']);
		    $table->dropIndex(['studentID']);
		    $table->dropIndex(['numStudents']);
		    $table->dropIndex(['course']);

		    $table->index('teacherSalaryPaymentsID', 'teacherSalaryPaymentsID');
	    });


	    DB::statement("
				ALTER TABLE `teacherSalaryPayments`
				    MODIFY COLUMN `numStudents` 			INT NOT NULL,
				    MODIFY COLUMN `course`		 VARBINARY(255) NULL,
				    MODIFY COLUMN `hoursTaught`	  VARBINARY(60) NULL,
				    MODIFY COLUMN `payPerHour`	  VARBINARY(60) NOT NULL DEFAULT '0',
				    MODIFY COLUMN `totalPay`	  VARBINARY(60) NOT NULL DEFAULT '0',
				    MODIFY COLUMN `adminNotes`	VARBINARY(2000) NULL
		");

	    DB::statement("
			ALTER TABLE `teacherSalaryPayments` CONVERT TO CHARACTER SET latin1 COLLATE latin1_swedish_ci
	    ");

	    DB::statement("
				ALTER TABLE `teacherSalaryPayments`
				    MODIFY COLUMN `course`		 VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
				    MODIFY COLUMN `hoursTaught`	  VARCHAR(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
				    MODIFY COLUMN `payPerHour`	  VARCHAR(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
				    MODIFY COLUMN `totalPay`	  VARCHAR(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
				    MODIFY COLUMN `adminNotes`	VARCHAR(2000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''
		");
    }
}
