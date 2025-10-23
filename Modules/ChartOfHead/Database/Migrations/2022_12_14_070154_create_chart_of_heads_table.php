<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartOfHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_of_heads', function (Blueprint $table) {
            $table->id();
            $table->enum('master_head',['1','2','3','4','5','6','7','8'])->default('1')->comment = "1 = Current Asset , 2 = Non-Current Asset , 3 = Current Liabilities , 4 = Non-Current Liabilities , 5 = Income , 6 = Cost Of Income , 7 = Expense , 8 = Equity";
            $table->enum('type',['1','2','3'])->default('1')->comment = " 1 = Head , 2 = Sub Head , 3 = Child Head";
            $table->bigInteger('head_id')->nullable();
            $table->bigInteger('sub_head_id')->nullable();
            $table->foreignId('party_id')->nullable()->constrained();
            $table->foreignId('bank_id')->nullable()->constrained();
            $table->foreignId('mobile_bank_id')->nullable()->constrained();
            $table->string('name')->nullable();
            $table->enum('classification',['1','2','3','4','5','6','7','8'])->default('1')->comment = "1 = Default , 2 = Party , 3 = Labor , 4 = Expense , 5 = Bank , 6 = Mobile Bank , 7 = Mill , 8 = Transport";
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
        Schema::dropIfExists('chart_of_heads');
    }
}
