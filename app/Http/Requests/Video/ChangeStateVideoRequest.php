<?php

namespace App\Http\Requests\Video;

use App\Rules\CanChangeVideoStateRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangeStateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'state' => ['required', new CanChangeVideoStateRule($this->video)]
        ];
    }
}
