<!-- Navigation -->
<nav class="fixed top-0 w-full z-50 glass border-b border-[#3d2b1f]/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-[#3d2b1f] rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758L6.243 17.757M12 12L15.758 15.758M12 12l-2.879-2.879"></path>
                    </svg>
                </div>
                <span class="text-2xl font-bold tracking-tight text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Easy Saloon</span>
            </div>
            
            <!-- Search Bar (Desktop) -->
            <div class="hidden lg:flex flex-1 max-w-md mx-8">
                <div class="relative w-full">
                    <input type="text" placeholder="Search for services..." class="w-full bg-[#3d2b1f]/5 border border-[#3d2b1f]/10 rounded-full py-2 px-10 text-sm focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:border-[#c6a664] transition-all">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-[#3d2b1f]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-sm font-medium hover:text-[#c6a664] transition-colors">Services</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-[#3d2b1f] text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-[#5d4037] transition-all shadow-md">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-[#c6a664] transition-colors">Log in</a>
                        <a href="{{ route('register') }}" class="bg-[#c6a664] text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-[#b59554] transition-all shadow-lg">Get App</a>
                    @endauth
                @endif
            </div>

            <!-- Mobile Actions -->
            <div class="md:hidden flex items-center gap-3">
                <!-- Mobile Search Icon (optional trigger) -->
                <button class="text-[#3d2b1f] p-2 hover:bg-[#3d2b1f]/5 rounded-lg transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                <div class="hidden sm:flex items-center gap-2 mr-2">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" class="h-7" alt="Play Store">
                    <img src="{{ asset('assets/img/appstore.png') }}" class="h-7" alt="App Store">
                </div>
                <button id="mobile-menu-btn" class="text-[#3d2b1f] p-2 hover:bg-[#3d2b1f]/5 rounded-lg transition-all">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Right Drawer -->
<div id="mobile-drawer" class="fixed inset-0 z-[60] pointer-events-none overflow-hidden">
    <!-- Backdrop -->
    <div id="drawer-backdrop" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-500 pointer-events-none"></div>
    
    <!-- Content -->
    <div id="drawer-content" class="absolute top-0 right-0 h-full w-[80%] max-w-sm bg-white shadow-2xl translate-x-full transition-transform duration-500 ease-in-out pointer-events-auto flex flex-col">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <span class="text-xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Menu</span>
            <button id="close-drawer" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="p-6 pb-0">
            <div class="relative w-full">
                <input type="text" placeholder="Search services..." class="w-full bg-[#3d2b1f]/5 border border-[#3d2b1f]/10 rounded-xl py-3 px-10 text-sm focus:outline-none focus:border-[#c6a664]">
                <svg class="absolute left-3 top-3.5 w-4 h-4 text-[#3d2b1f]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            <div class="flex flex-col space-y-4">
                <a href="#" class="text-lg font-semibold text-[#3d2b1f] hover:text-[#c6a664]">Home Services</a>
                <a href="#" class="text-lg font-semibold text-[#3d2b1f] hover:text-[#c6a664]">Salons</a>
                <a href="#" class="text-lg font-semibold text-[#3d2b1f] hover:text-[#c6a664]">Join as Professional</a>
            </div>
            
            <div class="h-px bg-gray-100 w-full"></div>
            
            <div class="flex flex-col space-y-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary text-center">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-lg font-semibold text-[#3d2b1f]">Log in</a>
                        <a href="{{ route('register') }}" class="btn-primary text-center">Register / Get App</a>
                    @endauth
                @endif
                
                <div class="flex items-center gap-3 pt-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" class="h-9" alt="Play Store">
                    <img src="{{ asset('assets/img/appstore.png') }}" class="h-9" alt="App Store">
                </div>
            </div>
        </div>
        
        <div class="p-6 bg-[#fdfbf7] border-t border-gray-100">
            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-4">Contact Us</p>
            <p class="text-sm font-bold text-[#3d2b1f]">support@easysaloon.com</p>
            <p class="text-sm text-gray-500">+91 1800 200 300</p>
        </div>
    </div>
</div>
