<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->unique();
            $table->string('name');
            $table->unsignedBigInteger('asset_type_id')->nullable();
            $table->foreign('asset_type_id')->references('id')->on('asset_types');
            $table->date('purchase_date')->nullable();
            $table->float('warranty')->nullable();
            $table->string('user')->nullable();
            $table->string('location')->nullable();
            $table->string('photo')->nullable();
            $table->double('cost');
            $table->integer('asset_status')->nullable();
            $table->text('description')->nullable();
            $table->enum('status',['1','2'])->default('1')->comment = "1=Active, 2=Inactive";
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
        Schema::dropIfExists('assets');
    }
}
