<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Services\ChannelService;

class ChannelController extends Controller
{
    /**
     * آپدیت اطلاعات کانال
     * @param UpdateChannelRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(UpdateChannelRequest $request)
    {
        return ChannelService::updateInfo($request);
    }

    /**
     * آپلود بنر کانال
     * @param UploadBannerForChannelRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updloadBanner(UploadBannerForChannelRequest $request)
    {
        return ChannelService::uploadBannerForChannel($request);
    }


    public function updateSocials(UpdateSocialsRequest $request)
    {
        return ChannelService::updateSocials($request);
    }
}
