<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantReceiveProductListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_receive_product_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_receive_product_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('qty',2)->nullable()->default(0);
            $table->double('scale',2)->nullable()->default(0);
            $table->double('rec_qty')->nullable()->default(0);
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
        Schema::dropIfExists('tenant_receive_product_lists');
    }
}
