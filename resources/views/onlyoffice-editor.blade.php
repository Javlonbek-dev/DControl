<!doctype html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <title>OnlyOffice Editor</title>
    <!-- Faqat HTTPS va sizning /oo yo'lingiz -->
    <script src="{{ rtrim($documentServerUrl,'/') }}/web-apps/apps/api/documents/api.js"></script>
    <!-- Ixtiyoriy: CSP (OODS skript/iframe kirishi uchun) -->
    <meta http-equiv="Content-Security-Policy"
          content="default-src 'self' https: data: blob:;
                 script-src 'self' https: 'unsafe-inline';
                 connect-src 'self' https:;
                 img-src 'self' https: data: blob:;
                 frame-src {{ parse_url($documentServerUrl, PHP_URL_SCHEME) }}://{{ parse_url($documentServerUrl, PHP_URL_HOST) }};
                 object-src 'none';">
    <style>html,body,#placeholder{height:100%;margin:0}</style>
</head>
<body>
<div id="placeholder"></div>
<script>
    const config = @json($config, JSON_UNESCAPED_UNICODE);
    window.docEditor = new DocsAPI.DocEditor("placeholder", config);
</script>
</body>
</html>
