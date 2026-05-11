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
            <main class="flex-1 space-y-12">
                <!-- Wallet Balance Card -->
                <section class="relative bg-[#3d2b1f] rounded-[3rem] p-12 overflow-hidden shadow-2xl shadow-[#3d2b1f]/20">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-[#c6a664]/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-12 text-center md:text-left">
                        <div>
                            <p class="text-[#c6a664] text-[10px] font-black uppercase tracking-[0.3em] mb-4">Total Balance</p>
                            <h2 class="text-6xl font-black text-white" style="font-family: 'Playfair Display', serif;">₹{{ number_format($wallet->balance, 2) }}</h2>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button class="bg-[#c6a664] text-[#3d2b1f] px-10 py-5 rounded-2xl font-bold hover:scale-105 transition-all shadow-xl">Add Money</button>
                            <button class="bg-white/10 text-white border border-white/20 px-10 py-5 rounded-2xl font-bold hover:bg-white/20 transition-all backdrop-blur-md">Withdraw</button>
                        </div>
                    </div>
                </section>

                <!-- Transactions -->
                <section class="bg-white rounded-[2.5rem] p-12 shadow-sm border border-gray-100">
                    <h2 class="text-3xl font-bold text-[#3d2b1f] mb-10" style="font-family: 'Playfair Display', serif;">Recent Transactions</h2>
                    
                    <div class="space-y-4">
                        @forelse($transactions as $transaction)
                            <div class="flex items-center justify-between p-6 rounded-3xl border border-gray-50 hover:bg-[#fdfbf7] transition-all">
                                <div class="flex items-center gap-6">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $transaction->type == 'credit' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        @if($transaction->type == 'credit')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="font-bold text-[#3d2b1f]">{{ $transaction->title }}</h6>
                                        <p class="text-xs text-gray-400 font-medium">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M, Y • h:i A') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-black {{ $transaction->type == 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type == 'credit' ? '+' : '-' }} ₹{{ number_format($transaction->amount, 2) }}
                                    </span>
                                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mt-1">Success</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-16 text-center border-2 border-dashed border-gray-100 rounded-[2rem]">
                                <p class="text-gray-400 font-bold mb-4">No transactions yet.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>
@endsection
