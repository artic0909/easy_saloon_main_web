@extends('frontend.layout.app')

@section('title', 'Media Coverage - Easy Saloon')

@section('content')
<!-- Hero Section -->
<section class="pt-32 pb-20 bg-[#fffaf0] relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-[#c6a664] opacity-[0.03] rounded-full blur-3xl -mr-48 -mt-48"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#c6a664] opacity-[0.03] rounded-full blur-3xl -ml-48 -mb-48"></div>

    <div class="max-w-7xl mx-auto px-4 relative">
        <div class="flex flex-col items-center text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 rounded-full bg-[#c6a664]/10 text-[#c6a664] text-[10px] font-black uppercase tracking-[0.2em] mb-6">Press & News</span>
            <h1 class="text-4xl md:text-6xl font-black text-[#3d2b1f] tracking-tighter mb-6 leading-tight">
                MEDIA <br> <span class="text-[#c6a664]">COVERAGES</span>
            </h1>
            <p class="text-gray-500 text-sm md:text-base leading-relaxed">
                Stay updated with the latest news, press releases, and media mentions about India's most trusted home salon brand.
            </p>
        </div>

        <!-- Blogs Grid -->
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            @foreach($blogs as $blog)
            <a href="{{ route('blogs.show', $blog->id) }}" class="group bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 text-left hover:shadow-2xl hover:shadow-[#c6a664]/10 hover:-translate-y-2 transition-all duration-500">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-1 bg-[#c6a664] rounded-full group-hover:w-16 transition-all duration-500"></div> 
                    <span class="font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#c6a664] transition-colors duration-300">{{ $blog->category }}</span>
                </div>
                
                <h3 class="text-lg font-bold text-[#3d2b1f] mb-6 line-clamp-2 group-hover:text-[#c6a664] transition-colors duration-300">
                    {{ $blog->title }}
                </h3>
                
                <div class="relative h-48 bg-gray-50 rounded-2xl mb-6 overflow-hidden">
                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">
                        {{ $blog->created_at->format('M d, Y') }}
                    </span>
                    <div class="flex items-center gap-2 text-[#c6a664] font-black text-[10px] uppercase tracking-widest opacity-0 group-hover:opacity-100 -translate-x-4 group-hover:translate-x-0 transition-all duration-500">
                        Read Story
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $blogs->links() }}
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-[#3d2b1f] mb-2">50+</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Media Features</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-[#3d2b1f] mb-2">10M+</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Reach</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-[#3d2b1f] mb-2">15+</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">News Portals</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-[#3d2b1f] mb-2">100%</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Trusted Brand</div>
            </div>
        </div>
    </div>
</section>
@endsection
