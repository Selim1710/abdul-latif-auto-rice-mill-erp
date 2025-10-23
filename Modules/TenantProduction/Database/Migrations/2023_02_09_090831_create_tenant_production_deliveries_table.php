<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantProductionDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_production_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_production_id')->constrained();
            $table->string('invoice_no')->unique();
            $table->date('date')->nullable();
            $table->double('total_delivery_qty',3)->nullable();
            $table->double('total_delivery_scale',3)->nullable();
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
        Schema::dropIfExists('tenant_production_deliveries');
    }
}
