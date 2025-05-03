<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des TFE - @yield('title', 'Accueil')</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Scripts alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles supplémentaires -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <nav class="flex justify-between">
                <div class="flex space-x-8">
                    <a href="{{ route('timeline') }}" class="text-gray-700 hover:text-blue-500 font-medium">Timeline</a>
                    <a href="{{ route('comments') }}" class="text-gray-700 hover:text-blue-500 font-medium">Commentaires</a>
                </div>
                <div>
                    <a href="{{ route('admin.milestones') }}" class="text-gray-700 hover:text-blue-500 font-medium">Administration</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="pb-6">
            <h1 class="text-3xl font-bold text-gray-900">@yield('header', 'Gestion des TFE')</h1>
        </div>
        
        @if(session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('message') }}</p>
            </div>
        @endif
        
        @yield('content')
    </main>

    <footer class="bg-white shadow-inner mt-10 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Gestion des TFE - Tous droits réservés
        </div>
    </footer>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>