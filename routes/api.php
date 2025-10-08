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
    $safeFile = basename($file); // path traversaldan himoya
    $payload  = $request->all();

    // 0) Kiruvchi ma'lumotni loglash (headerlardan authni qisqartiramiz)
    $headers = collect($request->headers->all())
        ->map(fn($v, $k) => in_array(strtolower($k), ['authorization','cookie']) ? ['...'] : $v);
    Log::debug('[OODS] Incoming callback', [
        'ip'      => $request->ip(),
        'file'    => $safeFile,
        'status'  => $payload['status'] ?? null,
        'has_url' => isset($payload['url']),
        'headers' => $headers
    ]);

    // 1) JWT tekshiruvi (agar yoqilgan bo‘lsa)
    $secret = env('ONLYOFFICE_JWT_SECRET', null);
    $token  = $request->bearerToken() ?: ($payload['token'] ?? null);
    if ($secret && $token) {
        try {
            JWT::decode($token, new Key($secret, 'HS256'));
            Log::debug('[OODS] JWT verify: OK');
        } catch (\Throwable $e) {
            Log::error('[OODS] JWT verify FAILED', ['err' => $e->getMessage()]);
            return response()->json(['error' => 1]); // rad etamiz
        }
    } else {
        Log::warning('[OODS] JWT missing or secret not set', ['have_secret' => (bool)$secret, 'have_token' => (bool)$token]);
    }

    // 2) Status tahlil
    $status = (int)($payload['status'] ?? 0);
    if (!in_array($status, [1,2,3,4,6,7])) {
        Log::warning('[OODS] Unknown status', ['status' => $status, 'file' => $safeFile]);
    }

    // 3) Saqlash kerak bo‘lgan holatlar: 2 (Saved) va 6 (Force saved)
    if (in_array($status, [2,6])) {
        $srcUrl = $payload['url'] ?? null;
        if (!$srcUrl) {
            Log::error('[OODS] No URL in payload for save', ['file' => $safeFile]);
            return response()->json(['error' => 1]);
        }

        $savePath = storage_path('app/public/docs/'.$safeFile);

        $ch = curl_init($srcUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,   // self-signed bo'lsa vaqtincha false qilib ko'rib tashxis qiling
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_USERAGENT      => 'dnazorat-oods-callback/1.0',
        ]);
        $bin   = curl_exec($ch);
        $errno = curl_errno($ch);
        $err   = curl_error($ch);
        $code  = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $size  = is_string($bin) ? strlen($bin) : 0;
        curl_close($ch);

        if ($bin === false || $code >= 400) {
            Log::error('[OODS] Download FAILED', [
                'http_code' => $code,
                'curl_errno'=> $errno,
                'curl_error'=> $err,
                'src'       => $srcUrl,
            ]);
            return response()->json(['error' => 1]);
        }

        file_put_contents($savePath, $bin);
        Log::info('[OODS] Saved OK', [
            'file' => $safeFile,
            'bytes'=> $size,
            'dst'  => $savePath
        ]);

        return response()->json(['error' => 0]);
    }

    // 4) Boshqa statuslar: log + 0
    // 1: viewing, 4: no changes, 3/7: errors
    Log::debug('[OODS] Non-save status', ['status' => $status, 'file' => $safeFile]);
    return response()->json(['error' => 0]);
});
