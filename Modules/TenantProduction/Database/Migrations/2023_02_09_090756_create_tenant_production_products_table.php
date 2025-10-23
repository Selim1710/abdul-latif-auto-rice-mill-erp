<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantProductionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_production_products', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('tenant_production_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('qty',3)->nullable();
            $table->double('scale',3)->nullable();
            $table->double('production_qty',3)->nullable();
            $table->foreignId('use_warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('use_product_id')->nullable()->constrained('products');
            $table->double('use_qty',3)->nullable();
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
        Schema::dropIfExists('tenant_production_products');
    }
}
