<?php

namespace App\Http\Requests;

use App\Rules\UniqueForUserRule;
use App\Rules\UploadedCategoryBannerIdRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
            'title' => ['required','string','min:4','max:100' , new UniqueForUserRule('categories')],
            'banner_id' => ['nullable', new UploadedCategoryBannerIdRule()],
            'icon' => 'nullable'
        ];
    }
}
