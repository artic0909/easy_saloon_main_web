@extends('frontend.layout.app')

@section('page_title', 'Professional Salon Services at Home')
@section('meta_description', 'Explore our wide range of luxury salon services available at your home. From facials to hair styling, our certified professionals bring the spa to you.')

@section('content')
<div x-data="{ 
    showFilter: false, 
    selectedCategories: {{ json_encode(collect(request('category', []))->flatten()->all()) }},
    isCategorySelected(slug) {
        return this.selectedCategories.includes(slug);
    }
}" 
@update-categories.window="selectedCategories = $event.detail"
class="pt-32 pb-24 bg-[#fdfbf7]">
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
                @if(request('category'))
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
                                <button type="button" @click="selectedCategories = []; $nextTick(() => $el.closest('form').requestSubmit())" class="text-[10px] font-bold text-[#c6a664] uppercase tracking-widest hover:opacity-70 transition-opacity">Reset</button>
                            </div>
                            
                            <!-- Categories -->
                            <div class="mb-5">
                                <h6 class="text-[11px] font-black uppercase text-gray-400 mb-5 tracking-widest border-b border-gray-50 pb-2">Categories</h6>
                                <div class="flex flex-col gap-4">
                                    @foreach($categories as $cat)
                                        <label class="flex items-center gap-3 cursor-pointer group custom-checkbox">
                                            <input type="checkbox" name="category[]" value="{{ $cat->slug }}" 
                                                class="hidden peer"
                                                x-model="selectedCategories"
                                                @change="$nextTick(() => $el.form.requestSubmit())">
                                            <div class="w-5 h-5 rounded-md border-2 border-gray-200 flex items-center justify-center peer-checked:bg-[#c6a664] peer-checked:border-[#c6a664] transition-all duration-300">
                                                <svg class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-600 group-hover:text-[#3d2b1f] transition-colors">{{ $cat->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </form>
                </aside>
            </div>

            <!-- Main Listing -->
            <div class="flex-1" id="services-container">
                @include('frontend.services.partials.service_list')
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filter-form');
        
        function fetchServices(url, push = true) {
            const container = document.getElementById('services-container');
            if (!container) return;

            container.style.transition = 'opacity 0.3s ease';
            container.style.opacity = '0.5';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                container.innerHTML = data.html;
                container.style.opacity = '1';

                if (push) {
                    history.pushState(null, '', url);
                }

                // Smooth scroll back to top of container/listing
                container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            })
            .catch(error => {
                console.error('Error fetching services:', error);
                container.style.opacity = '1';
            });
        }

        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const action = filterForm.getAttribute('action') || window.location.pathname;
                const newUrl = `${action}?${params.toString()}`;
                
                fetchServices(newUrl);
            });
        }

        // Intercept clicks on pagination and clear/reset filter links inside the container
        const servicesContainer = document.getElementById('services-container');
        if (servicesContainer) {
            servicesContainer.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link) {
                    const href = link.getAttribute('href');
                    if (href) {
                        try {
                            const url = new URL(href, window.location.origin);
                            const pathname = url.pathname.replace(/\/$/, '');
                            
                            // Only intercept if it's the services index route (including pagination or clearing all filters)
                            if (pathname === '/services') {
                                e.preventDefault();
                                fetchServices(href);
                                
                                // Update Alpine JS category filter state
                                const categories = url.searchParams.getAll('category[]');
                                window.dispatchEvent(new CustomEvent('update-categories', { detail: categories }));
                            }
                        } catch (err) {
                            console.error('Error parsing URL:', err);
                        }
                    }
                }
            });
        }

        // Handle back/forward navigation
        window.addEventListener('popstate', function() {
            try {
                const url = new URL(window.location.href);
                fetchServices(window.location.href, false);
                
                // Keep checkbox inputs in sync
                const categories = url.searchParams.getAll('category[]');
                window.dispatchEvent(new CustomEvent('update-categories', { detail: categories }));
            } catch (err) {
                console.error('Error on popstate navigation:', err);
            }
        });
    });
</script>
@endpush
