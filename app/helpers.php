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
