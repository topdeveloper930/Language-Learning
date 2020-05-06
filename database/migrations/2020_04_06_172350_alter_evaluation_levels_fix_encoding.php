<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEvaluationLevelsFixEncoding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    DB::statement("
				ALTER TABLE `evaluationLevels`
				    MODIFY COLUMN `skill`			VARBINARY(64)	NOT NULL,
				    MODIFY COLUMN `title`			VARBINARY(64)	NOT NULL,
				    MODIFY COLUMN `titleES`			VARBINARY(64)	NOT NULL,
				    MODIFY COLUMN `description`		VARBINARY(5000)	NOT NULL,
				    MODIFY COLUMN `descriptionES`	VARBINARY(5000)	NOT NULL
		");

	    DB::statement("
				ALTER TABLE `evaluationLevels`
				    MODIFY COLUMN `skill`			  VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `title`			  VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `titleES`			  VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `description`		VARCHAR(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `descriptionES`	VARCHAR(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `level`				  TINYINT NOT NULL
		");

	    DB::statement("
			ALTER TABLE `evaluationLevels` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
	    ");

	    Schema::table('evaluationLevels', function (Blueprint $table) {
		    $table->dropIndex('evaluationLevelsID');

		    $table->index(['skill', 'level']);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('evaluationLevels', function (Blueprint $table) {
		    $table->dropIndex(['skill', 'level']);

		    $table->index('evaluationLevelsID', 'evaluationLevelsID');
	    });

	    DB::statement("
				ALTER TABLE `evaluationLevels`
				    MODIFY COLUMN `skill`			VARBINARY(255)	NOT NULL,
				    MODIFY COLUMN `title`			VARBINARY(255)	NOT NULL,
				    MODIFY COLUMN `titleES`			VARBINARY(255)	NOT NULL,
				    MODIFY COLUMN `description`		VARBINARY(5000)	NOT NULL,
				    MODIFY COLUMN `descriptionES`	VARBINARY(5000)	NOT NULL
		");

	    DB::statement("
			ALTER TABLE `evaluationLevels` CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin
	    ");

	    DB::statement("
				ALTER TABLE `evaluationLevels`
				    MODIFY COLUMN `skill`			 VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `title`			 VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `titleES`			 VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `description`		VARCHAR(5000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `descriptionES`	VARCHAR(5000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `level`				  INT(10) NOT NULL
		");
    }
}
