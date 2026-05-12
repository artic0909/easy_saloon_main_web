@extends('frontend.layout.app')

@section('content')
<div class="pt-32 md:pt-48 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-20">
            <!-- Left: Package Details -->
            <div class="flex-1 space-y-12">
                <div>
                    <div class="inline-block bg-[#c6a664]/10 text-[#c6a664] px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest mb-8">
                        Exclusive Package
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-[#3d2b1f] leading-tight mb-6" style="font-family: 'Playfair Display', serif;">
                        {{ $package->name }}
                    </h1>
                    <div class="text-gray-500 text-lg md:text-xl leading-relaxed max-w-2xl prose prose-slate">
                        {!! $package->details !!}
                    </div>
                </div>

                <!-- Included Services -->
                <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-sm border border-gray-100">
                    <h3 class="text-2xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">What's Included</h3>
                    <div class="grid sm:grid-cols-2 gap-6">
                        @foreach($package->items as $item)
                            <div class="flex items-center gap-5 p-5 rounded-3xl bg-[#fdfbf7] border border-gray-50 group hover:bg-white hover:shadow-xl transition-all duration-500">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#c6a664] shadow-sm group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <h5 class="font-bold text-[#3d2b1f]">{{ $item->service->name }}</h5>
                                    <p class="text-xs text-gray-400 font-medium">Full Professional Service</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Booking Widget -->
            <div class="w-full lg:w-[450px]">
                <div class="bg-[#3d2b1f] rounded-[3rem] p-10 md:p-12 shadow-2xl sticky top-40" 
                    x-data="{ 
                        serviceType: 'home',
                        selectedEquipments: [],
                        toggleEquipment(name) {
                            if (this.selectedEquipments.includes(name)) {
                                this.selectedEquipments = this.selectedEquipments.filter(e => e !== name);
                            } else {
                                this.selectedEquipments.push(name);
                            }
                        }
                    }">
                    <!-- Price & Duration -->
                    <div class="flex justify-between items-end mb-10 pb-8 border-b border-white/10">
                        <div>
                            <p class="text-[#c6a664] text-[10px] font-black uppercase tracking-widest mb-1">Package Price</p>
                            <div class="flex items-baseline gap-3">
                                <span class="text-4xl font-black text-white">₹{{ number_format($package->sale_price, 0) }}</span>
                                <span class="text-sm text-white/30 line-through">₹{{ number_format($package->original_price, 0) }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-1">Total Duration</p>
                            <span class="text-lg font-bold text-white">2-3 Hours</span>
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

                    <!-- Equipment Selection (Service-wise) -->
                    <div class="mb-10 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-4">Required Equipments (Optional)</p>
                        @foreach($package->items as $item)
                            @if($item->service && $item->service->subCategory && $item->service->subCategory->equipment->count() > 0)
                                <div class="mb-6 last:mb-0">
                                    <p class="text-white/60 text-[10px] font-bold mb-3 border-l-2 border-[#c6a664] pl-2 uppercase tracking-wide">{{ $item->service->name }}</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($item->service->subCategory->equipment as $eq)
                                            <button type="button" 
                                                @click="toggleEquipment('{{ $eq->name }}')"
                                                :class="selectedEquipments.includes('{{ $eq->name }}') ? 'bg-[#c6a664] text-white border-[#c6a664]' : 'bg-white/5 text-white/40 border-white/10 hover:border-white/30'"
                                                class="px-3 py-1.5 rounded-xl text-[9px] font-bold border transition-all">
                                                {{ $eq->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

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
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-white/40 text-[10px] font-black uppercase tracking-widest">Select Date & Slot</p>
                            <div class="relative">
                                <input type="text" x-ref="datePicker" class="bg-transparent text-[#c6a664] text-xs font-bold border-none p-0 focus:ring-0 cursor-pointer text-right w-24" placeholder="Select Date">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <template x-for="date in [
                                { label: 'Today', value: '{{ date('Y-m-d') }}' },
                                { label: 'Tomorrow', value: '{{ date('Y-m-d', strtotime('+1 day')) }}' }
                            ]">
                                <button @click="selectedDate = date.value; $refs.datePicker._flatpickr.setDate(date.value)" :class="selectedDate === date.value ? 'bg-[#c6a664] text-white border-[#c6a664]' : 'bg-white/5 text-white/60 border-white/5'" class="py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all" x-text="date.label"></button>
                            </template>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="slot in ['Morning', 'Afternoon', 'Evening']">
                                <button @click="selectedSlot = slot" :class="selectedSlot === slot ? 'bg-[#c6a664] text-white border-[#c6a664]' : 'bg-white/5 text-white/60 border-white/5'" class="py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all" x-text="slot"></button>
                            </template>
                        </div>

                        <!-- Hidden fields for the form -->
                        <form id="bookingForm" action="{{ route('checkout') }}" method="GET" class="hidden">
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <input type="hidden" name="type" :value="serviceType">
                            <input type="hidden" name="date" :value="selectedDate">
                            <input type="hidden" name="slot" :value="selectedSlot">
                            <input type="hidden" name="equipment" :value="JSON.stringify(selectedEquipments)">
                        </form>
                    </div>

                    <!-- Action Button -->
                    <button onclick="document.getElementById('bookingForm').submit()" class="w-full bg-[#c6a664] text-white py-5 rounded-[2rem] font-bold text-lg shadow-xl hover:scale-[1.02] transition-all active:scale-[0.98]">
                        Book Package Now
                    </button>
                    
                    <p class="text-center text-white/30 text-[10px] font-bold uppercase mt-8 tracking-widest">Secure Checkout Powered by Easy Saloon</p>
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
        z-index: 999 !important; /* Below sticky header which is usually 1000+ */
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
    .flatpickr-prev-month:hover svg, .flatpickr-next-month:hover svg {
        fill: white !important;
    }
    
    /* Responsive adjustment */
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
