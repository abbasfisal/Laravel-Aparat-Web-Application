<?php

use App\Models\Video;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('category_id')->constrained('categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('channel_category_id')
                ->constrained('categories', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('slug');
            $table->string('title');
            $table->text('info')->nullable();

            $table->integer('duration');
            $table->boolean('enable_comments')->default(true);
            $table->string('banner')->nullable();

            $table->timestamp('publish_at')->nullable();

            $table->enum('state', Video::states)->default(Video::state_pending);


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
        Schema::dropIfExists('videos');
    }
}
