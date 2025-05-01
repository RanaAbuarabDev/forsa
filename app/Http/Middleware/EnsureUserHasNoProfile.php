<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasNoProfile
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->profile) {
            return response()->json([
                'message' => 'You already have a profile. You can only update or delete it.'
            ], 403); // Forbidden
        }

        return $next($request);
    }
}
