@extends('frontend.layout.app')

@section('page_title', 'Home Salon Services & Beauty at Home')
@section('meta_description', 'Book premium salon services at home. Easy Saloon provides professional grooming, facials, and beauty treatments at your doorstep in India.')

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-20 h-[600px] overflow-hidden">
        <div id="hero-carousel" class="absolute inset-0">
            @forelse($banners as $index => $banner)
            <div class="hero-carousel-item {{ $index == 0 ? 'active' : '' }} absolute inset-0 w-full h-full">
                <img src="{{ asset('storage/' . $banner->image) }}" class="w-full h-full object-cover" alt="{{ $banner->title }}">
                <div class="absolute inset-0 bg-[#3d2b1f]/50"></div>
                
                <div class="absolute inset-0 flex items-center justify-center text-center px-4 z-10">
                    <div class="max-w-4xl">
                        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 drop-shadow-2xl hero-text" style="font-family: 'Playfair Display', serif;">
                            {!! preg_replace('/\*(.*?)\*/', '<span class="text-[#c6a664]">$1</span>', str_replace('|', '<br>', $banner->title)) !!}
                        </h1>
                        <p class="text-lg md:text-xl text-white font-medium mb-10 drop-shadow-lg opacity-90 max-w-2xl mx-auto">
                            {{ $banner->subtitle }}
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <a href="{{ $banner->link }}" class="btn-primary shadow-2xl inline-flex items-center justify-center">Download App</a>
                            <button class="bg-[#c6a664] text-white px-8 py-3.5 rounded-full font-bold shadow-2xl hover:scale-105 transition-all">Register as Partner</button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="hero-carousel-item active absolute inset-0 w-full h-full">
                <img src="/assets/img/hero.png" class="w-full h-full object-cover" alt="Default Hero">
                <div class="absolute inset-0 bg-[#3d2b1f]/50"></div>
                <div class="absolute inset-0 flex items-center justify-center text-center px-4 z-10">
                    <div class="max-w-4xl">
                        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 drop-shadow-2xl hero-text" style="font-family: 'Playfair Display', serif;">
                            Bringing <span class="text-[#c6a664]">Salon Expertise</span> <br> to Your Doorstep
                        </h1>
                        <p class="text-lg md:text-xl text-white font-medium mb-10 drop-shadow-lg opacity-90 max-w-2xl mx-auto">
                            While Changing the Lives of Service Professionals
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button class="btn-primary shadow-2xl">Download App</button>
                            <button class="bg-[#c6a664] text-white px-8 py-3.5 rounded-full font-bold shadow-2xl hover:scale-105 transition-all">Register as Partner</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Achievements -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-center text-xl font-bold mb-10 text-[#3d2b1f]" style="font-family: 'Outfit', sans-serif;">Achievements so far</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                @php
                    $achievementColors = [
                        ['bg' => 'bg-pink-50', 'text' => 'text-pink-600'],
                        ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                        ['bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
                        ['bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
                        ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600'],
                    ];
                @endphp
                @foreach($achievements as $achievement)
                @php 
                    $color = $achievementColors[$loop->index % count($achievementColors)];
                    $iconName = str_replace('bi-', '', $achievement->svg_icon);
                    
                    // Original Premium SVG Paths Mapping
                    $svgPaths = [
                        'people' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                        'download' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4',
                        'calendar-check' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'geo-alt' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z',
                        'star' => 'M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z',
                        'heart' => 'M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z',
                        'shop' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
                    ];
                @endphp
                <div class="text-center">
                    <div class="w-12 h-12 {{ $color['bg'] }} {{ $color['text'] }} rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm">
                        <div class="flex items-center justify-center">
                            @if(isset($svgPaths[$iconName]))
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="{{ $iconName == 'star' ? 'fill: currentColor;' : '' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $svgPaths[$iconName] }}"></path>
                                </svg>
                            @elseif(str_contains($achievement->svg_icon, '<svg'))
                                <div class="w-6 h-6">{!! $achievement->svg_icon !!}</div>
                            @else
                                <i class="bi bi-{{ $iconName }} text-xl"></i>
                            @endif
                        </div>
                    </div>
                    <span class="text-lg font-bold text-[#3d2b1f]">{{ $achievement->value }}</span>
                    <p class="text-[9px] text-gray-400 uppercase font-bold tracking-wider">{{ $achievement->title }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Categories Grid -->
    <section class="py-16 bg-[#fffaf5]">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-[#3d2b1f] mb-3" style="font-family: 'Playfair Display', serif;">Explore by Category</h2>
                <div class="w-16 h-1 bg-[#c6a664] mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach($categories as $category)
                <a href="{{ route('services.index', ['category[]' => $category->slug]) }}" class="group block text-center">
                    <div class="relative aspect-square rounded-[2rem] overflow-hidden mb-4 shadow-sm group-hover:shadow-xl transition-all duration-500 bg-white p-2 border border-gray-100">
                        <div class="w-full h-full rounded-[1.8rem] overflow-hidden relative">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $category->name }}">
                            @else
                                <div class="w-full h-full bg-gray-50 flex items-center justify-center">
                                    <i class="bi bi-scissors text-2xl text-[#c6a664]"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-500"></div>
                        </div>
                    </div>
                    <h6 class="font-bold text-[#3d2b1f] text-sm group-hover:text-[#c6a664] transition-colors">{{ $category->name }}</h6>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div>
                    <h2 class="text-4xl font-bold text-[#3d2b1f] mb-4" style="font-family: 'Playfair Display', serif;">Services we offer</h2>
                    <p class="text-[#c6a664] font-medium uppercase tracking-widest text-xs">Curated luxury experiences for your wellbeing</p>
                </div>
                <a href="{{ route('services.index') }}" class="text-[#3d2b1f] font-bold text-sm border-b-2 border-[#c6a664] pb-1 hover:text-[#c6a664] transition-all">View all services</a>
            </div>
            
            <div class="grid md:grid-cols-3 gap-10">
                @forelse($services as $service)
                <a href="{{ route('services.show', $service->slug) }}" class="group relative aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-2xl hover:-translate-y-2 transition-all duration-500 {{ $loop->iteration == 2 ? 'mt-0 md:mt-12' : '' }}">
                    @if($service->image)
                    <img src="{{ asset('storage/' . $service->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $service->name }}">
                    @else
                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                        <i class="bi bi-image text-gray-300 text-4xl"></i>
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-[#3d2b1f] via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 p-10 w-full translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <div class="w-12 h-1 w-[#c6a664] bg-[#c6a664] mb-4 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                        <h4 class="text-2xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">{{ $service->name }}</h4>
                        <p class="text-white/60 text-sm opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100">{{ Str::limit(strip_tags($service->details), 80) }}</p>
                    </div>
                </a>
                @empty
                <!-- Fallback if no services are found -->
                <div class="col-span-3 text-center py-10">
                    <p class="text-gray-500">No services available at the moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Promo Banners -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8">
                @forelse($promo_banners as $promo)
                <div class="relative rounded-[2.5rem] overflow-hidden min-h-[280px] group shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-100">
                    <img src="{{ asset('storage/' . $promo->image) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $promo->title }}">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
                    <div class="relative z-10 p-10 h-full flex flex-col justify-between items-start">
                        <div>
                            <p class="text-white/70 font-bold text-[10px] mb-2 uppercase tracking-[0.2em]">{{ $promo->subtitle }}</p>
                            <h3 class="text-3xl md:text-4xl font-extrabold text-white mb-6 drop-shadow-2xl leading-tight">
                                {!! preg_replace('/\*(.*?)\*/', '<span class="text-[#c6a664]">$1</span>', str_replace('|', '<br>', $promo->title)) !!}
                            </h3>
                        </div>
                        <a href="{{ $promo->link ?? '#' }}" class="bg-white text-[#3d2b1f] px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-wider hover:bg-[#c6a664] hover:text-white transition-all transform hover:-translate-y-1 shadow-xl">
                            Request Call Back
                        </a>
                    </div>
                </div>
                @empty
                <!-- Fallback 1 -->
                <div class="bg-[#e0f7fa] rounded-3xl p-10 flex flex-col justify-between items-start min-h-[250px]">
                    <div>
                        <p class="text-[#00838f] font-bold text-xs mb-1">Have questions about</p>
                        <h3 class="text-3xl font-extrabold mb-6">HydraGlo Facial <br> Treatments?</h3>
                    </div>
                    <button class="bg-[#00acc1] text-white px-8 py-3 rounded-xl font-bold">Request Call Back</button>
                </div>
                <!-- Fallback 2 -->
                <div class="bg-[#f3e5f5] rounded-3xl p-10 flex flex-col justify-between items-start min-h-[250px]">
                    <div>
                        <p class="text-[#7b1fa2] font-bold text-xs mb-1">Have questions about</p>
                        <h3 class="text-3xl font-extrabold mb-6">Laser Hair <br> Reduction?</h3>
                    </div>
                    <button class="bg-[#8e24aa] text-white px-8 py-3 rounded-xl font-bold">Request Call Back</button>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Media Coverage -->
    <section class="py-24 bg-[#fffaf0] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex flex-col items-center gap-4 mb-16">
                <h2 class="text-5xl font-black tracking-tighter" style="font-family: 'Outfit', sans-serif;">MEDIA <br> COVERAGES</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($blogs->take(3) as $blog)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-left h-full flex flex-col">
                    <div class="flex items-center gap-2 mb-4"><div class="w-8 h-4 bg-red-600"></div> <span class="font-bold text-xs">{{ $blog->category }}</span></div>
                    <h5 class="font-bold mb-4 line-clamp-2">{{ $blog->title }}</h5>
                    <div class="h-40 bg-gray-100 rounded-xl mb-4 overflow-hidden mt-auto">
                        <img src="{{ asset('storage/' . $blog->image) }}" class="w-full h-full object-cover">
                    </div>
                    <a href="{{ route('blogs.show', $blog->id) }}" class="text-xs text-gray-500 hover:text-[#c6a664] transition-colors">Read more →</a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Feedback Section -->
    <section class="py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 mb-12">
            <h2 class="text-center text-4xl font-extrabold mb-16 text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Valuable Feedback from our customers</h2>
            
            @php
                $avgRating = number_format($feedbacks->avg('stars') ?? 4.9, 1);
                $reviewCount = $feedbacks->count() > 0 ? $feedbacks->count() : '49.8k';
                $displayCount = $feedbacks->count() > 0 ? $feedbacks->count() . ' reviews' : '49.8k reviews';
            @endphp

            <div class="flex flex-col md:flex-row justify-center items-center gap-12 mb-12">
                <div class="text-center md:text-left">
                    <p class="text-sm font-bold text-gray-400 mb-2 uppercase tracking-widest">Love from our customers</p>
                    <div class="flex items-center gap-4 justify-center md:justify-start">
                        <span class="text-6xl font-black text-[#3d2b1f]">{{ $avgRating }}</span>
                        <div class="flex flex-col">
                            <div class="flex text-[#c6a664] text-xl">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($avgRating))
                                        ★
                                    @else
                                        <span class="text-gray-200">★</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-xs font-bold text-gray-400">{{ $displayCount }}</span>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block w-px h-20 bg-gray-100"></div>
                <div class="flex -space-x-4">
                    @forelse($feedbacks->take(4) as $f)
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($f->name) }}&background=f4ece4&color=3d2b1f" class="w-14 h-14 rounded-full border-4 border-white shadow-sm" alt="User">
                    @empty
                    <img src="https://ui-avatars.com/api/?name=User&background=f4ece4&color=3d2b1f" class="w-14 h-14 rounded-full border-4 border-white shadow-sm" alt="User">
                    @endforelse
                    <div class="w-14 h-14 rounded-full bg-[#f4ece4] border-4 border-white flex items-center justify-center text-xs font-bold text-[#3d2b1f] shadow-sm">+{{ $reviewCount }}</div>
                </div>
            </div>
        </div>
        
        <div class="feedback-track">
            <!-- Set 1 -->
            @foreach($feedbacks as $feedback)
            <div class="feedback-card">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($feedback->name) }}&background=f4ece4&color=3d2b1f" class="w-12 h-12 rounded-full border border-gray-100" alt="{{ $feedback->name }}">
                    <div>
                        <h6 class="font-bold text-[#3d2b1f]">{{ $feedback->name }}</h6>
                        <div class="flex text-[#c6a664] text-[10px]">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $feedback->stars ? '★' : '☆' }}
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-[13px] text-gray-500 leading-relaxed italic">"{{ $feedback->description }}"</p>
            </div>
            @endforeach

            <!-- Set 2 (Duplicate for seamless marquee) -->
            @foreach($feedbacks as $feedback)
            <div class="feedback-card">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($feedback->name) }}&background=f4ece4&color=3d2b1f" class="w-12 h-12 rounded-full border border-gray-100" alt="{{ $feedback->name }}">
                    <div>
                        <h6 class="font-bold text-[#3d2b1f]">{{ $feedback->name }}</h6>
                        <div class="flex text-[#c6a664] text-[10px]">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $feedback->stars ? '★' : '☆' }}
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-[13px] text-gray-500 leading-relaxed italic">"{{ $feedback->description }}"</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- App Section -->
    <section class="py-24 bg-[#fff9fc]">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center gap-16">
                <div class="w-full md:w-1/3">
                    <img src="/assets/img/staff.png" class="w-full rounded-[30px] shadow-xl" alt="Staff">
                </div>
                <div class="w-full md:w-2/3">
                    <h2 class="text-4xl font-bold mb-6 text-[#3d2b1f]">Get the Easy Saloon App</h2>
                    <p class="text-gray-500 mb-10">We will send you a link, open it on your phone to download app.</p>
                    <div class="flex gap-2 mb-8 max-w-md">
                        <div class="flex-1 border border-gray-200 rounded-xl bg-white px-4 h-14 flex items-center">
                            <span class="text-gray-400 mr-2">+91</span>
                            <input type="text" placeholder="Enter mobile number" class="w-full focus:outline-none">
                        </div>
                        <button class="bg-[#3d2b1f] text-white px-10 rounded-2xl font-bold hover:bg-[#5d4037] transition-all">Send Link</button>
                    </div>
                    <div class="flex gap-4">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" class="h-10" alt="Play">
                        <img src="{{ asset('assets/img/appstore.png') }}" class="h-10" alt="App">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
