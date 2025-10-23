<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('qty',3)->nullable()->default(0);
            $table->double('scale',3)->nullable()->default(0);
            $table->double('sel_qty',3)->nullable()->default(0);
            $table->double('price',3)->nullable()->default(0);
            $table->double('sub_total',3)->nullable()->default(0);
            $table->double('delivery_scale',3)->nullable()->default(0);
            $table->double('delivery_qty',3)->nullable()->default(0);
            $table->double('return_scale',3)->nullable()->default(0);
            $table->double('return_qty',3)->nullable()->default(0);
            $table->date('date')->nullable();
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('sale_products');
    }
}
