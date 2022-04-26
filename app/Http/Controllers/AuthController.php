<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyRegistredException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register a new user with verify_code
     *
     * @param RegisterNewUserRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function register(RegisterNewUserRequest $request)
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
            DB::rollBack();

            Log::error($e->getMessage());
            return response(['خطایی رخ داده است'], 500);
        }
    }

    /**
     * verify a registered user
     *
     * @param RegisterVerifyUserRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function registerVerify(RegisterVerifyUserRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $code = $request->code;


        $user = User::query()
            ->where(User::col_verify_code, $code)
            ->where($field, $value)
            ->first();

        $this->checkUserExist($user);

        $this->userExist($user);

        return response($user, 200);


    }

    /**
     * if user was empty then throw model not found exception
     * @param ] $user
     */
    private function checkUserExist($user): void
    {
        if (empty($user)) {
            throw new ModelNotFoundException('user not found with that verify Code');
        }
    }

    /**
     * user was exist ,now verify it!
     * @param $user
     */
    private function userExist($user): void
    {
        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();
    }

    public function resendVerificationCode(ResendVerificationCodeRequest $request)
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
}
