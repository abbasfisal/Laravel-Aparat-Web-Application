<?php

namespace App\Http\Requests\Channel;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route()->hasParameter('id') && auth()->user()->type != User::ADMIN_TYPE) {
            return false;
        }
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
            'name' => 'required|string:255',
            'website' => 'nullable|url|max:255',
            'info' => 'nullable|string'
        ];
    }
}
