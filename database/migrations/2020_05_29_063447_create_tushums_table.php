<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTushumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tushums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('averageyear', 10, 3);
            $table->decimal('lastyearthismonth', 10, 3);
            $table->decimal('lastmonth', 10, 3);
            $table->decimal('thismonth', 10, 3);
            $table->decimal('subaverageyear', 10, 3);
            $table->decimal('sublastyear', 10, 3);
            $table->decimal('sublastmonth', 10, 3);
            $table->decimal('result', 10, 3);
            $table->foreignId('mfo_id');
            $table->foreignId('bank_id');
            $table->integer('month');
            $table->integer('year');
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
        Schema::dropIfExists('tushums');
    }
}
