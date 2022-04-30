<?php

namespace App\Listeners;

use App\Events\UploadNewVideoEvent;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ProcessUploadedVideListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\UploadNewVideoEvent $event
     * @return void
     */
    public function handle(UploadNewVideoEvent $event)
    {
        //App\Models\Video $video
        $video = $event->getVideo();


        //tmp video path
        $uploadedVideoPath = '/tmp/' . $event->getRequest()->video_id;

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
        $videoFile->save(Auth::id() . '/' . $video->slug);

        $video->duration = $videoUploaded->getDurationInSeconds();
        $video->save();

    }
}
