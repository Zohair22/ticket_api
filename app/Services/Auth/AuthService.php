<?php

namespace App\Services\Auth;

use App\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthService implements IAuthService
{
    /**
     * Authenticate the user using email and password.
     *
     * @param string $email
     * @param string $password
     * @return string
     * @throws \App\Exceptions\InvalidCredentialsException
     */
    public function login(string $email, string $password): string
    {
        $user = User::where('email',$email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException;
        }
        return $user->createToken('authToken', ['*'], now()->addMinutes(60))->plainTextToken;
    }

    /**
     * Register a new user and login.
     *
     * @param $request
     * @return mixed
     */
    public function register($request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return $user->createToken('authToken')->plainTextToken;
    }

    /**
     * Logout the authenticated user.
     *
     * @return bool
     */
    public function logout(): bool
    {
        auth()->user()->tokens()->delete();
        return true;
    }

    /**
     * Get token details.
     *
     * @param string $token
     * @return array
     */
    public function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
        ];
    }
}
