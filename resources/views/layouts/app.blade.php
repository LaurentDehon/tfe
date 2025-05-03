<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TFE Timeline' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 font-sans min-h-screen">
    <header class="bg-white shadow mb-8">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-tight">TFE Timeline</h1>
            <nav class="space-x-4">
                <a href="/" class="text-gray-700 hover:text-blue-600">Timeline</a>
                <a href="/comments" class="text-gray-700 hover:text-blue-600">Commentaires</a>
                <a href="/admin/milestones" class="text-gray-700 hover:text-blue-600">Back-office</a>
            </nav>
        </div>
    </header>
    <main class="container mx-auto px-4">
        @yield('content')
    </main>
    @livewireScripts
</body>
</html>