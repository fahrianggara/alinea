<?php

namespace App\Exceptions;

use App\Http\Resources\ResResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
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

    /**
     * unauthenticated
     *
     * @param  mixed $request
     * @param  mixed $exception
     * @return void
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // if the request is from api
        if (in_array('sanctum', $exception->guards())) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // if the request is from web
        return redirect()->guest(route('login'));
    }
}
