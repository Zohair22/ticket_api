<?php
declare(strict_types=1);
namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Log;
use App\Enums\ApiResponseTypes;

/**
 * Class ApiResponseTrait - contains all functions for HTTP Status Codes and Response Types
 *
 * Used extensively by the APiBaseController class - was split to allow the ApiBaseController class
 * to have project specific functions if needed, while this can be shared between projects
 *
 * References
 * https://mshossain.me/blog/designing-laravel-rest-api-best-practices/
 * https://www.linkedin.com/pulse/what-should-api-response-structure-atiqur-rahman/#
 * https://medium.com/@adelekandavid2013/laravel-restful-api-development-a-step-by-step-approach-part-1-fdf9341662e6
 *
 */
trait ApiResponseTrait
{

    /********************************************************************************
     * Generic Response structures
     ********************************************************************************/


    public function respondSuccess(array $data = [], string $message = "Success", int $status = 200, array $extraHeaders = [], $pagination = null): JsonResponse
    {
        Log::debug("SUCCESS API Response");

        $response = [
            'status' => ApiResponseTypes::SUCCESS,
            'code' => $status,
            "message" => $message,
            "data" => $data,
        ];

        if (!is_null($pagination)) {
            $response['pagination'] = $pagination;
        }

        $extraHeaders = $this->getDebugExtraHeaders();
        return response()->json($response, $status, $extraHeaders);
    }

    /**
     * https://medium.com/@adelekandavid2013/laravel-restful-api-development-a-step-by-step-approach-part-1-fdf9341662e6
     * @param Paginator $paginate
     * @param $data
     * @return mixed
     */
    public function respondSuccessWithPagination(Paginator $paginate, $data, string $message = "Success", int $status = 200, $extraHeaders = []): JsonResponse
    {
        Log::debug("SUCCESS API Response: with paginator");

        $data = array_merge($data, [
            'paginator' => [
                'total_count'  => $paginate->total(),
                'total_pages' => ceil($paginate->total() / $paginate->perPage()),
                'current_page' => $paginate->currentPage(),
                'limit' => $paginate->perPage(),
            ]
        ]);

        return $this->respondSuccess($data, $message, $status, $extraHeaders);

//        $response = [
//            'status' => ApiResponseTypes::SUCCESS,
//            'code' => $status,
//            'message' => $message,
//            'data' => $data
//        ];
//        !is_null($status) && $response['code'] = $status;
//
//        $response = response()->json($response, $status, $extraHeaders);
//        Log::debug($response);
//        return $response;
    }

    public function respondFail($errors, string $message = "Request/Response Failed", int $status = 400, array $extraHeaders = []): JsonResponse
    {
        Log::debug("FAIL API Response");

        $response = [
            'status' => ApiResponseTypes::FAILED,
            'code' => $status,
            "message" => $message,
        ];

        // checks if single error or multiple
        // TODO: Want to remove this and replace it with a more unified response
        if (is_array($errors)) {
            $response['errors'] = $errors;
        } else {
            $response['error'] = $errors;
        }

        $extraHeaders = $this->getDebugExtraHeaders();
        return response()->json($response, $status, $extraHeaders);
    }


    public function respondError($errors, string $message = "Internal Server Error", int $status = 500, array $extraHeaders = []): JsonResponse
    {
        Log::debug("ERROR API Response");

        $response = [
            "status" => ApiResponseTypes::ERROR,
            "code" => $status,
            "message" => $message
        ];

        // checks if single error or multiple
        if (is_array($errors)) {
            $response['errors'] = $errors;
        } else {
            $response['error'] = $errors;
        }

        $extraHeaders = $this->getDebugExtraHeaders();
        return response()->json($response, $status, $extraHeaders);
    }

    /********************************************************************************
     * SIMPLIFIED RESPONSES
     ********************************************************************************/

    // 200+ RESPONSES - SUCCESS
    // respondSuccess($data = [], $message = "Success", $status = 200, $extraHeaders = [])

    public function respondOK($data = [], $message = 'Response Success - OK'): JsonResponse
    {
        $status = 200;
        return $this->respondSuccess($data, $message, $status);
    }

    public function respondCreated($data = [], $message = 'Response Created'): JsonResponse
    {
        $status = 201;
        return $this->respondSuccess($data, $message, $status);
    }

    public function respondAccepted($data = [], $message = 'Response Accepted'): JsonResponse
    {
        $status = 202;
        return $this->respondSuccess($data, $message, $status);
    }

    public function respondNoContent(): JsonResponse
    {
        $status = 204;
        return $this->respondSuccess([], '', $status);
    }

    // --------------------------------------------------------------------------------------------------------------
    // 400 RESPONSES - CLIENT ERRORS
    // respondFail($errors, $message = "Request/Response Failed", $status = 400, $extraHeaders = [])
    // --------------------------------------------------------------------------------------------------------------

    public function respondBadRequest($errors = [], $message = 'Bad Request'): JsonResponse
    {
        $status = 400;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondUnauthorized($errors = [], $message = 'Unauthorized'): JsonResponse
    {
        $status = 401;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondForbidden($errors = [], $message = 'Forbidden'): JsonResponse
    {
        $status = 403;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondNotFound($errors = [], $message = 'Not Found'): JsonResponse
    {
        $status = 404;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondNotAllowed($errors = [], $message = 'Not Found'): JsonResponse
    {
        $status = 405;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondNotAcceptable($errors = [], $message = 'Not Acceptable'): JsonResponse
    {
        $status = 406;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondConflict($errors = [], $message = 'Conflict'): JsonResponse
    {
        $status = 409;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondFailedValidation($errors = [], $message = 'Failed Validation'): JsonResponse
    {
        $status = 422;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondUnprocessable($errors = [], $message = 'Unprocessable Entity'): JsonResponse
    {
        $status = 422;
        return $this->respondFail($errors, $message, $status);
    }

    // --------------------------------------------------------------------------------------------------------------
    // 500 RESPONSES - SERVER ERRORS
    // respondError($errors, $errorCode = null, $message = "Internal Server Error", $status = 500, $extraHeaders = [])
    // --------------------------------------------------------------------------------------------------------------

    public function respondWithError(array $errors = [], string $message = 'Internal Server Error'): JsonResponse
    {
        $status = 500;
        return $this->respondError($errors, $message, $status);
    }

    public function respondUnknownError(array $errors = [], string $message = 'Internal Server Error'): JsonResponse
    {
        $status = 500;
        return $this->respondError($errors, $message, $status);
    }

    /**
     * Attach response headers for debug to get around CORS
     * @param $response
     */
    private function getDebugExtraHeaders(): array
    {
        $isDebug = env('APP_DEBUG');
        // only send the extra debug if we are debugging the code
        if ($isDebug) {
            return [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS, PUT, PATCH, DELETE',
                'Access-Control-Allow-Headers' => 'Origin,Content-Type,X-Requested-With,Accept,Authorization'
            ];
        }
        return [];
    }
}
