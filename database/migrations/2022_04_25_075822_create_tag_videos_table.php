<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained('tags')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('video_id')->constrained('videos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

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
        Schema::dropIfExists('tag_videos');
    }
}
