<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $token = $request->header('Authorization');
        $isAuth = true;

        if (!$token) {
            $isAuth = false;
        }

        $user = User::select('id', 'name', 'email')->where('token', $token)->first();
        if(!$user){
            $isAuth = false;
        }else{
            Auth::login($user);
        }

        if ($isAuth) {
            return $next($request);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        
    }
}
