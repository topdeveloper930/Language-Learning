<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('purchases', function (Blueprint $table) {
		    $table->bigIncrements('id');
		    $table->unsignedInteger('transactionID')->nullable();
		    $table->string( 'gateway_reference' )->nullable()->comment('Transaction or order # of the payment service');
		    $table->string('courseType', 48)->nullable();
		    $table->decimal('hours', 5, 2)->nullable()->comment('Number of hours purchased');
		    $table->tinyInteger('numStudents', false, true)->nullable();
		    $table->string( 'coupon_code', 16 )->nullable()->comment('Code of the coupon used');
		    $table->string( 'giftcard_code', 48 )->nullable()->comment('Code of the dift card used');
		    $table->string('description')->nullable()->comment('Description of the purchase (Gift Card)');
		    $table->text( 'balance' )->nullable()->comment('JSON of the calculated values and other data to cache');
		    $table->text( 'error' )->nullable()->comment('Response from the payment system');
		    $table->timestamps();

		    $table->index([ 'transactionID' ]);
		    $table->index([ 'gateway_reference' ]);
		    $table->index([ 'courseType' ]);
		    $table->index([ 'coupon_code' ]);
		    $table->index([ 'giftcard_code' ]);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('purchases');
    }
}
