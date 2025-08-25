<?php

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \App\Http\Middleware\ValidateJsonPayload::class,
            \App\Http\Middleware\ForceJsonResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
         $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            // Se a requisição for para a nossa API...
            if ($request->is('api/*')) {
                // ...retorne uma resposta JSON customizada com status 404.
                return response()->json([
                    'message' => 'O recurso solicitado não foi encontrado.'
                ], 404);
            }
        });
    })->create();
