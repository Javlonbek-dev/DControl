<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/onlyoffice/callback/{file}', function (Request $request, $file) {
    $safe = basename($file);
    $payload = $request->all();
    $status = (int)($payload['status'] ?? 0);

    $needJwt = in_array($status, [2,6]);
    $secret  = env('ONLYOFFICE_JWT_SECRET');
    $token   = $request->bearerToken() ?: ($payload['token'] ?? null);

    if ($needJwt && $secret && $token) {
        try { JWT::decode($token, new Key($secret, 'HS256')); }
        catch (\Throwable $e) { \Log::error('[OODS] JWT FAILED', ['err'=>$e->getMessage()]); return response()->json(['error'=>1]); }
    }

    if (in_array($status, [2,6])) {
        $src = $payload['url'] ?? null;
        if (!$src) return response()->json(['error'=>1]);

        $ch = curl_init($src);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER=>true, CURLOPT_FOLLOWLOCATION=>true,
            CURLOPT_SSL_VERIFYPEER=>true, CURLOPT_SSL_VERIFYHOST=>2,
            CURLOPT_TIMEOUT=>120, CURLOPT_CONNECTTIMEOUT=>20,
        ]);
        $bin=curl_exec($ch); $code=curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err=curl_error($ch); curl_close($ch);
        if ($bin===false || $code>=400) {
            \Log::error('[OODS] Download FAILED', ['code'=>$code,'err'=>$err,'src'=>$src]);
            return response()->json(['error'=>1]);
        }
        file_put_contents(storage_path("app/public/docs/{$safe}"), $bin);
        \Log::info('[OODS] Saved OK', ['file'=>$safe]);
    }

    return response()->json(['error'=>0]);
});
