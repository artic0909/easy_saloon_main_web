@extends('frontend.layout.app')

@section('content')
<div class="pt-32 md:pt-40 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="text-center mb-12 md:mb-20">
            <h2 class="text-4xl md:text-5xl font-extrabold text-[#3d2b1f] mb-4" style="font-family: 'Playfair Display', serif;">Curated Packages</h2>
            <p class="text-[#c6a664] font-medium uppercase tracking-widest text-[10px] md:text-xs">Bundle your favorite services and save more</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
            @forelse($packages as $package)
                <div class="rounded-[2.5rem] md:rounded-[3rem] overflow-hidden shadow-2xl border border-gray-100 hover:shadow-3xl transition-all duration-700 group relative flex flex-col h-[400px] bg-[#3d2b1f]">
                    <!-- Background Image with Professional Overlay -->
                    <div class="absolute inset-0 z-0">
                        @if($package->image)
                            <img src="{{ asset('storage/' . $package->image) }}" class="w-full h-full object-cover opacity-80 transition-transform duration-1000 group-hover:scale-110">
                        @else
                            <img src="{{ asset('assets/img/service-bridal.png') }}" class="w-full h-full object-cover opacity-80 transition-transform duration-1000 group-hover:scale-110">
                        @endif
                        <!-- Multi-layer Gradient for Text Visibility -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#3d2b1f] via-[#3d2b1f]/60 to-transparent"></div>
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/5 transition-colors duration-700"></div>
                    </div>

                    <div class="relative z-10 p-8 md:p-10 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-auto">
                            <h3 class="text-2xl md:text-3xl font-black text-white leading-tight drop-shadow-lg" style="font-family: 'Playfair Display', serif;">
                                <a href="{{ route('packages.show', $package->slug) }}" class="hover:text-[#c6a664] transition-colors">
                                    {{ $package->name }}
                                </a>
                            </h3>
                            <div class="bg-[#c6a664] text-white px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl border border-white/10">
                                Save ₹{{ $package->original_price - $package->sale_price }}
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <p class="text-white/60 text-xs md:text-sm leading-relaxed line-clamp-2">
                                {{ Str::limit(strip_tags($package->details), 80) }}
                            </p>
                        </div>

                        <div class="mb-10">
                            <ul class="space-y-3">
                                @foreach($package->items->take(3) as $item)
                                    <li class="flex items-center gap-3">
                                        <div class="w-1.5 h-1.5 bg-[#c6a664] rounded-full shadow-[0_0_8px_#c6a664]"></div>
                                        <span class="text-xs md:text-sm font-bold text-white tracking-wide">{{ $item->service->name }}</span>
                                    </li>
                                @endforeach
                                @if($package->items->count() > 3)
                                    <li class="flex items-center gap-3">
                                        <div class="w-1.5 h-1.5 bg-[#c6a664] rounded-full shadow-[0_0_8px_#c6a664]"></div>
                                        <span class="text-xs md:text-sm font-bold text-white tracking-wide">& {{ $package->items->count() - 3 }} More Services</span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="pt-8 border-t border-white/10 flex justify-between items-center">
                            <div>
                                <p class="text-[9px] text-[#c6a664] font-black uppercase mb-1 tracking-[0.2em]">Limited Offer</p>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-black text-white">₹{{ number_format($package->sale_price, 0) }}</span>
                                    <span class="text-sm text-white/40 line-through">₹{{ number_format($package->original_price, 0) }}</span>
                                </div>
                            </div>
                            <a href="{{ route('packages.show', $package->slug) }}" class="bg-white text-[#3d2b1f] px-8 py-4 rounded-[1.5rem] font-bold hover:bg-[#c6a664] hover:text-white transition-all text-sm shadow-xl transform group-hover:translate-x-1">
                                Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-32 text-center">
                    <h4 class="text-2xl font-bold text-gray-300">Exclusive packages coming soon!</h4>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
