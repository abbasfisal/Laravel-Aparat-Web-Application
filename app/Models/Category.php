<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    const col_title = 'title';
    const col_icon = 'icon';
    const col_banner = 'banner';

    protected $fillable = [
        self::col_title,
        self::col_icon,
        self::col_banner
    ];


    /*
     |-------------------------------
     | Relations
     |-------------------------------
     |
     |
     |
     |
     |
     */

}
