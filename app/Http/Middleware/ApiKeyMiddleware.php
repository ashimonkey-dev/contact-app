<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Application;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');
        
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API Keyが提供されていません'
            ], 401);
        }

        $application = Application::where('api_key', $apiKey)->first();
        
        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'API Keyが無効です'
            ], 401);
        }

        // リクエストにアプリケーション情報を追加
        $request->merge(['application' => $application]);
        
        return $next($request);
    }
}
