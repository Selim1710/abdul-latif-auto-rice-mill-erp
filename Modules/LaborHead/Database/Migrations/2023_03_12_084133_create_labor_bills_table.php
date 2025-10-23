<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaborBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labor_bills', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->foreignId('labor_head_id')->constrained();
            $table->foreignId('labor_bill_rate_id')->constrained();
            $table->double('rate')->nullable();
            $table->double('qty')->nullable();
            $table->double('amount')->nullable();
            $table->enum('status',['1','2','3'])->default(2)->comment = " 1 = Approve , 2 = Reject , 3 = Pending";
            $table->longText('narration')->nullable();
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('labor_bills');
    }
}
