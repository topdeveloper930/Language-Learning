<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLearningQuizAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('learningQuiz', function (Blueprint $table) {
	    	$table->smallIncrements('id')->change();

		    $table->tinyInteger( 'sign' )->nullable()->after('distribution');
	    });

	    DB::statement("
				ALTER TABLE `learningQuiz`
				    MODIFY COLUMN `base`			VARBINARY(128)	NOT NULL,
				    MODIFY COLUMN `leftmost`		VARBINARY(128)	NOT NULL,
				    MODIFY COLUMN `rightmost`		VARBINARY(128)	NOT NULL,
				    MODIFY COLUMN `questionLeft`	VARBINARY(128)	NOT NULL,
				    MODIFY COLUMN `questionRight`	VARBINARY(128)	NOT NULL,
				    MODIFY COLUMN `type`			VARBINARY(16)	NOT NULL,
				    MODIFY COLUMN `distribution`	BINARY(2) 		NULL 
		");

	    DB::statement("
				ALTER TABLE `learningQuiz`
				    MODIFY COLUMN `base`			VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `leftmost`		VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `rightmost`		VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `questionLeft`	VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `questionRight`	VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `type`			 VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `distribution`		 CHAR(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL 
		");

	    DB::statement("
			ALTER TABLE `learningQuiz` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
	    ");

	    DB::statement("
			UPDATE `learningQuiz` 
			   SET `distribution` = IF(`id` % 4 = 1, 
			       							'JP', 
			       							IF(`id` % 4 = 2, 
			       							    'FT',
			       							    IF(`id` % 4 = 3, 'IE', 'SN'))),
			       `sign` = IF(`id` IN (2,3,7,9,11,14,17,18,19,24,25,26,28,30,31), -1, 1)
			 WHERE `id` >= 1 AND `id` < 33
	    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('learningQuiz', function (Blueprint $table) {
		    $table->increments('id')->change();

		    $table->dropColumn( 'sign' );
	    });

	    DB::statement("
				ALTER TABLE `learningQuiz`
				    MODIFY COLUMN `base`			VARBINARY(128)	NOT NULL,
				    MODIFY COLUMN `leftmost`		VARBINARY(128)	NOT NULL,
				    MODIFY COLUMN `rightmost`		VARBINARY(128)	NOT NULL,
				    MODIFY COLUMN `questionLeft`	VARBINARY(128)	NOT NULL,
				    MODIFY COLUMN `questionRight`	VARBINARY(128)	NOT NULL,
				    MODIFY COLUMN `type`			VARBINARY(16)	NOT NULL,
				    MODIFY COLUMN `distribution`	BINARY(2) 		NULL 
		");

	    DB::statement("
				ALTER TABLE `learningQuiz`
				    MODIFY COLUMN `base`			TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `leftmost`		TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `rightmost`		TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `questionLeft`	TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `questionRight`	TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `type`			TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `distribution`	TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''
		");

	    DB::statement("
			ALTER TABLE `learningQuiz` CONVERT TO CHARACTER SET latin1 COLLATE latin1_swedish_ci
	    ");

	    DB::statement("
			UPDATE `learningQuiz` SET `distribution` = '' WHERE `id` >= 1 AND `id` < 33
	    ");
    }
}
