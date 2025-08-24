<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o caminho da requisição começa com "api/"
        if ($request->is('api/*')) {
            // Define o header 'Accept' para 'application/json' na própria requisição.
            // Isso força o Laravel a tratar esta requisição como se ela quisesse uma resposta JSON.
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}