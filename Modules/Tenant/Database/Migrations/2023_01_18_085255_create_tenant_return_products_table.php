<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantReturnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_return_products', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->foreignId('tenant_id')->constrained();
            $table->date('date');
            $table->enum('status',['1','2'])->default(2)->comment = "1 = Active , 2 = InActive";
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('tenant_return_products');
    }
}
