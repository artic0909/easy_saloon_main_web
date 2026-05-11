@extends('frontend.layout.app')

@section('content')
<div class="min-h-screen relative flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Premium Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/images/auth-bg.png') }}" class="w-full h-full object-cover" alt="Background">
        <div class="absolute inset-0 bg-black/85 backdrop-blur-[3px]"></div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[10%] -right-[10%] w-[50%] h-[50%] bg-[#c6a664]/10 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -left-[10%] w-[50%] h-[50%] bg-[#3d2b1f]/20 rounded-full blur-[150px] animate-pulse" style="animation-delay: 3s;"></div>
    </div>

    <!-- Auth Card -->
    <div class="relative z-10 w-full max-w-2xl transform transition-all duration-700">
        <div class="bg-[#111111]/95 backdrop-blur-md border border-white/5 rounded-[3rem] shadow-[0_40px_80px_-15px_rgba(0,0,0,0.7)] overflow-hidden">
            
            <div class="flex flex-col lg:flex-row">
                <!-- Side Branding (Visible on large screens) -->
                <div class="hidden lg:flex lg:w-1/3 bg-gradient-to-br from-[#3d2b1f]/80 to-black/80 p-12 flex-col justify-between border-r border-white/10 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-[#c6a664]/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                    
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-[#c6a664] rounded-2xl flex items-center justify-center shadow-lg mb-8 transform -rotate-6">
                            <svg class="w-8 h-8 text-[#3d2b1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-6 leading-tight" style="font-family: 'Playfair Display', serif;">Join the Elite</h2>
                        <p class="text-[#c6a664]/80 text-[10px] font-bold uppercase tracking-[0.2em] leading-relaxed">Experience world-class salon expertise in the comfort of your sanctuary.</p>
                    </div>

                    <div class="relative z-10 pt-12 border-t border-white/10">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center border border-white/10">
                                <svg class="w-4 h-4 text-[#c6a664]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest">Premium Service</p>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="lg:w-2/3 p-10 lg:p-14">
                    <div class="mb-10 text-center lg:text-left">
                        <h1 class="text-4xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">Create Account</h1>
                        <p class="text-[#c6a664] text-xs font-bold uppercase tracking-[0.2em]">Start your journey with us</p>
                    </div>

                    <form id="registerForm" action="{{ route('register.post') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Input Group -->
                        <div class="space-y-2 group">
                            <label class="block text-[10px] font-black text-white uppercase tracking-[0.2em] ml-2 transition-colors group-focus-within:text-[#c6a664]">Full Name</label>
                            <div class="relative">
                                <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="John Doe" 
                                    class="block w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:bg-white/10 transition-all duration-300">
                            </div>
                        </div>

                        <!-- Input Group -->
                        <div class="space-y-2 group">
                            <label class="block text-[10px] font-black text-white uppercase tracking-[0.2em] ml-2 transition-colors group-focus-within:text-[#c6a664]">Email Address</label>
                            <div class="relative">
                                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="name@example.com" 
                                    class="block w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:bg-white/10 transition-all duration-300">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Input Group -->
                            <div class="space-y-2 group">
                                <label class="block text-[10px] font-black text-white uppercase tracking-[0.2em] ml-2 transition-colors group-focus-within:text-[#c6a664]">Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required placeholder="••••••••" 
                                        class="block w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:bg-white/10 transition-all duration-300">
                                </div>
                            </div>
                            <!-- Input Group -->
                            <div class="space-y-2 group">
                                <label class="block text-[10px] font-black text-white uppercase tracking-[0.2em] ml-2 transition-colors group-focus-within:text-[#c6a664]">Confirm</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••" 
                                        class="block w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:bg-white/10 transition-all duration-300">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start ml-2 py-2">
                            <label class="flex items-center gap-3 cursor-pointer group/check">
                                <div class="relative">
                                    <input type="checkbox" id="terms" required class="peer hidden">
                                    <div class="w-5 h-5 border border-white/20 rounded-md bg-white/5 peer-checked:bg-[#c6a664] peer-checked:border-[#c6a664] transition-all duration-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white scale-0 peer-checked:scale-100 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em] leading-relaxed group-hover/check:text-white/70 transition-colors">
                                    I agree to the <a href="#" class="text-[#c6a664] hover:underline">Terms of Service</a>
                                </span>
                            </label>
                        </div>

                        <button type="submit" id="submitBtn" class="group relative w-full overflow-hidden rounded-2xl bg-[#c6a664] py-5 px-8 text-[#3d2b1f] shadow-xl transition-all duration-300 hover:shadow-[#c6a664]/30 hover:scale-[1.02] active:scale-[0.98]">
                            <div class="absolute inset-0 bg-white/20 translate-y-[100%] group-hover:translate-y-0 transition-transform duration-300"></div>
                            <span class="relative flex items-center justify-center gap-3 font-bold text-lg tracking-widest uppercase">
                                <span class="btn-text">Create Account</span>
                                <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                        </button>
                    </form>

                    <div class="mt-12 text-center lg:text-left">
                        <p class="text-sm text-white/40 font-medium">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="inline-block ml-2 text-[#c6a664] font-bold uppercase tracking-[0.2em] text-[10px] group transition-all">
                                <span class="border-b-2 border-transparent group-hover:border-[#c6a664] pb-1">Sign In Instead</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <p class="text-center mt-8 text-white/30 text-[10px] font-bold uppercase tracking-[0.3em]">
            &copy; {{ date('Y') }} Easy Saloon • Defined by Luxury, Driven by Style
        </p>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnIcon = submitBtn.querySelector('.btn-icon');
        
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;
        const terms = document.getElementById('terms').checked;

        // Frontend Validation
        if (!name || !email || !password || !password_confirmation) {
            toast('Please fill in all fields', 'warning');
            return;
        }

        if (password !== password_confirmation) {
            toast('Passwords do not match', 'error');
            return;
        }

        if (password.length < 8) {
            toast('Password must be at least 8 characters', 'warning');
            return;
        }

        if (!terms) {
            toast('Please agree to the Terms of Service', 'warning');
            return;
        }

        // Loading State
        submitBtn.disabled = true;
        btnText.innerText = 'Creating...';
        btnIcon.classList.add('animate-spin');

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toast(data.message, 'success');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                toast(data.message, 'error');
                resetBtn();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toast('Something went wrong. Please try again.', 'error');
            resetBtn();
        });

        function toast(message, icon) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1a1a1a',
                color: '#fff',
                iconColor: icon === 'success' ? '#c6a664' : (icon === 'error' ? '#ef4444' : '#facc15')
            });
        }

        function resetBtn() {
            submitBtn.disabled = false;
            btnText.innerText = 'Create Account';
            btnIcon.classList.remove('animate-spin');
        }
    });
</script>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake {
        animation: shake 0.4s ease-in-out 0s 2;
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endsection

