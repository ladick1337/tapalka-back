<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('users')){
            Schema::rename('users', 'clients');
        }elseif(Schema::hasTable('clients')){
            return;
        }else {
            Schema::create('users', function (Blueprint $table) {
                $table->unsignedBigInteger('id', true);
                $table->string('chat_id');
                $table->string('name');
                $table->string('username')->nullable();
                $table->integer('energy');
                $table->integer('energy_max');
                $table->integer('energy_level')->default(1);
                $table->integer('energy_charges')->default(0);
                $table->timestamp('energy_bonus_at');
                $table->integer('invited_friends')->default(0);
                $table->integer('balance')->default(0);
                $table->string('lang');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->timestamp('activity_at')->useCurrent();
                $table->boolean('is_alive')->default(true);
                $table->timestamps();

                $table->unique('chat_id');
                $table->index('username');
                $table->index('parent_id');
                $table->index('lang');

                $table->foreign('parent_id')->references('id')->on('clients')->onDelete('SET NULL');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
