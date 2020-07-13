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
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique('email');
            $table->string('password');
            $table->string('api_token')->nullable(true);
            $table->string('api_key')->nullable(true);
            $table->timestamp('api_key_expired')->nullable(true);
            $table->rememberToken();
            $table->boolean('is_active');
            $table->timestamps();

            // authorize_key избыточно
            $table->index(['email', 'password'], 'c_authorize_key');
            $table->index(['api_key', 'is_active'], 'c_api_key');

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
