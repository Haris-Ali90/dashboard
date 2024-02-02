<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Request;

// use JWTAuth;

trait JWTUserTrait {

    protected static $userInstance;

    /**
     * Extract token value from request
     *
     * @return string|Request
     */
    public static function extractToken($request = false) {

//        $headers[] = getallheaders();
//        print_r($headers[0]['_token']);
//        die();
//        foreach (getallheaders() as $name => $value) {
//            echo "$name: $value\n";
//        }
//        die();
        if (!function_exists('getallheaders'))
        {
            function getallheaders()
            {
                $headers = [];
                foreach ($_SERVER as $name => $value)
                {
                    if (substr($name, 0, 5) == 'HTTP_')
                    {
                        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }
                return $headers;
            }
        }
        if ($request && $request instanceof Request) {
            $token = $request->only('token');
        } else if (is_string($request)) {
           // $token = $request;
        } else if (getallheaders()) {
            $headers[] = getallheaders();
            if (isset($headers[0]['token'])) {
                $token = $headers[0]['token'];
            }else if (isset($headers[0]['Token'])) {
                $token = $headers[0]['Token'];
            }
        } else {
           // $token = Request::get('_token');
        }

        return $token;
    }

    /**
     * Return User instance or false if not exist in DB
     *
     * @return string|Request
     */
    public static function getUserInstance($request = false) {

        if (!self::$userInstance) {
            $token = self::extractToken($request);

            self::$userInstance = \JWTAuth::toUser($token);
        }

        return self::$userInstance;
    }

}
