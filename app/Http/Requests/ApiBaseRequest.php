<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ApiBaseRequest
 *
 * Base Request for all API Requests
 *
 * @package App\Http\Requests
 */
abstract class ApiBaseRequest extends FormRequest
{

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function failedAuthorization(): void
    {
        throw new HttpException(403, 'Unauthorized');
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        // Transform validation errors to the required structure
        $transformed = [];
        foreach ($errors as $field => $message) {
            foreach ($message as $singleMessage) {
                $transformed[] = [
                    'field' => $field,
                    'message' => $singleMessage
                ];
            }
        }

        $errorCode = 422;
        $response = new JsonResponse(
            [
                'status' => "fail",
                'code' => $errorCode,
                'message' => 'Data validation failed',
                'errors' => $transformed
            ],
            $errorCode
        );
        Log::debug("Validation exception: " . json_encode($response->getData(), JSON_PRETTY_PRINT));
        throw new ValidationException($validator, $response);
    }

    /**
     * Determine if the request is for an update operation.
     *
     * @return bool
     */
    protected function isUpdate(): bool
    {
        return collect($this->segments())->last() === 'update';
    }
}
