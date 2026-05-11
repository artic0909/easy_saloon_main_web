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
            <main class="flex-1 space-y-8 md:space-y-12">
                <section class="bg-white rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-12 shadow-sm border border-gray-100">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                        <div>
                            <h2 class="text-3xl font-bold text-[#3d2b1f]" style="font-family: 'Playfair Display', serif;">Saved Addresses</h2>
                            <p class="text-gray-400 text-sm mt-2">Manage your delivery and service locations</p>
                        </div>
                        <button onclick="toggleAddressForm()" class="w-full md:w-auto bg-[#3d2b1f] text-white px-8 py-4 rounded-2xl font-bold hover:bg-[#c6a664] transition-all shadow-lg flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add New Address
                        </button>
                    </div>

                    <!-- Add/Edit Address Form (Hidden by default) -->
                    <div id="addressFormSection" class="hidden mb-12 p-10 bg-[#fdfbf7] rounded-[2.5rem] border border-gray-100">
                        <h3 id="formTitle" class="text-xl font-bold text-[#3d2b1f] mb-8">Add New Address</h3>
                        <form id="addressForm" action="{{ route('dashboard.addresses.save') }}" method="POST" class="grid md:grid-cols-2 gap-8">
                            @csrf
                            <input type="hidden" name="address_id" id="address_id">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Address Title (e.g. Home, Work)</label>
                                <input type="text" name="title" id="field_title" required placeholder="Home" class="w-full bg-white border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Landmark (Optional)</label>
                                <input type="text" name="landmark" id="field_landmark" placeholder="Near Central Park" class="w-full bg-white border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Full Address</label>
                                <input type="text" name="full_address" id="field_full_address" required placeholder="123 Luxury Street, Apartment 4B" class="w-full bg-white border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">City</label>
                                <input type="text" name="city" id="field_city" required placeholder="City Name" class="w-full bg-white border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">State</label>
                                <input type="text" name="state" id="field_state" required placeholder="State Name" class="w-full bg-white border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Country</label>
                                <input type="text" name="country" id="field_country" required placeholder="Country Name" class="w-full bg-white border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                            </div>
                            <div class="flex items-center gap-3 ml-4">
                                <input type="checkbox" name="is_primary" id="is_primary" class="w-5 h-5 text-[#c6a664] bg-white border-gray-200 rounded-lg focus:ring-[#c6a664]">
                                <label for="is_primary" class="text-xs font-bold text-[#3d2b1f]">Set as primary address</label>
                            </div>
                            <div class="md:col-span-2 flex gap-4 pt-4">
                                <button type="submit" class="bg-[#3d2b1f] text-white px-10 py-4 rounded-2xl font-bold hover:bg-[#c6a664] transition-all shadow-lg">Save Address</button>
                                <button type="button" onclick="toggleAddressForm()" class="px-10 py-4 rounded-2xl font-bold text-gray-400 hover:text-[#3d2b1f] transition-all">Cancel</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6 md:gap-8">
                        @forelse($addresses as $address)
                            <div class="group p-6 md:p-8 bg-[#fdfbf7] rounded-[2rem] md:rounded-[2.5rem] border {{ $address->is_primary ? 'border-[#c6a664]/30 shadow-xl shadow-[#c6a664]/5' : 'border-gray-50' }} hover:bg-white hover:shadow-2xl transition-all duration-500 relative overflow-hidden">
                                @if($address->is_primary)
                                    <div class="absolute top-0 right-0 bg-[#c6a664] text-white px-4 py-1.5 rounded-bl-2xl text-[8px] font-black uppercase tracking-widest">Primary</div>
                                @endif
                                
                                <div class="flex flex-col sm:flex-row items-start gap-4 md:gap-6">
                                    <div class="w-12 h-12 md:w-14 md:h-14 bg-white rounded-2xl flex items-center justify-center text-[#c6a664] shadow-sm flex-shrink-0">
                                        @if(strtolower($address->title) == 'home')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        @elseif(strtolower($address->title) == 'work' || strtolower($address->title) == 'office')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h5 class="text-lg md:text-xl font-bold text-[#3d2b1f] mb-2 md:mb-3 whitespace-normal break-words">{{ $address->title }}</h5>
                                        <p class="text-xs md:text-sm text-gray-500 leading-relaxed mb-4 whitespace-normal break-words">{{ $address->full_address }}</p>
                                        <div class="flex flex-wrap gap-x-2 gap-y-2">
                                            <span class="text-[9px] md:text-[10px] font-black text-[#c6a664] uppercase tracking-widest bg-[#c6a664]/5 px-2 py-0.5 rounded-md inline-block">{{ $address->city->name ?? '' }}</span>
                                            <span class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest inline-block">{{ $address->state->name ?? '' }}</span>
                                            <span class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest inline-block">{{ $address->country->name ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6 md:mt-8 pt-6 border-t border-white/50 flex gap-4 opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="editAddress({{ json_encode($address->only(['id', 'title', 'full_address', 'landmark', 'is_primary']) + ['city' => $address->city->name ?? '', 'state' => $address->state->name ?? '', 'country' => $address->country->name ?? '']) }})" class="text-xs font-bold text-[#3d2b1f] hover:text-[#c6a664] transition-all flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </button>
                                    <button onclick="deleteAddress({{ $address->id }})" class="text-xs font-bold text-red-400 hover:text-red-600 transition-all flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="md:col-span-2 py-24 text-center border-2 border-dashed border-gray-100 rounded-[3rem]">
                                <p class="text-gray-400 font-bold mb-4">No addresses saved yet.</p>
                                <button onclick="toggleAddressForm()" class="text-[#c6a664] font-black uppercase tracking-widest text-xs hover:text-[#3d2b1f] transition-all">Add your first address</button>
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
    function toggleAddressForm() {
        const section = document.getElementById('addressFormSection');
        const form = document.getElementById('addressForm');
        const title = document.getElementById('formTitle');
        
        if (section.classList.contains('hidden')) {
            form.reset();
            document.getElementById('address_id').value = '';
            title.innerText = 'Add New Address';
            section.classList.remove('hidden');
            section.scrollIntoView({ behavior: 'smooth' });
        } else {
            section.classList.add('hidden');
        }
    }

    function editAddress(address) {
        const section = document.getElementById('addressFormSection');
        const title = document.getElementById('formTitle');
        
        document.getElementById('address_id').value = address.id;
        document.getElementById('field_title').value = address.title;
        document.getElementById('field_landmark').value = address.landmark || '';
        document.getElementById('field_full_address').value = address.full_address;
        document.getElementById('field_city').value = address.city;
        document.getElementById('field_state').value = address.state;
        document.getElementById('field_country').value = address.country;
        document.getElementById('is_primary').checked = address.is_primary;
        
        title.innerText = 'Edit Address';
        section.classList.remove('hidden');
        section.scrollIntoView({ behavior: 'smooth' });
    }

    document.getElementById('addressForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
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
                    title: 'Success!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    });

    function deleteAddress(id) {
        Swal.fire({
            title: 'Delete Address?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, delete it!',
            background: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/dashboard/addresses/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.message, 'success').then(() => location.reload());
                    }
                });
            }
        });
    }
</script>
@endsection
