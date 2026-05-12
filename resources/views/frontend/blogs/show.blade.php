@extends('frontend.layout.app')

@section('title', $blog->title . ' - Easy Saloon')

@section('content')
<!-- Article Header -->
<section class="pt-32 pb-20 bg-[#fffaf0] relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-[#c6a664] opacity-[0.03] rounded-full blur-3xl -mr-48 -mt-48"></div>
    
    <div class="max-w-4xl mx-auto px-4 relative">
        <div class="flex flex-col items-center text-center mb-12">
            <span class="inline-block px-4 py-1.5 rounded-full bg-[#c6a664]/10 text-[#c6a664] text-[10px] font-black uppercase tracking-[0.2em] mb-8">{{ $blog->category }}</span>
            <h1 class="text-3xl md:text-5xl font-black text-[#3d2b1f] tracking-tighter mb-8 leading-tight">
                {{ $blog->title }}
            </h1>
            <div class="flex items-center gap-6 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $blog->created_at->format('d M, Y') }}
                </span>
                <span class="w-1 h-1 rounded-full bg-gray-200"></span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    5 Min Read
                </span>
            </div>
        </div>

        <div class="relative h-[300px] md:h-[500px] rounded-[3rem] overflow-hidden shadow-2xl shadow-[#c6a664]/10 mb-16">
            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
        </div>

        <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed space-y-6">
            {!! $blog->description !!}
        </div>

        <!-- Share Section -->
        <div class="mt-20 pt-10 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-4">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Share this story:</span>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 rounded-full bg-[#fdfbf7] flex items-center justify-center text-gray-400 hover:bg-[#c6a664] hover:text-white transition-all duration-300">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-[#fdfbf7] flex items-center justify-center text-gray-400 hover:bg-[#c6a664] hover:text-white transition-all duration-300">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-[#fdfbf7] flex items-center justify-center text-gray-400 hover:bg-[#c6a664] hover:text-white transition-all duration-300">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
            </div>
            <a href="{{ route('blogs.index') }}" class="flex items-center gap-3 text-[#c6a664] font-black text-[10px] uppercase tracking-widest hover:gap-5 transition-all">
                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                Back to Newsroom
            </a>
        </div>
    </div>
</section>

<!-- Related Articles -->
@if($relatedBlogs->count() > 0)
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col items-center text-center mb-16">
            <h2 class="text-3xl font-black text-[#3d2b1f] tracking-tighter mb-4 uppercase">More Coverage</h2>
            <div class="w-20 h-1 bg-[#c6a664] rounded-full"></div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($relatedBlogs as $rBlog)
            <a href="{{ route('blogs.show', $rBlog->id) }}" class="group block">
                <div class="relative h-48 rounded-2xl overflow-hidden mb-6">
                    <img src="{{ asset('storage/' . $rBlog->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                </div>
                <h4 class="text-base font-bold text-[#3d2b1f] mb-2 group-hover:text-[#c6a664] transition-colors line-clamp-2">{{ $rBlog->title }}</h4>
                <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest">{{ $rBlog->created_at->format('M d, Y') }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
