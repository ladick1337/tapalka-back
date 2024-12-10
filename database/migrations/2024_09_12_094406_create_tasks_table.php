<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tasks')){
            return;
        }
        Schema::create('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->string('type');
            $table->string('url');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('picture')->nullable();
            $table->integer('timeout')->default(0);
            $table->integer('complete_count')->default(0);
            $table->string('telegram_channel_id')->nullable();
            $table->integer('reward');
            $table->string('lang');
            $table->timestamps();

            $table->index('type');
            $table->index('lang');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
