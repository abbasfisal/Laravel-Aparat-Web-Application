<?php


if (!function_exists('to_valid_mobile_number')) {

    /** تبدیل موبایل به پترن +98 و مجموع 13 رقم
     * @param string $number
     * @return string
     */
    function to_valid_mobile_number(string $number)
    {
        return "+98" . substr($number, -10, 10);
    }

}

if (!function_exists('random_verification_code')) {

    /**
     * make random code
     * @return int
     * @throws Exception
     */
    function random_verification_cdoe()
    {
        return random_int(11111, 99999);
    }
}

if (!function_exists('jr')) {
    /**
     * create a json reponse
     */
    function jr($message, int $code = 200, $key = 'message')
    {
        return response([$key => $message], $code);
    }
}

if (!function_exists('uniqueId')) {
    /**
     * create a unuqie id
     *
     * @param int $value
     * @return string
     */
    function uniqueId(int $value)
    {
        $hash = new \Hashids\Hashids(env('app_key'), 10);
        return $hash->encode($value);
    }
}


if (!function_exists('client_ip')) {

    function client_ip()
    {
        return $_SERVER['REMOTE_ADDR'] . '-' . md5($_SERVER['HTTP_USER_AGENT']);
    }
}

