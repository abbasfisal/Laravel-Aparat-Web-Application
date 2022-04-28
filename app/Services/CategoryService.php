<?php


namespace App\Services;


use App\Http\Requests\ListCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryService extends BaseService
{

    /**
     * دریافت تمام کتکوری ها
     * @param ListCategoryRequest $request
     * @return Category[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getAllCategories(ListCategoryRequest $request)
    {
        return Category::all();
    }

    public static function getMyCategories(ListCategoryRequest $request)
    {
        return Category::query()->where(Category::col_user_id, Auth::id())->get();
    }
}
