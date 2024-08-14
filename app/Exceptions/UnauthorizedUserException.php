<?php

namespace App\Exceptions;

use \App\Exceptions\ApiBaseException;
use Illuminate\Http\JsonResponse;

class UnauthorizedUserException extends ApiBaseException
{
    public function render(): JsonResponse
    {
        return $this->respondUnauthorized(
            'fail',
            'Cannot Edit or Delete other user\'s ticket.',
        );
    }
}
