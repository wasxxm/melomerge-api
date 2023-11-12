<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckJamSessionOwner
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure  $next
     * @param  string  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null): mixed
    {
        $jamSession = $request->route('jam_session'); // Assuming 'jamSession' is the route parameter name

        if (auth()->check() && $jamSession && $jamSession->organizer_id === auth()->id()) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized access',
            'errors' => ['authorization' => ['You do not have permission to access this JamSession.']]
        ], 403);
    }
}
