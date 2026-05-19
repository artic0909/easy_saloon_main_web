@extends('frontend.layout.app')
@section('page_title', 'My Bookings')
@section('meta_description', 'My Bookings - Easy Saloon')
@section('content')
<div class="pt-40 pb-24 bg-[#fdfbf7]" x-data="{ 
    showModal: false, 
    selectedBooking: null,
    openDetail(booking) {
        this.selectedBooking = booking;
        this.showModal = true;
    }
}">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="flex flex-col lg:flex-row gap-16">
            <!-- Sidebar -->
            <aside class="hidden lg:block w-full lg:w-80 flex-shrink-0">
                @include('frontend.dashboard.includes.sidebar')
            </aside>

            <!-- Main Content -->
            <main class="flex-1 space-y-8 md:space-y-12">
                <section class="bg-white rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-12 shadow-sm border border-gray-100">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                        <h2 class="text-3xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">My Bookings</h2>
                        <div class="flex flex-wrap gap-2 p-1 bg-[#fdfbf7] rounded-2xl border border-gray-50 w-full md:w-auto">
                            <a href="{{ route('dashboard.bookings') }}" class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ !request('filter') ? 'bg-[#3d2b1f] text-white shadow-lg' : 'text-gray-400 hover:text-[#3d2b1f]' }}">All</a>
                            <a href="{{ route('dashboard.bookings', ['filter' => 'upcoming']) }}" class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request('filter') == 'upcoming' ? 'bg-[#3d2b1f] text-white shadow-lg' : 'text-gray-400 hover:text-[#3d2b1f]' }}">Upcoming</a>
                            <a href="{{ route('dashboard.bookings', ['filter' => 'past']) }}" class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request('filter') == 'past' ? 'bg-[#3d2b1f] text-white shadow-lg' : 'text-gray-400 hover:text-[#3d2b1f]' }}">Past</a>
                        </div>
                    </div>
                    
                    <div class="grid gap-6">
                        @forelse($bookings as $booking)
                            <div class="group p-6 md:p-8 bg-[#fdfbf7] rounded-[2rem] md:rounded-[2.5rem] border border-gray-50 hover:bg-white hover:shadow-2xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-500">
                                <div class="flex flex-col md:flex-row justify-between gap-8">
                                    <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-6 md:gap-8">
                                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-3xl flex items-center justify-center text-[#c6a664] shadow-sm flex-shrink-0">
                                            <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3 mb-3">
                                                <span class="text-[10px] md:text-xs font-black text-[#c6a664] uppercase tracking-[0.2em]">#{{ $booking->booking_number }}</span>
                                                <span class="hidden sm:block w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                                                <span class="text-[9px] md:text-[10px] font-black uppercase text-gray-400 tracking-widest">{{ $booking->service_type == 'home' ? 'Home Service' : 'Salon Visit' }}</span>
                                            </div>
                                            <h4 class="text-lg md:text-xl font-bold text-[#3d2b1f] mb-4">
                                                @php
                                                    $isCustom = isset($booking->service_ids);
                                                    if ($isCustom) {
                                                        $title = 'Custom Package';
                                                        $serviceCount = count($booking->service_ids);
                                                    } else {
                                                        $mainItem = $booking->items->where('item_type', 'package')->first() ?? $booking->items->where('item_type', 'service')->first();
                                                        $title = $mainItem ? ($mainItem->item_type == 'package' ? $mainItem->package->name : $mainItem->service->name) : 'Salon Service';
                                                        $serviceCount = $booking->items->count();
                                                    }
                                                @endphp
                                                {{ $title }}
                                                @if($serviceCount > 1)
                                                    <span class="text-xs md:text-sm font-medium text-gray-400"> +{{ $serviceCount - 1 }} more</span>
                                                @endif
                                            </h4>
                                            <div class="flex flex-wrap justify-center sm:justify-start gap-4 md:gap-6">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <span class="text-[11px] md:text-xs font-bold text-[#3d2b1f]">{{ \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y') }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <span class="text-[11px] md:text-xs font-bold text-[#3d2b1f]">{{ $booking->time_slot }}</span>
                                                </div>
                                            </div>
                                            @if($booking->status == 'completed')
                                                <div class="mt-5 pt-4 border-t border-gray-100/50 flex flex-col sm:flex-row items-center gap-3">
                                                    <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Rate Experience:</span>
                                                    <div class="flex items-center gap-1" data-booking-id="{{ $booking->id }}" data-booking-type="{{ $booking->getTable() == 'custom_bookings' ? 'custom_booking' : 'booking' }}" data-rating="{{ $booking->rating ?? 0 }}">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <button onclick="submitRating(this, {{ $i }})" class="star-btn p-1 focus:outline-none transition-transform hover:scale-125" data-index="{{ $i }}">
                                                                <svg class="w-6 h-6 transition-all duration-300 {{ ($booking->rating ?? 0) >= $i ? 'text-amber-400 fill-amber-400' : 'text-gray-300 fill-transparent' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                </svg>
                                                            </button>
                                                        @endfor
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-col justify-between items-center md:items-end gap-6 border-t border-gray-50 pt-6 md:border-none md:pt-0">
                                        <div class="text-center md:text-right space-y-3">
                                            <div class="flex flex-wrap items-center justify-center md:justify-end gap-2">
                                                <span class="inline-block px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest 
                                                    {{ $booking->status == 'completed' ? 'bg-green-100 text-green-600' : ($booking->status == 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-[#c6a664]/10 text-[#c6a664]') }}">
                                                    {{ $booking->status }}
                                                </span>
                                                <span class="inline-block px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-gray-100 text-gray-600">
                                                    {{ strtoupper($booking->pay_type ?? $booking->payment_type ?? 'ONLINE') }}
                                                </span>
                                                <span class="inline-block px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest 
                                                    {{ $booking->is_paid ? 'bg-emerald-500 text-white shadow-sm' : 'bg-amber-500 text-white shadow-sm' }}">
                                                    {{ $booking->is_paid ? 'PAID' : 'UNPAID' }}
                                                </span>
                                            </div>
                                            <h3 class="text-2xl md:text-3xl font-black text-[#3d2b1f] mt-2">₹{{ number_format($booking->payable_amount ?? $booking->total_price, 2) }}</h3>
                                        </div>
                                        <div class="flex flex-wrap justify-center md:justify-end gap-3 w-full sm:w-auto">
                                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                                <button onclick="cancelBooking({{ $booking->id }})" class="flex-1 sm:flex-none px-6 py-3 rounded-xl border border-red-100 text-red-500 text-[10px] font-black uppercase tracking-widest hover:bg-red-50 transition-all">Cancel</button>
                                                <!-- <button class="flex-1 sm:flex-none px-6 py-3 rounded-xl bg-[#3d2b1f] text-white text-[10px] font-black uppercase tracking-widest hover:bg-[#c6a664] transition-all shadow-lg">Reschedule</button> -->
                                            @endif
                                            <button @click="openDetail({{ json_encode($booking) }})" class="p-3 rounded-xl bg-white text-gray-400 border border-gray-100 hover:text-[#3d2b1f] hover:border-[#3d2b1f] transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if($booking->staff)
                                    <div class="mt-8 pt-8 border-t border-white/50 flex flex-col sm:flex-row items-center justify-between gap-6">
                                        <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                                            <img src="{{ $booking->staff->photo ? asset('storage/' . $booking->staff->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($booking->staff->name) }}" class="w-12 h-12 rounded-2xl object-cover shadow-sm">
                                            <div>
                                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Assigned Professional</p>
                                                <h6 class="text-sm font-bold text-[#3d2b1f]">{{ $booking->staff->name }}</h6>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 w-full sm:w-auto">
                                            <a href="tel:{{ $booking->staff->phone }}" class="flex-1 sm:flex-none flex items-center justify-center gap-3 px-6 py-3 rounded-xl bg-[#c6a664] text-white font-bold text-xs hover:bg-[#3d2b1f] transition-all shadow-md">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                Call Professional
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="py-24 text-center border-2 border-dashed border-gray-100 rounded-[3rem]">
                                <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8">
                                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <h4 class="text-xl font-bold text-[#3d2b1f] mb-4">No bookings found</h4>
                                <p class="text-gray-400 font-medium mb-10">You haven't scheduled any services yet.</p>
                                <a href="{{ route('services.index') }}" class="inline-block bg-[#3d2b1f] text-white px-10 py-4 rounded-2xl font-bold hover:bg-[#c6a664] transition-all shadow-xl shadow-[#3d2b1f]/20 uppercase tracking-widest text-xs">Book Now</a>
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </div>

    <!-- Premium Booking Details Modal -->
    <template x-if="showModal">
        <div class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-6 overflow-hidden">
            <!-- Glassmorphism Backdrop -->
            <div class="absolute inset-0 bg-[#3d2b1f]/40 backdrop-blur-xl" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 @click="showModal = false"></div>
            
            <div class="relative w-full max-w-2xl bg-white rounded-t-[3rem] md:rounded-[4rem] shadow-[0_32px_64px_-12px_rgba(61,43,31,0.3)] overflow-hidden max-h-[92vh] md:max-h-[85vh] flex flex-col border border-white/20" 
                 x-transition:enter="transition ease-out duration-500 cubic-bezier(0.16, 1, 0.3, 1)"
                 x-transition:enter-start="opacity-0 translate-y-full md:translate-y-24"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <!-- Modal Header: Luxury Styling -->
                <div class="px-8 md:px-16 py-8 md:py-10 bg-[#fdfbf7] border-b border-gray-100 flex justify-between items-center flex-shrink-0 relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#c6a664] to-transparent opacity-30"></div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 rounded-full bg-[#c6a664]/10 text-[#c6a664] text-[9px] font-black uppercase tracking-widest" x-text="'Booking #' + selectedBooking.booking_number"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest" x-text="selectedBooking.service_type == 'home' ? 'Home Visit' : 'Salon Appointment'"></span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Appointment Details</h3>
                    </div>
                    <button @click="showModal = false" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-gray-400 hover:text-[#3d2b1f] hover:scale-110 shadow-sm border border-gray-50 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Scrollable Content -->
                <div class="px-6 md:px-16 py-8 md:py-12 overflow-y-auto flex-1 custom-scrollbar space-y-10 md:space-y-12">
                    <!-- Date & Status Section -->
                    <div class="grid grid-cols-2 gap-4 md:gap-8" :class="selectedBooking.otp ? 'md:grid-cols-3' : 'md:grid-cols-2'">
                        <div class="group">
                            <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 md:mb-3">Appointment Date</p>
                            <div class="flex items-center gap-3 md:gap-4 p-3 md:p-4 rounded-2xl md:rounded-3xl bg-[#fdfbf7] border border-gray-50 group-hover:border-[#c6a664]/30 transition-colors">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-white shadow-sm flex items-center justify-center text-[#c6a664]">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-[10px] md:text-sm font-bold text-[#3d2b1f]" x-text="new Date(selectedBooking.booking_date).toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short' })"></span>
                            </div>
                        </div>
                        <div class="group">
                            <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 md:mb-3">Booking Status</p>
                            <div class="flex items-center gap-3 md:gap-4 p-3 md:p-4 rounded-2xl md:rounded-3xl bg-[#fdfbf7] border border-gray-50 group-hover:border-[#c6a664]/30 transition-colors">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-white shadow-sm flex items-center justify-center" :class="selectedBooking.status == 'completed' ? 'text-green-500' : (selectedBooking.status == 'cancelled' ? 'text-red-500' : 'text-[#c6a664]')">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="text-[9px] md:text-[10px] font-black uppercase tracking-widest" :class="selectedBooking.status == 'completed' ? 'text-green-600' : (selectedBooking.status == 'cancelled' ? 'text-red-600' : 'text-[#c6a664]')" x-text="selectedBooking.status"></span>
                            </div>
                        </div>
                        <!-- Security OTP -->
                        <div class="group col-span-2 md:col-span-1" x-show="selectedBooking.otp">
                            <p class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] mb-2 md:mb-3" :class="selectedBooking.verify ? 'text-emerald-500' : 'text-[#c6a664]'" x-text="selectedBooking.verify ? 'Security Verified' : 'Security OTP'"></p>
                            <div class="flex items-center gap-3 md:gap-4 p-3 md:p-4 rounded-2xl md:rounded-3xl border transition-colors" :class="selectedBooking.verify ? 'bg-emerald-50/50 border-emerald-100 group-hover:border-emerald-300' : 'bg-amber-50/50 border-amber-100 group-hover:border-amber-300'">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-white shadow-sm flex items-center justify-center" :class="selectedBooking.verify ? 'text-emerald-500' : 'text-amber-500'">
                                    <svg x-show="selectedBooking.verify" class="w-4 h-4 md:w-5 md:h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    <svg x-show="!selectedBooking.verify" class="w-4 h-4 md:w-5 md:h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[12px] md:text-base font-black tracking-[0.1em]" :class="selectedBooking.verify ? 'text-emerald-600' : 'text-amber-600'" x-text="selectedBooking.otp"></span>
                                    <span class="text-[8px] font-black uppercase tracking-wider opacity-90" :class="selectedBooking.verify ? 'text-emerald-500' : 'text-red-500'" x-text="selectedBooking.verify ? 'Verified' : 'Not Verified'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details Section -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 md:gap-8">
                        <div class="group">
                            <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 md:mb-3">Payment Method</p>
                            <div class="flex items-center gap-3 md:gap-4 p-3 md:p-4 rounded-2xl md:rounded-3xl bg-[#fdfbf7] border border-gray-50 group-hover:border-[#c6a664]/30 transition-colors">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-white shadow-sm flex items-center justify-center text-[#c6a664]">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <span class="text-[10px] md:text-sm font-bold text-[#3d2b1f] uppercase tracking-wider" x-text="selectedBooking.pay_type || selectedBooking.payment_type || 'ONLINE'"></span>
                            </div>
                        </div>
                        <div class="group">
                            <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 md:mb-3">Payment Status</p>
                            <div class="flex items-center gap-3 md:gap-4 p-3 md:p-4 rounded-2xl md:rounded-3xl bg-[#fdfbf7] border border-gray-50 group-hover:border-[#c6a664]/30 transition-colors">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-white shadow-sm flex items-center justify-center" :class="selectedBooking.is_paid ? 'text-emerald-500' : 'text-amber-500'">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="text-[10px] md:text-sm font-bold animate-pulse" :class="selectedBooking.is_paid ? 'text-emerald-600' : 'text-amber-600'" x-text="selectedBooking.is_paid ? 'PAID' : 'UNPAID'"></span>
                            </div>
                        </div>
                        <div class="group col-span-2 sm:col-span-1" x-show="selectedBooking.coupon_code">
                            <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 md:mb-3">Coupon Applied</p>
                            <div class="flex items-center gap-3 md:gap-4 p-3 md:p-4 rounded-2xl md:rounded-3xl bg-green-50/50 border border-green-100 group-hover:border-green-300 transition-colors">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-white shadow-sm flex items-center justify-center text-green-500">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-[10px] md:text-sm font-black text-green-600 uppercase tracking-widest" x-text="selectedBooking.coupon_code"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Services List Section -->
                    <div>
                        <div class="flex items-center justify-between mb-6 md:mb-8">
                            <h4 class="text-[10px] md:text-xs font-black text-[#3d2b1f] uppercase tracking-[0.2em]">Included Services</h4>
                            <span class="text-[9px] md:text-[10px] font-bold text-gray-300" x-text="(selectedBooking.items?.length || selectedBooking.services?.length || 0) + ' Items'"></span>
                        </div>
                        <div class="space-y-3 md:space-y-4">
                            <template x-for="(item, index) in (selectedBooking.items || selectedBooking.services)" :key="index">
                                <div class="relative group p-4 md:p-5 rounded-[1.5rem] md:rounded-[2rem] bg-white border border-gray-50 hover:border-[#c6a664]/20 hover:shadow-xl hover:shadow-gray-100/50 transition-all duration-300">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-4 md:gap-5">
                                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-[#fdfbf7] flex items-center justify-center text-[#c6a664] font-black text-[10px] md:text-xs" x-text="index + 1"></div>
                                            <div>
                                                <p class="text-xs md:text-base font-bold text-[#3d2b1f] mb-0.5" x-text="item.package_id ? item.package.name : (item.service ? item.service.name : item.name)"></p>
                                                <p class="text-[9px] md:text-[10px] text-gray-400 font-medium uppercase tracking-widest" x-text="item.package_id ? 'Curated Bundle' : 'Professional Service'"></p>
                                            </div>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <p class="text-xs md:text-sm font-black text-[#3d2b1f]" x-text="(item.price || item.sale_price) > 0 ? '₹' + parseFloat(item.price || item.sale_price).toLocaleString() : 'Free'"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Address / Location Section -->
                    <div x-show="selectedBooking.service_type == 'home' && selectedBooking.address">
                        <h4 class="text-[10px] md:text-xs font-black text-[#3d2b1f] uppercase tracking-[0.2em] mb-4 md:mb-6">Service Address</h4>
                        <div class="p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] bg-[#3d2b1f] text-white shadow-2xl shadow-[#3d2b1f]/20 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
                            <div class="relative flex items-start gap-5 md:gap-6">
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-[#c6a664] flex items-center justify-center text-white shadow-lg flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <h6 class="text-base md:text-lg font-bold mb-1 md:mb-2 text-[#c6a664] truncate" x-text="selectedBooking.address.title"></h6>
                                    <p class="text-[10px] md:text-xs text-white/70 leading-relaxed mb-3 md:mb-4" x-text="selectedBooking.address.full_address"></p>
                                    <div class="flex items-center gap-2">
                                        <span class="w-1 h-1 rounded-full bg-[#c6a664]"></span>
                                        <span class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-[#c6a664]/80" x-text="selectedBooking.address.city.name + ', ' + selectedBooking.address.state.name"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Experience Section (Only if Completed) -->
                    <template x-if="selectedBooking.status == 'completed'">
                        <div class="p-6 md:p-8 rounded-[2rem] bg-[#fdfbf7] border border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div>
                                <h5 class="text-base font-bold text-[#3d2b1f] mb-1" style="font-family: 'Playfair Display', serif;">Rate Your Experience</h5>
                                <p class="text-xs text-gray-400 font-medium">How was your service experience with us?</p>
                            </div>
                            <div class="flex items-center gap-1" x-data="{ hoverRating: 0 }">
                                <template x-for="i in [1, 2, 3, 4, 5]" :key="i">
                                    <button 
                                        @click="
                                            selectedBooking.rating = i;
                                            submitModalRating(selectedBooking.id, selectedBooking.service_ids ? 'custom_booking' : 'booking', i);
                                        " 
                                        @mouseenter="hoverRating = i"
                                        @mouseleave="hoverRating = 0"
                                        class="p-1 focus:outline-none transition-transform hover:scale-125">
                                        <svg class="w-8 h-8 transition-all duration-300" 
                                             :class="(hoverRating ? hoverRating >= i : selectedBooking.rating >= i) ? 'text-amber-400 fill-amber-400' : 'text-gray-300 fill-transparent'"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Final Summary Section -->
                    <div class="pt-8 md:pt-10 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-6 md:gap-8 bg-[#fdfbf7] -mx-6 md:-mx-16 px-6 md:px-16 pb-10 md:pb-12">
                        <div class="text-center sm:text-left">
                            <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 md:mb-2">Total Amount Paid</p>
                            <h3 class="text-3xl md:text-4xl font-black text-[#3d2b1f] tracking-tight" x-text="'₹' + parseFloat(selectedBooking.payable_amount || selectedBooking.total_price || 0).toLocaleString()"></h3>
                        </div>
                        <div class="flex flex-col items-center sm:items-end gap-2 md:gap-3 w-full sm:w-auto">
                            <!-- Paid State -->
                            <span x-show="selectedBooking.is_paid" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-2xl bg-white border border-green-100 text-green-600 text-[9px] md:text-[10px] font-black uppercase tracking-widest shadow-sm">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                Payment Verified
                            </span>
                            <!-- Unpaid State -->
                            <span x-show="!selectedBooking.is_paid" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-2xl bg-white border border-amber-100 text-amber-600 text-[9px] md:text-[10px] font-black uppercase tracking-widest shadow-sm">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                </span>
                                Payment Pending
                            </span>
                            <p class="text-[8px] md:text-[9px] text-gray-300 font-bold uppercase tracking-widest italic" x-text="selectedBooking.is_paid ? 'Digital Invoice Sent' : 'Awaiting Settlement'"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
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
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function cancelBooking(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to cancel this booking?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3d2b1f',
            cancelButtonColor: '#f3f4f6',
            cancelButtonText: '<span style="color: #9ca3af">No, keep it</span>',
            confirmButtonText: 'Yes, cancel it!',
            background: '#fff',
            customClass: {
                confirmButton: 'rounded-2xl px-8 py-3 font-bold',
                cancelButton: 'rounded-2xl px-8 py-3 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/dashboard/bookings/${id}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cancelled!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }

    function submitRating(button, ratingValue) {
        const container = button.closest('[data-booking-id]');
        const bookingId = container.getAttribute('data-booking-id');
        const bookingType = container.getAttribute('data-booking-type');
        
        // Optimistic UI update
        container.setAttribute('data-rating', ratingValue);
        const stars = container.querySelectorAll('.star-btn svg');
        stars.forEach((star, index) => {
            if (index < ratingValue) {
                star.classList.add('text-amber-400', 'fill-amber-400');
                star.classList.remove('text-gray-300', 'fill-transparent');
            } else {
                star.classList.remove('text-amber-400', 'fill-amber-400');
                star.classList.add('text-gray-300', 'fill-transparent');
            }
        });

        sendRatingRequest(bookingId, bookingType, ratingValue);
    }

    function submitModalRating(bookingId, bookingType, ratingValue) {
        // Find static container and sync
        const container = document.querySelector(`[data-booking-id="${bookingId}"][data-booking-type="${bookingType}"]`);
        if (container) {
            container.setAttribute('data-rating', ratingValue);
            const stars = container.querySelectorAll('.star-btn svg');
            stars.forEach((star, index) => {
                if (index < ratingValue) {
                    star.classList.add('text-amber-400', 'fill-amber-400');
                    star.classList.remove('text-gray-300', 'fill-transparent');
                } else {
                    star.classList.remove('text-amber-400', 'fill-amber-400');
                    star.classList.add('text-gray-300', 'fill-transparent');
                }
            });
        }

        sendRatingRequest(bookingId, bookingType, ratingValue);
    }

    function sendRatingRequest(bookingId, bookingType, ratingValue) {
        fetch(`/dashboard/bookings/${bookingId}/rate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                rating: ratingValue,
                type: bookingType
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Thank you for your rating!'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message || 'Something went wrong!',
                    confirmButtonColor: '#3d2b1f'
                });
            }
        })
        .catch(error => {
            console.error('Error submitting rating:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Could not connect to the server!',
                confirmButtonColor: '#3d2b1f'
            });
        });
    }

    // Set up hover states for static stars using event delegation
    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('mouseover', function(e) {
            const starBtn = e.target.closest('.star-btn');
            if (!starBtn) return;
            
            const index = parseInt(starBtn.getAttribute('data-index'));
            const container = starBtn.closest('[data-booking-id]');
            if (!container) return;
            
            const stars = container.querySelectorAll('.star-btn svg');
            stars.forEach((star, i) => {
                if (i < index) {
                    star.classList.add('text-amber-400', 'fill-amber-400');
                    star.classList.remove('text-gray-300', 'fill-transparent');
                } else {
                    star.classList.remove('text-amber-400', 'fill-amber-400');
                    star.classList.add('text-gray-300', 'fill-transparent');
                }
            });
        });

        document.addEventListener('mouseout', function(e) {
            const starBtn = e.target.closest('.star-btn');
            if (!starBtn) return;
            
            const container = starBtn.closest('[data-booking-id]');
            if (!container) return;
            
            const currentRating = parseInt(container.getAttribute('data-rating') || 0);
            const stars = container.querySelectorAll('.star-btn svg');
            stars.forEach((star, i) => {
                if (i < currentRating) {
                    star.classList.add('text-amber-400', 'fill-amber-400');
                    star.classList.remove('text-gray-300', 'fill-transparent');
                } else {
                    star.classList.remove('text-amber-400', 'fill-amber-400');
                    star.classList.add('text-gray-300', 'fill-transparent');
                }
            });
        });
    });
</script>
@endsection
