<?php


namespace App\Services;


use App\Exceptions\UserAlreadyRegistredException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService
{

    public static function registerNewUser(RegisterNewUserRequest $request)
    {
        try {

            $field = $request->getFieldName();
            $value = $request->getFieldValue();

            DB::beginTransaction();

            $user = User::query()->where($field, $value)->first();
            //کاربر از قبل ثبت نام کرده
            //لذا باید روال کار ثبت نام قطع بشه

            if ($user) {
                //اگر کاربر از قبول ثبت نام کرده بود و وری_فای شده بود
                //باید یک خطا بش بدیم
                $verifed_at = User::col_verified_at;

                if ($user->$verifed_at) {
                    throw new UserAlreadyRegistredException('you already registred completely');
                }

                return response(['verify code already sent'], 200);
            }


            //generate verification code
            $code = random_verification_cdoe();

            $user = User::query()->create([
                $field => $value,
                User::col_verify_code => $code
            ]);


            DB::commit();

            //TODO send verify code with sms
            Log::info('SEND-REGISTER-CODE-TO-USER', ['code' => $code]);

            return response(['message' => 'user registerd'], 200);


        } catch (\Exception $e) {

            //TODO handle UserAlreadyRegistredException
            DB::rollBack();

            if ($e instanceof UserAlreadyRegistredException) {
                throw $e;
            }
            Log::error($e->getMessage());
            return response(['خطایی رخ داده است'], 500);
        }
    }


    public static function registerNewUserVerify(RegisterVerifyUserRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $code = $request->code;


        $user = User::query()
            ->where(User::col_verify_code, $code)
            ->where($field, $value)
            ->first();

        static::checkUserExist($user);

        static::userExist($user);

        return response($user, 200);
    }

    public static function resendVerificationCodeToUser(ResendVerificationCodeRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::query()->where($field, $value)
            ->whereNull(User::col_verified_at)
            ->first();

        if (!empty($user)) {
            $verify_code = User::col_verify_code;

            $dateDiff = now()->diffInMinutes($user->updated_at);


            if ($dateDiff > 60) {
                $user->$verify_code = random_verification_cdoe();
                $user->save();
            }

            Log::info("Resend-Verification-Code", ['Code' => $user->$verify_code]);
            return response(['message' => 'Verification Code Resend'], 200);
        }

        throw new ModelNotFoundException('user not found or already Verified');
    }

    public static function changeEmail(ChangeEmailRequest $request)
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

    public static function changeEmailSubmit(ChangeEmailSubmitRequest $request)
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


    /**
     * change user password
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function changePassword(ChangePasswordRequest $request)
    {
        try {

            $old_password = $request->old_password;
            $new_password = $request->new_password;

            $user = Auth::user();

            if (!Hash::check($old_password, $user->getAuthPassword())) {
                return jr('پسورد مطابقت ندارد', 400);
            }

            $user->password = bcrypt($new_password);
            $user->save();
            return jr('passowrd changed successfully', 200);


        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return jr('error while changing passowrd', 500);
        }
    }







    //-------------------------- private methods

    /**
     * if user was empty then throw model not found exception
     * @param ] $user
     */
    private static function checkUserExist($user): void
    {
        if (empty($user)) {
            throw new ModelNotFoundException('user not found with that verify Code');
        }
    }

    /**
     * user was exist ,now verify it!
     * @param $user
     */
    private static function userExist($user): void
    {
        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();
    }

}
