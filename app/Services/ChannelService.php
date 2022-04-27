<?php


namespace App\Services;


use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Models\Channel;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
}
