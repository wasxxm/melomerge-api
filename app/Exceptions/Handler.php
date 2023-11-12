<?php

namespace App\Exceptions;

use App\Models\JamSession;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'errors' => $exception->errors(),
            ], $exception->status);
        }

        // Check if the exception is an instance of ModelNotFoundException
        if ($exception instanceof ModelNotFoundException) {
            // Optionally, add more specific checks for the model type
            if ($exception->getModel() === JamSession::class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jam Session not found',
                    'errors' => ['not_found' => ['The specified Jam Session does not exist.']]
                ], 404);
            }

            // Generic response for other models
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
                'errors' => ['not_found' => ['The requested resource does not exist.']]
            ], 404);
        }

        return parent::render($request, $exception);
    }

    /**
     * RegisterPage the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
