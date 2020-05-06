<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGiftCardsLogAddTransactionID extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('giftCardsLog', function (Blueprint $table) {
		    $table->dropUnique('giftCardsLogID');

		    $table->unsignedInteger('giftCardsID' )->nullable()->change();
		    $table->unsignedInteger('transactionID')->nullable()->after('paidFor');

		    $table->index(['transactionID']);
		    $table->index(['code']);
	    });

	    DB::statement("
			UPDATE `giftCardsLog` 
			INNER JOIN `transactions` ON `giftCardsLog`.`studentID` = `transactions`.`userID` 
	                                  AND `giftCardsLog`.`paidFor` = `transactions`.`paymentFor`
			                          AND 'Completed' = `transactions`.`paymentStatus`
	                                  AND SUBSTR(`giftCardsLog`.`createDate`, 1, 16)  = SUBSTR(`transactions`.`transactionDate`, 1, 16)
			SET `giftCardsLog`.`transactionID` = `transactions`.`transactionID`
		");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('giftCardsLog', function (Blueprint $table) {
		    $table->dropIndex(['transactionID']);
		    $table->dropIndex(['code']);

		    $table->dropColumn('transactionID');

		    $table->unique('giftCardsLogID');
	    });
    }
}
