<?php

namespace App\Http\Middleware;

use App;
use Closure;

class IpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$ips)
    {
        // Cek apakah request berasal dari localhost
        if ($request->ip() === '127.0.0.1') {
            return $next($request); // Izinkan akses dari localhost
        }

        // Logika untuk memeriksa IP lainnya
        $access = array_filter(array_map(function ($v) use ($request) {
            $star = strpos($v, "*");
            return ($star !== false) ? (substr($request->ip(), 0, $star) == substr($v, 0, $star)) : ($request->ip() == $v);
        }, $ips));

        return $access ? $next($request) : App::abort(403);
    }
}
