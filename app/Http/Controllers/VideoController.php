<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Services\VideoService;

class VideoController extends Controller
{


    public function upload(UploadVideoRequest $request)
    {
        return VideoService::uploadVideo($request);
    }

    public function uploadBanner(UploadVideoBannerRequest $request)
    {
        return VideoService::uploadBanner($request);
    }


    public function create(CreateVideoRequest $request)
    {

        dd(request()->all());
    }
}
