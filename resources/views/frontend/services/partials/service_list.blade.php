<div class="flex flex-col sm:flex-row justify-between items-center gap-6 mb-12">
    <h2 class="text-4xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Salon Services</h2>
    <p class="text-sm font-bold text-gray-400"><span class="text-[#3d2b1f]">{{ $services->total() }}</span> services available</p>
</div>

@if($services->count() > 0)
    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-8">
        @foreach($services as $service)
            <a href="{{ route('services.show', $service->slug) }}" class="block bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group border border-gray-100">
                <div class="relative h-56 overflow-hidden">
                    @php 
                        if(!empty($service->images) && is_array($service->images) && count($service->images) > 0) {
                            $bgImage = asset('storage/' . $service->images[0]);
                        } elseif(!empty($service->image)) {
                            $bgImage = asset('storage/' . $service->image);
                        } else {
                            $imageMap = [
                                'Facial' => 'cat-facial.png',
                                'Massage' => 'cat-massage.png',
                                'Makeup' => 'cat-makeup.png',
                                'Hair' => 'cat-hair.png'
                            ];
                            $fallback = 'service-bridal.png';
                            foreach($imageMap as $key => $img) {
                                if(str_contains($service->category->name, $key)) {
                                    $fallback = $img;
                                    break;
                                }
                            }
                            $bgImage = asset('assets/img/' . $fallback);
                        }
                    @endphp
                    <img src="{{ $bgImage }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute top-5 left-5 bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-full text-[10px] font-black text-[#3d2b1f] uppercase tracking-wider shadow-sm">
                        {{ $service->category->name }}
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                </div>
                <div class="p-8">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="text-lg font-bold text-[#3d2b1f] leading-snug group-hover:text-[#c6a664] transition-colors">{{ $service->name }}</h4>
                        <div class="flex items-center gap-1 text-[#c6a664] text-[10px] font-black bg-[#c6a664]/10 px-2 py-1 rounded-lg">★ 4.9</div>
                    </div>
                    <p class="text-[13px] text-gray-400 mb-8 line-clamp-2 leading-relaxed">{{ strip_tags($service->details) }}</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-black text-[#3d2b1f]">₹{{ number_format($service->sale_price, 2) }}</span>
                            @if($service->original_price > $service->sale_price)
                                <span class="text-xs text-gray-300 line-through ml-2">₹{{ number_format($service->original_price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-16">
        {{ $services->links('vendor.pagination.tailwind') }}
    </div>
@else
    <!-- Empty State -->
    <div class="py-32 text-center bg-white rounded-[4rem] border-2 border-dashed border-gray-100 px-6">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
            <i class="bi bi-search text-4xl text-gray-200"></i>
        </div>
        <h4 class="text-2xl font-bold text-[#3d2b1f] mb-2">No matches found</h4>
        <p class="text-gray-400 max-w-xs mx-auto">We couldn't find any services matching your current filters. Try resetting them or adjusting the price range.</p>
        <a href="{{ route('services.index') }}" class="mt-10 inline-block bg-[#3d2b1f] text-white px-10 py-4 rounded-2xl font-bold hover:bg-[#c6a664] transition-all">Clear All Filters</a>
    </div>
@endif
