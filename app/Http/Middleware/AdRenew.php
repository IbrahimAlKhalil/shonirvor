<?php

namespace App\Http\Middleware;

use App\Models\Ad;
use Closure;

class AdRenew
{
    public function handle($request, Closure $next)
    {
        $ad = $request->route()->parameters['ad'];

        if ($ad->user_id != $request->user()->id || is_null($ad->expire)) {
            abort(404, 'You have pending application or this ad doesn\'t belong to you');
        }

        return $next($request);
    }
}
