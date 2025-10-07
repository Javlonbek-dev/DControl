<?php

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerify; // vendor class


Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/edit/{file}', function ($file) {
    $documentServerUrl = 'https://dnazorat.uz/oo';
    $jwtSecret = 'MY_SUPER_SECRET';

    $fileName  = basename($file);
    $fileType  = pathinfo($fileName, PATHINFO_EXTENSION) ?: 'docx';
    $fileUrl   = secure_url("storage/docs/$fileName");

    $config = [
        "documentType" => "word",
        "document" => [
            "fileType"    => $fileType,
            "key"         => md5($fileName.'-'.time()),
            "title"       => $fileName,
            "url"         => $fileUrl,
            "permissions" => ["edit" => true, "download" => true],
        ],
        "editorConfig" => [
            "lang"        => "en",
            // Web route bilan mos!
            "callbackUrl" => secure_url("api/onlyoffice/callback/$fileName"),
        ],
    ];
    $config['token'] = JWT::encode($config, $jwtSecret, 'HS256');

    return view('onlyoffice-editor', compact('documentServerUrl', 'config'));
});
