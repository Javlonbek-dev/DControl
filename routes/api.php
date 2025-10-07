<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/onlyoffice/callback/{file}', function (Request $request, $file) {
    $data = $request->all();
    if (isset($data['status']) && in_array($data['status'], [2,6]) && !empty($data['url'])) {
        $savePath = storage_path('app/public/docs/'.basename($file));
        file_put_contents($savePath, file_get_contents($data['url']));
    }
    return response()->json(['error' => 0]);
});
