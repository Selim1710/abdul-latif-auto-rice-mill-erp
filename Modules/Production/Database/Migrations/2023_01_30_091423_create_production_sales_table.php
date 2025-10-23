<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained();
            $table->string('invoice_no')->nullable();
            $table->string('sale_date')->nullable();
            $table->enum('party_type',['1','2'])->default(1)->comment = ' 1 = General , 2 = Walking';
            $table->foreignId('party_id')->nullable()->constrained();
            $table->string('party_name')->nullable();
            $table->string('document')->nullable();
            $table->string('discount')->nullable()->default(0);
            $table->string('total_sale_qty')->nullable()->default(0);
            $table->string('total_sale_scale')->nullable()->default(0);
            $table->string('total_sale_sub_total')->nullable()->default(0);
            $table->double('previous_due')->nullable()->default(0);
            $table->double('net_total')->nullable()->default(0);
            $table->double('paid_amount')->nullable()->default(0);
            $table->double('due_amount')->nullable()->default(0);
            $table->enum('payment_status',['1','2','3'])->comment="1=Paid,2=Partial,3=Due";
            $table->enum('payment_method',['1','2','3'])->nullable()->comment="1=Cash,2=Bank Deposit,3=Mobile";
            $table->foreignId('account_id')->nullable()->constrained('chart_of_heads');
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
        Schema::dropIfExists('production_sales');
    }
}
