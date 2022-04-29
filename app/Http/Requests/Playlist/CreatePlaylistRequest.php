<?php

namespace App\Http\Requests\Playlist;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlaylistRequest extends FormRequest
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
            'title' => 'required|string|min:4|max:100|unique:playlists,title'
        ];
    }


}
