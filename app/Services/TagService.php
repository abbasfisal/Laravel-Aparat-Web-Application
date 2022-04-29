<?php


namespace App\Services;


use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\ListTagRequest;
use App\Models\Tag;

class TagService extends BaseService
{
    /**
     * Get All Tag
     * @param ListTagRequest $request
     * @return Tag[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getAll(ListTagRequest $request)
    {
        return Tag::all('id', 'title');
    }

    /**
     * create new Tag
     * @param CreateTagRequest $request
     */
    public static function create(CreateTagRequest $request)
    {
        return $tag = Tag::query()->create($request->toArray());
    }
}
