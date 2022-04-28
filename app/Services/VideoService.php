<?php


namespace App\Services;


use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VideoService extends BaseService
{

    public static function uploadVideo(UploadVideoRequest $request)
    {
        try {


            $video = $request->file('video');


            $videoName = time() . Str::random(15) . '.' . $video->extension();

            $path = public_path('videos/tmp');

            $video->move($path, $videoName);

            return response(['video' => $videoName]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return jr('error while uploading video', 500);
        }

    }


    public static function uploadBanner(UploadVideoBannerRequest $request)
    {
        try {

            $banner = $request->file('banner');


            $bannerName = time() . Str::random(15) . '-Banner' . '.' . $banner->extension();

            $path = public_path('videos/tmp');

            $banner->move($path, $bannerName);

            return response(['banner' => $bannerName]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return jr('error while uploading video Banner', 500);
        }

    }
}
