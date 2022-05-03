<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoRepublishes;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    /**
     * just admin can change video state to accepted or bloacked when a video state is = converted!
     *
     * @param User $user
     * @param Video $video
     * @return bool
     */
    public function changeState(User $user, Video $video)
    {
        return $user->isAdmin();
    }

    /**
     * Republish a video
     *
     * @param User $user
     * @param Video $video
     * @return bool
     */
    public function republish(User $user, Video $video)
    {
        return $video && (

                //ویدویو برای من نباشه
                $video->user_id != $user->id &&

                VideoRepublishes::query()
                    //ویدیو قبلا توسط کاربر جاری بازنشر نشده باشد
                    ->where([
                        VideoRepublishes::col_video_id => $video->id,
                        VideoRepublishes::col_user_id => $user->id
                    ])
                    ->count() < 1
            );
    }
}
