<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->integer('production_id')->nullable();
            $table->integer('tenant_production_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->integer('is_deleted')->default(2)->comment('2=not,1=deleted');
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
        Schema::dropIfExists('production_batches');
    }
}
