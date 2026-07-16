<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SensorsHub') - Learn Sensors. Build Projects. Share Ideas.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300 overflow-x-hidden">
    @php
        $homeRoute = 'home';
        if (auth()->check()) {
            if (auth()->user()->isSuperAdmin()) {
                $homeRoute = 'super-admin.dashboard';
            } elseif (auth()->user()->isAdmin()) {
                $homeRoute = 'admin.dashboard';
            } else {
                $homeRoute = 'dashboard.index';
            }
        }
        $isSuperAdmin = auth()->check() && auth()->user()->isSuperAdmin();
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
    @endphp

    <!-- Toast Notifications -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
            class="fixed top-20 right-4 z-[9999] max-w-sm w-full animate-slide-in">
            <div class="flex items-center gap-3 bg-white dark:bg-gray-800 border border-green-200 dark:border-green-700 rounded-xl shadow-lg p-4">
                <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                </div>
                <p class="flex-1 text-sm font-medium text-gray-800 dark:text-gray-200">{{ session('success') }}</p>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
            class="fixed top-20 right-4 z-[9999] max-w-sm w-full">
            <div class="flex items-center gap-3 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 rounded-xl shadow-lg p-4">
                <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation text-red-600 dark:text-red-400 text-sm"></i>
                </div>
                <p class="flex-1 text-sm font-medium text-gray-800 dark:text-gray-200">{{ session('error') }}</p>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    @endif

    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
    </style>

    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between gap-3 h-16">
                <div class="flex items-center min-w-0">
                    <a href="{{ route($homeRoute) }}" class="flex items-center space-x-2 min-w-0">
                        <i class="fas fa-microchip text-2xl sm:text-3xl text-primary shrink-0"></i>
                        <div>
                            <span class="block text-xl sm:text-2xl font-bold text-gray-800 dark:text-white leading-tight">SensorHub</span>
                            @if($isSuperAdmin)
                                <span class="text-xs text-primary font-semibold">Faculty Head</span>
                            @elseif($isAdmin)
                                <span class="text-xs text-secondary font-semibold">Instructor</span>
                            @endif
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    {{-- Super Admin Desktop Menu --}}
                    @if($isSuperAdmin)
                        <div class="relative group">
                            <a href="{{ route('super-admin.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition font-semibold flex items-center gap-1">
                                Control Panel <i class="fas fa-chevron-down text-xs"></i>
                            </a>
                            <div class="absolute right-0 top-full mt-1 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                                <a href="{{ route('super-admin.users.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-t-lg">
                                    <i class="fas fa-users w-4 mr-2"></i> Users
                                </a>
                                <a href="{{ route('super-admin.suggestions.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-lightbulb w-4 mr-2"></i> Suggestions
                                </a>
                                <a href="{{ route('super-admin.sensors.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-microchip w-4 mr-2"></i> Sensors
                                </a>
                                <a href="{{ route('super-admin.projects.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-project-diagram w-4 mr-2"></i> Projects
                                </a>
                                <a href="{{ route('super-admin.products.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-shopping-cart w-4 mr-2"></i> Products
                                </a>
                                <a href="{{ route('super-admin.videos.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-b-lg">
                                    <i class="fas fa-video w-4 mr-2"></i> Videos
                                </a>
                            </div>
                        </div>
                        <a href="https://donotopenthisweb.infinityfree.me/" target="_blank" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Simulation</a>
                    
                    {{-- Instructor Desktop Menu --}}
                    @elseif($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition font-semibold">Dashboard</a>
                        <a href="{{ route('admin.classes.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Classes</a>
                        <a href="{{ route('admin.sensors.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Sensors</a>
                        <a href="{{ route('admin.projects.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Projects</a>
                        <a href="{{ route('admin.products.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Products</a>
                        <a href="{{ route('suggestions.community') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Community</a>
                        <a href="{{ route('admin.videos.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Videos</a>
                        <a href="https://donotopenthisweb.infinityfree.me/" target="_blank" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Simulation</a>
                    
                    {{-- User Desktop Menu --}}
                    @else
                        <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Home</a>
                        <a href="{{ route('sensors.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Sensors</a>
                        <a href="{{ route('projects.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Projects</a>
                        <a href="{{ route('videos.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Tutorials</a>
                        <a href="{{ route('dashboard.classes.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Classes</a>
                        <a href="{{ route('suggestions.community') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Community</a>
                        <a href="{{ route('shop.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Shop</a>
                        <a href="https://donotopenthisweb.infinityfree.me/" target="_blank" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Simulation</a>
                    @endif
                    
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Register</a>
                    @endauth
                    
                    <button id="darkModeToggle" class="text-gray-700 dark:text-gray-300 hover:text-primary">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:inline"></i>
                    </button>
                </div>

                <div class="md:hidden flex items-center">
                    <button id="mobileMenuButton" class="text-gray-700 dark:text-gray-300 p-2 -mr-2">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white dark:bg-gray-800 border-t dark:border-gray-700">
            <div class="px-4 pt-2 pb-4 space-y-1">
                @if($isSuperAdmin)
                    <a href="{{ route('super-admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-tachometer-alt w-5"></i> Dashboard
                    </a>
                    <a href="{{ route('super-admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-users w-5"></i> Users
                    </a>
                    <a href="{{ route('super-admin.suggestions.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-lightbulb w-5"></i> Suggestions
                    </a>
                    <a href="{{ route('super-admin.sensors.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-microchip w-5"></i> Sensors
                    </a>
                    <a href="{{ route('super-admin.projects.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-project-diagram w-5"></i> Projects
                    </a>
                    <a href="{{ route('super-admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-shopping-cart w-5"></i> Products
                    </a>
                    <a href="{{ route('super-admin.videos.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-video w-5"></i> Videos
                    </a>
                    <a href="https://donotopenthisweb.infinityfree.me/" target="_blank" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-flask w-5"></i> Simulation
                    </a>
                @elseif($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-tachometer-alt w-5"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.classes.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-chalkboard w-5"></i> Classes
                    </a>
                    <a href="{{ route('admin.sensors.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-microchip w-5"></i> Sensors
                    </a>
                    <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-folder-open w-5"></i> Projects
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-shopping-cart w-5"></i> Products
                    </a>
                    <a href="{{ route('suggestions.community') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-comments w-5"></i> Community
                    </a>
                    <a href="{{ route('admin.videos.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-video w-5"></i> Videos
                    </a>
                    <a href="https://donotopenthisweb.infinityfree.me/" target="_blank" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-flask w-5"></i> Simulation
                    </a>
                @else
                    <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-home w-5"></i> Home
                    </a>
                    <a href="{{ route('sensors.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-microchip w-5"></i> Sensors
                    </a>
                    <a href="{{ route('projects.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-folder-open w-5"></i> Projects
                    </a>
                    <a href="{{ route('videos.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-play-circle w-5"></i> Tutorials
                    </a>
                    <a href="{{ route('suggestions.community') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-comments w-5"></i> Community
                    </a>
                    <a href="{{ route('shop.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-store w-5"></i> Shop
                    </a>
                    <a href="{{ route('dashboard.classes.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-chalkboard w-5"></i> Classes
                    </a>
                    <a href="https://donotopenthisweb.infinityfree.me/" target="_blank" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-flask w-5"></i> Simulation
                    </a>
                @endif
                
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full text-left px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                            <i class="fas fa-sign-out-alt w-5"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-key w-5"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center gap-3 px-3 py-2 text-primary font-semibold hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-user-plus w-5"></i> Register
                    </a>
                @endauth
                
                <button id="mobileDarkModeToggle" type="button" class="flex items-center gap-3 w-full px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                    <i class="fas fa-moon dark:hidden w-5"></i>
                    <i class="fas fa-sun hidden dark:inline w-5"></i>
                    Dark/Light Mode
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 dark:bg-gray-950 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4 flex items-center">
                        <i class="fas fa-microchip mr-2"></i> SensorHub
                    </h3>
                    <p class="text-gray-400">Learn Sensors. Build Projects. Share Ideas.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route($homeRoute) }}" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('sensors.index') }}" class="text-gray-400 hover:text-white transition">Sensors</a></li>
                        <li><a href="{{ route('projects.index') }}" class="text-gray-400 hover:text-white transition">Projects</a></li>
                        <li><a href="{{ route('videos.index') }}" class="text-gray-400 hover:text-white transition">Tutorials</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Resources</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('suggestions.community') }}" class="text-gray-400 hover:text-white transition">Community</a></li>
                        <li><a href="{{ route('shop.index') }}" class="text-gray-400 hover:text-white transition">Shop</a></li>
                        <li><a href="https://donotopenthisweb.infinityfree.me/" target="_blank" class="text-gray-400 hover:text-white transition">Simulation</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Connect</h4>
                    <div class="flex flex-wrap gap-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-youtube text-2xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-github text-2xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter text-2xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} SensorHub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        const darkModeToggle = document.getElementById('darkModeToggle');
        const mobileDarkModeToggle = document.getElementById('mobileDarkModeToggle');
        const html = document.documentElement;
        if (localStorage.getItem('darkMode') === 'true') html.classList.add('dark');
        darkModeToggle?.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
        });
        mobileDarkModeToggle?.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
        });
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenuButton?.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
        const mobileLinks = mobileMenu?.querySelectorAll('a, button[type="submit"]');
        mobileLinks?.forEach(link => link.addEventListener('click', () => mobileMenu.classList.add('hidden')));
    </script>
    @stack('scripts')
</body>
</html>