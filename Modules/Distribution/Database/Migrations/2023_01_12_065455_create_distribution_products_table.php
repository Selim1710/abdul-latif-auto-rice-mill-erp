<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribution_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('qty')->nullable()->default(0);
            $table->double('scale')->nullable()->default(0);
            $table->double('dis_qty')->nullable()->default(0);
            $table->date('date')->nullable()->default(0);
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
        Schema::dropIfExists('distribution_products');
    }
}
