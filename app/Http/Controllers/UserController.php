<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function changeEmail(ChangeEmailRequest $request)
    {
        try {

            $email = $request->email;
            $userId = Auth::id();
            $code = random_verification_cdoe();
            $expire = now()->addMinutes(env('expire_one_day'));

            Cache::put(
                'change.email.for.user.' . $userId,
                compact('email', 'code'),
                $expire
            );

            //------ Log and Response
            Log::info('change.email.for.user.' . $userId, compact('email', 'code'));
            //TODO ارسال ایمیل به کاربر جهت تغییر ایمیل
            return response(['message' => 'email for change email address was sent'], 200);
            //------
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'خطایی رخ داده و سرورو قادر به ارسال کد فعال سازی نیست'],
                500);
        }


    }

    /**
     * جهت تایید کد ارسال شده برای تغییر ایمیل
     */
    public function ChangeEmailSubmit(ChangeEmailSubmitRequest $request)
    {
        $userId = Auth::id();

        $cache_key = 'change.email.for.user.' . $userId;
        $cache = Cache::get($cache_key);

        if (
            empty($cache) ||
            $cache['code'] != $request->code
        ) {
            return response(['message' => 'درخواست نامتعبر است'], 400);
        }

        $user = Auth::user();
        $email = User::col_email;
        $user->$email = Cache::get('email');
        $user->save();

        Cache::forget($cache_key);

        return response([
            'message' => 'ایمیل با موفقیت تغییر یافت'
        ], 200);
    }
}
