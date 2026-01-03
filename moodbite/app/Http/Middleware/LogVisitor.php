<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $excludedPaths = [
            'admin',
            'login',
            'register',
            'logout',
            'api'
        ];

        foreach ($excludedPaths as $path) {
            if ($request->is($path) || $request->is($path . '/*')) {
                return $next($request);
            }
        }

        DB::table('visitor_logs')->insert([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'path' => $request->path(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return $next($request);
    }
}
