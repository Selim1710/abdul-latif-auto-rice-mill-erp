<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionRawProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_raw_products', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->foreignId('production_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('price',3)->nullable();
            $table->double('qty',3)->nullable();
            $table->double('use_qty',3)->nullable();
            $table->double('rest_qty',3)->nullable();
            $table->double('scale',3)->nullable();
            $table->double('use_scale',3)->nullable();
            $table->double('rest_scale',3)->nullable();
            $table->double('pro_qty',3)->nullable();
            $table->double('use_pro_qty',3)->nullable();
            $table->double('rest_pro_qty',3)->nullable();
            $table->double('milling',3)->nullable();
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
        Schema::dropIfExists('production_raw_products');
    }
}
