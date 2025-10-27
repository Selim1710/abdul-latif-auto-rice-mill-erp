<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaborBillDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labor_bill_details', function (Blueprint $table) {
            $table->id();
            $table->integer('labor_bill_id')->nullable();
            $table->integer('labor_bill_rate_detail_id')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->double('rate')->nullable();
            $table->double('qty')->nullable();
            $table->double('amount')->nullable();
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
        Schema::dropIfExists('labor_bill_details');
    }
}
