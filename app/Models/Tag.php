<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    const col_title = 'title';

    protected $fillable = [
        self::col_title
    ];


    /*
     |------------------------------
     | Relations
     |------------------------------
     |
     */
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'tag_videos');
    }



    //---------------
    public function toArray()
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title
        ];
    }
}
