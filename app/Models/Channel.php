<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $table = 'channels';

    const col_id = 'id';
    const col_user_id = 'user_id';
    const col_info = 'info';
    const col_name = 'name';
    const col_website = 'website';
    const col_banners = 'banner';
    const col_socials = 'socials';

    protected $fillable = [
        self::col_user_id,
        self::col_info,
        self::col_name,
        self::col_website,
        self::col_banners,
        self::col_socials,
    ];

    /*
     |--------------------------------------------------------------------------
     | Relation
     |--------------------------------------------------------------------------
     |
     |
     |
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //---------- Mutator

    // save socials in type =>json
    public function setSocialsAttribute($value)
    {
        if (is_array($value))
            $value = json_encode($value);

        $this->attributes[self::col_socials] = $value;
    }

    // get socials in type =>array
    public function getSocialsAttribute()
    {
        return json_decode($this->attributes[self::col_socials], true);
    }
}
