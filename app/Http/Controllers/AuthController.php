<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Services\UserService;

class AuthController extends Controller
{
    /**
     * ثبت نام کاربر جدید
     *
     * @param RegisterNewUserRequest $request
     */
    public function register(RegisterNewUserRequest $request)
    {
        return UserService::registerNewUser($request);
    }

    /**
     * تایید کد فعال سازی کاربر
     *
     * @param RegisterVerifyUserRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function registerVerify(RegisterVerifyUserRequest $request)
    {

        return UserService::registerNewUserVerify($request);

    }

    /**
     * ارسال مجدد کد فعال سازی به کاربر
     * @param ResendVerificationCodeRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function resendVerificationCode(ResendVerificationCodeRequest $request)
    {
        return UserService::resendVerificationCodeToUser($request);
    }
}
