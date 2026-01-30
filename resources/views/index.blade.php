<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes SPA</title>
    
    @php
        $distPath = public_path('vendor/quotes/assets');
        $jsFile = collect(glob("$distPath/*.js"))->map(fn($path) => basename($path))->first();
        $cssFile = collect(glob("$distPath/*.css"))->map(fn($path) => basename($path))->first();
    @endphp

    @if($cssFile)
        <link rel="stylesheet" href="{{ asset('vendor/quotes/assets/' . $cssFile) }}">
    @endif
</head>
<body>
    <div id="app"></div>

    @if($jsFile)
        <script type="module" src="{{ asset('vendor/quotes/assets/' . $jsFile) }}"></script>
    @else
        <p style="color:red">Error: File vendor/quotes/assets not found</p>
    @endif
</body>
</html>