<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

       

    public function login(Request $request)
    {

        $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);
    
            $credentials = $request->only('email', 'password');
    
            try 
            {
                $customClaims = ['foo' => 'bar', 'baz' => 'bob'];
                if (! $token = JWTAuth::attempt($credentials, $customClaims)) 
                {
                    return response()->json(['error' => 'invalid_credentials'], 400);
                }

                // $user = User::where('email', $request->email)->first();

                // $token = JWTAuth::fromUser($user);
            
                return $this->respondWithToken($token);
                //return $token;

            } 
            catch (JWTException $e) 
            {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

    }

    public function register(Request $request)
    {
        $user_id = time();

        $user = User::create([
                'user_id' => $user_id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'),201);

    }

//     public function getAuthenticatedUser()
//         {
//                 try {

//                         if (! $user = JWTAuth::parseToken()->authenticate()) {
//                                 return response()->json(['user_not_found'], 404);
//                         }

//                 } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

//                         return response()->json(['token_expired'], $e->getStatusCode());

//                 } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

//                         return response()->json(['token_invalid'], $e->getStatusCode());

//                 } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

//                         return response()->json(['token_absent'], $e->getStatusCode());

//                 }

//                 return response()->json(compact('user'));
//         }

        protected function respondWithToken($token)
        {
        return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user(),
        ]);
        }
        // protected function createNewToken($token){
        //         return response()->json([
        //             'access_token' => $token,
        //             'token_type' => 'bearer',
        //             'expires_in' => auth()->factory()->getTTL() * 60,
        //             'user' => auth()->user()
        //         ]);
        //     }
}