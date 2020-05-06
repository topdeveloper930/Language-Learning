<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTeachersAddCountriesRelation extends Migration
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
              JOIN `locationCountries` ON `teachers`.`countryOrigin` = `locationCountries`.`name`
               SET `teachers`.`countryOriginID` = `locationCountries`.`id`
			 WHERE `teachers`.`countryOriginID` = 0
        ");

		DB::statement("
            UPDATE `teachers` 
              JOIN `locationCountries` ON `teachers`.`countryResidence` = `locationCountries`.`name`
               SET `teachers`.`countryResidenceID` = `locationCountries`.`id`
			 WHERE `teachers`.`countryResidenceID` = 0
        ");

		Schema::table('teachers', function (Blueprint $table) {
			$table->index([ 'countryOriginID' ]);
			$table->index([ 'countryResidenceID' ]);
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
			$table->dropIndex([ 'countryOriginID' ]);
			$table->dropIndex([ 'countryResidenceID' ]);
		});
	}
}
