<?php

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerify; // vendor class


Route::get('/', function () {
    return redirect('/admin');
});
$userId = optional(Auth::user())->id ?? 0;
$userName = optional(Auth::user())->name ?? 'Guest';

Route::get('/edit/{file}', function (Request $request, $file) {
    $documentServerUrl = rtrim(config('onlyoffice.url', env('ONLYOFFICE_URL')), '/'); // https://dnazorat.uz/oo
    $jwtSecret = config('onlyoffice.jwt_secret', env('ONLYOFFICE_JWT_SECRET'));

    $fileName = basename($file);
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION) ?: 'docx');

    // OnlyOffice to'g'ridan-to'g'ri yuklay olishi uchun public-URL (authsiz)
    // Masalan: Route::get('/public/docs/{file}', ...) orqali file berilyapti
    $fileUrl = secure_url("public/docs/{$fileName}");

    // Versiyaga bog'liq kalit (keshlash muammolarini oldini oladi)
    $path = storage_path("app/public/docs/{$fileName}");
    $stat = file_exists($path) ? (filesize($path).'|'.filemtime($path)) : time();
    $docKey = substr(hash('sha256', $fileName.'|'.$stat), 0, 32);

    // DocumentType avtomatik
    $docTypeMap = ['doc'=>'word','docx'=>'word','odt'=>'word','rtf'=>'word','txt'=>'word',
        'xls'=>'cell','xlsx'=>'cell','ods'=>'cell','csv'=>'cell',
        'ppt'=>'slide','pptx'=>'slide','odp'=>'slide','pdf'=>'word']; // pdf-view
    $documentType = $docTypeMap[$ext] ?? 'word';

    $config = [
        "documentType" => $documentType,
        "document" => [
            "fileType" => $ext,
            "key"      => $docKey,
            "title"    => $fileName,
            "url"      => $fileUrl,
            "permissions" => [
                "edit" => true, "download" => true, "print" => true,
                "review" => true, "comment" => true, "fillForms" => true,
                "modifyFilter" => true, "modifyContentControl" => true,
            ],
        ],
        "editorConfig" => [
            "lang" => "en",
            "mode" => "edit",
            "callbackUrl" => secure_url("api/onlyoffice/callback/{$fileName}"),
            "user" => [
                "id" => (string)(optional($request->user())->id ?? 0),
                "name" => optional($request->user())->name ?? 'Guest',
            ],
            "customization" => [
                "autosave" => true,     // videodagi "forcesave" ga o'xshash foydali opsiya
            ],
        ],
    ];

    // >>> MUHIM: JWT tokenni config'ga qo'shamiz
    if (!empty($jwtSecret)) {
        $config['token'] = JWT::encode($config, $jwtSecret, 'HS256');
    }

    return view('onlyoffice-editor', compact('documentServerUrl', 'config'));
});
