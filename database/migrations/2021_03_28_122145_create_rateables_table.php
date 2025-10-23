<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateablesTable extends Migration
{
    public function up()
    {
        Schema::create('rateables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('labor_bill_id');
            $table->bigInteger('labor_bill_rate_id');
            $table->morphs('rateable');
            $table->float('qty')->comment = "Total Labor";
            $table->float('rate');
            $table->float('total');
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
        Schema::dropIfExists('rateables');
    }
}
