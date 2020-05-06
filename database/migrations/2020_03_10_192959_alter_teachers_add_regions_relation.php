<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTeachersAddRegionsRelation extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
            UPDATE `teachers`
              JOIN `locationRegions` ON `teachers`.`stateOrigin` = `locationRegions`.`name` AND `teachers`.`countryOriginID` = `locationRegions`.`id_country`
               SET `teachers`.`stateOriginID` = `locationRegions`.`id`
			 WHERE `teachers`.`stateOriginID` = 0
        ");

		DB::statement("
            UPDATE `teachers` 
              JOIN `locationRegions` ON `teachers`.`stateResidence` = `locationRegions`.`name` AND `teachers`.`countryResidenceID` = `locationRegions`.`id_country`
               SET `teachers`.`stateResidenceID` = `locationRegions`.`id`
			 WHERE `teachers`.`stateResidenceID` = 0
        ");

		Schema::table('teachers', function (Blueprint $table) {
			$table->index([ 'stateOriginID' ]);
			$table->index([ 'stateResidenceID' ]);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('teachers', function (Blueprint $table) {
			$table->dropIndex([ 'stateOriginID' ]);
			$table->dropIndex([ 'stateResidenceID' ]);
		});
	}
}
