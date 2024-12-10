<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sendings', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('lang');
            $table->longText('text');
            $table->string('status')->default(\App\Consts\SendingStatuses::ACTIVE);
            $table->integer('users_complete')->default(0);
            $table->integer('users_all');
            $table->timestamps();

            $table->index('lang');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sendings');
    }
}
