@extends('frontend.layout.app')

@section('content')
<div class="pt-32 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-16">
            <!-- Left: Checkout Forms -->
            <div class="flex-1 space-y-12">
                <h1 class="text-4xl font-bold text-[#3d2b1f] mb-12" style="font-family: 'Playfair Display', serif;">Secure Checkout</h1>

                <!-- 1. Address Section -->
                <section class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-[#3d2b1f] text-[#c6a664] rounded-full flex items-center justify-center font-bold">1</div>
                        <h3 class="text-xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Service Address</h3>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="border-2 border-[#3d2b1f] rounded-[2rem] p-6 relative">
                            <div class="absolute top-4 right-4 text-[#c6a664]">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <p class="text-[10px] text-gray-400 font-black uppercase mb-2 tracking-widest">Home</p>
                            <p class="text-sm font-bold text-[#3d2b1f]">Saklin Mustak</p>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">Street 12, Block 4, <br> Dhaka, Bangladesh</p>
                        </div>
                        <button class="border-2 border-dashed border-gray-100 rounded-[2rem] p-6 flex flex-col items-center justify-center gap-2 hover:border-[#3d2b1f] transition-all group">
                            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center group-hover:bg-[#3d2b1f]/5 transition-all">
                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-400">Add New Address</span>
                        </button>
                    </div>
                </section>

                <!-- 2. Date & Time Selection -->
                <section class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-[#3d2b1f] text-[#c6a664] rounded-full flex items-center justify-center font-bold">2</div>
                        <h3 class="text-xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Date & Time</h3>
                    </div>
                    
                    <div class="grid grid-cols-4 md:grid-cols-7 gap-4 mb-8">
                        @for($i=0; $i<7; $i++)
                            <button class="flex flex-col items-center p-4 rounded-2xl border {{ $i === 0 ? 'bg-[#3d2b1f] border-[#3d2b1f] text-white shadow-lg' : 'bg-[#fdfbf7] border-gray-100 text-gray-400 hover:border-[#3d2b1f] transition-all' }}">
                                <span class="text-[10px] font-black uppercase tracking-widest mb-1">{{ now()->addDays($i)->format('D') }}</span>
                                <span class="text-lg font-black">{{ now()->addDays($i)->format('d') }}</span>
                            </button>
                        @endfor
                    </div>

                    <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
                        @foreach(['09:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM', '05:00 PM', '06:00 PM'] as $time)
                            <button class="py-3 rounded-xl border border-gray-100 text-xs font-bold text-[#3d2b1f] hover:bg-[#3d2b1f] hover:text-white transition-all">
                                {{ $time }}
                            </button>
                        @endforeach
                    </div>
                </section>
            </div>

            <!-- Right: Order Summary -->
            <div class="w-full lg:w-[400px]">
                <div class="bg-white rounded-[3rem] p-10 shadow-2xl border border-gray-50 sticky top-32">
                    <h3 class="text-xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">Order Summary</h3>
                    
                    <!-- Items -->
                    <div class="space-y-6 mb-10 pb-8 border-b border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h6 class="text-sm font-bold text-[#3d2b1f]">Bridal Glow Facial</h6>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Home Service</p>
                            </div>
                            <span class="text-sm font-black text-[#3d2b1f]">₹1,499</span>
                        </div>
                    </div>

                    <!-- Coupons -->
                    <div class="mb-10">
                        <p class="text-[10px] text-gray-400 font-black uppercase mb-4 tracking-widest">Apply Coupon</p>
                        <div class="flex gap-2">
                            <input type="text" placeholder="EASYLUX" class="flex-1 bg-[#fdfbf7] border-none rounded-xl py-3 px-4 text-xs font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                            <button class="bg-[#3d2b1f] text-white px-6 rounded-xl text-xs font-bold hover:bg-[#c6a664] transition-all">Apply</button>
                        </div>
                    </div>

                    <!-- Pricing Details -->
                    <div class="space-y-4 mb-10">
                        <div class="flex justify-between text-sm text-gray-500 font-medium">
                            <span>Subtotal</span>
                            <span>₹1,499</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500 font-medium">
                            <span>Service Charge</span>
                            <span>₹99</span>
                        </div>
                        <div class="flex justify-between text-sm text-green-600 font-bold">
                            <span>Discount</span>
                            <span>-₹0</span>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex justify-between items-end">
                            <span class="text-lg font-bold text-[#3d2b1f]">Total Amount</span>
                            <span class="text-2xl font-black text-[#3d2b1f]">₹1,598</span>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <button class="w-full bg-[#c6a664] text-white py-5 rounded-[2rem] font-bold text-lg shadow-xl hover:scale-[1.02] transition-all active:scale-[0.98]">
                        Proceed to Pay
                    </button>
                    
                    <div class="mt-8 flex items-center justify-center gap-4 grayscale opacity-40">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e1/UPI-Logo.png" class="h-4">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-3">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-6">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
