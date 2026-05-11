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
                <!-- Profile Section -->
                <section class="bg-white rounded-[2.5rem] p-12 shadow-sm border border-gray-100">
                    <h2 class="text-3xl font-bold text-[#3d2b1f] mb-8" style="font-family: 'Playfair Display', serif;">Profile Settings</h2>
                    <form id="profileForm" action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-8">
                        @csrf
                        <div class="md:col-span-2 flex flex-col items-center lg:items-start gap-6 mb-4">
                            <div class="relative group">
                                <div id="photoPreview" class="w-32 h-32 rounded-[2.5rem] bg-[#fdfbf7] flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-200">
                                    @if(auth()->user()->photo)
                                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                <label for="photoInput" class="absolute bottom-2 right-2 w-10 h-10 bg-[#c6a664] text-white rounded-2xl flex items-center justify-center shadow-lg cursor-pointer hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*" onchange="previewImage(this)">
                                </label>
                            </div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Profile Photo</p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Full Name</label>
                            <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}" required class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Email Address</label>
                            <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}" required class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Phone Number</label>
                            <input type="text" name="phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="+91" class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <div class="md:col-span-2 pt-4">
                            <button type="submit" id="profileSubmitBtn" class="bg-[#3d2b1f] text-white px-10 py-4 rounded-2xl font-bold hover:bg-[#c6a664] transition-all shadow-lg flex items-center gap-3">
                                <span class="btn-text">Save Changes</span>
                                <svg class="w-5 h-5 hidden animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </div>
                    </form>
                </section>

                <!-- Bookings Section Preview -->
                <section class="bg-white rounded-[2.5rem] p-12 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-10">
                        <h2 class="text-3xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Recent Bookings</h2>
                        <a href="{{ route('dashboard.bookings') }}" class="text-xs font-bold text-[#c6a664] uppercase tracking-widest hover:text-[#3d2b1f] transition-all">View All</a>
                    </div>
                    
                    <div class="space-y-6">
                        @forelse($recentBookings as $booking)
                            <div class="flex items-center justify-between p-6 bg-[#fdfbf7] rounded-[2rem] border border-gray-50">
                                <div class="flex items-center gap-6">
                                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-[#c6a664] shadow-sm">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-[#3d2b1f]">#{{ $booking->booking_number }}</h5>
                                        <p class="text-xs text-gray-400 font-medium">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }} at {{ $booking->time_slot }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-8">
                                    <span class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest 
                                        {{ $booking->status == 'completed' ? 'bg-green-100 text-green-600' : ($booking->status == 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-[#c6a664]/10 text-[#c6a664]') }}">
                                        {{ $booking->status }}
                                    </span>
                                    <span class="text-lg font-black text-[#3d2b1f]">₹{{ number_format($booking->payable_amount, 2) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-16 text-center border-2 border-dashed border-gray-100 rounded-[2rem]">
                                <p class="text-gray-400 font-bold mb-4">You haven't booked anything yet.</p>
                                <a href="{{ route('services.index') }}" class="inline-block bg-[#3d2b1f] text-white px-8 py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-[#c6a664] transition-all shadow-md">Book a Service</a>
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
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = document.getElementById('profileSubmitBtn');
        const btnText = btn.querySelector('.btn-text');
        const btnIcon = btn.querySelector('.animate-spin');

        btn.disabled = true;
        btnText.innerText = 'Saving...';
        btnIcon.classList.remove('hidden');

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false,
                    background: '#fff',
                    iconColor: '#c6a664'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message,
                    confirmButtonColor: '#3d2b1f'
                });
                resetBtn();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.',
                confirmButtonColor: '#3d2b1f'
            });
            resetBtn();
        });

        function resetBtn() {
            btn.disabled = false;
            btnText.innerText = 'Save Changes';
            btnIcon.classList.add('hidden');
        }
    });
</script>

<style>
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endsection
