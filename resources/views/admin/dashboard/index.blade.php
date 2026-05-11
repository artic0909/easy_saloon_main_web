@extends('frontend.layout.app')

@section('content')
<div class="pt-32 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-4xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">
                    {{ ucfirst(auth()->user()->role) }} Dashboard
                </h1>
                <p class="text-[#c6a664] font-medium uppercase tracking-widest text-xs mt-2">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <div class="flex gap-4">
                <span class="px-4 py-2 bg-[#3d2b1f] text-white rounded-full text-[10px] font-black uppercase tracking-widest">
                    {{ auth()->user()->role }} Account
                </span>
            </div>
        </div>

        <!-- Role Based View -->
        @if(auth()->user()->role === 'admin')
            <!-- Admin View -->
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Total Revenue</p>
                    <h3 class="text-2xl font-black text-[#3d2b1f]">₹1,24,500</h3>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Total Bookings</p>
                    <h3 class="text-2xl font-black text-[#3d2b1f]">152</h3>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Active Staff</p>
                    <h3 class="text-2xl font-black text-[#3d2b1f]">12</h3>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">New Users</p>
                    <h3 class="text-2xl font-black text-[#3d2b1f]">48</h3>
                </div>
            </div>
        @else
            <!-- Staff View -->
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Today's Jobs</p>
                    <h3 class="text-2xl font-black text-[#3d2b1f]">4</h3>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Earnings Today</p>
                    <h3 class="text-2xl font-black text-[#3d2b1f]">₹2,499</h3>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Rating</p>
                    <h3 class="text-2xl font-black text-[#c6a664]">★ 4.9</h3>
                </div>
            </div>
        @endif

        <!-- Recent Activities (Common) -->
        <div class="bg-white rounded-[3rem] p-12 shadow-sm border border-gray-100">
            <h3 class="text-2xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">Recent {{ auth()->user()->role === 'admin' ? 'System' : 'Job' }} Activities</h3>
            <div class="space-y-6">
                <div class="flex items-center gap-6 p-6 rounded-2xl bg-[#fdfbf7]">
                    <div class="w-12 h-12 bg-[#3d2b1f] rounded-full flex items-center justify-center text-[#c6a664]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-[#3d2b1f]">Authentication System Test</h5>
                        <p class="text-xs text-gray-400">Security guard successfully verified your {{ auth()->user()->role }} role.</p>
                    </div>
                    <span class="ml-auto text-[10px] font-black text-gray-300 uppercase tracking-widest">Just Now</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
