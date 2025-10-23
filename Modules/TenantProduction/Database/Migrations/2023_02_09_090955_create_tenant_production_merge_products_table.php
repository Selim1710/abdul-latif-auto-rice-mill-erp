<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantProductionMergeProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_production_merge_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_production_id')->constrained();
            $table->string('invoice_no')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('price',3)->nullable();
            $table->double('qty',3)->nullable();
            $table->double('scale',3)->nullable();
            $table->double('mer_qty',3)->nullable();
            $table->double('sub_total',3)->nullable();
            $table->enum('type',['1','2','3'])->default(1)->comment = "1 = Milling , 2 = Delivery , 3 = Stock";
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
        Schema::dropIfExists('tenant_production_merge_products');
    }
}
