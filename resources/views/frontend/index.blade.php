@extends('frontend.layout.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-20 h-[600px] overflow-hidden">
        <div id="hero-carousel" class="absolute inset-0">
            <div class="hero-carousel-item active absolute inset-0">
                <img src="/assets/img/hero.png" class="w-full h-full object-cover" alt="Slide 1">
                <div class="absolute inset-0 bg-[#3d2b1f]/50"></div>
            </div>
            <div class="hero-carousel-item absolute inset-0">
                <img src="/assets/img/cat-makeup.png" class="w-full h-full object-cover" alt="Slide 2">
                <div class="absolute inset-0 bg-[#3d2b1f]/50"></div>
            </div>
        </div>
        <div class="relative z-10 h-full flex items-center justify-center text-center px-4">
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
    </section>

    <!-- Achievements -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-center text-xl font-bold mb-10 text-[#3d2b1f]" style="font-family: 'Outfit', sans-serif;">Achievements so far</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
                    <span class="text-lg font-bold">7000+</span>
                    <p class="text-[9px] text-gray-400 uppercase font-bold">Professionals</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></div>
                    <span class="text-lg font-bold">6M+</span>
                    <p class="text-[9px] text-gray-400 uppercase font-bold">App Downloads</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    <span class="text-lg font-bold">8M+</span>
                    <p class="text-[9px] text-gray-400 uppercase font-bold">Bookings</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg></div>
                    <span class="text-lg font-bold">50+</span>
                    <p class="text-[9px] text-gray-400 uppercase font-bold">Cities</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></div>
                    <span class="text-lg font-bold">4.8 ★</span>
                    <p class="text-[9px] text-gray-400 uppercase font-bold">Rating</p>
                </div>
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
                <a href="{{ route('services.index', ['category' => $category->slug]) }}" class="group block text-center">
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
                <button class="text-[#3d2b1f] font-bold text-sm border-b-2 border-[#c6a664] pb-1 hover:text-[#c6a664] transition-all">View all services</button>
            </div>
            
            <div class="grid md:grid-cols-3 gap-10">
                <!-- Service 1 -->
                <div class="group relative aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <img src="/assets/img/cat-facial.png" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Salon at Home">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#3d2b1f] via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 p-10 w-full translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <div class="w-12 h-1 w-[#c6a664] bg-[#c6a664] mb-4 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                        <h4 class="text-2xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">Salon at Home</h4>
                        <p class="text-white/60 text-sm opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100">Premium grooming services delivered at your convenience.</p>
                    </div>
                </div>
                
                <!-- Service 2 -->
                <div class="group relative aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-2xl hover:-translate-y-2 transition-all duration-500 mt-0 md:mt-12">
                    <img src="/assets/img/cat-massage.png" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Spa & Massage">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#3d2b1f] via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 p-10 w-full translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <div class="w-12 h-1 w-[#c6a664] bg-[#c6a664] mb-4 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                        <h4 class="text-2xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">Spa & Massage</h4>
                        <p class="text-white/60 text-sm opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100">Rejuvenate your senses with our expert therapeutic massages.</p>
                    </div>
                </div>
                
                <!-- Service 3 -->
                <div class="group relative aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <img src="/assets/img/cat-makeup.png" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Bridal & Party">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#3d2b1f] via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 p-10 w-full translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <div class="w-12 h-1 w-[#c6a664] bg-[#c6a664] mb-4 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                        <h4 class="text-2xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">Bridal & Party</h4>
                        <p class="text-white/60 text-sm opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100">Stunning bridal and party makeup by certified professionals.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Promo Banners -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-[#e0f7fa] rounded-3xl p-10 flex flex-col justify-between items-start min-h-[250px]">
                    <div>
                        <p class="text-[#00838f] font-bold text-xs mb-1">Have questions about</p>
                        <h3 class="text-3xl font-extrabold mb-6">HydraGlo Facial <br> Treatments?</h3>
                    </div>
                    <button class="bg-[#00acc1] text-white px-8 py-3 rounded-xl font-bold">Request Call Back</button>
                </div>
                <div class="bg-[#f3e5f5] rounded-3xl p-10 flex flex-col justify-between items-start min-h-[250px]">
                    <div>
                        <p class="text-[#7b1fa2] font-bold text-xs mb-1">Have questions about</p>
                        <h3 class="text-3xl font-extrabold mb-6">Laser Hair <br> Reduction?</h3>
                    </div>
                    <button class="bg-[#8e24aa] text-white px-8 py-3 rounded-xl font-bold">Request Call Back</button>
                </div>
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
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-left">
                    <div class="flex items-center gap-2 mb-4"><div class="w-8 h-4 bg-red-600"></div> <span class="font-bold text-xs">News 18</span></div>
                    <h5 class="font-bold mb-4">India's most trusted home salon brand is here.</h5>
                    <div class="h-40 bg-gray-100 rounded-xl mb-4 overflow-hidden"><img src="/assets/img/hero.png" class="w-full h-full object-cover"></div>
                    <p class="text-xs text-gray-500">Read more →</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-left">
                    <div class="flex items-center gap-2 mb-4"><div class="w-8 h-4 bg-red-600"></div> <span class="font-bold text-xs">The Economic Times</span></div>
                    <h5 class="font-bold mb-4">How Easy Saloon is redefining the grooming industry.</h5>
                    <div class="h-40 bg-gray-100 rounded-xl mb-4 overflow-hidden"><img src="/assets/img/cat-makeup.png" class="w-full h-full object-cover"></div>
                    <p class="text-xs text-gray-500">Read more →</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-left">
                    <div class="flex items-center gap-2 mb-4"><div class="w-8 h-4 bg-red-600"></div> <span class="font-bold text-xs">Times of India</span></div>
                    <h5 class="font-bold mb-4">The rise of salon-at-home services in major cities.</h5>
                    <div class="h-40 bg-gray-100 rounded-xl mb-4 overflow-hidden"><img src="/assets/img/cat-hair.png" class="w-full h-full object-cover"></div>
                    <p class="text-xs text-gray-500">Read more →</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Feedback Section -->
    <section class="py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 mb-12">
            <h2 class="text-center text-4xl font-extrabold mb-16 text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Valuable Feedback from our customers</h2>
            
            <div class="flex flex-col md:flex-row justify-center items-center gap-12 mb-12">
                <div class="text-center md:text-left">
                    <p class="text-sm font-bold text-gray-400 mb-2 uppercase tracking-widest">Love from our customers</p>
                    <div class="flex items-center gap-4 justify-center md:justify-start">
                        <span class="text-6xl font-black text-[#3d2b1f]">4.5</span>
                        <div class="flex flex-col">
                            <div class="flex text-[#c6a664] text-xl">★★★★★</div>
                            <span class="text-xs font-bold text-gray-400">49.8k reviews</span>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block w-px h-20 bg-gray-100"></div>
                <div class="flex -space-x-4">
                    <img src="https://ui-avatars.com/api/?name=User+1&background=f4ece4&color=3d2b1f" class="w-14 h-14 rounded-full border-4 border-white shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=User+2&background=f4ece4&color=3d2b1f" class="w-14 h-14 rounded-full border-4 border-white shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=User+3&background=f4ece4&color=3d2b1f" class="w-14 h-14 rounded-full border-4 border-white shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=User+4&background=f4ece4&color=3d2b1f" class="w-14 h-14 rounded-full border-4 border-white shadow-sm">
                    <div class="w-14 h-14 rounded-full bg-[#f4ece4] border-4 border-white flex items-center justify-center text-xs font-bold text-[#3d2b1f] shadow-sm">+10k</div>
                </div>
            </div>
        </div>
        
        <div class="feedback-track">
            <!-- First Set -->
            <div class="feedback-card">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name=Afreen+Hussain&background=f4ece4&color=3d2b1f" class="w-12 h-12 rounded-full border border-gray-100" alt="User">
                    <div>
                        <h6 class="font-bold text-[#3d2b1f]">Afreen Hussain</h6>
                        <div class="flex text-[#c6a664] text-[10px]">★★★★★</div>
                    </div>
                </div>
                <p class="text-[13px] text-gray-500 leading-relaxed italic">"Experience with Easy Saloon was really good, she was very professional and thorough... her hands were like magic. Massage and body polishing was up to the mark."</p>
            </div>
            <div class="feedback-card">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name=Srishti+Kanth&background=f4ece4&color=3d2b1f" class="w-12 h-12 rounded-full border border-gray-100" alt="User">
                    <div>
                        <h6 class="font-bold text-[#3d2b1f]">Srishti Kanth</h6>
                        <div class="flex text-[#c6a664] text-[10px]">★★★★★</div>
                    </div>
                </div>
                <p class="text-[13px] text-gray-500 leading-relaxed italic">"I recently booked a facial service with Preeti Chauhan, and I must say I am absolutely thrilled with the experience. Preeti's work is sincere and satisfying."</p>
            </div>
            <div class="feedback-card">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name=Rahul+Sharma&background=f4ece4&color=3d2b1f" class="w-12 h-12 rounded-full border border-gray-100" alt="User">
                    <div>
                        <h6 class="font-bold text-[#3d2b1f]">Rahul Sharma</h6>
                        <div class="flex text-[#c6a664] text-[10px]">★★★★★</div>
                    </div>
                </div>
                <p class="text-[13px] text-gray-500 leading-relaxed italic">"The men's grooming session was top-notch. Very clean and professional. Highly recommended for home services!"</p>
            </div>
            <div class="feedback-card">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name=Priya+M&background=f4ece4&color=3d2b1f" class="w-12 h-12 rounded-full border border-gray-100" alt="User">
                    <div>
                        <h6 class="font-bold text-[#3d2b1f]">Priya Mehra</h6>
                        <div class="flex text-[#c6a664] text-[10px]">★★★★★</div>
                    </div>
                </div>
                <p class="text-[13px] text-gray-500 leading-relaxed italic">"Easy Saloon has changed how I groom. The convenience of getting a high-end pedicure at home is unmatched."</p>
            </div>

            <!-- Second Set (Duplicate for Loop) -->
            <div class="feedback-card">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name=Afreen+Hussain&background=f4ece4&color=3d2b1f" class="w-12 h-12 rounded-full border border-gray-100" alt="User">
                    <div>
                        <h6 class="font-bold text-[#3d2b1f]">Afreen Hussain</h6>
                        <div class="flex text-[#c6a664] text-[10px]">★★★★★</div>
                    </div>
                </div>
                <p class="text-[13px] text-gray-500 leading-relaxed italic">"Experience with Easy Saloon was really good, she was very professional and thorough... her hands were like magic. Massage and body polishing was up to the mark."</p>
            </div>
            <div class="feedback-card">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name=Srishti+Kanth&background=f4ece4&color=3d2b1f" class="w-12 h-12 rounded-full border border-gray-100" alt="User">
                    <div>
                        <h6 class="font-bold text-[#3d2b1f]">Srishti Kanth</h6>
                        <div class="flex text-[#c6a664] text-[10px]">★★★★★</div>
                    </div>
                </div>
                <p class="text-[13px] text-gray-500 leading-relaxed italic">"I recently booked a facial service with Preeti Chauhan, and I must say I am absolutely thrilled with the experience. Preeti's work is sincere and satisfying."</p>
            </div>
            <div class="feedback-card">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name=Rahul+Sharma&background=f4ece4&color=3d2b1f" class="w-12 h-12 rounded-full border border-gray-100" alt="User">
                    <div>
                        <h6 class="font-bold text-[#3d2b1f]">Rahul Sharma</h6>
                        <div class="flex text-[#c6a664] text-[10px]">★★★★★</div>
                    </div>
                </div>
                <p class="text-[13px] text-gray-500 leading-relaxed italic">"The men's grooming session was top-notch. Very clean and professional. Highly recommended for home services!"</p>
            </div>
            <div class="feedback-card">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name=Priya+M&background=f4ece4&color=3d2b1f" class="w-12 h-12 rounded-full border border-gray-100" alt="User">
                    <div>
                        <h6 class="font-bold text-[#3d2b1f]">Priya Mehra</h6>
                        <div class="flex text-[#c6a664] text-[10px]">★★★★★</div>
                    </div>
                </div>
                <p class="text-[13px] text-gray-500 leading-relaxed italic">"Easy Saloon has changed how I groom. The convenience of getting a high-end pedicure at home is unmatched."</p>
            </div>
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
