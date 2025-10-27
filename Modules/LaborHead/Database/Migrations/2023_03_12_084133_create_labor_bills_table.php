<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaborBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labor_bills', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->date('date')->nullable();
            $table->integer('labor_head_id')->nullable();
            $table->double('grand_total')->nullable();
            $table->enum('status',['1','2','3'])->default(2)->comment = " 1 = Approve , 2 = Reject , 3 = Pending";
            $table->longText('narration')->nullable();
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
        Schema::dropIfExists('labor_bills');
    }
}
