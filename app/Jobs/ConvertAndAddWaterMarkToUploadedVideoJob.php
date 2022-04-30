<?php

namespace App\Jobs;

use App\Http\Requests\Video\CreateVideoRequest;
use App\Models\Video;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertAndAddWaterMarkToUploadedVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Video
     */
    private $video;
    /**
     * @var CreateVideoRequest
     */
    private $videoId;

    private $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video, string $videoId)
    {
        $this->video = $video;
        $this->videoId = $videoId;
        $this->userId = Auth::id();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        //tmp video path
        $uploadedVideoPath = '/tmp/' . $this->videoId;

        //open video
        $videoUploaded = FFMpeg::fromDisk('videos')
            ->open($uploadedVideoPath);

        //تنظیم کدک
        $format = new X264('libmp3lame');

        //تنظیمات واترماک
        $filter = new CustomFilter("drawtext=text='goooogle':
             fontcolor=blue: fontsize=24:
              box=1: boxcolor=white@0.5:
               boxborderw=5:
                x=10: y=(h - text_h - 10)");


        //اعمال واترمارک به ویدیو
        $videoFile = $videoUploaded->addFilter($filter)
            ->export()
            ->toDisk(Storage::disk('videos'))
            ->inFormat($format);

        //ذخیره در دیسک
        $videoFile->save($this->userId . '/' . $this->video->slug);

        $this->video->duration = $videoUploaded->getDurationInSeconds();
        $this->video->save();

        //delete from tmp dir
        Storage::disk('videos')->delete($uploadedVideoPath);

    }
}
