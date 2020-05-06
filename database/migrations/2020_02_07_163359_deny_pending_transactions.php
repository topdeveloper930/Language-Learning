<?php

use Illuminate\Database\Migrations\Migration;

class DenyPendingTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::table('transactions')
                                      ->leftJoin('purchases', 'transactions.transactionID', '=', 'purchases.transactionID')
                                      ->whereNull('purchases.id')
                                      ->where('transactions.paymentStatus', \App\Transaction::PENDING)
                                      ->update(['transactions.paymentStatus' => \App\Transaction::DENIED]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
