@extends('frontend.layout.app')

@section('content')
<div class="pt-40 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="flex flex-col lg:flex-row gap-16">
            <!-- Sidebar -->
            <aside class="hidden lg:block w-full lg:w-80 flex-shrink-0">
                @include('frontend.dashboard.includes.sidebar')
            </aside>

            <!-- Main Content -->
            <main class="flex-1 space-y-8 md:space-y-12">
                <section class="bg-white rounded-[2rem] md:rounded-[3rem] p-6 md:p-12 shadow-sm border border-gray-100">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6 w-full">
                            <h2 class="text-3xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Notifications</h2>
                            <div class="flex items-center gap-4">
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('dashboard.notifications.read-all') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-black uppercase text-[#c6a664] tracking-widest hover:text-[#3d2b1f] transition-all">Mark all as read</button>
                                    </form>
                                @endif
                                <div class="flex items-center gap-2 px-4 py-2 bg-[#fdfbf7] rounded-xl border border-gray-50">
                                    <span class="w-2 h-2 rounded-full bg-[#c6a664] animate-pulse"></span>
                                    <span class="text-[10px] font-black uppercase text-[#3d2b1f] tracking-widest">{{ auth()->user()->unreadNotifications->count() }} New</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @forelse($notifications as $notification)
                                @php
                                $data = $notification->data;
                                $isUnread = is_null($notification->read_at);
                                $type = $data['type'] ?? 'general';
                                $icon = $data['icon'] ?? ($type == 'coupon' ? 'ticket' : 'bell');
                                
                                $isExpired = false;
                                if ($type == 'coupon' && isset($data['coupon_id'])) {
                                    $coupon = \App\Models\Coupon::find($data['coupon_id']);
                                    $isExpired = !$coupon || !$coupon->is_active || ($coupon->expiry_date && $coupon->expiry_date->isPast());
                                }
                            @endphp
                            <div class="group relative p-6 md:p-8 {{ $isUnread ? 'bg-[#fdfbf7]' : 'bg-white' }} {{ $isExpired ? 'opacity-50 grayscale-[0.5]' : '' }} rounded-[2rem] border border-gray-50 {{ !$isExpired && $type == 'coupon' ? 'cursor-pointer' : '' }} hover:bg-white hover:shadow-2xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-500"
                                 @if(!$isExpired && $type == 'coupon' && isset($data['code'])) onclick="copyCoupon('{{ $data['code'] }}')" @endif>
                                
                                @if($isUnread && !$isExpired)
                                    <div class="absolute top-8 right-8 w-2 h-2 rounded-full bg-[#c6a664]"></div>
                                @endif
                                
                                <div class="flex items-start gap-6 md:gap-8">
                                    <div class="w-12 h-12 md:w-16 md:h-16 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm
                                        {{ $type == 'booking' ? 'bg-blue-50 text-blue-500' : ($type == 'payment' ? 'bg-green-50 text-green-500' : 'bg-[#c6a664]/10 text-[#c6a664]') }}">
                                        @if($icon == 'calendar-check')
                                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @elseif($icon == 'credit-card')
                                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        @else
                                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                                            <h4 class="text-lg font-bold text-[#3d2b1f] flex items-center gap-2">
                                                {{ $data['title'] }}
                                                @if($isExpired)
                                                    <span class="text-[9px] bg-red-100 text-red-500 px-2 py-0.5 rounded-full font-black uppercase tracking-widest">Expired</span>
                                                @elseif($type == 'coupon')
                                                    <span class="text-[9px] bg-green-100 text-green-600 px-2 py-0.5 rounded-full font-black uppercase tracking-widest">Active • Click to Copy</span>
                                                @endif
                                            </h4>
                                            <span class="text-[10px] font-black uppercase text-gray-300 tracking-widest">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-500 leading-relaxed">{{ $data['message'] }}</p>
                                        
                                        @if($type == 'booking' && isset($data['booking_id']))
                                             <div class="mt-6 flex items-center gap-6">
                                                 <a href="{{ route('dashboard.bookings') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase text-[#c6a664] tracking-widest hover:text-[#3d2b1f] transition-colors">
                                                     View Booking Details
                                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                                 </a>
                                                 
                                                 @if($isUnread)
                                                     <form action="{{ route('dashboard.notifications.read', $notification->id) }}" method="POST">
                                                         @csrf
                                                         <button type="submit" class="text-[10px] font-black uppercase text-gray-400 tracking-widest hover:text-[#3d2b1f] transition-all">Mark as read</button>
                                                     </form>
                                                 @endif
                                             </div>
                                         @elseif($isUnread)
                                             <div class="mt-6">
                                                 <form action="{{ route('dashboard.notifications.read', $notification->id) }}" method="POST">
                                                     @csrf
                                                     <button type="submit" class="text-[10px] font-black uppercase text-[#c6a664] tracking-widest hover:text-[#3d2b1f] transition-all">Mark as read</button>
                                                 </form>
                                             </div>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-24 text-center border-2 border-dashed border-gray-100 rounded-[3rem]">
                                <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8">
                                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                </div>
                                <h4 class="text-xl font-bold text-[#3d2b1f] mb-2">All caught up!</h4>
                                <p class="text-gray-400 font-medium">No new notifications at the moment.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function copyCoupon(code) {
        navigator.clipboard.writeText(code).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Code Copied!',
                text: 'Coupon code ' + code + ' has been copied to your clipboard.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#fff',
                iconColor: '#c6a664'
            });
        }).catch(err => {
            console.error('Could not copy text: ', err);
        });
    }
</script>
@endsection
