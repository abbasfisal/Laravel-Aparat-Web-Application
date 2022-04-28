<?php

namespace App\Rules;

use App\Models\PlayList;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class OwnPlayListIdRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //ای دی پلی لیست باید موجود باشد و
        // پلی لیست متعلق به کاربر لاگین شده باشد
        return PlayList::query()
            ->where(PlayList::col_id, $value)
            ->where(PlayList::col_user_id, Auth::id())->count();

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid PlayList ID';
    }
}
