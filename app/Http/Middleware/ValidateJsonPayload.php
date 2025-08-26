<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateJsonPayload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Só aplicamos esta lógica para métodos que normalmente enviam um corpo de dados.
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            
            json_decode($request->getContent());

            // Se o último erro de JSON não for "nenhum erro", significa que o JSON é inválido.
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Paramos tudo e retornamos um erro 400 Bad Request.
                return response()->json([
                    'success' => false,
                    'message' => 'Malformed JSON syntax: ' . json_last_error_msg()
                ], 400);
            }
        }

        // Se o JSON for válido ou a requisição for de outro tipo (GET, DELETE), ela continua normalmente.
        return $next($request);
    }
}