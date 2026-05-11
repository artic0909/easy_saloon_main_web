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
                    <div class="flex justify-between items-center mb-10">
                        <h2 class="text-3xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Notifications</h2>
                        <button class="text-[10px] font-black uppercase text-[#c6a664] tracking-widest hover:text-[#3d2b1f] transition-all">Mark all as read</button>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($notifications as $notification)
                            <div class="group p-6 rounded-3xl border border-gray-50 {{ $notification->read_at ? 'bg-white opacity-60' : 'bg-[#fdfbf7]' }} hover:bg-white hover:shadow-xl transition-all duration-300">
                                <div class="flex items-start gap-6">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-white shadow-sm flex-shrink-0">
                                        @php
                                            $type = $notification->data['type'] ?? 'info';
                                        @endphp
                                        @if($type == 'booking')
                                            <svg class="w-6 h-6 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @elseif($type == 'payment')
                                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @else
                                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-2">
                                            <h6 class="font-bold text-[#3d2b1f]">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-500 leading-relaxed">{{ $notification->data['message'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-24 text-center border-2 border-dashed border-gray-100 rounded-[3rem]">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                </div>
                                <h4 class="text-xl font-bold text-[#3d2b1f] mb-2">All caught up!</h4>
                                <p class="text-gray-400 text-sm">No new notifications at the moment.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>
@endsection
