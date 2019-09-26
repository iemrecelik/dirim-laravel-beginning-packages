<?php

namespace Dirim\BeginningPackage\Middleware;

use Closure;

use Dirim\BeginningPackage\Documents\ClientsLog;
use Session;

class RequestInfoLogging
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
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $this->clientInfoLogging($request, $response);
    }

    protected function clientInfoLogging($request, $response = null)
    {
        $url = $request->url();
        $path = $request->path();
        $method = $request->getMethod();
        $ip = $request->getClientIp();
        $status = $response->getStatusCode();
        $email = empty($request->user())?null:$request->user()->email;

        $clientsInfo = [
            'uniqueID' => Session::getId(),
            'email' => $email,
            'method' => $method,
            'path' => $path,
            'url' => $url,
            'status' => $status,
            'enteredAt' => date('Y-m-d H-i-s'),
        ];

        ClientsLog::create($clientsInfo);
    }
}
