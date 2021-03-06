<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    //protected $with = ['republishedVideos', 'channelVideos'];
    protected $table = 'users';

    const ADMIN_TYPE = 'admin';
    const USER_TYPE = 'user';
    const TYPES = [self::ADMIN_TYPE, self::USER_TYPE];

    const col_id = 'id';
    const col_mobile = 'mobile';
    const col_type = 'type';
    const col_email = 'email';
    const col_name = 'name';
    const col_password = 'password';
    const col_avatar = 'avatar';
    const col_website = 'website';
    const col_verify_code = 'verify_code';
    const col_verified_at = 'verified_at';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::col_mobile,
        self::col_type,
        self::col_email,
        self::col_name,
        self::col_password,
        self::col_avatar,
        self::col_website,
        self::col_verify_code,
        self::col_verified_at,
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        self::col_password,
        self::col_verify_code,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /*
     |--------------------------------------------------------------------------
     |  Relations
     |--------------------------------------------------------------------------
     |
     |
     */

    public function channels()
    {
        return $this->hasOne(Channel::class);
    }

    public function playlists()
    {
        return $this->hasMany(PlayList::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function channelVideos()
    {
        return $this->hasMany(Video::class);
    }

    /**
     * get republished videos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function republishedVideos()
    {
        // its a has many through relation
        return $this->hasManyThrough(
            Video::class,
            VideoRepublishes::class,
            'user_id',
            'id',
            'id',
            'video_id'

        );
    }

    /**
     * get union videos (user videos and republished vides)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return
            $this->channelVideos()
                ->selectRaw('* , 0 as republished')
                ->union(
                    $this->republishedVideos()->selectRaw('videos.* ,1 as republished ')
                );
    }


    /**
     *
     */
    public function favVideos()
    {
        return $this->hasManyThrough(
            Video::class,
            VideoFavorite::class,
            'user_id',
            'id',
            'id',
            'video_id'
        );

    }

    //-------------------------Methods

    /**
     * Login By Email | Mobile
     *
     * @param $username
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findForPassport($identifier)
    {
        return $this->newQuery()
            ->where(self::col_mobile, $identifier)
            ->orWhere(self::col_email, $identifier)
            ->first();

    }

    /**
     * save mobile according to the pattern =>+989-xxx-xxx-xxx
     * @param $value
     */
    public function setMobileAttribute($value)
    {
        $this->attributes[self::col_mobile] = to_valid_mobile_number($value);

    }


    public function isAdmin()
    {
        return $this->type === User::ADMIN_TYPE;
    }

    public function isBaseUser()
    {
        return $this->type === User::USER_TYPE;
    }


    //------------

}
