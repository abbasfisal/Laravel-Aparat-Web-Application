<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListCategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function getAllCategories(ListCategoryRequest $request)
    {
        return CategoryService::getAllCategories($request);
    }


    public function getMyCategories()
    {
        dd('s');
    }


}
