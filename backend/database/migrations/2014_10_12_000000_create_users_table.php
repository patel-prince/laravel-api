<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('firstname',50);
            $table->string('lastname',50);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->text('verification_code')->nullable();
            $table->string('password')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('image_url')->nullable();
            $table->foreignId('hospital_id')->nullable();
            $table->string('registered_with')->default('email');
            $table->string('register_id')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0:Inactive, 1:Active');
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
        Schema::dropIfExists('users');
    }
}
