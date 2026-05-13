<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title', 'Luxury Grooming at Home') | Easy Saloon</title>
    <meta name="description" content="@yield('meta_description', 'Experience premium salon services at the comfort of your home with Easy Saloon. Professional grooming, bridal packages, and bespoke beauty treatments.')">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#fdfbf7] text-[#2c1e14] overflow-x-hidden">
    
    @include('frontend.includes.header')
    
    <main>
        @yield('content')
    </main>

    @include('frontend.includes.footer')
    
    @include('frontend.includes.scripts')
    @stack('scripts')
</body>
</html>
