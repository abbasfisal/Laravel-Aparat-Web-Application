<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Services\VideoService;
use http\Env\Request;

class VideoController extends Controller
{


    public function upload(UploadVideoRequest $request)
    {
        return VideoService::uploadVideo($request);
    }


    public function create(CreateVideoRequest $request)
    {

        dd(request()->all());
    }
}
