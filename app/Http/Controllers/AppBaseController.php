<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class AppBaseController
{
    /**
     * Send a success response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($message, $data = null, $statusCode = Response::HTTP_OK)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Send an error response.
     *
     * @param string $error
     * @param int $errorCode
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $errorCode = null, $statusCode = Response::HTTP_BAD_REQUEST)
    {
        $response = [
            'success' => false,
            'error' => $error,
        ];

        if (!is_null($errorCode)) {
            $response['error_code'] = $errorCode;
        }

        return response()->json($response, $statusCode);
    }
}
