<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantProductionDeliveryProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_production_delivery_products', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->foreignId('t_p_d_id')->constrained('tenant_production_deliveries');
            $table->foreignId('product_id')->constrained();
            $table->double('qty',3)->nullable();
            $table->double('scale',3)->nullable();
            $table->double('del_qty',3)->nullable();
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
        Schema::dropIfExists('tenant_production_delivery_products');
    }
}
