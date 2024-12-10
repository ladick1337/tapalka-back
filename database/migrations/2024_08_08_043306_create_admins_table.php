<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('admins')){
            return;
        }
        Schema::create('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->string('login');
            $table->string('password');
            $table->string('tfa_secret')->nullable();
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->index('role_id');

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
