<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Exception;
use JWTAuth;
use App\Helpers\RESTAPIHelper;
use App\Http\Traits\JWTUserTrait;
use Config;

class authJWT {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $token = null;
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
        if (getallheaders())
        {
            $headers[] = getallheaders();
            if (isset($headers[0]['_token'])) {
                $token = $headers[0]['_token'];
            }
        }

        $request->input('user_id');

        $guestUserToken  =  base64_encode(strtolower(Config::get('constants.global.site.name')));

        if($guestUserToken == $token && $request->input('user_id') <1) {
            return $next($request);
        }



        try {
            $user = JWTUserTrait::getUserInstance($request->input('_token'));
//            if($user->status == User::STATUS_BLOCKED){
//                throw new \Exception('User is not an active user. Admin may have banned you.', 403);
//            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return RESTAPIHelper::response( 'Invalid token.', false, 'invalid_token' );
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return RESTAPIHelper::response( 'Your token has been expired, please log-in again.', false, 'invalid_token' );
            } else {
                if($e->getCode() == 403){
                    return RESTAPIHelper::response($e->getMessage(), false, 'invalid_token');
                } else {
                    if (null === $token) {
                        return RESTAPIHelper::response('_token parameter not found.', false, 'invalid_token');
                    } else {
                        return RESTAPIHelper::response('Something went wrong.', false, $e->getMessage());
                    }
                }
            }
        }

        return $next($request);
    }
}
