<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckJamSessionExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jamSession = $request->route('jam_session'); // Assuming 'jamSession' is the route parameter name

        if ($jamSession) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Jam Session does not exist.',
            'errors' => ['not_found' => ['The requested Jam Session does not exist.']]
        ], 404);
    }
}
