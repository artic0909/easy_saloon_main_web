@extends('frontend.layout.app')

@section('content')
@php
    $service = \App\Models\Service::where('slug', $slug)->with('category')->firstOrFail();
@endphp

<div class="pt-32 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mb-12">
            <a href="{{ route('home') }}" class="hover:text-[#3d2b1f]">Home</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <a href="{{ route('services.index') }}" class="hover:text-[#3d2b1f]">Services</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#3d2b1f]">{{ $service->name }}</span>
        </div>

        <div class="grid lg:grid-cols-12 gap-16">
            <!-- Left: Service Info -->
            <div class="lg:col-span-7">
                <div class="relative rounded-[3rem] overflow-hidden shadow-2xl mb-12 aspect-video">
                    <img src="/assets/img/service-bridal.png" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    <div class="absolute bottom-10 left-10 text-white">
                        <span class="bg-[#c6a664] px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 inline-block">
                            {{ $service->category->name }}
                        </span>
                        <h1 class="text-4xl md:text-5xl font-extrabold" style="font-family: 'Playfair Display', serif;">{{ $service->name }}</h1>
                    </div>
                </div>

                <div class="space-y-12">
                    <!-- Description -->
                    <section>
                        <h3 class="text-2xl font-bold text-[#3d2b1f] mb-6" style="font-family: 'Playfair Display', serif;">About the Service</h3>
                        <p class="text-gray-500 leading-relaxed text-lg">
                            {{ $service->details }} 
                            Our expert professionals bring the luxury salon experience directly to your home. We use only premium, dermatologically tested products to ensure the best results for your skin and hair.
                        </p>
                    </section>

                    <!-- What's Included -->
                    <section class="bg-white rounded-[2rem] p-10 shadow-sm border border-gray-50">
                        <h3 class="text-xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">What's Included?</h3>
                        <div class="grid sm:grid-cols-2 gap-6">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 bg-[#f4ece4] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#3d2b1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600">Professional Consultation</span>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 bg-[#f4ece4] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#3d2b1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600">Premium Product Kit</span>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 bg-[#f4ece4] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#3d2b1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600">Certified Staff Member</span>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 bg-[#f4ece4] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#3d2b1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600">Post-service cleanup</span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Right: Booking Widget -->
            <div class="lg:col-span-5">
                <div class="bg-[#3d2b1f] rounded-[3rem] p-10 shadow-2xl sticky top-32" x-data="{ serviceType: 'home' }">
                    <!-- Price & Duration -->
                    <div class="flex justify-between items-end mb-10 pb-8 border-b border-white/10">
                        <div>
                            <p class="text-[#c6a664] text-[10px] font-black uppercase tracking-widest mb-1">Estimated Price</p>
                            <span class="text-4xl font-black text-white">₹{{ $service->sale_price }}</span>
                        </div>
                        <div class="text-right">
                            <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-1">Duration</p>
                            <span class="text-lg font-bold text-white">{{ $service->duration_minutes }} Mins</span>
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
                        Book Now
                    </button>
                    
                    <p class="text-center text-white/30 text-[10px] font-bold uppercase mt-6 tracking-widest">Secure Checkout Powered by Easy Saloon</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
