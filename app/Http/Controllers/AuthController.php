<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->apiResponse(201, 'User registered successfully', null, [
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    /*---------------------------------------------------------------------------------------------*/

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->apiResponse(400, 'Invalid credentials');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->apiResponse(200, 'User logged in successfully', null, [
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    /*---------------------------------------------------------------------------------------------*/

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->apiResponse(200, 'User logged out successfully');
    }
}
