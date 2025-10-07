<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/onlyoffice/callback/{file}', function (Request $request, $file) {
    $data = $request->all();

    // 2 (Saved) yoki 6 (Force save) holatlarida yuklab saqlaymiz
    if (isset($data['status']) && in_array($data['status'], [2,6]) && !empty($data['url'])) {
        $savePath = storage_path('app/public/docs/' . basename($file));

        // Barqaror yuklash uchun cURL
        $ch = curl_init($data['url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_TIMEOUT => 120,
        ]);
        $bin  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($bin === false || $code >= 400) {
            \Log::error('OO download failed', ['code'=>$code, 'src'=>$data['url'] ?? null]);
            return response()->json(['error' => 1]);
        }

        file_put_contents($savePath, $bin);
        \Log::info('OO saved', ['path'=>$savePath]);
    }

    return response()->json(['error' => 0]);
});
