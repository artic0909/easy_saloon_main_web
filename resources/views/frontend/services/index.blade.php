@extends('frontend.layout.app')

@section('content')
<div class="pt-32 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mb-8">
            <a href="{{ route('home') }}" class="hover:text-[#3d2b1f]">Home</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#3d2b1f]">All Services</span>
        </div>

        <div class="flex flex-col md:flex-row gap-12">
            <!-- Sidebar / Filters -->
            <aside class="w-full md:w-64 flex-shrink-0">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 sticky top-32">
                    <h5 class="text-lg font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">Filters</h5>
                    
                    <!-- Categories -->
                    <div class="mb-10">
                        <h6 class="text-[10px] font-black uppercase text-gray-400 mb-4 tracking-widest">Categories</h6>
                        <div class="flex flex-col gap-3">
                            @foreach(\App\Models\Category::all() as $cat)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" class="w-5 h-5 rounded border-gray-200 text-[#3d2b1f] focus:ring-[#c6a664]">
                                    <span class="text-sm font-medium text-gray-600 group-hover:text-[#3d2b1f]">{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-10">
                        <h6 class="text-[10px] font-black uppercase text-gray-400 mb-4 tracking-widest">Price Range</h6>
                        <div class="space-y-4">
                            <input type="range" min="0" max="10000" step="100" class="w-full accent-[#3d2b1f]">
                            <div class="flex justify-between text-xs font-bold text-gray-500 uppercase">
                                <span>₹0</span>
                                <span>₹10,000+</span>
                            </div>
                        </div>
                    </div>

                    <!-- Popularity -->
                    <div>
                        <h6 class="text-[10px] font-black uppercase text-gray-400 mb-4 tracking-widest">Sort By</h6>
                        <select class="w-full bg-[#fdfbf7] border-none rounded-xl text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                            <option>Most Popular</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Newest First</option>
                        </select>
                    </div>
                </div>
            </aside>

            <!-- Main Listing -->
            <div class="flex-1">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
                    <h2 class="text-4xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Salon Services</h2>
                    <p class="text-sm font-bold text-gray-400"><span class="text-[#3d2b1f]">{{ \App\Models\Service::count() }}</span> services available</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach(\App\Models\Service::with('category')->get() as $service)
                        <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 group border border-gray-50">
                            <div class="relative h-48 overflow-hidden">
                                <img src="/assets/img/service-bridal.png" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-black text-[#3d2b1f] uppercase tracking-tighter">
                                    {{ $service->category->name }}
                                </div>
                            </div>
                            <div class="p-8">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="text-lg font-bold text-[#3d2b1f]">{{ $service->name }}</h4>
                                    <div class="flex items-center gap-1 text-[#c6a664] text-[10px] font-bold">★ 4.9</div>
                                </div>
                                <p class="text-xs text-gray-400 mb-6 line-clamp-2">{{ $service->details }}</p>
                                <div class="flex items-center justify-between mt-auto">
                                    <div>
                                        <span class="text-xl font-black text-[#3d2b1f]">₹{{ $service->sale_price }}</span>
                                        <span class="text-xs text-gray-300 line-through ml-2">₹{{ $service->original_price }}</span>
                                    </div>
                                    <a href="{{ route('services.show', $service->slug) }}" class="w-10 h-10 bg-[#3d2b1f] rounded-full flex items-center justify-center text-white hover:bg-[#c6a664] transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Empty State -->
                @if(\App\Models\Service::count() === 0)
                    <div class="py-24 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-400">No services found</h4>
                        <p class="text-sm text-gray-300">Try adjusting your filters or search term.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
