<?php

namespace App\Listeners;

use App\Events\UploadNewVideoEvent;
use App\Jobs\ConvertAndAddWaterMarkToUploadedVideoJob;

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

        ConvertAndAddWaterMarkToUploadedVideoJob::dispatch($event->getVideo(), $event->getRequest()->video_id);


    }
}
