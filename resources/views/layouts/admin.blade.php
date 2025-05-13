<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.admin_panel') }} - {{ __('messages.site_title') }}</title>
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        [dir="rtl"] .rtl-flip {
            transform: scaleX(-1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="bg-purple-900 text-white w-64 flex-shrink-0">
            <div class="p-4 border-b border-purple-800">
                <h1 class="text-xl font-bold">{{ __('messages.admin_panel') }}</h1>
            </div>
            
            <nav class="mt-4">
                <ul>
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="block p-4 hover:bg-purple-800 {{ request()->routeIs('admin.dashboard') ? 'bg-purple-800' : '' }}">
                            <span class="mr-2">📊</span> {{ __('messages.dashboard') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.bookings.index') }}" class="block p-4 hover:bg-purple-800 {{ request()->routeIs('admin.bookings.*') ? 'bg-purple-800' : '' }}">
                            <span class="mr-2">📅</span> {{ __('messages.bookings') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.contacts.index') }}" class="block p-4 hover:bg-purple-800 {{ request()->routeIs('admin.contacts.*') ? 'bg-purple-800' : '' }}">
                            <span class="mr-2">✉️</span> {{ __('messages.contact_messages') }}
                        </a>
                    </li>
                    <li class="mt-8">
                        <a href="{{ route('home') }}" class="block p-4 hover:bg-purple-800">
                            <span class="mr-2">🏠</span> {{ __('messages.back_to_site') }}
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <header class="bg-white shadow">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        @yield('title', __('messages.admin_panel'))
                    </h2>
                    
                    <div class="flex items-center">
                        <!-- Language Switcher -->
                        <div class="mr-4">
                            @if(app()->getLocale() == 'ar')
                                <a href="{{ route('locale.set', 'en') }}" class="text-sm text-gray-600 hover:text-gray-900">English</a>
                            @else
                                <a href="{{ route('locale.set', 'ar') }}" class="text-sm text-gray-600 hover:text-gray-900">العربية</a>
                            @endif
                        </div>
                        
                        <!-- User Dropdown -->
                        <div class="relative">
                            <button class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none">
                                <span class="mr-2">{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('messages.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        // Toggle dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const userDropdownButton = document.querySelector('header button');
            const userDropdownMenu = document.querySelector('header .absolute');
            
            if (userDropdownButton && userDropdownMenu) {
                userDropdownButton.addEventListener('click', function() {
                    userDropdownMenu.classList.toggle('hidden');
                });
                
                // Close on click outside
                document.addEventListener('click', function(event) {
                    if (!userDropdownButton.contains(event.target) && !userDropdownMenu.contains(event.target)) {
                        userDropdownMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
