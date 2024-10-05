<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function validationErrorResponse($validator)
    {
        return response()->json([
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422); // Respond with 422 Unprocessable Entity status
    }

    /**
     * Respond with a success message.
     *
     * @param  string  $message
     * @param  mixed   $data
     * @param  int     $statusCode
     * @return JsonResponse
     */
    protected function successResponse(string $message, $data = null, int $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'code' => $statusCode,
        ], $statusCode);
    }

    /**
     * Respond with an error message.
     *
     * @param  string  $message
     * @param  int     $statusCode
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $statusCode = 400)
    {
        return response()->json([
            'message' => $message,
            'code' => $statusCode,
        ], $statusCode);
    }
}
