<?php

namespace App\Services\Auth;

interface IAuthService
{
    public function login(string $email, string $password): string;
    public function register($request);
    public function logout(): bool;
    public function respondWithToken(string $token): array;
}
