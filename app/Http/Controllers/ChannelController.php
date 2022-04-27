<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Services\ChannelService;

class ChannelController extends Controller
{
    public function update(UpdateChannelRequest $request)
    {

        return ChannelService::updateInfo($request);
    }
}
