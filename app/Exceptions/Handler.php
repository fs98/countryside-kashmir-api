<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Custom function for handling exceptions.
     *
     * @return void
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api*')) {

            if ($e instanceof AccessDeniedHttpException) {

                $response = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];

                return response()->json($response, 403);
            }

            if ($e instanceof AuthorizationException) {

                $response = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];

                return response()->json($response, 403);
            }

            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {

                $response = [
                    'success' => false,
                    'error'    => "Resource not found!",
                ];

                return response()->json($response, 404);
            }

            if ($e instanceof AuthenticationException) {

                $response = [
                    'success' => false,
                    'error'    => $e->getMessage(),
                ];

                return response()->json($response, 401);
            }

            if ($e instanceof ThrottleRequestsException) {

                $response = [
                    'success' => false,
                    'error'    => $e->getMessage(),
                ];

                return response()->json($response, 429);
            }

            if ($e instanceof ValidationException) {
                $response = [
                    'success' => false,
                    'message' => 'Validation failed!',
                    'errors' => $e->errors()
                ];

                return response()->json($response, 422);
            }

            return response(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
        parent::render($request, $e);
    }
}
