<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayList extends Model
{
    use HasFactory;

    protected $table = 'playlists';

    const col_id = 'id';
    const col_user_id = 'user_id';
    const col_title = 'title';

    protected $fillable = [
        self::col_user_id,
        self::col_title
    ];

    /*
     |------------------------------
     | Relations
     |------------------------------
     |
     |
     |
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'playlist_videos');
    }
}
