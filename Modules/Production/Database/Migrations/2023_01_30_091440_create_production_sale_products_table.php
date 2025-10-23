<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_sale_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_sale_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('qty',3)->nullable();
            $table->double('scale',3)->nullable();
            $table->double('sel_qty',3)->nullable();
            $table->double('price',3)->nullable();
            $table->double('sub_total',3)->nullable();
            $table->foreignId('use_warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('use_product_id')->nullable()->constrained('products');
            $table->double('use_qty',3)->nullable();
            $table->double('use_price',3)->nullable();
            $table->double('use_sub_total',3)->nullable();
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
        Schema::dropIfExists('production_sale_products');
    }
}
