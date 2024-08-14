<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;

class ApiBaseException extends Exception
{
    use ApiResponseTrait;

    /**
     * ? Render the exception as an error response.
     * ? Base rendering method
     *
     * @return JsonResponse The JSON response containing the error details.
     */
    public function render(): JsonResponse {
        return $this->respondError(
            $this->getTrace(),
            $this->getMessage()
        );
    }
}
