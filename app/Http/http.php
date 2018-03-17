<?php

namespace App\Http;

function notFoundResponse($code, $name) {
    return errorResponse(404, $code . '_not_found', $name . ' could not be found.');
}
function errorResponse($status, $code, $message, $extra = [], $headers = []) {
    return response($status, [
        'code' => $code,
        'message' => $message,
    ] + $extra, $headers);
}

function failedValidationResponse($errors, $status = 422, $code = 'failed_validation', $message = 'The request failed validation.', $headers = []) {
    return response($status, [
        'code' => $code,
        'message' => $message,
        'errors' => $errors
    ], $headers);
}

function exceptionResponse($status, $code, $e, $headers = []) {
    return errorResponse($status, $code, $e->getMessage(), [
        'exception' => get_class($e) . ': ' . $e->getMessage(),
    ], $headers);
}

function response($status, $data, $headers = []) {
    return \response()->json($data, $status, $headers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

function emptyResponse($status = 204, $headers = []) {
    return \response()->make('', $status, $headers);
}
