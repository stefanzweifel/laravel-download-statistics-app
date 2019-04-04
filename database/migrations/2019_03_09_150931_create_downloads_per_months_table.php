<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadsPerMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloads_per_months', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('version');
            $table->string('minor_version');
            $table->year('year');
            $table->string('month');
            $table->string('date');
            $table->bigInteger('downloads')->nullable();

            $table->timestamps();

            $table->index(['minor_version', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('downloads_per_months');
    }
}
