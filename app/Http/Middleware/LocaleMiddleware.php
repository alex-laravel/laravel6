<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class LocaleMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Closure
     */
    public function handle($request, Closure $next)
    {
        if (config('locale.enabled') &&
            session()->has('locale') &&
            in_array(session()->get('locale'), array_keys(config('locale.languages')))
        ) {
            app()->setLocale(session()->get('locale'));

            setlocale(LC_TIME, config('locale.languages')[session()->get('locale')][1]);

            Carbon::setLocale(config('locale.languages')[session()->get('locale')][0]);
        }

        return $next($request);
    }
}
