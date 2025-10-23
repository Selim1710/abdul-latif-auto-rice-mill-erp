<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleProductReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_product_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained();
            $table->string('invoice_no')->nullable();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('price',3)->nullable()->default(0);
            $table->double('scale',3)->nullable()->default(0);
            $table->double('qty',3)->nullable()->default(0);
            $table->date('date')->nullable();
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
        Schema::dropIfExists('sale_product_returns');
    }
}
