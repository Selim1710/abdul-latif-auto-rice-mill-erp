<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->date('date');
            $table->foreignId('truck_id')->constrained();
            $table->enum('party_type',['1','2'])->default(1)->comment = ' 1 = General , 2 = Walking';
            $table->foreignId('party_id')->nullable()->constrained();
            $table->string('party_name')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('rent_name')->nullable();
            $table->double('rent_amount',2)->nullable();
            $table->double('total_expense',2)->nullable();
            $table->double('income',2)->nullable();
            $table->text('note')->nullable();
            $table->enum('status',['1','2','3'])->default(3)->comment = " 1 = Approve , 2 = Reject , 3 = Pending";
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
        Schema::dropIfExists('transports');
    }
}
