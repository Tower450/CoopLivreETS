<?php

namespace App\Http\Middleware;

use Closure;
use App\Etudiant;
use JWTAuth;
use JWTFactory;

class GetStudentFromToken
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
        $data['phone'] = $credential->get("phone");
        $data['password'] = $credential->get("password");

        if(Etudiant::authenticate($data)){
            return $next($request);
        }
    }
}
