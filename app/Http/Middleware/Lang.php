<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class Lang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLanguages = ['ar', 'en'];
        $excludedPaths = ['livewire', 'admin', 'api' , 'storage'];
        $queryLang = $request->query('lang');
        // التحقق من وجود باراميتر lang في الرابط ?lang=ar
        $queryLang = $request->query('lang');
        if ($queryLang && in_array($queryLang, $supportedLanguages)) {
            app()->setLocale($queryLang);
            URL::defaults(['lang' => $queryLang]);
            return $next($request);
        }

        // تحديد اللغة من أول جزء في الرابط
        $lang = $request->segment(1);

        // استثناء المسارات المحددة من فحص اللغة
        if (in_array($lang, $excludedPaths)) {
            return $next($request);
        }


        if (in_array($lang, $supportedLanguages)) {
            // إذا كانت اللغة صحيحة
            app()->setLocale($lang);
            URL::defaults(['lang' => $lang]);
        } else {
            // إذا لم يكتب المستخدم اللغة، نحوله للغة الافتراضية مع الاحتفاظ بالمسار
            $path = ltrim($request->getPathInfo(), '/');
            $newPath = '/ar' . ($path ? '/' . $path : '');
            return redirect($newPath);
        }
        return $next($request);
    }
}
