@extends('frontend.layout.app')

@section('content')
<div class="pt-32 md:pt-40 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="text-center mb-12 md:mb-20">
            <h2 class="text-4xl md:text-5xl font-extrabold text-[#3d2b1f] mb-4" style="font-family: 'Playfair Display', serif;">Curated Packages</h2>
            <p class="text-[#c6a664] font-medium uppercase tracking-widest text-[10px] md:text-xs">Bundle your favorite services and save more</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
            @foreach(\App\Models\Package::with('items.service')->get() as $package)
                <div class="bg-white rounded-[2.5rem] md:rounded-[3rem] p-6 md:p-10 shadow-sm border border-gray-100 hover:shadow-2xl transition-all duration-500 group relative flex flex-col h-full">
                    <div class="mb-4 md:absolute md:top-8 md:right-8 bg-[#c6a664]/10 text-[#c6a664] px-3 py-1.5 md:px-4 md:py-1.5 rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-widest inline-block w-fit">
                        Save ₹{{ $package->original_price - $package->sale_price }}
                    </div>
                    
                    <h3 class="text-xl md:text-2xl font-bold text-[#3d2b1f] mb-6 whitespace-normal break-words md:pr-24" style="font-family: 'Playfair Display', serif;">
                        <a href="{{ route('packages.show', $package->slug) }}" class="hover:text-[#c6a664] transition-colors">
                            {{ $package->name }}
                        </a>
                    </h3>
                    
                    <div class="flex-1">
                        <ul class="space-y-4 mb-10">
                            @foreach($package->items as $item)
                                <li class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-[#c6a664] rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-600">{{ $item->service->name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="pt-8 border-t border-gray-50 flex flex-wrap justify-between items-end gap-4">
                        <div class="flex-1">
                            <p class="text-[10px] text-gray-400 font-black uppercase mb-1">Package Price</p>
                            <span class="text-2xl md:text-3xl font-black text-[#3d2b1f]">₹{{ $package->sale_price }}</span>
                            <span class="text-xs md:text-sm text-gray-300 line-through ml-2">₹{{ $package->original_price }}</span>
                        </div>
                        <a href="{{ route('packages.show', $package->slug) }}" class="w-full sm:w-auto bg-[#3d2b1f] text-white px-8 py-3.5 rounded-2xl font-bold hover:bg-[#c6a664] transition-all text-sm text-center">Details</a>
                    </div>
                </div>
            @endforeach
        </div>

        @if(\App\Models\Package::count() === 0)
            <div class="py-32 text-center">
                <h4 class="text-2xl font-bold text-gray-300">Exclusive packages coming soon!</h4>
            </div>
        @endif
    </div>
</div>
@endsection
