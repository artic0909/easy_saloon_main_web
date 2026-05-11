@auth
    @php
        $dashboardRoute = match(auth()->user()->role) {
            'admin' => route('admin.dashboard'),
            'staff' => route('staff.dashboard'),
            default => route('dashboard'),
        };
    @endphp
@endauth

<!-- Navigation -->
<header class="fixed top-0 w-full z-50 bg-white border-b border-[#3d2b1f]/5 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group cursor-pointer">
                <div class="w-10 h-10 bg-[#3d2b1f] rounded-xl flex items-center justify-center group-hover:rotate-6 transition-transform shadow-lg">
                    <svg class="w-6 h-6 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758L6.243 17.757M12 12L15.758 15.758M12 12l-2.879-2.879"></path>
                    </svg>
                </div>
                <span class="text-2xl font-bold tracking-tight text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Easy Saloon</span>
            </a>
            
            <!-- Search Bar (Desktop) -->
            <div class="hidden lg:flex flex-1 max-w-md mx-8">
                <form action="{{ route('services.index') }}" method="GET" class="relative w-full">
                    <input type="text" name="search" placeholder="Search for services..." class="w-full bg-[#3d2b1f]/5 border border-[#3d2b1f]/10 rounded-full py-2 px-10 text-sm focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:border-[#c6a664] transition-all">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-[#3d2b1f]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </form>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('services.index') }}" class="text-sm font-medium hover:text-[#c6a664] transition-colors">Services</a>
                <a href="{{ route('packages.index') }}" class="text-sm font-medium hover:text-[#c6a664] transition-colors">Packages</a>
                
                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 bg-[#3d2b1f] text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-[#5d4037] transition-all shadow-md">
                            <span>Dashboard</span>
                            <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-3 z-50">
                            <div class="px-6 py-2 mb-2">
                                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Logged in as</p>
                                <p class="text-sm font-bold text-[#3d2b1f] truncate">{{ auth()->user()->name }}</p>
                            </div>
                            <hr class="border-gray-50 mb-2">
                            <a href="{{ $dashboardRoute }}" class="flex items-center gap-3 px-6 py-3 text-sm font-bold text-[#3d2b1f] hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                My Dashboard
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-6 py-3 text-sm font-bold text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium hover:text-[#c6a664] transition-colors">Log in</a>
                    <a href="{{ route('register') }}" class="bg-[#c6a664] text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-[#b59554] transition-all shadow-lg">Get Started</a>
                @endauth
            </div>

            <!-- Mobile Actions -->
            <div class="md:hidden flex items-center gap-3">
                <button id="mobile-search-btn" class="p-2 text-[#3d2b1f]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                <button id="mobile-menu-btn" class="p-2 text-[#3d2b1f]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Navigation Drawer -->
<div id="mobile-drawer" class="fixed inset-0 z-[110] pointer-events-none overflow-hidden" x-data x-cloak>
    <!-- Backdrop -->
    <div id="drawer-backdrop" class="absolute inset-0 bg-[#3d2b1f]/60 backdrop-blur-sm opacity-0 transition-opacity duration-500"></div>
    
    <!-- Content -->
    <div id="drawer-content" class="absolute right-0 top-0 bottom-0 w-80 bg-white translate-x-full transition-transform duration-500 flex flex-col">
        <div class="p-8 flex items-center justify-between border-b border-gray-100">
            <span class="text-xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Menu</span>
            <button id="close-drawer" class="text-gray-400 hover:text-[#3d2b1f]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-8 flex-1 overflow-y-auto">
            <!-- Mobile Search -->
            <div class="mb-8">
                <form action="{{ route('services.index') }}" method="GET" class="relative">
                    <input type="text" name="search" placeholder="Search services..." class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm focus:ring-2 focus:ring-[#c6a664]">
                    <button type="submit" class="absolute right-4 top-3.5 text-[#c6a664]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
            </div>

            <nav class="space-y-6">
                <a href="{{ route('home') }}" class="block text-lg font-bold text-[#3d2b1f] hover:text-[#c6a664] transition-colors">Home</a>
                <a href="{{ route('services.index') }}" class="block text-lg font-bold text-[#3d2b1f] hover:text-[#c6a664] transition-colors">Services</a>
                <a href="{{ route('packages.index') }}" class="block text-lg font-bold text-[#3d2b1f] hover:text-[#c6a664] transition-colors">Packages</a>
                <hr class="border-gray-50">
                @auth
                    <a href="{{ $dashboardRoute }}" class="block text-lg font-bold text-[#c6a664]">My Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block text-lg font-bold text-red-500">Sign Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-lg font-bold text-[#3d2b1f]">Login</a>
                    <a href="{{ route('register') }}" class="block w-full bg-[#3d2b1f] text-white py-4 rounded-2xl font-bold mt-4 text-center shadow-lg">Join Now</a>
                @endauth
            </nav>
        </div>
        
        <div class="p-8 bg-[#fdfbf7]">
            <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-4">Download Our App</p>
            <div class="flex gap-4">
                <a href="#" class="flex-1 bg-[#3d2b1f] text-white p-3 rounded-xl flex items-center justify-center">
                    <img src="{{ asset('images/playstore.png') }}" class="h-5 invert" alt="Play Store">
                </a>
                <a href="#" class="flex-1 bg-[#3d2b1f] text-white p-3 rounded-xl flex items-center justify-center">
                    <img src="{{ asset('assets/img/appstore.png') }}" class="h-5 invert" alt="App Store">
                </a>
            </div>
        </div>
    </div>
</div>
