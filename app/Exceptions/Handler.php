<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Psr\Log\LogLevel;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            if ($request->routeIs('login') || $request->is('login') || $request->is('login/*')) {
                return redirect()->route('login')
                    ->withInput($request->except('password'))
                    ->with('error', 'Sesi login Anda telah kedaluwarsa. Silakan coba kembali.');
            }

            return redirect()->back()
                ->withInput($request->except('password', '_token'))
                ->with('error', 'Halaman atau sesi Anda telah kedaluwarsa. Silakan coba kirim ulang formulir.');
        }

        return parent::render($request, $e);
    }
}
