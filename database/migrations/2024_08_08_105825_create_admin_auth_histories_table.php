<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminAuthHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('admin_auth_histories')){
            return;
        }
        Schema::create('admin_auth_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->string('ip');
            $table->unsignedBigInteger('admin_id');
            $table->timestamps();

            $table->index('admin_id');

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_auth_histories');
    }
}
