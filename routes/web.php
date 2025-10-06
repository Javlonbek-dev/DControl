<?php

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/edit/{file}', function ($file) {
    $documentServerUrl = 'http://192.168.151.93:8080';
    $jwtSecret = 'f67a137951bc360ad89f6dd6a41e2dcacc99b70e62020e44841c1dc6d9da2374';

    // Fayl URL (agar public/docs ichida bo‘lsa)
    $fileUrl = url("/docs/$file");
    $fileType = pathinfo($file, PATHINFO_EXTENSION);
    $fileName = basename($file);

    // 🧠 Faqat shu qator qo‘shilmay qolgan:
    $documentType = 'word'; // docx uchun, excel uchun 'cell', ppt uchun 'slide'

    $config = [
        "documentType" => $documentType, // <— majburiy!
        "document" => [
            "fileType" => $fileType,
            "key" => md5($file . time()),
            "title" => $fileName,
            "url" => $fileUrl,
            "permissions" => [
                "edit" => true,
                "download" => true,
            ]
        ],
        "editorConfig" => [
            "callbackUrl" => url("/onlyoffice/callback/$file"),
            "lang" => "en",
        ]
    ];

    $token = JWT::encode($config, $jwtSecret, 'HS256');

    return view('onlyoffice-editor', compact('documentServerUrl', 'config', 'token'));
});
Route::post('/onlyoffice/callback/{file}', function (Request $request, $file) {
    $data = $request->all();

    if ($data['status'] == 2) { // 2 = fayl saqlanmoqda
        $url = $data['url'];
        $path = storage_path("app/docs/$file");

        file_put_contents($path, file_get_contents($url));
    }

    return response()->json(['error' => 0]);
});
