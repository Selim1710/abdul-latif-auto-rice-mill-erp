<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mills', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->longText('address')->nullable();
            $table->enum('status',['1','2'])->default('1')->comment = "1=Active, 2=Inactive";
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
        Schema::dropIfExists('mills');
    }
}
