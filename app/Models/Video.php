<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
