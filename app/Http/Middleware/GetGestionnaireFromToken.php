<?php

namespace App\Http\Middleware;

use Closure;
use App\Gestionnaire;
use JWTAuth;
use JWTFactory;


class GetGestionnaireFromToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        JWTAuth::setToken($request->input('token'));
        $token = JWTAuth::getToken();
        $credential = JWTAuth::decode($token);

        $data['email'] = $credential->get("email");
        $data['password'] = $credential->get("password");

        if(Gestionnaire::authenticate($data)){
            return $next($request);
        }
    }
}
