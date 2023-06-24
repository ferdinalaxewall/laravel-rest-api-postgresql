<?php

namespace App\Helpers;

class Response
{
    /*  Returning Response as Object Response
        $statusCode = Response Status Code
        $message = Response Message
        $success = Response is success or not
        $data = Response Data */
    public static function baseResponseWithStatusCode($statusCode, $message, $success = false, $data = null)
    {
        return (object) [
            'success' => $success,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data
        ];
    }

    /*  Returning Response as JSON Response
        $statusCode = Response Status Code
        $message = Response Message
        $success = Response is success or not
        $data = Response Data */
    public static function jsonResponse($statusCode, $message, $success = false, $data = null)
    {
        return response()->json(self::baseResponseWithStatusCode($statusCode, $message, $success, $data), $statusCode);
    }
}