<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\ChangeStateVideoRequest;
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

        return VideoService::create($request);

    }

    public function changeState(ChangeStateVideoRequest $request)
    {

        return VideoService::changeState($request);
    }
}
