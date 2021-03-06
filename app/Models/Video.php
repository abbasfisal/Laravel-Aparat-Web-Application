<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';


    //----- sate
    const state_pending = 'pending';
    const state_converted = 'converted';
    const state_accepted = 'accepted';
    const state_bloacked = 'blocked';
    const states = [
        self::state_pending,
        self::state_converted,
        self::state_accepted,
        self::state_bloacked
    ];
    //------- end sate

    const col_id = 'id';
    const col_user_id = 'user_id';
    const col_category_id = 'category_id';
    const col_channel_category_id = 'channel_category_id';
    const col_slug = 'slug';
    const col_title = 'title';
    const col_info = 'info';
    const col_duration = 'duration';
    const col_banner = 'banner';
    const col_publish_at = 'publish_at';
    const col_enable_comments = 'enable_comments';
    const col_state = 'state';

    protected $fillable = [
        self::col_user_id,
        self::col_category_id,
        self::col_channel_category_id,
        self::col_slug,
        self::col_title,
        self::col_info,
        self::col_duration,
        self::col_banner,
        self::col_enable_comments,
        self::col_publish_at,
        self::col_state
    ];


    /*
     |------------------------------
     | Relations
     |------------------------------
     |
     */

    public function playlists()
    {
        return $this->belongsToMany(PlayList::class, 'playlist_videos')->first();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_videos');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //-------------


    public function getRouteKeyName()
    {
        return Video::col_slug;
    }

    //----------- methods

    public function isInState($state)
    {
        return $this->state === $state;
    }

    public function isAccepted()
    {
        return $this->isInState(self::state_accepted);
    }

    public function isPending()
    {
        return $this->isInState(self::state_pending);
    }

    public function isConverted()
    {
        return $this->isInState(self::state_converted);
    }

    public function isBlocked()
    {
        return $this->isInState(self::state_bloacked);
    }

    public function toArray()
    {
        $data = parent::toArray();

        $condition = [
            VideoFavorite::col_video_id => $this->id,
            VideoFavorite::col_user_id => Auth::guard('api')->check() ? Auth::guard('api')->id() : null,
        ];


        //?????? ?????? ?????????? ????????
        if (!Auth::guard('api')->check()) {
            $condition[VideoFavorite::col_user_ip] = client_ip();
        }

        $data['like'] = VideoFavorite::query()->where($condition)->count();

        return $data;
    }


    /*
     |------------------------------
     | Scopes
     |------------------------------
     |
     */
    public static function WhereNotRepublished()
    {
        return static::query()->whereRaw('id not in  ( select video_id from video_republishes ) ');
    }

    public static function WhereRepublished()
    {
        return static::query()->whereRaw('id in ( select video_id from video_republishes ) ');
    }
}
