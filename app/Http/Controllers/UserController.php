<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * تغییر ایمیل کاربر
     * @param ChangeEmailRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeEmail(ChangeEmailRequest $request)
    {

        return UserService::changeEmail($request);

    }

    /**
     * جهت تایید کد ارسال شده برای تغییر ایمیل
     */
    public function ChangeEmailSubmit(ChangeEmailSubmitRequest $request)
    {
        return UserService::changeEmailSubmit($request);
    }


    /**
     * change user passowrd
     *
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        return UserService::changePassword($request);
    }
}
