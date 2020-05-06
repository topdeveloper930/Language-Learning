<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationRegions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    DB::statement("
				ALTER TABLE `locationRegions`
				    MODIFY COLUMN `name` VARBINARY(128) NOT NULL
		");

	    DB::statement("
				ALTER TABLE `locationRegions`
				    MODIFY COLUMN `name` VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
		");

	    DB::statement("
			ALTER TABLE `locationRegions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
	    ");

	    Schema::table('locationRegions', function (Blueprint $table) {
		    $table->dropUnique('name');

		    $table->unique([ 'name', 'id_country' ]);
	    });

	    DB::unprepared(file_get_contents('database/sql/up/2020_03_10_insert_locationRegions_table.sql'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    DB::unprepared(file_get_contents('database/sql/down/2020_03_10_dump_locationRegions_table.sql'));
    }
}
