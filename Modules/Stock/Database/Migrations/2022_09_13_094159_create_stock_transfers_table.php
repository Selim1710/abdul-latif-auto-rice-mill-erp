<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->foreignId('transfer_warehouse_id')->constrained('warehouses');
            $table->foreignId('receive_warehouse_id')->constrained('warehouses');
            $table->enum('status',['1','2'])->default('2')->comment = ' 1 = Active , 2 = InActive';
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('stock_transfers');
    }
}
