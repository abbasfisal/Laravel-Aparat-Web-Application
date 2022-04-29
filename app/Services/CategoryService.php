<?php


namespace App\Services;


use App\Http\Requests\ListCategoryRequest;
use App\Http\Requests\UploadCategoryBannerRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    /**
     * دریافت کتگوری های کاربر لاگین کرده
     * @param ListCategoryRequest $request
     */
    public static function getMyCategories()
    {
        return Auth::user()->categories;

    }

    /**
     * آپلود بنر دسته بندی
     * @param UploadCategoryBannerRequest $request
     */
    public static function uploadBanner(UploadCategoryBannerRequest $request)
    {

        try {
            //get banner (image)
            $banner = $request->file('banner');

            //set unique name to banner
            $banner_name = uniqueId(time() . Auth::id()) . '-Banner.' . $banner->extension();

            //save banner in tmp foler
            Storage::disk('categories')->put('tmp/' . $banner_name, $banner->getContent());

            return jr($banner_name, 200, 'banner');

        } catch (\Exception $e) {

            Log::info($e);
            return jr('خطا در ارسال بنر دسته بندی', 500);
        }
    }

    /**
     * ذخیره یک دسته بندی
     * @param \App\Http\Requests\CreateCategoryRequest $request
     */
    public static function create(\App\Http\Requests\CreateCategoryRequest $request)
    {

        try {
            DB::beginTransaction();

            //save category to db
            $cat = Auth::user()->categories()->create([
                Category::col_title => $request->title,
                Category::col_icon => $request->icon,
                Category::col_banner => $request->banner_id,

            ]);

            // banner (image) exist?
            if ($request->banner_id) {

                $saving_path = uniqueId(Auth::id()) . '/' . $request->banner_id;
                //move banner to main folder
                Storage::disk('categories')->move('/tmp/' . $request->banner_id, $saving_path);
            }

            DB::commit();
            return response($cat);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return jr('error while saving new Category', 500);
        }


    }
}
