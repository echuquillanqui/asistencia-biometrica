<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;

class DeviceApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-DEVICE-API-KEY', $request->input('api_key'));

        if (!$apiKey) {
            return response()->json(['message' => 'API key requerida'], 401);
        }

        $device = Device::where('api_key', $apiKey)->first();

        if (!$device) {
            return response()->json(['message' => 'API key inválida'], 401);
        }

        $request->attributes->set('device', $device);

        return $next($request);
    }
}
