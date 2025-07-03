<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Requests\Api\V1\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Enums\ApiAction;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $data['user'] = $user;
        $data['token'] = $token;

        return $this->successResponse(
            $data, 
            $this->generateMessage(User::class, ApiAction::CREATED),
            201
        );
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        $data['user'] = $user;
        $data['token'] = $token;

        return $this->successResponse(
            $data, 
            $this->generateMessage(User::class, ApiAction::RETRIEVED)
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(
            null, 
            'Logged out',
        );
    }
}

