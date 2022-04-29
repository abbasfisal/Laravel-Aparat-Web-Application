<?php


namespace App\Services;


use App\Http\Requests\Tag\ListTagRequest;
use App\Models\Tag;

class TagService extends BaseService
{
    public static function getAll(ListTagRequest $request)
    {
        return Tag::all('id','title');

    }
}
