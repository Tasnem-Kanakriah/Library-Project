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
        // app()->setLocale($locale[0] ?? 'ar');
        
        // $locale = $request->getLanguages();
        // app()->setLocale($locale[0] ?? config('app.locale'));
        // return $next($request);
        
        $locale = $request->getPreferredLanguage(['ar', 'en']) ?? config('app.locale');
        app()->setLocale($locale);
        return $next($request);
        
        // $locale = $request->getLanguages();
        // app()->setLocale(empty($locale) ? config('app.locale') : $locale[0]);
        // // return config('app.locale');
        // return $next($request);
        // in web, Arabic language send ar, but English language send en_GB
    }
}
