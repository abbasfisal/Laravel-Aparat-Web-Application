<?php

namespace App\Http\Controllers;

use App\Http\Requests\Playlist\ListPlayListRequest;
use App\Http\Requests\Playlist\MyListPlayListRequest;
use App\Services\PlaylistService;

class PlayListController extends Controller
{
    public function getAllPlayList(ListPlayListRequest $request)
    {

        return PlaylistService::getAllPlayList($request);
    }


    public function getMyPlaylist(MyListPlayListRequest $request)
    {
        return PlaylistService::getMyPlaylist($request);
    }

}
