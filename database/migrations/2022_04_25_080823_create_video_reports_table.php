<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained('videos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('video_report_category_id')
                ->constrained('video_report_categories', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();

            $table->string('info');

            $table->smallInteger('first_time')->nullable();
            $table->smallInteger('second_time')->nullable();
            $table->smallInteger('third_time')->nullable();
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
        Schema::dropIfExists('video_reports');
    }
}
