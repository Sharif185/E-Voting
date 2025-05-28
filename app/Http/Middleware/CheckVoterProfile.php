<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVoterProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (auth()->check() && auth()->user()->role === 'voter')
          {
                 if (!auth()->user()->hasCompletedProfile()) {
                     return redirect()->route('voter.profile.create');
                 }

                 if (!auth()->user()->isApprovedVoter()) {
                     return redirect()->route('voter.dashboard')
                         ->with('status', 'Your profile is pending approval');
                 }
             }
        return $next($request);
    }
}
