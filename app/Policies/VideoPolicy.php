<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    public function changeState(User $user, Video $video)
    {
        return $user->isAdmin();
    }
}
