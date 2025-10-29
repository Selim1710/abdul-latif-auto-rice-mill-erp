<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantProductionRawProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_production_raw_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_production_id')->constrained();
            $table->date('date')->nullable();
            $table->foreignId('warehouse_id')->constrained();
            $table->string('batch_no')->nullable();
            $table->foreignId('product_id')->constrained();
            $table->double('qty', 3)->nullable();
            $table->double('use_qty', 3)->nullable();
            $table->double('scale', 3)->nullable();
            $table->double('use_scale', 3)->nullable();
            $table->double('pro_qty', 3)->nullable();
            $table->double('use_pro_qty', 3)->nullable();
            $table->double('milling', 3)->nullable();
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
        Schema::dropIfExists('tenant_production_raw_products');
    }
}
