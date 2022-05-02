<?php

namespace App\Rules;

use App\Models\Video;
use Illuminate\Contracts\Validation\Rule;

class CanChangeVideoStateRule implements Rule
{
    /**
     * @var Video
     */
    private $video;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        //

        $this->video = $video;
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


        return !empty($this->video) &&
            (
                $this->video->state == Video::state_converted && in_array($value, [Video::state_accepted, Video::state_bloacked]) ||
                $this->video->state == Video::state_accepted && $value === Video::state_bloacked ||
                $this->video->state == Video::state_bloacked && $value === Video::state_accepted
            );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'invalid ';
    }
}
