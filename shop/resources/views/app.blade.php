<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>My App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @routes
    @vite('resources/js/app.js')
</head>
<body>
    @inertia
</body>
</html>
