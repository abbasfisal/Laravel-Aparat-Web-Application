<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CategoryIdRule implements Rule
{
    const PUBLIC_CATEGORIES = 'public';
    const PRIVATE_CATEGORIES = 'private';
    const ALL_CATEGORIES = 'all';

    private $categoryType;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($categoryType = self::ALL_CATEGORIES)
    {
        $this->categoryType = $categoryType;
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
        if ($this->categoryType === 'public') {

            return Category::query()->where('id', $value)->whereNull('user_id')->count();
        }


        if ($this->categoryType === 'private') {
            return Category::query()
                ->where('id', $value)
                ->where('user_id' ,Auth::id())
                ->count();
        }

        return Category::query()->where('id', $value)->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public
    function message()
    {
        return 'Invalid Category Id.';
    }
}
