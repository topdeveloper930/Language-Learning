<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTestimonialsFixEncoding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    DB::statement("
				ALTER TABLE `testimonials`
				    MODIFY COLUMN `firstName`		VARBINARY(150)	NOT NULL,
				    MODIFY COLUMN `lastName`		VARBINARY(150)	NOT NULL,
				    MODIFY COLUMN `language`		VARBINARY(150)	NOT NULL,
				    MODIFY COLUMN `profession`		VARBINARY(150)	NOT NULL,
				    MODIFY COLUMN `professionES`	VARBINARY(255)	NOT NULL,
				    MODIFY COLUMN `comment`			VARBINARY(5000)	NOT NULL,
				    MODIFY COLUMN `commentES`		VARBINARY(5000) NOT NULL
		");

	    DB::statement("
				ALTER TABLE `testimonials`
				    MODIFY COLUMN `firstName`		VARCHAR(150)	CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `lastName`		VARCHAR(150)	CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `language`		VARCHAR(150)	CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `profession`		VARCHAR(150)	CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `professionES`	VARCHAR(255)	CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `comment`			VARCHAR(5000)	CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				    MODIFY COLUMN `commentES`		VARCHAR(5000)	CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
		");

	    DB::statement("
			ALTER TABLE `testimonials` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
	    ");

	    Schema::table('testimonials', function (Blueprint $table) {
		    $table->dropIndex('testimonialID');

		    $table->index(['language']);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('testimonials', function (Blueprint $table) {
		    $table->dropIndex(['language']);

		    $table->index('testimonialID', 'testimonialID');
	    });


	    DB::statement("
				ALTER TABLE `testimonials`
				    MODIFY COLUMN `firstName`		VARBINARY(150)	NOT NULL,
				    MODIFY COLUMN `lastName`		VARBINARY(150)	NOT NULL,
				    MODIFY COLUMN `language`		VARBINARY(150)	NOT NULL,
				    MODIFY COLUMN `profession`		VARBINARY(150)	NOT NULL,
				    MODIFY COLUMN `professionES`	VARBINARY(255)	NOT NULL,
				    MODIFY COLUMN `comment`			VARBINARY(5000)	NOT NULL,
				    MODIFY COLUMN `commentES`		VARBINARY(5000) NOT NULL
		");

	    DB::statement("
			ALTER TABLE `testimonials` CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin
	    ");

	    DB::statement("
				ALTER TABLE `testimonials`
				    MODIFY COLUMN `firstName`		VARCHAR(150)	CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `lastName`		VARCHAR(150)	CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `language`		VARCHAR(150)	CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `profession`		VARCHAR(150)	CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `professionES`	VARCHAR(255)	CHARACTER SET utf8 COLLATE	 utf8_bin 		   NOT NULL,
				    MODIFY COLUMN `comment`			VARCHAR(5000)	CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
				    MODIFY COLUMN `commentES`		VARCHAR(5000)	CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
		");
    }
}
