<?php

namespace App\Exceptions;

use Exception;
use Krak\Validation;
use Illuminate;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Model\Exception\NotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function App\Http\{failedValidationResponse, errorResponse, notFoundResponse};

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Krak\Validation\Exception\ViolationException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (!$request->expectsJson()) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof Validation\Exception\ViolationException) {
            return failedValidationResponse($exception->violation->format());
        }
        if ($exception instanceof Illuminate\Validation\ValidationException) {
            return failedValidationResponse($exception->validator->errors()->toArray());
        }
        if ($exception instanceof NotFoundException) {
            return notFoundResponse($exception->getEntityCode(), $exception->getMessage());
        }
        if ($exception instanceof NotFoundHttpException) {
            return notFoundResponse('endpoint', 'Endpoint');
        }

        $debug = $this->container['config']->get('app.debug');
        if (!$debug) {
            return errorResponse(500, 'unhandled_exception', $exception->getMessage());
        }

        return errorResponse(500, 'unhandled_exception', $exception->getMessage(), [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ]);
    }
}
