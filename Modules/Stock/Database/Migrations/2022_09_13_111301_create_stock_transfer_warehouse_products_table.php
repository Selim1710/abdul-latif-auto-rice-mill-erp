<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransferWarehouseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfer_warehouse_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_transfer_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('scale',3);
            $table->bigInteger('qty');
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
        Schema::dropIfExists('stock_transfer_warehouse_products');
    }
}
