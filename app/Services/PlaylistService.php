<?php


namespace App\Services;


use App\Http\Requests\Playlist\ListPlayListRequest;
use App\Http\Requests\Playlist\MyListPlayListRequest;
use App\Models\PlayList;
use Illuminate\Support\Facades\Auth;

class PlaylistService extends BaseService
{

    /**
     * get all playlist
     * @param ListPlayListRequest $request
     * @return PlayList[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getAllPlayList(ListPlayListRequest $request)
    {
        return PlayList::all();
    }

    public static function getMyPlaylist(MyListPlayListRequest $request)
    {
        return Auth::user()->playlists;
    }
}
