<?php

namespace App\Http\Requests\Video;

use App\Rules\UploadedVideoBannerIdRule;
use App\Rules\UploadedVideoIdRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateVideoRequest extends FormRequest
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
            'video_id' => ['required', new UploadedVideoIdRule()],//TODO video_id validation
            'title' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'info' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'playlist' => 'nullable|exists:playlists,id',//TODO slelct user own playlist
            'channel_category' => 'nullable',//TODO channel category
            'banner' => ['nullable','string', new UploadedVideoBannerIdRule()],//TODO banner must be uploaded before create video
            'publish_at' => 'nullable|date'
        ];
    }
}
