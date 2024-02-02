<?php

namespace App\Http\Middleware;

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
        if (getallheaders()) {
            $headers[] = getallheaders();
            if (isset($headers[0]['token'])) {
                $token = $headers[0]['token'];
            }elseif (isset($headers[0]['Token'])) {
                $token = $headers[0]['Token'];
            }
        }

        $request->input('user_id');

        $guestUserToken  =  base64_encode(strtolower(Config::get('constants.global.site.name')));

        if($guestUserToken == $token && $request->input('user_id') <1) {
            return $next($request);
        }



        try {
            $user = JWTUserTrait::getUserInstance($request->input('_token'));
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return RESTAPIHelper::response( 'Invalid token.', false, 'invalid_token' );
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return RESTAPIHelper::response( 'Your token has been expired, please log-in again.', false, 'invalid_token' );
            } else {
                if ( null === $token ) {
                    return RESTAPIHelper::response( 'token parameter not found.', false, 'invalid_token' );
                } else {
                    return RESTAPIHelper::response( 'Something went wrong.', false );
                }
            }
        }

        return $next($request);
    }
}
