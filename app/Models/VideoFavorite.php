<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VideoFavorite extends Pivot
{
    protected $table = 'video_favorites';

    const col_user_id = 'user_id';
    const col_user_ip = 'user_ip';
    const col_video_id = 'video_id';


    protected $fillable = [
        self::col_user_id,
        self::col_user_ip,
        self::col_video_id
    ];
}
