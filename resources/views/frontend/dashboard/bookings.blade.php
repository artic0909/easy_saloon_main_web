@extends('frontend.layout.app')

@section('content')
<div class="pt-40 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="flex flex-col lg:flex-row gap-16">
            <!-- Sidebar -->
            <aside class="w-full lg:w-80 flex-shrink-0">
                @include('frontend.dashboard.includes.sidebar')
            </aside>

            <!-- Main Content -->
            <main class="flex-1 space-y-12">
                <section class="bg-white rounded-[2.5rem] p-12 shadow-sm border border-gray-100">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                        <h2 class="text-3xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">My Bookings</h2>
                        <div class="flex gap-2 p-1 bg-[#fdfbf7] rounded-2xl border border-gray-50">
                            <button class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-[#3d2b1f] text-white shadow-lg">All</button>
                            <button class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-[#3d2b1f]">Upcoming</button>
                            <button class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-[#3d2b1f]">Past</button>
                        </div>
                    </div>
                    
                    <div class="grid gap-6">
                        @forelse($bookings as $booking)
                            <div class="group p-8 bg-[#fdfbf7] rounded-[2.5rem] border border-gray-50 hover:bg-white hover:shadow-2xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-500">
                                <div class="flex flex-col md:flex-row justify-between gap-8">
                                    <div class="flex gap-8">
                                        <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-[#c6a664] shadow-sm flex-shrink-0">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="text-xs font-black text-[#c6a664] uppercase tracking-[0.2em]">#{{ $booking->booking_number }}</span>
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                                                <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest">{{ $booking->service_type == 'home' ? 'Home Service' : 'Salon Visit' }}</span>
                                            </div>
                                            <h4 class="text-xl font-bold text-[#3d2b1f] mb-4">
                                                @if($booking->items && $booking->items->count() > 0)
                                                    {{ $booking->items->first()->service->name ?? 'Multiple Services' }}
                                                    @if($booking->items->count() > 1)
                                                        <span class="text-sm font-medium text-gray-400"> +{{ $booking->items->count() - 1 }} more</span>
                                                    @endif
                                                @else
                                                    Salon Service
                                                @endif
                                            </h4>
                                            <div class="flex flex-wrap gap-6">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <span class="text-xs font-bold text-[#3d2b1f]">{{ \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y') }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <span class="text-xs font-bold text-[#3d2b1f]">{{ $booking->time_slot }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col justify-between items-end gap-6">
                                        <div class="text-right">
                                            <span class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest 
                                                {{ $booking->status == 'completed' ? 'bg-green-100 text-green-600' : ($booking->status == 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-[#c6a664]/10 text-[#c6a664]') }}">
                                                {{ $booking->status }}
                                            </span>
                                            <h3 class="text-2xl font-black text-[#3d2b1f] mt-3">₹{{ number_format($booking->payable_amount, 2) }}</h3>
                                        </div>
                                        
                                        <div class="flex gap-3">
                                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                                <button onclick="cancelBooking({{ $booking->id }})" class="px-6 py-3 rounded-xl border border-red-100 text-red-500 text-[10px] font-black uppercase tracking-widest hover:bg-red-50 transition-all">Cancel</button>
                                                <button class="px-6 py-3 rounded-xl bg-[#3d2b1f] text-white text-[10px] font-black uppercase tracking-widest hover:bg-[#c6a664] transition-all shadow-lg">Reschedule</button>
                                            @endif
                                            <button class="p-3 rounded-xl bg-white text-gray-400 border border-gray-100 hover:text-[#3d2b1f] hover:border-[#3d2b1f] transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($booking->staff)
                                    <div class="mt-8 pt-8 border-t border-white/50 flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ $booking->staff->photo ? asset('storage/' . $booking->staff->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($booking->staff->name) }}" class="w-10 h-10 rounded-xl object-cover shadow-sm">
                                            <div>
                                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Assigned Professional</p>
                                                <h6 class="text-sm font-bold text-[#3d2b1f]">{{ $booking->staff->name }}</h6>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="tel:{{ $booking->staff->phone }}" class="p-2.5 rounded-xl bg-white border border-gray-100 text-[#c6a664] hover:bg-[#c6a664] hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
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
</div>

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
</script>
@endsection
