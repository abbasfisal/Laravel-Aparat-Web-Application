<?php


namespace App\Services;


use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Models\PlayList;
use App\Models\Video;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\WMV;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

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

    /**
     * ایجاد یک ویدیو به همراه انتقال ویدیو و بنر مربوطه از پوشه تمپ به پوشه اصلی
     * @param CreateVideoRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(CreateVideoRequest $request)
    {
        try {

            DB::beginTransaction();

            //بدست اوردن زمان ویدیو به ثانیه توسط پکیج ffmpeg
            $video = FFMpeg::fromDisk('videos')
                ->open('/tmp/' . $request->video_id);

            $filter = new CustomFilter("drawtext=text='goooogle':
             fontcolor=blue: fontsize=24:
              box=1: boxcolor=white@0.5:
               boxborderw=5:
                x=10: y=(h - text_h - 10)");


            $format = new WMV();
            $video->addFilter($filter)
                ->export()
                ->toDisk(Storage::disk('videos'))
                ->inFormat($format)
                ->save('/tmp/export.wmv');
            dd($video);

            //ذخیره ویدوی
            $video = Video::query()->create([
                Video::col_title => $request->title,
                Video::col_user_id => Auth::id(),
                Video::col_category_id => $request->category,
                Video::col_channel_category_id => $request->channel_category,
                Video::col_slug => '',
                Video::col_info => $request->info,
                Video::col_duration => 0,
                Video::col_enable_comments => $request->enable_comments,
                Video::col_banner => $video->getDurationInSeconds(),
                Video::col_publish_at => $request->publish_at,
            ]);
            //ایجاد اسلاگ یکتا از روی آی دی
            $video->slug = uniqueId($video->id) . '.mp4';
            $video->banner = (Str::before($video->slug, '.mp4')) . '-Banner.jpg';
            $video->save();

            //ذخیره فایل ویدیو
            Storage::disk('videos')->move('/tmp/' . $request->video_id, Auth::id() . '/' . $video->slug);

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
}
