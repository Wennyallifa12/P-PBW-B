<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <nav class="bg-white border-b">
        <div class="max-w-5xl mx-auto px-4 py-3 flex justify-between">
            <a href="/" class="font-semibold">SMP Mentari</a>
            <a href="/kegiatan" class="text-sm">Kegiatan</a>
        </div>
    </nav>
    
    <main class="max-w-5xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    <footer class="text-center text-sm text-gray-500 py-6">
        &copy; {{ date('Y') }} SMP Mentari
    </footer>
</body>
</html>