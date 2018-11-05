<?php

namespace App\Http\Middleware;

use App\Models\Ad;
use Closure;

class AdRenew
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

//        dd($request->route()->parameters);

        $ad = $request->route()->parameters['ad'];

        if ($ad->user_id != $request->user()->id || is_null($ad->expire)) {
            abort(404, 'You have pending application or this ad doesn\'t belong to you');
        }

        return $next($request);
    }
}
