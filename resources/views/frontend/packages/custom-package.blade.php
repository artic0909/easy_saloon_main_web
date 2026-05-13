@extends('frontend.layout.app')

@section('content')
<div x-data="customPackage()" class="bg-[#fdfbf7] min-h-screen selection:bg-[#c6a664]/30 pb-32 xl:pb-0">
    
    <!-- Cinematic Hero Section -->
    <div class="relative pt-32 pb-16 md:pt-48 md:pb-32 overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full opacity-[0.03] pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100" fill="none" stroke="#3d2b1f" stroke-width="0.1" />
            </svg>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
            <div class="inline-block bg-[#c6a664]/10 text-[#c6a664] px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.3em] mb-6 md:mb-8 animate-fade-in">
                Bespoke Beauty
            </div>
            <h1 class="text-4xl md:text-8xl font-black text-[#3d2b1f] mb-6 leading-tight md:leading-[0.9]" style="font-family: 'Playfair Display', serif;">
                Craft Your <span class="text-[#c6a664] italic">Experience</span>
            </h1>
            <p class="max-w-2xl mx-auto text-gray-500 text-xs md:text-lg font-medium leading-relaxed px-4">
                A personalized sanctuary where you select every detail. Curate your perfect session from our premium suite of professional services.
            </p>
        </div>
    </div>

    <!-- Main Interface -->
    <div class="max-w-[1600px] mx-auto px-4 md:px-12 pb-24 md:pb-32">
        <div class="flex flex-col xl:flex-row gap-8 md:gap-12 items-start">
            
            <!-- Left Column: Service Discovery -->
            <div class="flex-1 w-full">
                <!-- Advanced Category Filter -->
                <div class="sticky top-20 md:top-28 z-30 bg-[#fdfbf7]/90 backdrop-blur-md py-4 md:py-6 mb-8 md:mb-12 border-b border-[#3d2b1f]/5">
                    <div class="flex items-center gap-2 md:gap-3 overflow-x-auto pb-2 no-scrollbar">
                        <template x-for="cat in categories" :key="cat.id">
                            <button @click="activeCategoryId = cat.id" 
                                :class="activeCategoryId === cat.id ? 'bg-[#3d2b1f] text-white shadow-xl scale-105 border-[#3d2b1f]' : 'bg-white text-gray-400 hover:text-[#3d2b1f] border-gray-100'"
                                class="px-5 md:px-8 py-3 md:py-4 rounded-2xl md:rounded-3xl font-black text-[9px] md:text-[11px] uppercase tracking-widest transition-all whitespace-nowrap border-2 flex items-center gap-2 md:gap-3 group">
                                <span x-text="cat.name"></span>
                                <span :class="activeCategoryId === cat.id ? 'bg-[#c6a664]' : 'bg-gray-100'" class="w-1.5 h-1.5 rounded-full transition-colors"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Services Grid -->
                <div class="grid md:grid-cols-2 2xl:grid-cols-3 gap-6 md:gap-8">
                    <template x-for="service in filteredServices" :key="service.id">
                        <div class="bg-white rounded-[2.5rem] p-4 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-700 border border-gray-50 group flex flex-col h-full">
                            <!-- Service Image -->
                            <div class="relative h-48 md:h-64 rounded-[2rem] md:rounded-[2.5rem] overflow-hidden mb-6">
                                <img :src="service.image ? '/storage/' + service.image : '{{ asset('assets/img/service-bridal.png') }}'" 
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                    onerror="this.src='{{ asset('assets/img/service-bridal.png') }}'">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#3d2b1f]/40 to-transparent"></div>
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-md px-4 py-2 rounded-2xl text-[10px] md:text-[11px] font-black text-[#3d2b1f] shadow-xl">
                                    ₹<span x-text="parseInt(service.sale_price)"></span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="px-2 md:px-4 pb-4 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-3 md:mb-4">
                                    <h3 class="text-base md:text-xl font-bold text-[#3d2b1f] group-hover:text-[#c6a664] transition-colors leading-tight" x-text="service.name"></h3>
                                    <span class="text-[8px] md:text-[10px] font-black text-gray-300 uppercase tracking-widest whitespace-nowrap ml-2" x-text="(service.duration_minutes || 0) + ' min'"></span>
                                </div>
                                <p class="text-[10px] md:text-xs text-gray-400 mb-6 md:mb-8 line-clamp-2 leading-relaxed" x-text="stripHtml(service.details)"></p>
                                
                                <!-- Equipment Selector -->
                                <div class="mb-6 md:mb-8 mt-auto" x-show="service.sub_category?.equipment?.length > 0">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="h-[1px] flex-1 bg-gray-100"></div>
                                        <span class="text-[8px] md:text-[9px] font-black text-gray-300 uppercase tracking-widest">Equipments</span>
                                        <div class="h-[1px] flex-1 bg-gray-100"></div>
                                    </div>
                                    <div class="flex flex-wrap gap-1.5 md:gap-2">
                                        <template x-for="eq in service.sub_category.equipment" :key="eq.id">
                                            <button @click="toggleEquipment(service.id, eq.name)"
                                                :class="isSelectedEquipment(service.id, eq.name) ? 'bg-[#c6a664] text-white border-[#c6a664] shadow-md' : 'bg-gray-50 text-gray-400 border-gray-50 hover:border-gray-200'"
                                                class="px-3 md:px-4 py-1.5 md:py-2 rounded-xl md:rounded-2xl text-[8px] md:text-[10px] font-bold border transition-all"
                                                x-text="eq.name">
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <button @click="addService(service)" 
                                    :disabled="isServiceSelected(service.id)"
                                    :class="isServiceSelected(service.id) ? 'bg-[#c6a664]/10 text-[#c6a664]' : 'bg-[#3d2b1f] text-white hover:bg-[#c6a664] shadow-xl shadow-black/5'"
                                    class="w-full py-4 md:py-5 rounded-2xl md:rounded-[2rem] font-bold text-[10px] md:text-xs uppercase tracking-widest transition-all flex items-center justify-center gap-3">
                                    <template x-if="isServiceSelected(service.id)">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </template>
                                    <template x-if="!isServiceSelected(service.id)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m6-6H6"></path></svg>
                                    </template>
                                    <span x-text="isServiceSelected(service.id) ? 'Selected' : 'Add to Package'"></span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right Column: Premium Booking Card -->
            <div id="booking-card" class="w-full xl:w-[480px] xl:sticky xl:top-32 mt-12 xl:mt-0">
                <div class="bg-[#3d2b1f] rounded-[3rem] md:rounded-[4rem] p-8 md:p-14 shadow-2xl relative overflow-hidden">
                    <!-- Accent Pattern -->
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-[#c6a664]/5 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-black/20 rounded-full blur-3xl"></div>

                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-10 md:mb-12">
                            <div>
                                <h4 class="text-2xl md:text-3xl font-bold text-white leading-none mb-3" style="font-family: 'Playfair Display', serif;">Your Session</h4>
                                <p class="text-[#c6a664] text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em]">Personalized Package</p>
                            </div>
                            <div class="w-12 h-12 md:w-16 md:h-16 bg-white/5 rounded-2xl md:rounded-3xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 md:w-8 md:h-8 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                        </div>

                        <!-- Selected Items Preview -->
                        <div class="mb-10 md:mb-12">
                            <div x-show="selectedServices.length === 0" class="py-10 md:py-12 border-2 border-dashed border-white/10 rounded-[2rem] md:rounded-[2.5rem] text-center">
                                <p class="text-white/30 text-[10px] md:text-xs font-medium px-4">Select services to begin your luxury journey</p>
                            </div>
                            
                            <div class="space-y-3 md:space-y-4 max-h-64 md:max-h-80 overflow-y-auto pr-2 md:pr-4 custom-scrollbar-white" x-show="selectedServices.length > 0">
                                <template x-for="(service, index) in selectedServices" :key="service.id">
                                    <div class="bg-white/5 rounded-2xl md:rounded-3xl p-4 md:p-5 border border-white/5 flex items-center gap-4 md:gap-5 group animate-fade-in relative">
                                        <button @click="removeService(index)" 
                                            class="absolute -top-2 -right-2 w-8 h-8 bg-white text-[#3d2b1f] rounded-full flex items-center justify-center shadow-[0_4px_20px_rgba(0,0,0,0.3)] z-20 group-hover:scale-110 transition-all border border-gray-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <img :src="service.image" class="w-12 h-12 md:w-14 md:h-14 rounded-xl md:rounded-2xl object-cover shadow-2xl">
                                        <div class="flex-1 min-w-0">
                                            <h6 class="text-xs md:text-sm font-bold text-white truncate" x-text="service.name"></h6>
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-[10px] md:text-xs font-black text-[#c6a664]" x-text="'₹' + service.price"></span>
                                                <span class="text-[8px] md:text-[9px] text-white/40 font-bold" x-text="service.duration + ' min'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Session Configuration -->
                        <div class="space-y-8 md:space-y-10 mb-10 md:mb-12" x-show="selectedServices.length > 0">
                            <!-- Location -->
                            <div>
                                <label class="text-white/30 text-[9px] md:text-[10px] font-black uppercase tracking-widest mb-5 md:mb-6 block">Service Location</label>
                                <div class="grid grid-cols-2 gap-3 md:gap-4">
                                    <button @click="booking.type = 'home'" :class="booking.type === 'home' ? 'bg-[#c6a664] text-white shadow-xl' : 'bg-white/5 text-white/40 hover:bg-white/10'" class="py-4 md:py-5 rounded-2xl md:rounded-[2rem] font-bold text-[10px] md:text-xs transition-all flex items-center justify-center gap-2 md:gap-3">
                                        <i class="bi bi-house-door"></i> Home
                                    </button>
                                    <button @click="booking.type = 'salon'" :class="booking.type === 'salon' ? 'bg-[#c6a664] text-white shadow-xl' : 'bg-white/5 text-white/40 hover:bg-white/10'" class="py-4 md:py-5 rounded-2xl md:rounded-[2rem] font-bold text-[10px] md:text-xs transition-all flex items-center justify-center gap-2 md:gap-3">
                                        <i class="bi bi-shop"></i> Salon
                                    </button>
                                </div>
                            </div>

                            <!-- DateTime -->
                            <div>
                                <div class="flex justify-between items-center mb-5 md:mb-6">
                                    <label class="text-white/30 text-[9px] md:text-[10px] font-black uppercase tracking-widest">Date & Slot</label>
                                    <div class="flex items-center gap-2 text-[#c6a664]" 
                                        x-init="flatpickr($refs.datePicker, {
                                            minDate: 'today',
                                            defaultDate: 'today',
                                            theme: 'dark',
                                            dateFormat: 'Y-m-d',
                                            onChange: (selectedDates, dateStr) => {
                                                booking.date = dateStr;
                                            }
                                        })">
                                        <i class="bi bi-calendar3 text-[9px] md:text-[10px]"></i>
                                        <input type="text" x-ref="datePicker" readonly 
                                            class="bg-transparent text-[#c6a664] text-[11px] md:text-sm font-black border-none p-0 focus:ring-0 cursor-pointer text-right w-24 md:w-32">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <template x-for="date in [
                                        { label: 'Today', value: '{{ date('Y-m-d') }}' },
                                        { label: 'Tomorrow', value: '{{ date('Y-m-d', strtotime('+1 day')) }}' }
                                    ]">
                                        <button @click="booking.date = date.value; $refs.datePicker._flatpickr.setDate(date.value)" 
                                            :class="booking.date === date.value ? 'bg-[#c6a664] text-white shadow-xl' : 'bg-white/5 text-white/40 hover:bg-white/10'" 
                                            class="py-3 rounded-2xl text-[8px] md:text-[9px] font-black uppercase tracking-widest transition-all" x-text="date.label"></button>
                                    </template>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <template x-for="slot in ['Morning', 'Afternoon', 'Evening']">
                                        <button @click="booking.slot = slot" :class="booking.slot === slot ? 'bg-white text-[#3d2b1f] shadow-xl' : 'bg-white/5 text-white/40 hover:bg-white/10'" class="py-3.5 md:py-4 rounded-xl md:rounded-2xl text-[8px] md:text-[9px] font-black uppercase tracking-widest transition-all" x-text="slot"></button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="bg-black/20 rounded-[2.5rem] md:rounded-[3rem] p-8 md:p-10 mb-10 border border-white/5">
                            <div class="flex justify-between items-center mb-5 md:mb-6">
                                <span class="text-white/40 text-[9px] md:text-[10px] font-bold uppercase tracking-widest">Items</span>
                                <span class="text-white font-black text-lg md:text-xl" x-text="selectedServices.length"></span>
                            </div>
                            <div class="flex justify-between items-center mb-5 md:mb-6 pt-5 md:pt-6 border-t border-white/5">
                                <span class="text-white/40 text-[9px] md:text-[10px] font-bold uppercase tracking-widest">Time</span>
                                <span class="text-white font-black text-lg md:text-xl" x-text="totalDuration + ' min'"></span>
                            </div>
                            <div class="flex justify-between items-center pt-6 md:pt-8 border-t border-white/10">
                                <span class="text-[#c6a664] font-black text-[10px] md:text-xs uppercase tracking-widest">Total</span>
                                <div class="text-right">
                                    <span class="text-white font-black text-3xl md:text-5xl block leading-none" x-text="'₹' + totalPrice"></span>
                                    <span class="text-white/20 text-[8px] md:text-[9px] font-bold uppercase mt-2 block tracking-widest">Inc. Taxes</span>
                                </div>
                            </div>
                        </div>

                        <button @click="submitBooking" :disabled="selectedServices.length === 0" 
                            class="w-full bg-[#c6a664] text-white py-6 md:py-8 rounded-[1.5rem] md:rounded-[2.5rem] font-black text-sm md:text-lg uppercase tracking-widest shadow-2xl hover:bg-[#b5955a] hover:scale-[1.02] transition-all disabled:opacity-20 disabled:grayscale active:scale-[0.98]">
                            Book Package
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Floating Summary Bar -->
    <div x-show="selectedServices.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed bottom-6 left-4 right-4 z-[60] xl:hidden">
        <div class="bg-[#3d2b1f] rounded-[2rem] p-4 shadow-2xl border border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="w-12 h-12 bg-[#c6a664]/20 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l3 3 3-3"></path>
                        </svg>
                    </div>
                    <span class="absolute -top-2 -right-2 bg-[#c6a664] text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center shadow-lg" x-text="selectedServices.length"></span>
                </div>
                <div>
                    <p class="text-white font-black text-lg leading-none" x-text="'₹' + totalPrice"></p>
                    <p class="text-[#c6a664] text-[8px] font-black uppercase tracking-widest mt-1" x-text="totalDuration + ' min total'"></p>
                </div>
            </div>
            <button @click="scrollToBooking()" class="bg-[#c6a664] text-white px-8 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg active:scale-95 transition-all">
                Finalize
            </button>
        </div>
    </div>

    <!-- Hidden Form for Checkout -->
    <form id="customBookingForm" action="{{ route('packages.custom.checkout') }}" method="GET" class="hidden">
        <input type="hidden" name="booking_title" value="Custom Package">
        <input type="hidden" name="service_ids" :value="JSON.stringify(selectedServices.map(s => s.id))">
        <input type="hidden" name="equipments" :value="JSON.stringify(allSelectedEquipments)">
        <input type="hidden" name="date" :value="booking.date">
        <input type="hidden" name="slot" :value="booking.slot">
        <input type="hidden" name="type" :value="booking.type">
        <input type="hidden" name="is_custom" value="1">
    </form>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    [x-cloak] { display: none !important; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(61, 43, 31, 0.1); border-radius: 10px; }
    
    .custom-scrollbar-white::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar-white::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar-white::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .flatpickr-calendar {
        background: #3d2b1f !important;
        border: 1px solid rgba(198, 166, 100, 0.3) !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7) !important;
        border-radius: 2rem !important;
        padding: 15px !important;
        width: 300px !important;
        font-family: 'Outfit', sans-serif !important;
        z-index: 9999 !important;
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

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endpush

@push('scripts')
<script>
    function customPackage() {
        return {
            categories: @json($categories),
            activeCategoryId: @json($categories->first()->id ?? null),
            selectedServices: [],
            serviceEquipments: {},
            booking: {
                date: '{{ date('Y-m-d') }}',
                slot: 'Morning',
                type: 'home'
            },

            init() {
                // Flatpickr is now handled by x-init on the element to match service page exactly
            },

            scrollToBooking() {
                document.getElementById('booking-card').scrollIntoView({ behavior: 'smooth', block: 'center' });
            },

            get filteredServices() {
                const category = this.categories.find(c => c.id === this.activeCategoryId);
                return category ? category.services : [];
            },

            get totalPrice() {
                return this.selectedServices.reduce((sum, s) => sum + parseInt(s.price), 0);
            },

            get totalDuration() {
                return this.selectedServices.reduce((sum, s) => sum + (parseInt(s.duration) || 0), 0);
            },

            get allSelectedEquipments() {
                return this.serviceEquipments;
            },

            addService(service) {
                if (this.isServiceSelected(service.id)) return;
                
                const selectedEqs = this.serviceEquipments[service.id] || [];
                
                this.selectedServices.push({
                    id: service.id,
                    name: service.name,
                    price: service.sale_price,
                    image: service.image_url ? '/' + service.image_url : '{{ asset('assets/img/service-bridal.png') }}',
                    duration: service.duration_minutes || 0,
                    selectedEquipments: [...selectedEqs]
                });
            },

            removeService(index) {
                this.selectedServices.splice(index, 1);
            },

            isServiceSelected(id) {
                return this.selectedServices.some(s => s.id === id);
            },

            toggleEquipment(serviceId, equipmentName) {
                if (!this.serviceEquipments[serviceId]) {
                    this.serviceEquipments[serviceId] = [];
                }
                
                const index = this.serviceEquipments[serviceId].indexOf(equipmentName);
                if (index > -1) {
                    this.serviceEquipments[serviceId].splice(index, 1);
                } else {
                    this.serviceEquipments[serviceId].push(equipmentName);
                }

                const selectedService = this.selectedServices.find(s => s.id === serviceId);
                if (selectedService) {
                    selectedService.selectedEquipments = [...this.serviceEquipments[serviceId]];
                }
            },

            isSelectedEquipment(serviceId, name) {
                return (this.serviceEquipments[serviceId] || []).includes(name);
            },

            stripHtml(html) {
                let tmp = document.createElement("DIV");
                tmp.innerHTML = html;
                return tmp.textContent || tmp.innerText || "";
            },

            submitBooking() {
                if (this.selectedServices.length === 0) return;
                document.getElementById('customBookingForm').submit();
            }
        }
    }
</script>
@endpush
@endsection
