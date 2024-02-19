<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function login(LoginUserRequest $request): JsonResponse {

        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if(!$user || !Hash::check($data['password'], $user->password)){
            throw new HttpResponseException(response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Email atau password salah'
                ]
            ], 401));
        }

        $user->token = Str::uuid()->toString();

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'data' => new UserResource($user)
        ]);

    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $user->token = Str::uuid()->toString();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Register Berhasil',
            'data' => new UserResource($user)
        ], 201);
    }

    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource(Auth::user())
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = User::where('id',  Auth::user()->id)->first();
        $user->token = null;
        $user->save();

        
        return response()->json([
            'success' => true,
            'message' => 'Logout Berhasil'
        ]);
    }
}
