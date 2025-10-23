<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantProductionExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_production_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_production_id')->constrained();
            $table->foreignId('expense_item_id')->constrained();
            $table->double('expense_cost',3)->nullable();
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
        Schema::dropIfExists('tenant_production_expenses');
    }
}
