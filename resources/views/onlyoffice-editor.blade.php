<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>OnlyOffice</title>
    <script src="{{ $documentServerUrl }}/web-apps/apps/api/documents/api.js"></script>
</head>
<body style="margin:0">
<div id="placeholder" style="width:100%; height:100vh;"></div>
<script>
    const cfg = {!! json_encode($config, JSON_UNESCAPED_SLASHES) !!};
    new DocsAPI.DocEditor("placeholder", cfg);
</script>
</body>
</html>
