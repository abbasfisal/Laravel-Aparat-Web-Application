<?php


namespace App\Services;


use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Models\PlayList;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoService extends BaseService
{

    public static function uploadVideo(UploadVideoRequest $request)
    {
        try {


            $video = $request->file('video');


            $videoName = time() . Str::random(15) . '.' . $video->extension();

            $path = public_path('videos/tmp');

            $video->move($path, $videoName);

            return response(['video' => $videoName]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return jr('error while uploading video', 500);
        }

    }


    public static function uploadBanner(UploadVideoBannerRequest $request)
    {
        try {

            $banner = $request->file('banner');


            $bannerName = time() . Str::random(15) . '-Banner' . '.' . $banner->extension();

            $path = public_path('videos/tmp');

            $banner->move($path, $bannerName);

            return response(['banner' => $bannerName]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return jr('error while uploading video Banner', 500);
        }

    }

    public function create(CreateVideoRequest $request)
    {
        try {

            DB::beginTransaction();

            $tmp_path = 'tmp/' . $request->video_id; //مسیر موقت ذخیره ویدیو
            $saving_path = Auth::id() . '/' . $request->video_id; //مسیر اصلی ذخیره ویودیو

            Storage::disk('videos')->move($tmp_path, $saving_path); //TODO موقع دیپلوی این کد پاک شود
            //Storage::disk('videos')->move($tmp_path, $saving_path);//TODO این کد اصلی هست موقع دیپلو

            if ($request->banner) {


                $tmp_path_banner = 'tmp/' . $request->banner;
                $video_name = Str::before($request->video_id, '.mp4');
                $banner_name = $video_name . '-Banner' . '.jpg';

                Storage::disk('videos')->move($tmp_path_banner, Auth::id() . '/' . $banner_name);

            }
            $video = Video::query()->create([
                Video::col_title => $request->title,
                Video::col_user_id => Auth::id(),
                Video::col_category_id => $request->category,
                Video::col_channel_category_id => $request->channel_category,
                Video::col_slug => $request->video_id,
                Video::col_info => $request->info,
                Video::col_duration => 0, //TODO get video Time
                Video::col_banner => $request->banner,
                Video::col_publish_at => $request->publish_at,
            ]);


            //Play List
            if ($request->playlist) {
                $playlist = PlayList::find($request->playlist);

                $playlist->videos()->attach($video->id);
            }


            //Tag
            if (!empty($request->tags)) {

                $video->tags()->attach($request->tags);
            }
            //DB::rollBack();
            DB::commit();

            return response(['message' => 'video uploaded succ', 'data' => $video], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return jr('error while moving video form tmp to user folder', 500);
        }

    }
}
