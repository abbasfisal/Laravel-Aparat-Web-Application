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
            $table->string('mobile', 13)->unique()->nullable();
            $table->string('email')->unique()->nullable();

            $table->string('name')->nullable();
            $table->string('password')->nullable();

            $table->enum('type', \App\Models\User::TYPES)
                ->default(\App\Models\User::USER_TYPE);

            $table->string('avatar')->nullable();
            $table->string('website')->nullable();
            $table->string('verify_code')->nullable();
            $table->timestamp('verified_at')->nullable();

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
