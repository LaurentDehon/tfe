<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des TFE - @yield('title', 'Accueil')</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
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
<body class="bg-gray-50 flex flex-col min-h-screen">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <nav class="flex justify-between">
                <div class="flex space-x-8">
                    <a href="{{ route('timeline') }}" class="text-gray-700 hover:text-blue-500 font-medium">Timeline</a>
                    <a href="{{ route('wiki.concepts') }}" class="text-gray-700 hover:text-blue-500 font-medium">Wiki Concepts</a>
                    <a href="{{ route('comments') }}" class="text-gray-700 hover:text-blue-500 font-medium">Commentaires</a>
                </div>
                <div class="flex items-center space-x-6">
                    @auth
                        <div class="admin-dropdown relative" id="adminDropdown">
                            <button id="adminBtn" class="text-gray-700 hover:text-blue-500 font-medium flex items-center">
                                Administration
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="adminMenu" class="dropdown-menu hidden absolute right-0 w-40 bg-white rounded-md shadow-lg py-1 z-10">
                                <a href="{{ route('admin.milestones') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Jalons</a>
                                <a href="{{ route('admin.tools') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Outils</a>
                                <a href="{{ route('admin.concepts') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Concepts</a>
                                <a href="{{ route('admin.courses') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cours</a>
                                <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Utilisateurs</a>
                            </div>
                        </div>

                        <div class="user-dropdown relative" id="userDropdown">
                            <button id="userBtn" class="text-gray-700 hover:text-blue-500 font-medium flex items-center">
                                {{ Auth::user()->name }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="userMenu" class="dropdown-menu hidden absolute right-0 w-40 bg-white rounded-md shadow-lg py-1 z-10">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Se déconnecter</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-500 font-medium">Se connecter</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex-grow">
        <div class="pb-6">
            <h1 class="text-3xl font-bold text-gray-900">@yield('header', 'Gestion des TFE')</h1>
        </div>
        
        @if(session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('message') }}</p>
            </div>
        @endif

        @if(session()->has('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        @yield('content')
    </main>

    <footer class="bg-white shadow-inner py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Gestion des TFE - Tous droits réservés
        </div>
    </footer>

    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Menu déroulant script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menu admin
            const adminDropdown = document.getElementById('adminDropdown');
            if (adminDropdown) {
                const adminBtn = document.getElementById('adminBtn');
                const adminMenu = document.getElementById('adminMenu');
                let isAdminOpen = false;
                
                function toggleAdminMenu() {
                    if (isAdminOpen) {
                        adminMenu.classList.add('hidden');
                    } else {
                        adminMenu.classList.remove('hidden');
                    }
                    isAdminOpen = !isAdminOpen;
                }
                
                // Ouvrir le menu admin au clic
                adminBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleAdminMenu();
                });
                
                // Garder le menu ouvert quand on clique dessus
                adminMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            // Menu utilisateur
            const userDropdown = document.getElementById('userDropdown');
            if (userDropdown) {
                const userBtn = document.getElementById('userBtn');
                const userMenu = document.getElementById('userMenu');
                let isUserOpen = false;
                
                function toggleUserMenu() {
                    if (isUserOpen) {
                        userMenu.classList.add('hidden');
                    } else {
                        userMenu.classList.remove('hidden');
                    }
                    isUserOpen = !isUserOpen;
                }
                
                // Ouvrir le menu utilisateur au clic
                userBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleUserMenu();
                });
                
                // Garder le menu ouvert quand on clique dessus
                userMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            // Fermer les menus si on clique ailleurs sur la page
            document.addEventListener('click', function() {
                const adminMenu = document.getElementById('adminMenu');
                const userMenu = document.getElementById('userMenu');
                
                if (adminMenu && !adminMenu.classList.contains('hidden')) {
                    adminMenu.classList.add('hidden');
                }
                
                if (userMenu && !userMenu.classList.contains('hidden')) {
                    userMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>