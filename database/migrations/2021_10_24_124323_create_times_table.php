<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('times', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('times', function (Blueprint $table) {
            $table->dropForeign('times_worker_id_foreign');
        });
    }
}