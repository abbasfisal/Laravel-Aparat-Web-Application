<?php


namespace App\Services;


use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChannelService extends BaseService
{
    public static function updateInfo(UpdateChannelRequest $request)
    {
        try {


            if ($channelId = $request->route('id')) {
                //مطمئن هستیم که کاربر ادمینه
                $channel = Channel::query()->findOrFail($channelId);
                $user = $channel->user;


            } else {
                $user = auth()->user();

                $channel = $user->channels;
            }

            DB::beginTransaction();

            //update channel
            $chnnel = $user->channels()
                ->update($request->only(['name', 'info']));

            //update use website
            $user->website = $request->website;
            $user->save();

            DB::commit();

            return jr('تغییرات کانال با موفقیت ثبت شد', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return jr('خطایی رخ داده است ', 500);
        }

    }

    public static function uploadBannerForChannel(UploadBannerForChannelRequest $request)
    {
        try {

            $banner = $request->file('banner');

            $fileName = Auth::id() . '-' . Str::random(15) . '.' . $banner->extension();

            $channel = \auth()->user()->channels;


            if ($channel->banner) {
                unlink(public_path($channel->banner));
            }


            $channel->banner = 'channel-banners/' . $fileName;
            $channel->save();

            $banner->move(public_path('channel-banners'), $fileName);

            return response([
                'banner' => url('channel-banners/' . $fileName)], 200);


        } catch (\Exception $e) {
            return jr('error in upload image', 500);
        }
    }


    public static function updateSocials(UpdateSocialsRequest $request)
    {
        try {

            $data = [
                'cloob' => $request->cloob,
                'facebook' => $request->facebook,
                'telegram' => $request->telegram
            ];

            $j = json_encode($data);


            \auth()->user()->channels()->update(
                [Channel::col_socials => $data]
            );


            return jr('successfully socials updated', 200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return jr('error occured (socials operations)', 500);
        }

    }
}
