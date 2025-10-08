<!doctype html>
<html lang="uz">
<head>
    <meta charset="utf-8" />
    <title>OnlyOffice Editor</title>

    <!-- MUHIM: /oo prefiks bilan -->
    <script src="{{ rtrim($documentServerUrl,'/') }}/web-apps/apps/api/documents/api.js"></script>

    <!-- CSP: oddiy, ishlaydigan -->
    <meta http-equiv="Content-Security-Policy"
          content="default-src 'self' https: data: blob:;
                 script-src 'self' https: 'unsafe-inline';
                 style-src  'self' https: 'unsafe-inline';
                 img-src    'self' https: data: blob:;
                 connect-src 'self' https: wss:;
                 frame-src https://dnazorat.uz https://dnazorat.uz/oo;">
    <style>html,body,#placeholder{height:100%;margin:0;padding:0}</style>
</head>
<body>
<div id="placeholder"></div>
<script>
    const config = @json($config);
    console.log('api.js loaded?', typeof DocsAPI !== 'undefined');
    window.docEditor = new DocsAPI.DocEditor('placeholder', config);
</script>
</body>
</html>
