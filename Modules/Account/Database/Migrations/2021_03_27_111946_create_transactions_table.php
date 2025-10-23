<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chart_of_head_id')->constrained();
            $table->date('date')->nullable();
            $table->string('voucher_no')->nullable();
            $table->string('voucher_type')->nullable();
            $table->longText('narration')->nullable();
            $table->double('debit')->nullable();
            $table->double('credit')->nullable();
            $table->enum('status',['1','2','3'])->default(1)->comment = " 1 = Approve , 2 = Reject , 3 = Pending";
            $table->enum('is_opening',['1','2'])->default('2')->comment = "1=Yes, 2=No";
            $table->string('created_by')->nullable();
            $table->string('modified_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
