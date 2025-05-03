<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des TFE - @yield('title', 'Accueil')</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles supplémentaires -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        
        .dropdown-menu {
            top: 100%;
            margin-top: 0.5rem;
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
                <div class="admin-dropdown relative" id="adminDropdown">
                    <button id="adminBtn" class="text-gray-700 hover:text-blue-500 font-medium flex items-center">
                        Administration
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="adminMenu" class="dropdown-menu hidden absolute right-0 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <a href="{{ route('admin.milestones') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Jalons</a>
                        <a href="{{ route('admin.tools') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Outils</a>
                        <a href="{{ route('admin.concepts') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Concepts</a>
                        <a href="{{ route('admin.courses') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cours</a>
                    </div>
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
    
    <!-- Menu déroulant script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const adminDropdown = document.getElementById('adminDropdown');
            const adminBtn = document.getElementById('adminBtn');
            const adminMenu = document.getElementById('adminMenu');
            let isOpen = false;
            
            function toggleMenu() {
                if (isOpen) {
                    adminMenu.classList.add('hidden');
                } else {
                    adminMenu.classList.remove('hidden');
                }
                isOpen = !isOpen;
            }
            
            // Ouvrir le menu au clic
            adminBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleMenu();
            });
            
            // Garder le menu ouvert quand on clique dessus
            adminMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            // Fermer le menu si on clique ailleurs sur la page
            document.addEventListener('click', function() {
                if (isOpen) {
                    adminMenu.classList.add('hidden');
                    isOpen = false;
                }
            });
        });
    </script>
</body>
</html>