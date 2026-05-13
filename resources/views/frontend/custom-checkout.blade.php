@extends('frontend.layout.app')

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
                <div class="bg-white rounded-[2.5rem] md:rounded-[3rem] p-8 md:p-10 shadow-sm border border-gray-100">
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
                                    <span class="font-black text-[#c6a664]">₹{{ number_format($service->sale_price, 2) }}</span>
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
                <div class="bg-white rounded-[2.5rem] md:rounded-[3rem] p-8 md:p-10 shadow-sm border border-gray-100">
                    @if($type == 'home')
                        <h3 class="text-xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">Service Address</h3>
                        <div class="grid md:grid-cols-2 gap-4" x-data="{ selectedAddress: '{{ $userAddresses->where('is_primary', true)->first()->id ?? ($userAddresses->first()->id ?? '') }}' }">
                            @forelse($userAddresses as $address)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" x-model="selectedAddress" class="absolute opacity-0">
                                    <div :class="selectedAddress == {{ $address->id }} ? 'bg-[#3d2b1f] text-white border-[#3d2b1f] shadow-xl' : 'bg-[#fdfbf7] text-gray-500 border-gray-50'" class="p-6 rounded-3xl border-2 transition-all group-hover:-translate-y-1">
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
                            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-[#c6a664] flex-shrink-0">
                                <i class="bi bi-shop text-3xl"></i>
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
                <div class="bg-white rounded-[3rem] p-10 md:p-12 shadow-2xl shadow-gray-200/50 border border-gray-100 sticky top-40">
                    <h3 class="text-xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">Booking Summary</h3>
                    
                    <div class="space-y-6 mb-10 pb-10 border-b border-gray-50">
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
                    </div>

                    <!-- Final Total -->
                    <div class="flex justify-between items-center mb-12">
                        <div>
                            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Payable Amount</p>
                            <h4 class="text-4xl font-black text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">₹{{ number_format($totalPrice, 2) }}</h4>
                        </div>
                        <div class="w-16 h-16 bg-[#fdfbf7] rounded-full flex items-center justify-center text-[#c6a664]">
                            <i class="bi bi-check-circle-fill text-3xl"></i>
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
                // Initiate Payment
                payWithRazorpay(data.booking_id, data.booking_type);
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
