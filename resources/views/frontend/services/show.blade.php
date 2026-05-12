@extends('frontend.layout.app')

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
                    @if($service->image)
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
                    @if($service->subCategory && $service->subCategory->equipment->count() > 0)
                    <div class="mb-10">
                        <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-4">Required Equipment (Optional)</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($service->subCategory->equipment as $eq)
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
                    <div class="mb-10" x-data="{ selectedDate: '{{ date('Y-m-d') }}', selectedSlot: 'Morning' }">
                        <div class="flex justify-between items-center mb-5">
                            <p class="text-white/40 text-[10px] font-black uppercase tracking-widest">Select Date & Slot</p>
                            <input type="date" x-model="selectedDate" min="{{ date('Y-m-d') }}" class="bg-transparent text-[#c6a664] text-xs font-black border-none p-0 focus:ring-0 cursor-pointer">
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <template x-for="date in [
                                { label: 'Today', value: '{{ date('Y-m-d') }}' },
                                { label: 'Tomorrow', value: '{{ date('Y-m-d', strtotime('+1 day')) }}' }
                            ]">
                                <button @click="selectedDate = date.value" :class="selectedDate === date.value ? 'bg-[#c6a664] text-white border-[#c6a664]' : 'bg-white/5 text-white/60 border-transparent'" class="py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border transition-all" x-text="date.label"></button>
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
                    
                    <p class="text-center text-white/20 text-[10px] font-bold uppercase mt-8 tracking-widest">Secure Checkout Powered by Easy Saloon</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
