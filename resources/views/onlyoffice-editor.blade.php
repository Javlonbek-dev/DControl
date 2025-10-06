<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OnlyOffice Editor</title>
    <script type="text/javascript" src="{{ $documentServerUrl }}/web-apps/apps/api/documents/api.js"></script>
</head>
<body>
<div id="placeholder" style="width:100%; height:100vh;"></div>

<script type="text/javascript">
    const docEditor = new DocsAPI.DocEditor("placeholder", {
        "width": "100%",
        "height": "100%",
        "type": "desktop",
        "documentType": "word",
        "token": "{{ $token }}",
        "document": {!! $configJson !!},
        "editorConfig": {
            "mode": "edit",
            "lang": "en"
        }
    });
</script>
</body>
</html>
