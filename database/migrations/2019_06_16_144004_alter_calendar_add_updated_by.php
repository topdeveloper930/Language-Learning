<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCalendarAddUpdatedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('calendar', function (Blueprint $table) {
		    $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('createDate');
		    $table->string( 'updated_by', 50 )->nullable()->after('createdBy');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('calendar', function (Blueprint $table) {
		    $table->dropColumn(['updated_at', 'updated_by']);
	    });
    }
}
