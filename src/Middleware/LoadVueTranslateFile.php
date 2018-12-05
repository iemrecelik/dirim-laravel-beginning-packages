<?php

namespace Dirim\BeginningPackage\Middleware;

use Closure;
use Artisan;

class LoadVueTranslateFile
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
        $this->createVueTranslateFile();
        return $next($request);
    }

    private function createVueTranslateFile()
    {
        $translateJSPath = config('beginningPack.toLangPath');

        $translateJSPath = $translateJSPath 
                            ? base_path($translateJSPath) 
                            : resource_path('lang/translate.js');
        
        $devMode = config('beginningPack.transDevMode');
        
        if (!file_exists($translateJSPath) || $devMode) {
            Artisan::call('make:vue-translate-js');
        }
    }
}
