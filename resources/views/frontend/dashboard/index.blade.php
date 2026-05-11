@extends('frontend.layout.app')

@section('content')
<div class="pt-40 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="flex flex-col lg:flex-row gap-16">
            <!-- Sidebar -->
            <aside class="w-full lg:w-80 flex-shrink-0">
                <div class="bg-white rounded-[3rem] p-10 shadow-xl shadow-gray-200/50 border border-gray-100/50 sticky top-40">
                    <div class="text-center mb-10">
                        <div class="w-24 h-24 bg-[#3d2b1f] rounded-[2rem] mx-auto mb-6 flex items-center justify-center text-3xl font-black text-[#c6a664] shadow-lg transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <h4 class="text-2xl font-black text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">{{ auth()->user()->name ?? 'User Name' }}</h4>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-2">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                    </div>

                    <nav class="space-y-3">
                        <a href="#" class="flex items-center gap-4 px-6 py-4 rounded-2xl bg-[#3d2b1f] text-white font-bold transition-all shadow-xl shadow-[#3d2b1f]/20 group">
                            <svg class="w-5 h-5 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="text-sm">My Profile</span>
                        </a>
                        <a href="#" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 font-bold transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            My Bookings
                        </a>
                        <a href="#" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 font-bold transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            Saved Addresses
                        </a>
                        <a href="#" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 font-bold transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Wallet
                        </a>
                        <div class="pt-6 mt-6 border-t border-gray-100">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-red-500 hover:bg-red-50 font-bold transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 space-y-12">
                <!-- Profile Section -->
                <section class="bg-white rounded-[2.5rem] p-12 shadow-sm border border-gray-100">
                    <h2 class="text-3xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">Profile Settings</h2>
                    <form class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Full Name</label>
                            <input type="text" value="{{ auth()->user()->name ?? '' }}" class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Email Address</label>
                            <input type="email" value="{{ auth()->user()->email ?? '' }}" class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Phone Number</label>
                            <input type="text" placeholder="+91" class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <div class="md:col-span-2 pt-4">
                            <button class="bg-[#3d2b1f] text-white px-10 py-4 rounded-2xl font-bold hover:bg-[#c6a664] transition-all shadow-lg">Save Changes</button>
                        </div>
                    </form>
                </section>

                <!-- Bookings Section Preview -->
                <section class="bg-white rounded-[2.5rem] p-12 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-10">
                        <h2 class="text-3xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Recent Bookings</h2>
                        <a href="#" class="text-xs font-bold text-[#c6a664] uppercase tracking-widest hover:text-[#3d2b1f] transition-all">View All</a>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Empty State -->
                        <div class="py-16 text-center border-2 border-dashed border-gray-100 rounded-[2rem]">
                            <p class="text-gray-400 font-bold mb-4">You haven't booked anything yet.</p>
                            <a href="{{ route('services.index') }}" class="inline-block bg-[#3d2b1f] text-white px-8 py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-[#c6a664] transition-all shadow-md">Book a Service</a>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>
@endsection
