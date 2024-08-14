<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class InvalidCredentialsException extends Exception
{
    use ApiResponseTrait;

    public function report()
    {
        Log::error('Ticket not found exception', ['exception' => $this]);
    }

    /**
     * Render the exception as an error response.
     *
     * @return JsonResponse The JSON response containing the error details.
     */
    public function render(): JsonResponse
    {
        return $this->respondFail(
            'Unauthorized',
            'The provided credentials are incorrect.',
            401
        );
    }
}
