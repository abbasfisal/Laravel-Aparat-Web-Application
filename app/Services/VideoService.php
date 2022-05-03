<?php


namespace App\Services;


use App\Events\UploadNewVideoEvent;
use App\Http\Requests\Video\ChangeStateVideoRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\ListVideoRequest;
use App\Http\Requests\Video\RepublishVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Models\PlayList;
use App\Models\Video;
use App\Models\VideoRepublishes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoService extends BaseService
{
    /**
     * آپلود ویدیو به در پوشه تمپ
     * @param UploadVideoRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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

    /**
     * آپلود بنر ویدیو در پوشه تمپ
     * @param UploadVideoBannerRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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

    public static function changeState(ChangeStateVideoRequest $request)
    {


        $video = $request->video;

        $video->state = $request->state;
        $video->save();
        return $video;
    }

    /**
     * ایجاد یک ویدیو به همراه انتقال ویدیو و بنر مربوطه از پوشه تمپ به پوشه اصلی
     * @param CreateVideoRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(CreateVideoRequest $request)
    {
        try {
            //save video data in db
            DB::beginTransaction();
            $video = Video::query()->create([
                Video::col_title => $request->title,
                Video::col_user_id => Auth::id(),
                Video::col_category_id => $request->category,
                Video::col_channel_category_id => $request->channel_category,
                Video::col_slug => '',
                Video::col_info => $request->info,
                Video::col_duration => 0,
                Video::col_enable_comments => $request->enable_comments,
                Video::col_banner => 0,
                Video::col_publish_at => $request->publish_at,
                Video::col_state => Video::state_pending
            ]);

            //ساخت اسلاگ به صورت یونیک
            $video->slug = uniqueId($video->id) . '.mp4';
            $video->banner = (Str::before($video->slug, '.mp4')) . '-Banner.jpg';
            $video->save();


            event(new UploadNewVideoEvent($video, $request));


            //حذف ویدیو از پیش موجود

            //ذخیره بنر ویدیو
            if ($request->banner) {
                Storage::disk('videos')->move('/tmp/' . $request->banner, Auth::id() . '/' . $video->banner);
            }


            //تخصیص ویدیو به لیست پخش
            if ($request->playlist) {
                $playlist = PlayList::find($request->playlist);

                $playlist->videos()->attach($video->id);
            }

            //تخصیص تگ ها به ویدو
            if (!empty($request->tags)) {

                $video->tags()->attach($request->tags);
            }
            DB::commit();

            return response(['message' => 'video uploaded succ', 'data' => $video], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return jr('error while moving video form tmp to user folder', 500);
        }

    }

    /**
     * get loged in user video list
     * @param ListVideoRequest $request
     */
    public static function list(ListVideoRequest $request)
    {
        $user = Auth::user();

        if ($request->has('republished')) {

            $videos = $request->republished ? $user->republishedVideos() : $user->channelVideos();

        } else {

            $videos = $user->videos();
        }

        return $videos
            ->orderBy('id' ,'desc')
            ->paginate(3);

    }

    /**
     * Republish a video
     * @param RepublishVideoRequest $request
     */
    public static function republish(RepublishVideoRequest $request)
    {
        try {
            //create
            VideoRepublishes::query()
                ->create([
                    VideoRepublishes::col_user_id => Auth::id(),
                    VideoRepublishes::col_video_id => $request->video->id
                ]);

            return jr('Republishe was created successfully', 200);

        } catch (\Exception $e) {

            Log::error($e);
            return jr('Republish was faild', 500);
        }


    }


}
