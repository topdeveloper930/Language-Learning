<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixLanguageMasterCharset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    DB::statement("
				ALTER TABLE `languageMaster`
				    MODIFY COLUMN `name`		VARBINARY(64)		NOT NULL,
				    MODIFY COLUMN `taught`		TINYINT				NOT NULL DEFAULT 0,
				    MODIFY COLUMN `position`	INT					NOT NULL DEFAULT 0,
				    MODIFY COLUMN `altName`		VARBINARY(1000)		NOT NULL,
				    MODIFY COLUMN `description`	VARBINARY(5000)		NOT NULL
		");

	    DB::statement("
				ALTER TABLE `languageMaster`
				    MODIFY COLUMN `name`		  VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `altName`		VARCHAR(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `description`			 TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
		");

	    DB::statement("
			ALTER TABLE `languageMaster` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
	    ");

	    Schema::table('languageMaster', function (Blueprint $table) {
		    $table->dropIndex('languageID');
		    $table->index(['taught']);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('languageMaster', function (Blueprint $table) {
		    $table->dropIndex(['taught']);
		    $table->index('id','languageID');
	    });

	    DB::statement("
				ALTER TABLE `languageMaster`
				    MODIFY COLUMN `name`		VARBINARY(255)	NOT NULL,
				    MODIFY COLUMN `taught`		TINYINT			NOT NULL,
				    MODIFY COLUMN `position`	INT				NOT NULL,
				    MODIFY COLUMN `altName`		VARBINARY(1000)	NOT NULL,
				    MODIFY COLUMN `description`	VARBINARY(5000)	NOT NULL
		");

	    DB::statement("
				ALTER TABLE `languageMaster`
				    MODIFY COLUMN `name`			 VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `altName`			VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `description`		VARCHAR(5000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
		");

	    DB::statement("
			ALTER TABLE `languageMaster` CONVERT TO CHARACTER SET latin1 COLLATE latin1_swedish_ci
	    ");
    }
}
