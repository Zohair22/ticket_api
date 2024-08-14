<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\IAuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiBaseController
{
    protected IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle login request.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse|\App\Exceptions\InvalidCredentialsException
     */
    public function login(LoginRequest $request): JsonResponse|InvalidCredentialsException
    {
        try {
            $token = $this->authService->login($request->email, $request->password);
            return $this->respondWithToken($token);
        } catch (InvalidCredentialsException $e) {
            return  $this->respondFail(
                'Unauthorized',
                'The provided credentials are incorrect.',
                401
            );
        }
    }

    /**
     * Handle registration request.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $token = $this->authService->register($request);
        return $this->respondWithToken($token);
    }

    /**
     * Handle logout request.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return $this->respondOK([], 'Successfully logged out');
    }

    /**
     * Respond with token.
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        $tokenDetails = $this->authService->respondWithToken($token);
        return $this->respondSuccess($tokenDetails, 'Authenticated successfully');
    }
}
