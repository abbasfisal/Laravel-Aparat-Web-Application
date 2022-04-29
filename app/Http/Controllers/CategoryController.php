<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\ListCategoryRequest;
use App\Http\Requests\UploadCategoryBannerRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function getAllCategories(ListCategoryRequest $request)
    {
        return CategoryService::getAllCategories($request);
    }


    public function getMyCategories()
    {
        return Category::query()->where(Category::col_user_id ,Auth::id())->get();
    }

    /**
     * create category for logged in user
     */
    public function create(CreateCategoryRequest $request)
    {
        return CategoryService::create($request);
    }

    public function uploadBanner(UploadCategoryBannerRequest $request)
    {
        return CategoryService::uploadBanner($request);
    }

}
