<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('product_name');
            $table->string('product_code')->unique();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->string('purchase_price')->default(0)->nullable();
            $table->string('sale_price')->default(0)->nullable();
            $table->double('alert_qty')->nullable();
            $table->enum('opening_stock',['1','2'])->default('2')->comment = "1=Yes, 2=No";
            $table->unsignedBigInteger('opening_warehouse_id')->nullable();
            $table->foreign('opening_warehouse_id')->references('id')->on('warehouses');
            $table->double('opening_stock_qty')->nullable();
            $table->enum('status',['1','2'])->default('1')->comment = "1=Active, 2=Inactive";
            $table->string('created_by')->nullable();
            $table->string('modified_by')->nullable();
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
        Schema::dropIfExists('products');
    }
}
