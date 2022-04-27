<?php


namespace App\Services;


use App\Http\Requests\Video\UploadVideoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VideoService extends BaseService
{

    public static function uploadVideo(UploadVideoRequest $request)
    {
        try {

            $video = $request->file('video');


            $videoName = time(). Str::random(15) .'.'. $video->extension();

            $path = public_path('videos/tmp');

            $video->move($path, $videoName);

            return response(['video' => $videoName]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return jr('error while uploading video', 500);
        }

    }
}
