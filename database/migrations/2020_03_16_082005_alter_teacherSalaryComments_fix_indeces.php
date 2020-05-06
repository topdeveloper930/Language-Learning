<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTeacherSalaryCommentsFixIndeces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		DB::statement("
				ALTER TABLE `teacherSalaryComments`
				    MODIFY COLUMN `comments` VARBINARY(5000) NULL,
				    MODIFY COLUMN `month`	 ENUM('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December') NULL,
				    MODIFY COLUMN `year`	 SMALLINT NULL
		");

		DB::statement("
				ALTER TABLE `teacherSalaryComments`
				    MODIFY COLUMN `comments` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
		");

		DB::statement("
			ALTER TABLE `teacherSalaryComments` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
	    ");

		Schema::table('teacherSalaryComments', function (Blueprint $table) {
			$table->dropIndex('teacherSalaryCommentsID');

			$table->index(['teacherID']);
			$table->index(['month']);
			$table->index(['year']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('teacherSalaryComments', function (Blueprint $table) {
			$table->dropIndex(['teacherID']);
			$table->dropIndex(['month']);
			$table->dropIndex(['year']);

			$table->index('teacherSalaryCommentsID', 'teacherSalaryCommentsID');
		});

		DB::statement("
				ALTER TABLE `teacherSalaryComments`
				    MODIFY COLUMN `comments` VARBINARY(5000) NULL,
				    MODIFY COLUMN `month`	   VARBINARY(20) NULL,
				    MODIFY COLUMN `year`	   VARBINARY(10) NULL
		");

		DB::statement("
			ALTER TABLE `teacherSalaryComments` CONVERT TO CHARACTER SET latin1 COLLATE latin1_swedish_ci
	    ");

		DB::statement("
				ALTER TABLE `teacherSalaryComments`
				    MODIFY COLUMN `comments` VARCHAR(5000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
				    MODIFY COLUMN `month`	   VARCHAR(20) NULL,
				    MODIFY COLUMN `year`	   VARCHAR(10) NULL
		");
	}
}
