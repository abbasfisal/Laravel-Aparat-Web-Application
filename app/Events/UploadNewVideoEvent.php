<?php

namespace App\Events;

use App\Http\Requests\Video\CreateVideoRequest;
use App\Models\Video;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Client\Request;
use Illuminate\Queue\SerializesModels;

class UploadNewVideoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Video
     */
    private $video;
    /**
     * @var Request
     */
    private $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Video $video, CreateVideoRequest $request)
    {

        $this->video = $video;
        $this->request = $request;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }


    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Video
     */
    public function getVideo(): Video
    {
        return $this->video;
    }
}
