@extends('frontend.layout.app')

@section('content')
<div class="pt-32 md:pt-48 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-20">
            <!-- Left: Package Details -->
            <div class="flex-1 space-y-12">
                <div>
                    <div class="inline-block bg-[#c6a664]/10 text-[#c6a664] px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest mb-8">
                        Exclusive Package
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-[#3d2b1f] leading-tight mb-6" style="font-family: 'Playfair Display', serif;">
                        {{ $package->name }}
                    </h1>
                    <p class="text-gray-500 text-lg md:text-xl leading-relaxed max-w-2xl">
                        {{ $package->details }}
                    </p>
                </div>

                <!-- Included Services -->
                <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-sm border border-gray-100">
                    <h3 class="text-2xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">What's Included</h3>
                    <div class="grid sm:grid-cols-2 gap-6">
                        @foreach($package->items as $item)
                            <div class="flex items-center gap-5 p-5 rounded-3xl bg-[#fdfbf7] border border-gray-50 group hover:bg-white hover:shadow-xl transition-all duration-500">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#c6a664] shadow-sm group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <h5 class="font-bold text-[#3d2b1f]">{{ $item->service->name }}</h5>
                                    <p class="text-xs text-gray-400 font-medium">Full Professional Service</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Booking Widget -->
            <div class="w-full lg:w-[450px]">
                <div class="bg-[#3d2b1f] rounded-[3rem] p-10 md:p-12 shadow-2xl sticky top-40" x-data="{ serviceType: 'home' }">
                    <!-- Price & Duration -->
                    <div class="flex justify-between items-end mb-10 pb-8 border-b border-white/10">
                        <div>
                            <p class="text-[#c6a664] text-[10px] font-black uppercase tracking-widest mb-1">Package Price</p>
                            <div class="flex items-baseline gap-3">
                                <span class="text-4xl font-black text-white">₹{{ number_format($package->sale_price, 0) }}</span>
                                <span class="text-sm text-white/30 line-through">₹{{ number_format($package->original_price, 0) }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-1">Total Duration</p>
                            <span class="text-lg font-bold text-white">2-3 Hours</span>
                        </div>
                    </div>

                    <!-- Service Type Selection -->
                    <div class="mb-10">
                        <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-4">Choose Service Location</p>
                        <div class="grid grid-cols-2 gap-4">
                            <button @click="serviceType = 'home'" :class="serviceType === 'home' ? 'bg-[#c6a664] text-white' : 'bg-white/5 text-white/60 hover:bg-white/10'" class="py-4 rounded-2xl font-bold text-sm transition-all flex flex-col items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                At Home
                            </button>
                            <button @click="serviceType = 'salon'" :class="serviceType === 'salon' ? 'bg-[#c6a664] text-white' : 'bg-white/5 text-white/60 hover:bg-white/10'" class="py-4 rounded-2xl font-bold text-sm transition-all flex flex-col items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                At Salon
                            </button>
                        </div>
                    </div>

                    <!-- Date & Time Slot (Placeholder) -->
                    <div class="mb-10">
                        <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-4">Select Slot</p>
                        <div class="grid grid-cols-4 gap-2">
                            <button class="bg-white/5 py-2 rounded-lg text-[10px] text-white/80 font-bold border border-white/5 hover:border-[#c6a664] transition-all">Today</button>
                            <button class="bg-white/5 py-2 rounded-lg text-[10px] text-white/80 font-bold border border-white/5 hover:border-[#c6a664] transition-all">Tomorrow</button>
                            <button class="bg-white/5 py-2 rounded-lg text-[10px] text-white/80 font-bold border border-white/5 hover:border-[#c6a664] transition-all">14 May</button>
                            <button class="bg-white/5 py-2 rounded-lg text-[10px] text-white/80 font-bold border border-white/5 hover:border-[#c6a664] transition-all">15 May</button>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button class="w-full bg-[#c6a664] text-white py-5 rounded-[2rem] font-bold text-lg shadow-xl hover:scale-[1.02] transition-all active:scale-[0.98]">
                        Book Package Now
                    </button>
                    
                    <p class="text-center text-white/30 text-[10px] font-bold uppercase mt-8 tracking-widest">Secure Checkout Powered by Easy Saloon</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
