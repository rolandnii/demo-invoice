<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\Events\Login;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->bearerToken() || request('auth_key') ?? false) {

            $token =  $request->query('auth_key') ?? $request->bearerToken();

            $key = PersonalAccessToken::findToken($token);

            
            if (!empty($key->id)) {

                //Only admin accounts can add , update and delete items
                if (($request->is('api/item') && $request->isMethod('post')) || ($request->is('api/item/*') && ($request->isMethod('post') || $request->isMethod('delete'))) ) {
                    $user = User::findOr($key->tokenable_id,function(){
                        return false;
                    });

                    if (!$user) {
                        return response()->json([
                            'ok' => false,
                            'msg' => 'auth key is invalid'
                        ], Response::HTTP_BAD_REQUEST);
                    }

                    if ( strtolower($user->usertype) == 'customer') {
                        return response()->json([
                            'ok' => false,
                            'msg' => 'This account is not authorized to access this api'
                        ], Response::HTTP_BAD_REQUEST);
                    }


                }
        
                  return $next($request);
                
            }


            return response()->json([
                'ok' => false,
                'msg' => 'auth key is invalid'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'ok' => false,
            'msg' => 'You are not authorized to access this API.'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
