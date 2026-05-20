@extends('frontend.layout.app')

@section('page_title', $service->name)
@section('meta_description', 'Book ' . $service->name . ' at home. ' . Str::limit(strip_tags($service->details), 150))

@section('content')
<div class="pt-32 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mb-12">
            <a href="{{ route('home') }}" class="hover:text-[#3d2b1f]">Home</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <a href="{{ route('services.index') }}" class="hover:text-[#3d2b1f]">Services</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#3d2b1f]">{{ $service->name }}</span>
        </div>

        <div class="grid lg:grid-cols-12 gap-16">
            <!-- Left: Service Info -->
            <div class="lg:col-span-7">
                <div class="relative rounded-[3rem] overflow-hidden shadow-2xl mb-12 aspect-video group">
                    @if(!empty($service->images) && is_array($service->images) && count($service->images) > 0)
                        <div x-data="{ 
                            activeSlide: 0, 
                            slides: {{ json_encode($service->images) }},
                            autoPlayInterval: null,
                            next() {
                                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                            },
                            prev() {
                                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                            },
                            startAutoPlay() {
                                this.autoPlayInterval = setInterval(() => {
                                    this.next();
                                }, 3000);
                            },
                            stopAutoPlay() {
                                clearInterval(this.autoPlayInterval);
                            }
                        }" 
                        x-init="startAutoPlay()"
                        @mouseenter="stopAutoPlay()"
                        @mouseleave="startAutoPlay()"
                        class="absolute inset-0 w-full h-full">
                            
                            <!-- Slide Tracks -->
                            <div class="relative w-full h-full">
                                <template x-for="(slide, index) in slides" :key="index">
                                    <div x-show="activeSlide === index" 
                                         x-transition:enter="transition ease-out duration-700"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-700"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="absolute inset-0 w-full h-full">
                                        <img :src="'/storage/' + slide" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Service Image">
                                    </div>
                                </template>
                            </div>

                            <!-- Next/Prev Buttons (only if slides > 1) -->
                            <template x-if="slides.length > 1">
                                <div class="absolute inset-y-0 inset-x-5 flex justify-between items-center pointer-events-none z-10">
                                    <button @click.prevent="prev()" class="pointer-events-auto w-10 h-10 bg-white/80 hover:bg-white backdrop-blur-sm rounded-full flex items-center justify-center text-[#3d2b1f] hover:text-[#c6a664] transition-all duration-300 shadow-lg">
                                        <i class="bi bi-chevron-left text-sm font-black"></i>
                                    </button>
                                    <button @click.prevent="next()" class="pointer-events-auto w-10 h-10 bg-white/80 hover:bg-white backdrop-blur-sm rounded-full flex items-center justify-center text-[#3d2b1f] hover:text-[#c6a664] transition-all duration-300 shadow-lg">
                                        <i class="bi bi-chevron-right text-sm font-black"></i>
                                    </button>
                                </div>
                            </template>

                            <!-- Indicators (only if slides > 1) -->
                            <template x-if="slides.length > 1">
                                <div class="absolute bottom-5 right-10 flex gap-2 z-20">
                                    <template x-for="(slide, index) in slides" :key="index">
                                        <button @click.prevent="activeSlide = index" 
                                                class="w-2 h-2 rounded-full transition-all duration-300"
                                                :class="activeSlide === index ? 'bg-[#c6a664] w-5' : 'bg-white/50 hover:bg-white/80'"></button>
                                    </template>
                                </div>
                            </template>
                        </div>
                    @elseif($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
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
                        <img src="{{ $bgImage }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-10 left-10 text-white">
                        <span class="bg-[#c6a664] px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 inline-block shadow-lg">
                            {{ $service->category->name }}
                        </span>
                        <h1 class="text-4xl md:text-5xl font-extrabold" style="font-family: 'Playfair Display', serif;">{{ $service->name }}</h1>
                    </div>
                </div>

                <div class="space-y-12">
                    <!-- Description -->
                    <section>
                        <h3 class="text-2xl font-bold text-[#3d2b1f] mb-6" style="font-family: 'Playfair Display', serif;">About the Service</h3>
                        <div class="text-gray-500 leading-relaxed text-lg prose prose-stone">
                            {!! $service->details !!}
                            <p class="mt-4">Our expert professionals bring the luxury salon experience directly to your home. We use only premium, dermatologically tested products to ensure the best results for your skin and hair.</p>
                        </div>
                    </section>

                    <!-- What's Included -->
                    <section class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-50">
                        <h3 class="text-xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">What's Included?</h3>
                        <div class="grid sm:grid-cols-2 gap-8">
                            @if($service->what_included && is_array($service->what_included))
                                @foreach($service->what_included as $item)
                                    <div class="flex items-start gap-4 group">
                                        <div class="w-10 h-10 bg-[#fdfbf7] rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#c6a664] group-hover:text-white transition-all duration-300">
                                            <svg class="w-5 h-5 text-[#c6a664] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700 pt-2">{{ $item }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-[#fdfbf7] rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 pt-2">Professional Consultation</span>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-[#fdfbf7] rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 pt-2">Certified Professional</span>
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            <!-- Right: Booking Widget -->
            <div class="lg:col-span-5">
                <div class="bg-[#3d2b1f] rounded-[3rem] p-10 shadow-2xl sticky top-32" x-data="{ 
                    serviceType: 'home', 
                    selectedEquipments: [],
                    toggleEquipment(name) {
                        if (this.selectedEquipments.includes(name)) {
                            this.selectedEquipments = this.selectedEquipments.filter(i => i !== name);
                        } else {
                            this.selectedEquipments.push(name);
                        }
                    }
                }">
                    <!-- Price & Duration -->
                    <div class="flex justify-between items-end mb-10 pb-8 border-b border-white/10">
                        <div>
                            <p class="text-[#c6a664] text-[10px] font-black uppercase tracking-widest mb-1">Estimated Price</p>
                            <span class="text-4xl font-black text-white">₹{{ number_format($service->sale_price, 2) }}</span>
                            @if($service->original_price > $service->sale_price)
                                <div class="text-xs text-white/30 line-through mt-1">₹{{ number_format($service->original_price, 2) }}</div>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-1">Duration</p>
                            <span class="text-lg font-bold text-white">{{ $service->duration_minutes }} Mins</span>
                        </div>
                    </div>

                    <!-- Service Type Selection -->
                    <div class="mb-10">
                        <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-4">Choose Service Location</p>
                        <div class="grid grid-cols-2 gap-4">
                            <button @click="serviceType = 'home'" :class="serviceType === 'home' ? 'bg-[#c6a664] text-white shadow-lg' : 'bg-white/5 text-white/60 hover:bg-white/10'" class="py-5 rounded-3xl font-bold text-sm transition-all flex flex-col items-center gap-3 group">
                                <svg class="w-7 h-7" :class="serviceType === 'home' ? 'text-white' : 'text-white/40 group-hover:text-white/60'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                At Home
                            </button>
                            <button @click="serviceType = 'salon'" :class="serviceType === 'salon' ? 'bg-[#c6a664] text-white shadow-lg' : 'bg-white/5 text-white/60 hover:bg-white/10'" class="py-5 rounded-3xl font-bold text-sm transition-all flex flex-col items-center gap-3 group">
                                <svg class="w-7 h-7" :class="serviceType === 'salon' ? 'text-white' : 'text-white/40 group-hover:text-white/60'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                At Salon
                            </button>
                        </div>
                    </div>

                    <!-- Equipment Selection -->
                    @if($service->equipment && $service->equipment->count() > 0)
                    <div class="mb-10">
                        <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-4">Required Equipment (Optional)</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($service->equipment as $eq)
                                <button type="button" 
                                    @click="toggleEquipment('{{ $eq->name }}')"
                                    :class="selectedEquipments.includes('{{ $eq->name }}') ? 'bg-[#c6a664] text-white border-[#c6a664]' : 'bg-white/5 text-white/40 border-white/10 hover:border-white/30'"
                                    class="px-4 py-2 rounded-xl text-[10px] font-bold border transition-all">
                                    {{ $eq->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Date & Time Slot -->
                    <div class="mb-10" x-data="{ 
                        selectedDate: '{{ date('Y-m-d') }}', 
                        selectedSlot: 'Morning',
                        init() {
                            flatpickr($refs.datePicker, {
                                minDate: 'today',
                                defaultDate: 'today',
                                theme: 'dark',
                                onChange: (selectedDates, dateStr) => {
                                    this.selectedDate = dateStr;
                                }
                            });
                        }
                    }">
                        <div class="flex justify-between items-center mb-5">
                            <p class="text-white/40 text-[10px] font-black uppercase tracking-widest">Select Date & Slot</p>
                            <div class="relative">
                                <input type="text" x-ref="datePicker" class="bg-transparent text-[#c6a664] text-xs font-black border-none p-0 focus:ring-0 cursor-pointer text-right w-24" placeholder="Select Date">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <template x-for="date in [
                                { label: 'Today', value: '{{ date('Y-m-d') }}' },
                                { label: 'Tomorrow', value: '{{ date('Y-m-d', strtotime('+1 day')) }}' }
                            ]">
                                <button @click="selectedDate = date.value; $refs.datePicker._flatpickr.setDate(date.value)" :class="selectedDate === date.value ? 'bg-[#c6a664] text-white border-[#c6a664]' : 'bg-white/5 text-white/60 border-transparent'" class="py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border transition-all" x-text="date.label"></button>
                            </template>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <template x-for="slot in ['Morning', 'Afternoon', 'Evening']">
                                <button @click="selectedSlot = slot" :class="selectedSlot === slot ? 'bg-[#c6a664] text-white border-[#c6a664]' : 'bg-white/5 text-white/60 border-transparent'" class="py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border transition-all" x-text="slot"></button>
                            </template>
                        </div>

                        <!-- Hidden fields for the form -->
                        <form id="bookingForm" action="{{ route('checkout') }}" method="GET" class="hidden">
                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                            <input type="hidden" name="type" :value="serviceType">
                            <input type="hidden" name="date" :value="selectedDate">
                            <input type="hidden" name="slot" :value="selectedSlot">
                            <input type="hidden" name="equipment" :value="JSON.stringify(selectedEquipments)">
                        </form>
                    </div>

                    <!-- Action Button -->
                    <button onclick="document.getElementById('bookingForm').submit()" class="w-full bg-[#c6a664] text-white py-5 rounded-[2.5rem] font-bold text-lg shadow-2xl hover:bg-[#d4b574] transition-all transform active:scale-95">
                        Book Now
                    </button>

                    <!-- Contact Buttons -->
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <a href="https://wa.me/919999999999?text=Hello%20Esy%20Saloon,%20I%20want%20to%20book%20{{ rawurlencode($service->name) }}%20service." target="_blank" class="w-full bg-white/10 text-white border border-white/20 py-4 rounded-[2rem] font-bold text-sm shadow-lg hover:bg-white/20 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <i class="bi bi-whatsapp text-lg"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a href="tel:919999999999" class="w-full bg-white/10 text-white border border-white/20 py-4 rounded-[2rem] font-bold text-sm shadow-lg hover:bg-white/20 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <i class="bi bi-telephone-fill text-sm"></i>
                            <span>Call Now</span>
                        </a>
                    </div>
                    
                    <p class="text-center text-white/20 text-[10px] font-bold uppercase mt-8 tracking-widest">Secure Checkout Powered by Easy Saloon</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    .flatpickr-calendar {
        background: #3d2b1f !important;
        border: 1px solid rgba(198, 166, 100, 0.3) !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7) !important;
        border-radius: 2rem !important;
        padding: 15px !important;
        width: 300px !important;
        font-family: 'Outfit', sans-serif !important;
        z-index: 999 !important;
    }
    .flatpickr-months {
        margin-bottom: 10px !important;
    }
    .flatpickr-months .flatpickr-month {
        background: transparent !important;
        color: #c6a664 !important;
        fill: #c6a664 !important;
        height: 60px !important;
    }
    .flatpickr-current-month {
        font-size: 1.2rem !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        padding: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        height: auto !important;
        width: 100% !important;
        left: 0 !important;
    }
    .flatpickr-monthDropdown-months {
        font-weight: 800 !important;
        padding: 0 !important;
        margin-bottom: -5px !important;
    }
    .numInputWrapper {
        width: 6ch !important;
        display: inline-block !important;
    }
    .numInputWrapper input.cur-year {
        font-weight: 800 !important;
        color: #c6a664 !important;
        padding: 0 !important;
    }
    .flatpickr-weekday {
        color: rgba(198, 166, 100, 0.5) !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        font-size: 0.7rem !important;
    }
    .flatpickr-day {
        color: white !important;
        border-radius: 12px !important;
        border: none !important;
        font-weight: 500 !important;
        height: 35px !important;
        line-height: 35px !important;
        margin: 2px !important;
    }
    .flatpickr-day:hover {
        background: rgba(198, 166, 100, 0.2) !important;
    }
    .flatpickr-day.selected, .flatpickr-day.selected:hover {
        background: #c6a664 !important;
        color: #3d2b1f !important;
        box-shadow: 0 4px 15px rgba(198, 166, 100, 0.4) !important;
        font-weight: 900 !important;
    }
    .flatpickr-day.today {
        border: 1px solid #c6a664 !important;
    }
    .flatpickr-day.flatpickr-disabled, .flatpickr-day.flatpickr-disabled:hover {
        color: rgba(255, 255, 255, 0.05) !important;
        background: transparent !important;
    }
    .flatpickr-prev-month, .flatpickr-next-month {
        color: #c6a664 !important;
        fill: #c6a664 !important;
        padding: 10px !important;
    }
    
    @media (max-width: 450px) {
        .flatpickr-calendar {
            width: 280px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
        }
    }
</style>
@endpush
@endsection
