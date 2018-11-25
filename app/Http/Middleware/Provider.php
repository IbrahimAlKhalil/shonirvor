<?php

namespace App\Http\Middleware;

use App\Models\Ind;
use App\Models\Org;
use Closure;

class Provider
{
    public function handle($request, Closure $next)
    {
        $indService = Ind::withTrashed()->where('user_id', $request->user()->id)->exists();
        $orgService = Org::withTrashed()->where('user_id', $request->user()->id)->exists();

        if ( ! $indService && ! $orgService) {
            return abort(403, 'Your request is blocked by provider middleware');
        }

        return $next($request);
    }
}
