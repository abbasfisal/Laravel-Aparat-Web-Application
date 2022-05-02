<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VideoRepublishes extends Pivot
{
    protected $table = 'video_republishes';

    const col_video_id = 'video_id';
    const col_user_id = 'user_id';


    //fillables
    protected $fillable = [
        self::col_user_id,
        self::col_video_id
    ];

}
