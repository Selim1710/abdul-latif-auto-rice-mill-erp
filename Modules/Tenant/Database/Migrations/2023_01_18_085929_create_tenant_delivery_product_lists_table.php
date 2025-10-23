<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantDeliveryProductListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_delivery_product_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_delivery_product_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('qty',2)->nullable()->default(0);
            $table->double('scale',2)->nullable()->default(0);
            $table->double('del_qty',2)->nullable()->default(0);
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
        Schema::dropIfExists('tenant_delivery_product_lists');
    }
}
