<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    DB::statement("
				ALTER TABLE `transactions` MODIFY COLUMN `transactionDate` TIMESTAMP NULL DEFAULT NULL
		");


	    Schema::table('transactions', function (Blueprint $table) {
		    $table->dropIndex('transactionID');
		    $table->string('gatewayTransID', 60)->nullable()->change();
		    $table->string('paymentFee', 60)->nullable()->change();
		    $table->string('emailUsed', 60)->nullable()->change();
		    $table->string('referrer', 150)->nullable()->change();
		    $table->decimal('referrerCommission', 5, 2 )->nullable()->change();

		    $table->index('gatewayTransID');
	    });

	    DB::statement("
				ALTER TABLE `transactions` 
				    MODIFY COLUMN `paymentCurrency` ENUM('USD') NOT NULL DEFAULT 'USD',
				    MODIFY COLUMN `paymentStatus` ENUM('Pending', 'Completed', 'Denied') NOT NULL DEFAULT 'Pending',
				    MODIFY COLUMN `transactionDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

		");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('transactions', function (Blueprint $table) {
		    $table->dropIndex(['gatewayTransID']);
		    $table->dropIndex(['course_id']);
		    $table->dropColumn('course_id');
	    });

	    DB::statement("
				ALTER TABLE `transactions` 
				    MODIFY COLUMN `paymentCurrency` VARCHAR(60) NOT NULL DEFAULT 'USD',
				    MODIFY COLUMN `paymentStatus` VARCHAR(60) NOT NULL DEFAULT 'Pending'
		");
    }
}
