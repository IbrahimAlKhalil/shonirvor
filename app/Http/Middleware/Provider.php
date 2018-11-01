<?php

namespace App\Http\Middleware;

use App\Models\Ind;
use App\Models\Org;
use Closure;

class Provider
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $indService = Ind::where('user_id', $request->user()->id)->exists();
        $orgService = Org::where('user_id', $request->user()->id)->exists();

        if ( ! $indService && ! $orgService) {
            return abort(403);
        }

        return $next($request);
    }
}
