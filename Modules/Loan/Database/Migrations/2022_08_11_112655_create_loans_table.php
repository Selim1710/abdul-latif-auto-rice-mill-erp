<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_categories_id');
            $table->foreign('loan_categories_id')->references('id')->on('loan_categories');
            $table->unsignedBigInteger('bank_id');
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->string('reference_no')->nullable();
            $table->bigInteger('loan_amount')->nullable();
            $table->bigInteger('loan_percentage_percent')->nullable();
            $table->bigInteger('loan_percentage_tk')->nullable();
            $table->bigInteger('processing_charge')->nullable();
            $table->bigInteger('misc_charge')->nullable();
            $table->string('apply_date')->nullable();
            $table->bigInteger('total_amount')->nullable();
            $table->bigInteger('total_installment_month')->nullable();
            $table->bigInteger('monthly_installment')->nullable();
            $table->string('document')->nullable();
            $table->longText('note')->nullable();
            $table->string('start_date')->nullable();
            $table->enum('status',['1','2'])->default(2)->comment = "1 = active , 2 = in-active";
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
        Schema::dropIfExists('loans');
    }
}
