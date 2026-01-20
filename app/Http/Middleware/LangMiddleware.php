<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $locale = $request->header('Accept-Language') ?? 'ar';
        $locale = $request->getLanguages();
        // dd($locale);
        app()->setLocale($locale[0]);
        return $next($request);
    }
}
