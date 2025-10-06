<?php

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerify; // vendor class


Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/edit/{file}', function ($file) {
    $documentServerUrl = 'http://192.168.151.93:8080';
    $jwtSecret = 'my_secret_123';

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
    if (isset($data['status']) && in_array($data['status'], [2,6]) && !empty($data['url'])) {
        $savePath = storage_path('app/public/docs/' . basename($file));
        file_put_contents($savePath, file_get_contents($data['url']));
    }
    return response()->json(['error' => 0]);
})->withoutMiddleware([BaseVerify::class]); // <--

