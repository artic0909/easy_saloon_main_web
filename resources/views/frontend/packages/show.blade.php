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

            <!-- Right: Pricing & Booking -->
            <div class="w-full lg:w-[400px]">
                <div class="lg:sticky lg:top-40 bg-[#3d2b1f] rounded-[3rem] p-10 md:p-12 text-white shadow-2xl shadow-[#3d2b1f]/30 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-[#c6a664]/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-10">
                            <div>
                                <p class="text-[#c6a664] text-[10px] font-black uppercase tracking-[0.3em] mb-3">Package Value</p>
                                <div class="flex items-baseline gap-3">
                                    <span class="text-5xl font-black">₹{{ number_format($package->sale_price, 0) }}</span>
                                    <span class="text-xl text-white/30 line-through">₹{{ number_format($package->original_price, 0) }}</span>
                                </div>
                            </div>
                            <div class="bg-[#c6a664] text-[#3d2b1f] px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">
                                Save {{ round((($package->original_price - $package->sale_price) / $package->original_price) * 100) }}%
                            </div>
                        </div>

                        <div class="space-y-6 mb-12">
                            <div class="flex items-center gap-4 text-white/70">
                                <svg class="w-5 h-5 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-sm font-medium">Estimated Time: 2-3 Hours</span>
                            </div>
                            <div class="flex items-center gap-4 text-white/70">
                                <svg class="w-5 h-5 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                <span class="text-sm font-medium">Quality Guaranteed</span>
                            </div>
                        </div>

                        <button class="w-full bg-[#c6a664] text-[#3d2b1f] py-5 rounded-2xl font-black uppercase tracking-widest hover:bg-white transition-all shadow-xl shadow-[#c6a664]/20 text-sm">
                            Book This Package
                        </button>
                        
                        <p class="text-center text-white/30 text-[10px] font-black uppercase tracking-widest mt-8">
                            * Terms and conditions apply
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
