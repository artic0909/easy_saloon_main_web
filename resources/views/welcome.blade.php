<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Easy Saloon | Premium Salon Services at Home & In-Store</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        .gradient-text { background: linear-gradient(135deg, #6d28d9 0%, #db2777 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-gradient { background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.1)), url('/assets/img/hero.png'); background-size: cover; background-position: center; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 selection:bg-pink-100 selection:text-pink-600">
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-tr from-purple-600 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758L6.243 17.757M12 12L15.758 15.758M12 12l-2.879-2.879"></path></svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight gradient-text">Easy Saloon</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-sm font-medium hover:text-pink-600 transition-colors">Home</a>
                    <a href="#services" class="text-sm font-medium hover:text-pink-600 transition-colors">Services</a>
                    <a href="#about" class="text-sm font-medium hover:text-pink-600 transition-colors">About</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-gray-900 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-gray-800 transition-all shadow-md">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium hover:text-pink-600 transition-colors">Log in</a>
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-purple-600 to-pink-500 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:opacity-90 transition-all shadow-lg">Book Now</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 hero-gradient"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                Beauty and Grooming <br> 
                <span class="text-pink-300">Delivered to your Doorstep.</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-100 mb-10 max-w-2xl mx-auto font-light">
                Premium salon services by certified professionals. Choose from home service or visit our nearest partner salon.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#services" class="w-full sm:w-auto bg-white text-gray-900 px-10 py-4 rounded-full font-bold hover:bg-pink-50 transition-all shadow-2xl">
                    View Services
                </a>
                <a href="#" class="w-full sm:w-auto glass text-white px-10 py-4 rounded-full font-bold hover:bg-white/20 transition-all">
                    Explore Packages
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="services" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Our Service Categories</h2>
                    <p class="text-gray-500 max-w-md">Everything you need for your perfect look, from head to toe.</p>
                </div>
                <a href="#" class="text-pink-600 font-semibold flex items-center gap-2 hover:underline">
                    View all services <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Hair -->
                <div class="group relative rounded-3xl overflow-hidden aspect-square shadow-xl cursor-pointer">
                    <img src="/assets/img/cat-hair.png" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="Hair">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-xl font-bold">Hair Care</h3>
                        <p class="text-sm opacity-80">24+ Services</p>
                    </div>
                </div>
                <!-- Makeup -->
                <div class="group relative rounded-3xl overflow-hidden aspect-square shadow-xl cursor-pointer">
                    <img src="/assets/img/cat-makeup.png" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="Makeup">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-xl font-bold">Makeup</h3>
                        <p class="text-sm opacity-80">15+ Services</p>
                    </div>
                </div>
                <!-- Facials -->
                <div class="group relative rounded-3xl overflow-hidden aspect-square shadow-xl cursor-pointer bg-pink-100 flex items-center justify-center">
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Facial & Spa</h3>
                        <p class="text-sm text-gray-600">Pure relaxation</p>
                    </div>
                </div>
                <!-- Men's Grooming -->
                <div class="group relative rounded-3xl overflow-hidden aspect-square shadow-xl cursor-pointer bg-gray-900 flex items-center justify-center">
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">Men's Grooming</h3>
                        <p class="text-sm text-gray-400">Sharp & Clean</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-24 bg-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="absolute -top-20 -left-20 w-64 h-64 bg-pink-200 rounded-full blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-purple-200 rounded-full blur-3xl opacity-50"></div>
                    <div class="relative glass rounded-[40px] p-8 shadow-2xl border border-white/40">
                        <img src="/assets/img/hero.png" class="rounded-[30px] w-full shadow-lg" alt="Service">
                        <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-xl border border-gray-100 max-w-xs animate-bounce-slow">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="text-xs font-bold text-gray-500 uppercase">Live Update</span>
                            </div>
                            <p class="text-sm font-semibold">"Your professional is on the way. Arriving in 12 mins."</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-4xl font-bold mb-8 leading-tight">The Best Salon Experience <br> <span class="text-pink-600">Now at your home.</span></h2>
                    <div class="space-y-8">
                        <div class="flex gap-6">
                            <div class="w-14 h-14 shrink-0 bg-white rounded-2xl flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold mb-1">Certified Professionals</h4>
                                <p class="text-gray-500">Every staff member goes through a rigorous 5-step background check and skill test.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="w-14 h-14 shrink-0 bg-white rounded-2xl flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold mb-1">Real-time Tracking</h4>
                                <p class="text-gray-500">Track your beautician's live location and get notified on every step of the service.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="w-14 h-14 shrink-0 bg-white rounded-2xl flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold mb-1">Secure Online Payment</h4>
                                <p class="text-gray-500">Safe and seamless payments via UPI, Cards, or Wallet. 100% money back guarantee.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-gradient-to-r from-purple-700 to-pink-600 rounded-[40px] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full -mr-48 -mt-48 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-black/10 rounded-full -ml-48 -mb-48 blur-3xl"></div>
                
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-8 relative z-10">Ready for your transformation?</h2>
                <p class="text-pink-100 text-lg mb-12 max-w-xl mx-auto relative z-10 opacity-90">Join over 10,000+ happy customers who trust Easy Saloon for their grooming needs.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 relative z-10">
                    <a href="{{ route('register') }}" class="bg-white text-gray-900 px-10 py-4 rounded-full font-bold hover:scale-105 transition-all">Get Started Free</a>
                    <a href="#" class="border border-white/30 text-white px-10 py-4 rounded-full font-bold hover:bg-white/10 transition-all">Download App</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-tr from-purple-600 to-pink-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758L6.243 17.757M12 12L15.758 15.758M12 12l-2.879-2.879"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">Easy Saloon</span>
                </div>
                <p class="text-gray-400 text-sm">© 2026 Easy Saloon. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="text-gray-400 hover:text-pink-600"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                    <a href="#" class="text-gray-400 hover:text-pink-600"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
