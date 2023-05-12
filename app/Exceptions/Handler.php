<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // not authorized
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'message' => 'Unauthorized',
                'error'   => $exception->getMessage()
            ], 403);
        }

        // unexisting resource
        if($exception instanceof ModelNotFoundException){ 
            return response([
                'message' => 'Resource not found!'
            ], 404);
        }

        // unexisting page
        if($exception instanceof NotFoundHttpException){ 
            return response([
                'message' => 'Page not found!'
            ], 404);
        }

        return parent::render($request, $exception);
    }
}
