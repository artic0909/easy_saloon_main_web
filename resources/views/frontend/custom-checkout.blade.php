@extends('frontend.layout.app')

@section('page_title', 'Checkout - Custom Package')
@section('meta_description', 'Finalize your personalized beauty appointment. Secure luxury salon booking powered by Easy Saloon.')

@section('content')
<div class="pt-32 md:pt-48 pb-24 bg-[#fdfbf7]">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-[#3d2b1f] mb-4" style="font-family: 'Playfair Display', serif;">Finalize Your Custom Package</h1>
            <p class="text-gray-400 font-medium uppercase tracking-widest text-[10px] md:text-xs">Review your personalized selection and confirm your appointment</p>
        </div>

        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16">
            <!-- Left: Selection Review -->
            <div class="lg:col-span-7 space-y-8">
                <!-- Services Summary -->
                <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] md:rounded-[3rem] p-5 sm:p-8 md:p-10 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">Selected Services</h3>
                    <div class="space-y-4">
                        @foreach($services as $service)
                            <div class="flex items-center gap-6 p-4 bg-[#fdfbf7] rounded-[2rem] border border-gray-50 group hover:shadow-lg transition-all">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden flex-shrink-0">
                                    <img src="{{ $service->image ? asset('storage/' . $service->image) : asset('assets/img/service-bridal.png') }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-[#3d2b1f]">{{ $service->name }}</h4>
                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">{{ $service->duration_minutes }} Mins</p>
                                </div>
                                <div class="text-right">
                                    @if(isset($service->is_free) && $service->is_free)
                                        <div class="flex flex-col items-end">
                                            <span class="text-xs text-gray-400 line-through">₹{{ number_format($service->sale_price, 2) }}</span>
                                            <span class="font-black text-green-600">₹0.00</span>
                                        </div>
                                    @else
                                        <span class="font-black text-[#c6a664]">₹{{ number_format($service->sale_price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10 grid sm:grid-cols-3 gap-4">
                        <div class="flex items-center gap-3 bg-[#fdfbf7] p-4 rounded-2xl">
                            <i class="bi bi-calendar3 text-[#c6a664]"></i>
                            <span class="text-xs font-bold text-[#3d2b1f]">{{ \Carbon\Carbon::parse($date)->format('D, d M') }}</span>
                        </div>
                        <div class="flex items-center gap-3 bg-[#fdfbf7] p-4 rounded-2xl">
                            <i class="bi bi-clock text-[#c6a664]"></i>
                            <span class="text-xs font-bold text-[#3d2b1f]">{{ $slot }}</span>
                        </div>
                        <div class="flex items-center gap-3 bg-[#fdfbf7] p-4 rounded-2xl">
                            <i class="bi bi-geo-alt text-[#c6a664]"></i>
                            <span class="text-xs font-bold text-[#3d2b1f]">{{ $type == 'home' ? 'Home' : 'Salon' }}</span>
                        </div>
                    </div>

                    @if(!empty($equipment))
                        <div class="mt-8">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Selected Equipment</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($equipment as $eq)
                                    <span class="bg-[#3d2b1f] text-white text-[9px] font-bold px-4 py-2 rounded-full shadow-sm">{{ $eq }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Location Details -->
                <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] md:rounded-[3rem] p-5 sm:p-8 md:p-10 shadow-sm border border-gray-100">
                    @if($type == 'home')
                        <h3 class="text-xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">Service Address</h3>
                        <div class="grid md:grid-cols-2 gap-4" x-data="{ selectedAddress: '{{ $userAddresses->where('is_primary', true)->first()->id ?? ($userAddresses->first()->id ?? '') }}' }">
                            @forelse($userAddresses as $address)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" x-model="selectedAddress" class="absolute opacity-0">
                                    <div :class="selectedAddress == {{ $address->id }} ? 'bg-[#3d2b1f] text-white border-[#3d2b1f] shadow-xl' : 'bg-[#fdfbf7] text-gray-500 border-gray-50'" class="p-5 sm:p-6 rounded-2xl sm:rounded-3xl border-2 transition-all group-hover:-translate-y-1">
                                        <div class="flex items-center justify-between mb-4">
                                            <span class="text-[10px] font-black uppercase tracking-widest">{{ $address->title }}</span>
                                            <div x-show="selectedAddress == {{ $address->id }}" class="w-4 h-4 bg-[#c6a664] rounded-full flex items-center justify-center">
                                                <svg class="w-2 h-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        </div>
                                        <p class="text-xs leading-relaxed opacity-70">{{ $address->full_address }}</p>
                                    </div>
                                </label>
                            @empty
                                <div class="md:col-span-2 text-center py-10 bg-[#fdfbf7] rounded-[2rem] border-2 border-dashed border-gray-100">
                                    <p class="text-gray-400 text-sm font-bold mb-4">No saved addresses found</p>
                                    <a href="{{ route('dashboard.addresses') }}" class="text-[#c6a664] text-xs font-black uppercase tracking-widest hover:underline">Add New Address</a>
                                </div>
                            @endforelse
                        </div>
                    @else
                        <h3 class="text-xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">Salon Location</h3>
                        <div class="p-8 bg-[#3d2b1f] rounded-[2rem] text-white flex items-center gap-6">
                            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-[#c6a664] flex-shrink-0 shadow-inner">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-bold text-lg">Easy Saloon Main Branch</h5>
                                <p class="text-sm text-white/40">123 Luxury Street, Beauty Park, City Hub</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Price Breakdown -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] md:rounded-[3rem] p-5 sm:p-8 md:p-12 shadow-2xl shadow-gray-200/50 border border-gray-100 sticky top-40">
                    <h3 class="text-xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">Booking Summary</h3>
                    
                    <div class="space-y-6 mb-8 pb-8 border-b border-gray-50">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium">Total Services</span>
                            <span class="text-[#3d2b1f] font-bold">{{ count($services) }} Items</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium">Total Duration</span>
                            <span class="text-[#3d2b1f] font-bold">{{ $totalDuration }} Mins</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium">Service Fee</span>
                            <span class="text-[#3d2b1f] font-bold">₹0.00</span>
                        </div>
                        <div id="coupon_discount_row" class="flex justify-between items-center text-sm hidden">
                            <span class="text-gray-400 font-medium">Coupon Discount (<span id="coupon_applied_code"></span>)</span>
                            <span class="text-green-500 font-bold">- ₹<span id="coupon_discount_value">0.00</span></span>
                        </div>
                    </div>

                    <!-- Coupon Code Input -->
                    <div class="mb-8 p-5 sm:p-6 bg-[#fdfbf7] rounded-[1.5rem] sm:rounded-[2rem] border border-gray-100/50">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Apply Coupon Code</label>
                        <div class="flex gap-2">
                            <input type="text" id="coupon_input" placeholder="Enter coupon..." class="flex-1 bg-white border border-gray-200 rounded-2xl px-4 py-2.5 text-xs focus:outline-none focus:border-[#3d2b1f] font-semibold text-[#3d2b1f] uppercase tracking-wider">
                            <button type="button" onclick="applyCoupon()" class="bg-[#3d2b1f] text-white px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-[#c6a664] transition-colors shadow-sm">Apply</button>
                        </div>
                        <div id="coupon_status" class="mt-2.5 text-[11px] font-bold hidden"></div>
                    </div>

                    <!-- Payment Methods Selection -->
                    <div class="mb-10" x-data="{ paymentMethod: 'online' }">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Select Payment Method</label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Online Payment Option -->
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="online" x-model="paymentMethod" class="absolute opacity-0" checked>
                                <div :class="paymentMethod == 'online' ? 'bg-[#3d2b1f] text-white border-[#3d2b1f] shadow-lg' : 'bg-[#fdfbf7] text-gray-500 border-gray-100'" class="p-3 sm:p-4 rounded-xl sm:rounded-2xl border-2 text-center transition-all group-hover:-translate-y-0.5 flex flex-col items-center justify-center gap-1.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    <span class="text-[10px] font-black uppercase tracking-wider">Pay Online</span>
                                </div>
                            </label>
                            <!-- Cash Payment Option -->
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="cash" x-model="paymentMethod" class="absolute opacity-0">
                                <div :class="paymentMethod == 'cash' ? 'bg-[#3d2b1f] text-white border-[#3d2b1f] shadow-lg' : 'bg-[#fdfbf7] text-gray-500 border-gray-100'" class="p-3 sm:p-4 rounded-xl sm:rounded-2xl border-2 text-center transition-all group-hover:-translate-y-0.5 flex flex-col items-center justify-center gap-1.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span class="text-[10px] font-black uppercase tracking-wider">Pay Cash</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Final Total -->
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Payable Amount</p>
                            <h4 class="text-4xl font-black text-[#3d2b1f]" id="final_total_display" style="font-family: 'Playfair Display', serif;">₹{{ number_format($totalPrice, 2) }}</h4>
                        </div>
                        <div class="w-16 h-16 bg-[#fdfbf7] rounded-full flex items-center justify-center text-[#c6a664]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <button onclick="confirmCustomBooking()" class="w-full bg-[#3d2b1f] text-white py-5 rounded-[2rem] font-bold text-lg shadow-xl shadow-[#3d2b1f]/20 hover:bg-[#c6a664] transition-all flex items-center justify-center gap-3">
                        Confirm Appointment
                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <p class="text-center text-gray-400 text-[10px] font-bold uppercase mt-8 tracking-widest">
                        Secure luxury booking powered by Easy Saloon
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    let appliedCouponCode = '';
    let baseAmount = {{ $totalPrice }};

    function applyCoupon() {
        const input = document.getElementById('coupon_input');
        const code = input.value.trim();
        const statusDiv = document.getElementById('coupon_status');
        
        if (!code) {
            Swal.fire({
                icon: 'warning',
                title: 'Enter Code',
                text: 'Please enter a coupon code.',
                confirmButtonColor: '#3d2b1f'
            });
            return;
        }
        
        statusDiv.classList.remove('hidden', 'text-green-600', 'text-red-600');
        statusDiv.classList.add('text-gray-500');
        statusDiv.innerText = 'Verifying...';
        
        fetch('{{ route("coupon.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                code: code,
                amount: baseAmount
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                appliedCouponCode = data.data.code;
                const discount = data.data.discount_amount;
                const finalTotal = data.data.final_amount;
                
                statusDiv.classList.remove('text-gray-500');
                statusDiv.classList.add('text-green-600');
                statusDiv.innerText = 'Coupon applied! You saved ₹' + discount.toFixed(2);
                
                // Show breakdown row
                document.getElementById('coupon_applied_code').innerText = data.data.code;
                document.getElementById('coupon_discount_value').innerText = discount.toFixed(2);
                document.getElementById('coupon_discount_row').classList.remove('hidden');
                
                // Update final total
                document.getElementById('final_total_display').innerText = '₹' + finalTotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            } else {
                appliedCouponCode = '';
                statusDiv.classList.remove('text-gray-500');
                statusDiv.classList.add('text-red-600');
                statusDiv.innerText = data.message || 'Invalid coupon code.';
                
                // Hide breakdown row
                document.getElementById('coupon_discount_row').classList.add('hidden');
                document.getElementById('final_total_display').innerText = '₹' + baseAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        })
        .catch(err => {
            console.error(err);
            appliedCouponCode = '';
            statusDiv.classList.remove('text-gray-500');
            statusDiv.classList.add('text-red-600');
            statusDiv.innerText = 'Failed to verify coupon.';
            
            document.getElementById('coupon_discount_row').classList.add('hidden');
            document.getElementById('final_total_display').innerText = '₹' + baseAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        });
    }

    function confirmCustomBooking() {
        const addressId = document.querySelector('input[name="address_id"]:checked')?.value;
        const type = '{{ $type }}';
        
        if (type === 'home' && !addressId) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a service address!',
                confirmButtonColor: '#3d2b1f'
            });
            return;
        }
 
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'online';

        Swal.fire({
            title: 'Securing Your Selection...',
            text: 'Please wait while we finalize your custom package.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('service_ids', '@json($serviceIds)');
        formData.append('equipment', '@json($equipment)');
        formData.append('service_type', type);
        formData.append('date', '{{ $date }}');
        formData.append('slot', '{{ $slot }}');
        formData.append('payment_method', paymentMethod);
        if (appliedCouponCode) {
            formData.append('coupon_code', appliedCouponCode);
        }
        if (addressId) formData.append('address_id', addressId);

        fetch('{{ route("packages.custom.confirm") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.is_free) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Confirmed!',
                        text: 'Your free appointment has been booked successfully!',
                        confirmButtonColor: '#3d2b1f'
                    }).then(() => {
                        window.location.href = "{{ route('dashboard.bookings') }}";
                    });
                } else if (paymentMethod === 'cash') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Confirmed!',
                        text: 'Your appointment is booked successfully. Please pay by cash after your session.',
                        confirmButtonColor: '#3d2b1f'
                    }).then(() => {
                        window.location.href = "{{ route('dashboard.bookings') }}";
                    });
                } else {
                    // Initiate Payment
                    payWithRazorpay(data.booking_id, data.booking_type);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Booking Failed',
                    text: data.message,
                    confirmButtonColor: '#3d2b1f'
                });
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.',
                confirmButtonColor: '#3d2b1f'
            });
        });
    }

    function payWithRazorpay(bookingId, bookingType) {
        Swal.fire({
            title: 'Preparing Payment Gateway...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('{{ route("payment.initiate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                booking_id: bookingId,
                type: bookingType
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) throw new Error(data.error);

            const options = {
                "key": data.key,
                "amount": data.amount,
                "currency": "INR",
                "name": "Easy Saloon",
                "description": "Custom Package Payment for #" + data.booking_number,
                "order_id": data.order_id,
                "handler": function (response) {
                    verifyPayment(response, bookingType);
                },
                "prefill": {
                    "name": data.user.name,
                    "email": data.user.email,
                    "contact": data.user.contact
                },
                "theme": {
                    "color": "#3d2b1f"
                },
                "modal": {
                    "ondismiss": function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Payment Cancelled',
                            text: 'Your booking is pending. Please complete payment from dashboard.',
                            confirmButtonColor: '#3d2b1f'
                        }).then(() => {
                            window.location.href = "{{ route('dashboard.bookings') }}";
                        });
                    }
                }
            };
            const rzp = new Razorpay(options);
            Swal.close();
            rzp.open();
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Payment Error',
                text: err.message,
                confirmButtonColor: '#3d2b1f'
            });
        });
    }

    function verifyPayment(rzpResponse, bookingType) {
        Swal.fire({
            title: 'Verifying Payment...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('{{ route("payment.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                type: bookingType,
                razorpay_order_id: rzpResponse.razorpay_order_id,
                razorpay_payment_id: rzpResponse.razorpay_payment_id,
                razorpay_signature: rzpResponse.razorpay_signature
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful!',
                    text: 'Your custom appointment has been confirmed.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = "{{ route('dashboard.bookings') }}";
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Verification Failed',
                    text: data.message,
                    confirmButtonColor: '#3d2b1f'
                });
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not verify payment. Please contact support.',
                confirmButtonColor: '#3d2b1f'
            });
        });
    }
</script>
@endsection
