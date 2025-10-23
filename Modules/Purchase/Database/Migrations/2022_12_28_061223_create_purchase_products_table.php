<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained();
            $table->foreignId('warehouse_id')->nullable()->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('qty',3)->nullable()->default(0);
            $table->double('scale',3)->nullable()->default(0);
            $table->double('rec_qty',3)->nullable();
            $table->double('price',3)->nullable()->default(0);
            $table->double('sub_total',3)->nullable()->default(0);
            $table->double('receive_scale',3)->nullable()->default(0);
            $table->double('receive_qty',3)->nullable()->default(0);
            $table->double('return_scale',3)->nullable()->default(0);
            $table->double('return_qty',3)->nullable()->default(0);
            $table->date('purchase_date')->nullable()->default(0);
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
        Schema::dropIfExists('purchase_products');
    }
}
