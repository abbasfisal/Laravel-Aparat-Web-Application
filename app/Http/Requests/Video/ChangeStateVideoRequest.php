<?php

namespace App\Http\Requests\Video;

use App\Rules\CanChangeVideoStateRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ChangeStateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('changeState', $this->video);

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
