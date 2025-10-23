<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_productions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('tenant_id')->constrained();
            $table->foreignId('mill_id')->constrained();
            $table->date('date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->double('total_raw_scale',3)->nullable();
            $table->double('total_merge_scale',3)->nullable();
            $table->double('total_use_product_qty',3)->nullable();
            $table->double('total_milling',3)->nullable();
            $table->double('total_expense',3)->nullable();
            $table->double('total_delivery_scale')->nullable();
            $table->double('total_stock_scale')->nullable();
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
        Schema::dropIfExists('tenant_productions');
    }
}
