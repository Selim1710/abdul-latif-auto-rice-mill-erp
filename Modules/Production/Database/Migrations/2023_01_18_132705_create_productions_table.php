<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('production_type')->nullable();
            $table->foreignId('mill_id')->constrained();
            $table->date('date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->double('total_raw_scale',3)->nullable();
            $table->double('total_raw_amount',3)->nullable();
            $table->double('total_use_product_qty',3)->nullable();
            $table->double('total_use_product_amount',3)->nullable();
            $table->double('total_milling',3)->nullable();
            $table->double('total_expense',3)->nullable();
            $table->double('total_sale_scale',3)->nullable();
            $table->double('total_sale_amount',3)->nullable();
            $table->double('total_stock_scale',3)->nullable();
            $table->double('total_stock_amount',3)->nullable();
            $table->double('per_unit_scale_cost',3)->nullable();
            $table->enum('production_status',['1','2','3','4'])->default('1')->comment = "1 = Pending , 2 = Cancel , 3 = Processing , 4 = Finished";
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('productions');
    }
}
