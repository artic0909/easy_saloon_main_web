@extends('frontend.layout.app')

@section('content')
<div x-data="{ 
    showFilter: false, 
    selectedCategories: {{ json_encode(collect(request('category', []))->flatten()->all()) }},
    isCategorySelected(slug) {
        return this.selectedCategories.includes(slug);
    }
}" class="pt-32 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mb-8">
            <a href="{{ route('home') }}" class="hover:text-[#3d2b1f]">Home</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#3d2b1f]">All Services</span>
        </div>

        <!-- Mobile Filter Toggle -->
        <div class="lg:hidden mb-8">
            <button @click="showFilter = true" class="w-full flex items-center justify-center gap-3 bg-white py-4 rounded-2xl shadow-sm border border-gray-100 font-bold text-[#3d2b1f] hover:bg-gray-50 transition-all">
                <i class="bi bi-funnel"></i>
                <span>Filter Services</span>
                @if(request('category') || request('subcategory') || request('max_price'))
                    <span class="w-2 h-2 bg-[#c6a664] rounded-full"></span>
                @endif
            </button>
        </div>

        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Sidebar / Filters (Off-canvas for Mobile, Permanent for Desktop) -->
            <div 
                class="fixed inset-0 z-[100] lg:relative lg:inset-auto lg:z-0 lg:block"
                :class="showFilter ? 'block' : 'hidden lg:block'"
                x-cloak
            >
                <!-- Backdrop for mobile -->
                <div x-show="showFilter" @click="showFilter = false" x-transition:opacity class="absolute inset-0 bg-black/50 lg:hidden"></div>

                <!-- Drawer Content -->
                <aside 
                    class="relative w-72 h-full lg:h-auto bg-white lg:bg-transparent overflow-y-auto lg:overflow-visible p-8 lg:p-0 shadow-2xl lg:shadow-none transition-transform duration-300 transform lg:translate-x-0"
                    :class="showFilter ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                >
                    <div class="flex items-center justify-between mb-8 lg:hidden">
                        <h5 class="text-xl font-bold text-[#3d2b1f]">Filters</h5>
                        <button @click="showFilter = false" class="p-2 text-gray-400 hover:text-[#3d2b1f]">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form id="filter-form" action="{{ route('services.index') }}" method="GET" class="sticky top-32">
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                            <div class="hidden lg:flex items-center justify-between mb-8">
                                <h5 class="text-xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Filters</h5>
                                <a href="{{ route('services.index') }}" class="text-[10px] font-bold text-[#c6a664] uppercase tracking-widest hover:opacity-70 transition-opacity">Reset</a>
                            </div>
                            
                            <!-- Categories -->
                            <div class="mb-10">
                                <h6 class="text-[11px] font-black uppercase text-gray-400 mb-5 tracking-widest border-b border-gray-50 pb-2">Categories</h6>
                                <div class="flex flex-col gap-4">
                                    @foreach($categories as $cat)
                                        <label class="flex items-center gap-3 cursor-pointer group custom-checkbox">
                                            <input type="checkbox" name="category[]" value="{{ $cat->slug }}" 
                                                class="hidden peer"
                                                x-model="selectedCategories">
                                            <div class="w-5 h-5 rounded-md border-2 border-gray-200 flex items-center justify-center peer-checked:bg-[#c6a664] peer-checked:border-[#c6a664] transition-all duration-300">
                                                <svg class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-600 group-hover:text-[#3d2b1f] transition-colors">{{ $cat->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Sub Categories (Dynamic via Alpine) -->
                            <div class="mb-10" x-show="selectedCategories.length > 0" x-transition>
                                <h6 class="text-[11px] font-black uppercase text-gray-400 mb-5 tracking-widest border-b border-gray-50 pb-2">Sub Categories</h6>
                                <div class="flex flex-col gap-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach($categories as $cat)
                                        <div x-show="isCategorySelected('{{ $cat->slug }}')">
                                            @foreach($cat->subCategories as $sub)
                                                <label class="flex items-center gap-3 cursor-pointer group custom-checkbox mb-3">
                                                    <input type="checkbox" name="subcategory[]" value="{{ $sub->slug }}" 
                                                        class="hidden peer"
                                                        {{ in_array($sub->slug, (array)request('subcategory')) ? 'checked' : '' }}>
                                                    <div class="w-5 h-5 rounded-md border-2 border-gray-200 flex items-center justify-center peer-checked:bg-[#c6a664] peer-checked:border-[#c6a664] transition-all duration-300">
                                                        <svg class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-600 group-hover:text-[#3d2b1f] transition-colors">{{ $sub->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-10">
                                <h6 class="text-[11px] font-black uppercase text-gray-400 mb-5 tracking-widest border-b border-gray-50 pb-2">Max Price</h6>
                                <div class="space-y-5">
                                    <input type="range" name="max_price" min="0" max="10000" step="100" 
                                        value="{{ request('max_price', 10000) }}"
                                        class="w-full accent-[#c6a664]"
                                        oninput="priceDisplay.innerText = '₹' + this.value">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">₹0</span>
                                        <span id="priceDisplay" class="text-sm font-black text-[#3d2b1f] bg-gray-50 px-3 py-1 rounded-lg">₹{{ request('max_price', 10000) }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">₹10,000+</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort By -->
                            <div class="mb-10">
                                <h6 class="text-[11px] font-black uppercase text-gray-400 mb-5 tracking-widest border-b border-gray-50 pb-2">Sort By</h6>
                                <select name="sort" class="w-full bg-[#fdfbf7] border-none rounded-2xl text-sm font-bold text-[#3d2b1f] py-3.5 focus:ring-2 focus:ring-[#c6a664] cursor-pointer">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                            </div>
                            
                            <div class="mt-8">
                                <button type="submit" class="w-full bg-[#3d2b1f] text-white py-4 rounded-2xl font-bold hover:bg-[#c6a664] transition-all shadow-lg shadow-black/10">Apply Filters</button>
                            </div>
                        </div>
                    </form>
                </aside>
            </div>

            <!-- Main Listing -->
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-6 mb-12">
                    <h2 class="text-4xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Salon Services</h2>
                    <p class="text-sm font-bold text-gray-400"><span class="text-[#3d2b1f]">{{ $services->total() }}</span> services available</p>
                </div>

                @if($services->count() > 0)
                    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-8">
                        @foreach($services as $service)
                            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group border border-gray-100">
                                <div class="relative h-56 overflow-hidden">
                                    @php 
                                        $imageMap = [
                                            'Facial' => 'cat-facial.png',
                                            'Massage' => 'cat-massage.png',
                                            'Makeup' => 'cat-makeup.png',
                                            'Hair' => 'cat-hair.png'
                                        ];
                                        $bgImage = '/assets/img/service-bridal.png';
                                        foreach($imageMap as $key => $img) {
                                            if(str_contains($service->category->name, $key)) {
                                                $bgImage = '/assets/img/' . $img;
                                                break;
                                            }
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
                                        <a href="{{ route('services.show', $service->slug) }}" class="w-12 h-12 bg-[#3d2b1f] rounded-2xl flex items-center justify-center text-white hover:bg-[#c6a664] hover:rounded-full transition-all duration-500 shadow-lg shadow-black/5">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m6-6H6"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
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
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.4s ease-out forwards;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #fdfbf7;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #d1d5db;
    }
    [x-cloak] { display: none !important; }
</style>
@endsection
