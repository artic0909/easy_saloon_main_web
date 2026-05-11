<div class="bg-white rounded-[3rem] p-10 shadow-xl shadow-gray-200/50 border border-gray-100/50 sticky top-40">
    <div class="text-center mb-10">
        <div class="relative inline-block group">
            @if(auth()->user()->photo)
                <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-24 h-24 rounded-[2rem] mx-auto mb-6 object-cover shadow-lg transform -rotate-3 group-hover:rotate-0 transition-transform duration-500">
            @else
                <div class="w-24 h-24 bg-[#3d2b1f] rounded-[2rem] mx-auto mb-6 flex items-center justify-center text-3xl font-black text-[#c6a664] shadow-lg transform -rotate-3 group-hover:rotate-0 transition-transform duration-500">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
            @endif
        </div>
        <h4 class="text-2xl font-black text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">{{ auth()->user()->name ?? 'User Name' }}</h4>
        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-2">{{ auth()->user()->email ?? 'user@example.com' }}</p>
    </div>

    <nav class="space-y-3">
        @php
            $currentRoute = Route::currentRouteName();
        @endphp
        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl {{ $currentRoute == 'dashboard' ? 'bg-[#3d2b1f] text-white shadow-xl shadow-[#3d2b1f]/20' : 'text-gray-500 hover:bg-gray-50' }} font-bold transition-all group">
            <svg class="w-5 h-5 {{ $currentRoute == 'dashboard' ? 'text-[#c6a664]' : 'text-gray-400 group-hover:text-[#3d2b1f]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-sm">My Profile</span>
        </a>
        <a href="{{ route('dashboard.bookings') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl {{ $currentRoute == 'dashboard.bookings' ? 'bg-[#3d2b1f] text-white shadow-xl shadow-[#3d2b1f]/20' : 'text-gray-500 hover:bg-gray-50' }} font-bold transition-all group">
            <svg class="w-5 h-5 {{ $currentRoute == 'dashboard.bookings' ? 'text-[#c6a664]' : 'text-gray-400 group-hover:text-[#3d2b1f]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm">My Bookings</span>
        </a>
        <a href="{{ route('dashboard.addresses') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl {{ $currentRoute == 'dashboard.addresses' ? 'bg-[#3d2b1f] text-white shadow-xl shadow-[#3d2b1f]/20' : 'text-gray-500 hover:bg-gray-50' }} font-bold transition-all group">
            <svg class="w-5 h-5 {{ $currentRoute == 'dashboard.addresses' ? 'text-[#c6a664]' : 'text-gray-400 group-hover:text-[#3d2b1f]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            <span class="text-sm">Saved Addresses</span>
        </a>
        <a href="{{ route('dashboard.notifications') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl {{ $currentRoute == 'dashboard.notifications' ? 'bg-[#3d2b1f] text-white shadow-xl shadow-[#3d2b1f]/20' : 'text-gray-500 hover:bg-gray-50' }} font-bold transition-all group">
            <svg class="w-5 h-5 {{ $currentRoute == 'dashboard.notifications' ? 'text-[#c6a664]' : 'text-gray-400 group-hover:text-[#3d2b1f]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <span class="text-sm">Notifications</span>
        </a>
        <div class="pt-6 mt-6 border-t border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-red-500 hover:bg-red-50 font-bold transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="text-sm">Logout</span>
                </button>
            </form>
        </div>
    </nav>
</div>
