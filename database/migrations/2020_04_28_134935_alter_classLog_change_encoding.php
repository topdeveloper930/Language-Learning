<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClassLogChangeEncoding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    DB::statement( "
				ALTER TABLE `classLog`
				    MODIFY COLUMN `studentID` 		INTEGER UNSIGNED NOT NULL,
				    MODIFY COLUMN `teacherID`		INTEGER UNSIGNED NOT NULL,
				    MODIFY COLUMN `numStudents`		TINYINT UNSIGNED NOT NULL DEFAULT 1,
				    MODIFY COLUMN `course`			  VARBINARY(128) NOT NULL,
				    MODIFY COLUMN `creditsUsed`			DECIMAL(5,2) NOT NULL DEFAULT 1,
				    MODIFY COLUMN `whatWasStudied` 	 VARBINARY(1000) NOT NULL,
				    MODIFY COLUMN `internalNotes`	 VARBINARY(1000) NULL,
				    MODIFY COLUMN `active`			TINYINT UNSIGNED NOT NULL DEFAULT 1,
				    MODIFY COLUMN `classDate`					DATE NULL,
				    MODIFY COLUMN `recordedDate`			DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				    MODIFY COLUMN `processPay`		TINYINT UNSIGNED NOT NULL DEFAULT 0
		" );

	    DB::statement("
				ALTER TABLE `classLog`
				    MODIFY COLUMN `course`			VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `whatWasStudied`			TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `internalNotes`			TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
		");

	    DB::statement("
			ALTER TABLE `classLog` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
	    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    DB::statement("
				ALTER TABLE `classLog`
				    MODIFY COLUMN `course`			 VARBINARY(128)	NOT NULL,
				    MODIFY COLUMN `whatWasStudied`	VARBINARY(1000)	NOT NULL,
				    MODIFY COLUMN `internalNotes`	VARBINARY(1000)	NOT NULL DEFAULT '' 
		");

	    DB::statement("
			ALTER TABLE `classLog` CONVERT TO CHARACTER SET latin1 COLLATE latin1_swedish_ci
	    ");

	    DB::statement("
				ALTER TABLE `classLog`
				    MODIFY COLUMN `studentID` 		 VARCHAR(10) NOT NULL,
				    MODIFY COLUMN `teacherID`		 VARCHAR(10) NOT NULL,
				    MODIFY COLUMN `numStudents`		 VARCHAR(10) NOT NULL DEFAULT '1',
				    MODIFY COLUMN `course`		    VARCHAR(255) NOT NULL,
				    MODIFY COLUMN `creditsUsed`	    VARCHAR(255) NOT NULL,
				    MODIFY COLUMN `whatWasStudied` VARCHAR(1000) NOT NULL,
				    MODIFY COLUMN `internalNotes`  VARCHAR(1000) NOT NULL DEFAULT '1',
				    MODIFY COLUMN `active`		   		 INTEGER NOT NULL DEFAULT 1,
				    MODIFY COLUMN `classDate`			DATETIME NOT NULL,
				    MODIFY COLUMN `recordedDate`	   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				    MODIFY COLUMN `processPay`			 INTEGER NOT NULL DEFAULT 0
		");
    }
}
