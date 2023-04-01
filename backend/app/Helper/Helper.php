<?php

namespace App\Helper;

use Illuminate\Support\Facades\Config;

class Helper
{
    public static function success($response = [])
    {
        $success = ['status' => Config::get('global.status.success')];
        return  array_merge($success, $response);
    }

    public static function error($response = [])
    {
        $error = ['status' => Config::get('global.status.error')];
        return  array_merge($error, $response);
    }
}
