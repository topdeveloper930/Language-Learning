<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEvaluationNullables extends Migration
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
				    MODIFY COLUMN `speakingLevel`	TINYINT UNSIGNED NULL,
				    MODIFY COLUMN `listeningLevel`	TINYINT UNSIGNED NULL,
				    MODIFY COLUMN `writingLevel`	TINYINT UNSIGNED NULL,
				    MODIFY COLUMN `readingLevel`	TINYINT UNSIGNED NULL,

				    MODIFY COLUMN `speakingLevelID`	 TINYINT UNSIGNED NULL,
				    MODIFY COLUMN `listeningLevelID` TINYINT UNSIGNED NULL,
				    MODIFY COLUMN `writingLevelID`	 TINYINT UNSIGNED NULL,
				    MODIFY COLUMN `readingLevelID`	 TINYINT UNSIGNED NULL
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
				ALTER TABLE `evaluation`
				    MODIFY COLUMN `speakingLevel`	TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `listeningLevel`	TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `writingLevel`	TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `readingLevel`	TINYINT UNSIGNED NOT NULL,

				    MODIFY COLUMN `speakingLevelID`	 TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `listeningLevelID` TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `writingLevelID`	 TINYINT UNSIGNED NOT NULL,
				    MODIFY COLUMN `readingLevelID`	 TINYINT UNSIGNED NOT NULL
		");
    }
}
