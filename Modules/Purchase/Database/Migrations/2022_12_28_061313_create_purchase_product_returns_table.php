<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseProductReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_product_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained();
            $table->string('invoice_no')->nullable();
            $table->foreignId('warehouse_id')->nullable()->constrained();
            $table->foreignId('product_id')->constrained();
            $table->string('price')->default(0);
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
        Schema::dropIfExists('purchase_product_returns');
    }
}
