<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->string('sale_date')->nullable();
            $table->string('transport_name')->nullable();
            $table->enum('party_type',['1','2'])->default(1)->comment = ' 1 = General , 2 = Walking';
            $table->foreignId('party_id')->nullable()->constrained();
            $table->string('party_name')->nullable();
            $table->enum('sale_type',['1','2'])->comment = " 1 = Regular , 2 = Pre Order";
            $table->enum('sale_status',['1','2','3','4'])->comment = " 1 = Ordered , 2 = Pending , 3 = Rejected , 4 = Approved";
            $table->string('document')->nullable();
            $table->string('discount')->nullable()->default(0);
            $table->string('total_sale_qty')->nullable()->default(0);
            $table->string('total_delivery_qty')->default(0);
            $table->string('total_return_qty')->nullable()->default(0);
            $table->string('total_sale_sub_total')->nullable()->default(0);
            $table->string('total_delivery_sub_total')->nullable()->default(0);
            $table->string('total_return_sub_total')->nullable()->default(0);
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
        Schema::dropIfExists('sales');
    }
}
