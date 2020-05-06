<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEvaluationIndexCollation extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
				ALTER TABLE `evaluation`
				    MODIFY COLUMN `language` VARBINARY(60) NULL,
				    MODIFY COLUMN `course` VARBINARY(255) NOT NULL,
				    MODIFY COLUMN `speakingLevelTitle` VARBINARY(255) NULL,

				    MODIFY COLUMN `listeningLevelTitle` VARBINARY(255) NULL,
				    MODIFY COLUMN `writingLevelTitle` VARBINARY(255) NULL,
				    MODIFY COLUMN `readingLevelTitle` VARBINARY(255) NULL,

				    MODIFY COLUMN `comments` VARBINARY(5000) NULL
		");

		DB::statement("
				ALTER TABLE `evaluation`
				    MODIFY COLUMN `language`			VARCHAR(60)	 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
				    MODIFY COLUMN `course`				VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `speakingLevelTitle`	VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,

				    MODIFY COLUMN `listeningLevelTitle`	VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
				    MODIFY COLUMN `writingLevelTitle`	VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
				    MODIFY COLUMN `readingLevelTitle`	VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,

				    MODIFY COLUMN `speakingLevel`	TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `listeningLevel`	TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `writingLevel`	TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `readingLevel`	TINYINT UNSIGNED NOT NULL,

				    MODIFY COLUMN `speakingLevelID`	 TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `listeningLevelID` TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `writingLevelID`	 TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `readingLevelID`	 TINYINT UNSIGNED NOT NULL,

				    MODIFY COLUMN `comments` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
		");

		DB::statement("
			ALTER TABLE `evaluation` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
	    ");

		Schema::table('evaluation', function (Blueprint $table) {
			$table->dropIndex('evaluationID');

			$table->index([ 'studentID' ]);
			$table->index([ 'teacherID' ]);
			$table->index([ 'course' ]);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("
				ALTER TABLE `evaluation`
				    MODIFY COLUMN `language` VARBINARY(60) NOT NULL,
				    MODIFY COLUMN `course` VARBINARY(255) NOT NULL,
				    MODIFY COLUMN `speakingLevelTitle` VARBINARY(255) NOT NULL,
				    
				    MODIFY COLUMN `listeningLevelTitle` VARBINARY(255) NOT NULL,
				    MODIFY COLUMN `writingLevelTitle` VARBINARY(255) NOT NULL,
				    MODIFY COLUMN `readingLevelTitle` VARBINARY(255) NOT NULL,
				    
				    MODIFY COLUMN `comments` BLOB NOT NULL
		");

		DB::statement("
				ALTER TABLE `evaluation` 
				    MODIFY COLUMN `language` VARCHAR(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `course` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `speakingLevelTitle` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    
				    MODIFY COLUMN `listeningLevelTitle` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `writingLevelTitle` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `readingLevelTitle` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    
				    MODIFY COLUMN `speakingLevel`	VARCHAR(255) NOT NULL,
				    MODIFY COLUMN `listeningLevel`	VARCHAR(255) NOT NULL,
				    MODIFY COLUMN `writingLevel`	VARCHAR(255) NOT NULL,
				    MODIFY COLUMN `readingLevel`	VARCHAR(255) NOT NULL,
				    
				    MODIFY COLUMN `speakingLevelID`	 INT(10) NOT NULL,
				    MODIFY COLUMN `listeningLevelID` INT(10) NOT NULL,
				    MODIFY COLUMN `writingLevelID`	 INT(10) NOT NULL,
				    MODIFY COLUMN `readingLevelID`	 INT(10) NOT NULL,
				    
				    MODIFY COLUMN `comments` VARCHAR(5000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
		");

		DB::statement("
			ALTER TABLE `evaluation` CONVERT TO CHARACTER SET latin1 COLLATE latin1_swedish_ci
	    ");

		Schema::table('evaluation', function (Blueprint $table) {
			$table->index( 'evaluationID', 'evaluationID' );

			$table->dropIndex([ 'studentID' ]);
			$table->dropIndex([ 'teacherID' ]);
			$table->dropIndex([ 'course' ]);
		});
	}
}
