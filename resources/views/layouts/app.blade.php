<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300 overflow-x-clip">
    @php
        $homeRoute = 'home';
        if (auth()->check()) {
            if (auth()->user()->isAdministrator()) {
                $homeRoute = 'super-admin.dashboard';
            } elseif (auth()->user()->isInstructor()) {
                $homeRoute = 'admin.dashboard';
            } else {
                $homeRoute = 'dashboard.index';
            }
        }
        $isAdministrator = auth()->check() && auth()->user()->isAdministrator();
        $isInstructor = auth()->check() && auth()->user()->isInstructor();
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
    <nav class="bg-white dark:bg-gray-800 shadow-lg fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between gap-3 h-16">
                <div class="flex items-center min-w-0">
                    <a href="{{ route($homeRoute) }}" class="flex items-center space-x-2 min-w-0">
                        <i class="fas fa-microchip text-2xl sm:text-3xl text-primary shrink-0"></i>
                        <div>
                            <span class="block text-xl sm:text-2xl font-bold text-gray-800 dark:text-white leading-tight">SensorsHub</span>
                            @if($isAdministrator)
                                <span class="text-xs text-primary font-semibold">Administrator</span>
                            @elseif($isInstructor)
                                <span class="text-xs text-secondary font-semibold">Instructor</span>
                            @endif
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                   {{-- Administrator Desktop Menu --}}
                    @if($isAdministrator)
                        <div class="relative group">
                            <a href="{{ route('super-admin.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition font-semibold flex items-center gap-1">
                                Control Panel <i class="fas fa-chevron-down text-xs"></i>
                            </a>
                            <div class="absolute right-0 top-full mt-1 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                                <a href="{{ route('super-admin.analytics') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-t-lg">
                                    <i class="fas fa-chart-bar w-4 mr-2"></i> Analytics
                                </a>
                                <a href="{{ route('super-admin.users.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
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
                                <a href="{{ route('super-admin.videos.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-video w-4 mr-2"></i> Videos
                                </a>
                                <a href="{{ route('super-admin.logs') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-history w-4 mr-2"></i> Activity Logs
                                </a>
                                <a href="{{ route('super-admin.backup') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-b-lg">
                                    <i class="fas fa-database w-4 mr-2"></i> Database Backup
                                </a>
                            </div>
                        </div>
                        <a href="https://donotopenthisweb.infinityfree.me/" target="_blank" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Simulation</a>
                    
                    {{-- Instructor Desktop Menu --}}
                    @elseif($isInstructor)
                        <a href="{{ route('instructor.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition font-semibold">Dashboard</a>
                        <a href="{{ route('instructor.classes.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Classes</a>
                        <a href="{{ route('instructor.sensors.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Sensors</a>
                        <a href="{{ route('instructor.projects.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Projects</a>
                        <a href="{{ route('instructor.products.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Products</a>
                        <a href="{{ route('suggestions.community') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Community</a>
                        <a href="{{ route('instructor.videos.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Videos</a>
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
                        <form method="POST" action="{{ route('logout') }}" class="inline-flex items-center">
                            @csrf
                            <button type="submit" class="bg-transparent border-0 p-0 m-0 text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition cursor-pointer leading-normal">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Register</a>
                    @endauth

                    @auth
                    @php $unreadCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-700 dark:text-gray-300 hover:text-primary relative">
                            <i class="fas fa-bell"></i>
                            @if($unreadCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>
                        <div x-show="open" @click.outside="open = false" 
                            class="absolute right-0 top-full mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 z-50 max-h-80 overflow-y-auto">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="font-bold text-gray-900 dark:text-white">Notifications</h3>
                            </div>
                           @php $notifications = auth()->user()->notifications()->latest()->take(4)->get(); @endphp
                            @if($notifications->count() > 0)
                                @foreach($notifications as $notification)
                                    <a href="{{ $notification->link ?? '#' }}" 
                                        onclick="markAsRead({{ $notification->id }})"
                                        class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 {{ $notification->is_read ? '' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $notification->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </a>
                                @endforeach
                            @else
                                <p class="px-4 py-3 text-sm text-gray-500">No notifications</p>
                            @endif
                            <div class="p-3 border-t border-gray-200 dark:border-gray-700 text-center">
                                <a href="{{ route('notifications.index') }}" class="text-sm text-primary hover:underline">View All</a>
                            </div>
                        </div>
                    </div>
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
                @if($isAdministrator)
                <a href="{{ route('super-admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                    <i class="fas fa-tachometer-alt w-5"></i> Dashboard
                </a>
                <a href="{{ route('super-admin.analytics') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                   <i class="fas fa-chart-bar w-5"></i> Analytics
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
                    <a href="{{ route('super-admin.logs') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-history w-5"></i> Activity Logs
                    </a>
                    <a href="{{ route('super-admin.backup') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-database w-5"></i> Database Backup
                    </a>
                    <a href="https://donotopenthisweb.infinityfree.me/" target="_blank" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-flask w-5"></i> Simulation
                    </a>
                @elseif($isInstructor)
                    <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-tachometer-alt w-5"></i> Dashboard
                    </a>
                    <a href="{{ route('instructor.classes.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-chalkboard w-5"></i> Classes
                    </a>
                    <a href="{{ route('instructor.sensors.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-microchip w-5"></i> Sensors
                    </a>
                    <a href="{{ route('instructor.projects.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-folder-open w-5"></i> Projects
                    </a>
                    <a href="{{ route('instructor.products.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-shopping-cart w-5"></i> Products
                    </a>
                    <a href="{{ route('suggestions.community') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-comments w-5"></i> Community
                    </a>
                    <a href="{{ route('instructor.videos.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
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

                @auth
                @php $unreadCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded relative">
                    <i class="fas fa-bell w-5"></i> Notifications
                    @if($unreadCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    @endif
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
    <main class="pt-16">
    @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 dark:bg-gray-950 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4 flex items-center">
                        <i class="fas fa-microchip mr-2"></i> SensorsHub
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
                <p>&copy; {{ date('Y') }} SensorsHub. All rights reserved.</p>
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

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    var onsubmit = form.getAttribute('onsubmit');
                    if (onsubmit && onsubmit.includes('confirm')) {
                        return;
                    }
                    var button = form.querySelector('button[type="submit"]');
                    if (button && !button.disabled) {
                        button.disabled = true;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                    }
                });
            });
        });
    </script>

     <script>
        function markAsRead(id) {
            fetch('/notifications/' + id + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            });
        }
    </script>

    @stack('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', function () {

         @auth
            @if(request()->is('email/verify*'))
                const audioSrc = null;
                const storageKey = null;
            @elseif(auth()->user()->isAdministrator())
                const audioSrc = "{{ asset('audio/welcome-administrator.mp3') }}";
                const storageKey = 'welcome_administrator_played';
            @elseif(auth()->user()->isInstructor())
                const audioSrc = "{{ asset('audio/welcome-instructor.mp3') }}";
                const storageKey = 'welcome_instructor_played';
            @else
                const audioSrc = "{{ asset('audio/welcome-back.mp3') }}";
                const storageKey = 'welcome_back_played';
            @endif
        @else
            const audioSrc = "{{ asset('audio/welcome-guest.mp3') }}";
            const storageKey = 'welcome_guest_played';
        @endauth

        if (!audioSrc) return;
        const audio = new Audio(audioSrc);
        audio.volume = 0.8;

        let hasPlayed = false;

        function cleanup() {
            document.removeEventListener('click', playWelcome);
            document.removeEventListener('keydown', playWelcome);
            document.removeEventListener('touchstart', playWelcome);
        }

        function playWelcome() {

            if (hasPlayed) return;

            hasPlayed = true;

            audio.currentTime = 0;

            audio.play()
                .then(() => {
                    sessionStorage.setItem(storageKey, 'true');
                    cleanup();
                    console.log("Welcome played.");
                })
                .catch(err => {
                    hasPlayed = false;
                    console.error(err);
                });
        }

        // ==========================
        // Audio Button (dimmed, mobile-friendly)
        // ==========================
        const style = document.createElement('style');
        style.textContent = `
            .audio-btn {
                position:fixed;
                bottom:16px;
                right:16px;
                z-index:9999;
                background:rgba(59,130,246,0.75);
                backdrop-filter:blur(8px);
                color:white;
                width:40px;
                height:40px;
                border-radius:50%;
                border:1px solid rgba(255,255,255,0.15);
                cursor:pointer;
                box-shadow:0 4px 12px rgba(59,130,246,0.3);
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:14px;
                transition:all 0.3s ease;
                opacity:0.5;
            }
            .audio-btn:hover, .audio-btn:focus {
                opacity:1;
                transform:scale(1.1);
                box-shadow:0 8px 20px rgba(59,130,246,0.5);
            }
            .audio-btn.playing {
                animation: audioPulse 2s infinite;
                opacity:1;
            }
            @keyframes audioPulse {
                0%, 100% { box-shadow: 0 4px 12px rgba(59,130,246,0.4); }
                50% { box-shadow: 0 4px 20px rgba(59,130,246,0.7), 0 0 0 6px rgba(59,130,246,0.1); }
            }
            @media (max-width: 640px) {
                .audio-btn {
                    width: 34px;
                    height: 34px;
                    bottom: 12px;
                    right: 12px;
                    font-size: 12px;
                    opacity:0.45;
                }
            }
        `;
        document.head.appendChild(style);

        const btn = document.createElement('button');
        btn.className = 'audio-btn';
        btn.innerHTML = '<i class="fas fa-volume-up"></i>';
        btn.title = "Play Welcome Message";

        function startPulse() {
            btn.classList.add('playing');
            btn.title = "Stop Audio";
        }
        function stopPulse() {
            btn.classList.remove('playing');
            btn.title = "Play Welcome Message";
        }

        btn.addEventListener('click', function () {
            if (audio.paused) {
                audio.currentTime = 0;
                audio.play();
            } else {
                audio.pause();
                audio.currentTime = 0;
            }
        });

        audio.addEventListener('play', startPulse);
        audio.addEventListener('pause', stopPulse);
        audio.addEventListener('ended', stopPulse);

        document.body.appendChild(btn);

        // ==========================
        // First-time welcome
        // ==========================
        if (!sessionStorage.getItem(storageKey)) {

            audio.play()
                .then(() => {
                    hasPlayed = true;
                    sessionStorage.setItem(storageKey, 'true');
                    console.log("Autoplay succeeded.");
                })
                .catch(() => {
                    console.log("Autoplay blocked.");

                    document.addEventListener('click', playWelcome, { once: true });
                    document.addEventListener('keydown', playWelcome, { once: true });
                    document.addEventListener('touchstart', playWelcome, { once: true });
                });

        }

    });
    </script>

     {{-- ========================== AI Chatbot ========================== --}}
    @auth
    @if(auth()->user()->role === 'student')
    <style>
        .chat-bubble { position:fixed; bottom:70px; right:16px; z-index:9998; width:40px; height:40px; border-radius:50%; background:rgba(59,130,246,0.75); backdrop-filter:blur(8px); color:white; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 4px 12px rgba(59,130,246,0.3); font-size:16px; border:1px solid rgba(255,255,255,0.15); opacity:0.75; }
        .chat-bubble:hover { opacity:1; transform:scale(1.1); }
        .chat-window { position:fixed; bottom:120px; right:16px; z-index:9999; width:350px; max-height:450px; background:white; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.2); display:flex; flex-direction:column; }
        .dark .chat-window { background:#1F2937; }
        .chat-messages { flex:1; overflow-y:auto; padding:15px; max-height:300px; font-size:13px; }
        .chat-user { background:#3B82F6; color:white; margin-left:auto; padding:8px 12px; border-radius:12px; max-width:80%; margin-bottom:8px; }
        .chat-ai { background:#F3F4F6; color:#1F2937; padding:8px 12px; border-radius:12px; max-width:80%; margin-bottom:8px; }
        .dark .chat-ai { background:#374151; color:#E5E7EB; }
        .chat-input-area { padding:10px; border-top:1px solid #E5E7EB; display:flex; gap:8px; }
        .dark .chat-input-area { border-color:#374151; }
        .chat-input-area input { flex:1; padding:8px 12px; border:1px solid #E5E7EB; border-radius:20px; font-size:13px; outline:none; }
        .dark .chat-input-area input { background:#374151; border-color:#4B5563; color:#E5E7EB; }
        .chat-input-area button { padding:8px 14px; background:#3B82F6; color:white; border:none; border-radius:20px; cursor:pointer; font-size:13px; }
        @media (max-width:640px) { .chat-window { width:90vw; right:5vw; } .chat-bubble { bottom:60px; right:12px; width:34px; height:34px; font-size:14px; } }
    </style>

    <div x-data="chatBot()">
        <div class="chat-bubble" @click="open = !open">
            <span x-show="!open">💬</span>
            <span x-show="open">✕</span>
        </div>
        <div class="chat-window" x-show="open" x-transition>
            <div style="padding:12px 15px;border-bottom:1px solid #E5E7EB;font-weight:bold;font-size:14px;" class="dark:border-gray-700 dark:text-white">
                🤖 SensorsHub AI
            </div>
            <div class="chat-messages" x-ref="messages">
                <template x-for="msg in messages">
                    <div :class="msg.role === 'user' ? 'chat-user' : 'chat-ai'" x-text="msg.text"></div>
                </template>
                <div x-show="loading" class="chat-ai" style="opacity:0.6;">🧠 Thinking...</div>
            </div>
            <div class="chat-input-area">
                <input type="text" x-model="input" @keyup.enter="sendMessage()" placeholder="Ask about sensors, ESP32, wiring..." :disabled="loading">
                <button @click="sendMessage()" :disabled="loading">Send</button>
            </div>
        </div>
    </div>

    <script>
        function chatBot() {
            return {
                open: false,
                messages: [{
                    role: 'ai',
                    text: "👋 Welcome to SensorsHub AI!\n\nI'm here to help with sensors, ESP32, Pico, wiring, and electronics.\n\nWhat would you like to learn today?"
                }],
                input: '',
                loading: false,
                sendMessage() {
                    const content = this.input?.trim();
                    if (!content || this.loading) return;
                    this.messages.push({ role: 'user', text: content });
                    this.input = '';
                    this.loading = true;
                    this.$nextTick(() => this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight);
                    fetch('/api/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ message: content })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.messages.push({ role: 'ai', text: data.reply });
                        this.loading = false;
                        this.$nextTick(() => this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight);
                    })
                    .catch(() => {
                        this.messages.push({ role: 'ai', text: 'Sorry, something went wrong!' });
                        this.loading = false;
                    });
                }
            }
        }
    </script>
    @endif
    @endauth


</body>
</html>>