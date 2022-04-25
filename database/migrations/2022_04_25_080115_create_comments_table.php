<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('video_id')->constrained('videos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('comments', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('body');
            $table->timestamp('accepted_at')->nullable();

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
        Schema::dropIfExists('comments');
    }
}
