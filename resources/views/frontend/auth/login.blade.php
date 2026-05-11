@extends('frontend.layout.app')

@section('content')
<div class="min-h-screen relative flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Premium Background Image with Blur Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/images/auth-bg.png') }}" class="w-full h-full object-cover" alt="Background">
        <div class="absolute inset-0 bg-black/85 backdrop-blur-[3px]"></div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-[#c6a664]/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-[#3d2b1f]/30 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- Auth Card -->
    <div class="relative z-10 w-full max-w-lg transform transition-all duration-700 hover:scale-[1.01]">
        <div class="bg-[#111111]/95 backdrop-blur-md border border-white/5 rounded-[2.5rem] shadow-[0_40px_80px_-15px_rgba(0,0,0,0.7)] overflow-hidden">
            
            <!-- Card Header -->
            <div class="px-10 pt-12 pb-8 text-center relative">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-1 bg-gradient-to-r from-transparent via-[#c6a664] to-transparent"></div>
                
                <!-- Branding -->
                <div class="mb-6 inline-flex items-center justify-center p-4 bg-white/5 rounded-2xl border border-white/10 backdrop-blur-md shadow-inner">
                    <svg class="w-8 h-8 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758L6.243 17.757M12 12L15.758 15.758M12 12l-2.879-2.879"></path>
                    </svg>
                </div>
                
                <h1 class="text-4xl font-bold text-white mb-2 tracking-tight" style="font-family: 'Playfair Display', serif;">Welcome Back</h1>
                <p class="text-[#c6a664] text-xs font-bold uppercase tracking-[0.3em]">The Art of Luxury Grooming</p>
            </div>

            <!-- Form Section -->
            <div class="px-10 pb-12">
                <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Input Group -->
                    <div class="space-y-2 group">
                        <label class="block text-[10px] font-black text-white uppercase tracking-[0.2em] ml-2 transition-colors group-focus-within:text-[#c6a664]">Email Address</label>
                        <div class="relative">
                            <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="name@example.com" 
                                class="block w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:bg-white/10 transition-all duration-300">
                        </div>
                    </div>

                    <!-- Input Group -->
                    <div class="space-y-2 group">
                        <div class="flex justify-between items-center ml-2 mr-2">
                            <label class="block text-[10px] font-black text-white uppercase tracking-[0.2em] transition-colors group-focus-within:text-[#c6a664]">Password</label>
                            <a href="#" class="text-[10px] font-bold text-[#c6a664] uppercase tracking-widest hover:text-white transition-colors">Forgot?</a>
                        </div>
                        <div class="relative">
                            <input type="password" name="password" id="password" required placeholder="••••••••" 
                                class="block w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:bg-white/10 transition-all duration-300">
                        </div>
                    </div>

                    <div class="flex items-center ml-2">
                        <label class="flex items-center gap-3 cursor-pointer group/check">
                            <div class="relative">
                                <input type="checkbox" name="remember" class="peer hidden">
                                <div class="w-5 h-5 border border-white/20 rounded-md bg-white/5 peer-checked:bg-[#c6a664] peer-checked:border-[#c6a664] transition-all duration-300 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white scale-0 peer-checked:scale-100 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-[11px] font-bold text-white/40 uppercase tracking-widest group-hover/check:text-white/70 transition-colors">Remember Me</span>
                        </label>
                    </div>

                    <button type="submit" id="submitBtn" class="group relative w-full overflow-hidden rounded-2xl bg-[#c6a664] py-5 px-8 text-[#3d2b1f] shadow-xl transition-all duration-300 hover:shadow-[#c6a664]/30 hover:scale-[1.02] active:scale-[0.98]">
                        <div class="absolute inset-0 bg-white/20 translate-y-[100%] group-hover:translate-y-0 transition-transform duration-300"></div>
                        <span class="relative flex items-center justify-center gap-3 font-bold text-lg tracking-widest uppercase">
                            <span class="btn-text">Sign In</span>
                            <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </span>
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-sm text-white/40 font-medium mb-3 italic">Not a member yet?</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-[#c6a664] font-bold uppercase tracking-[0.2em] text-[10px] group transition-all">
                        <span class="border-b-2 border-transparent group-hover:border-[#c6a664] pb-1">Create Your Account</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <p class="text-center mt-8 text-white/30 text-[10px] font-bold uppercase tracking-[0.3em]">
            &copy; {{ date('Y') }} Easy Saloon • Excellence in Every Detail
        </p>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnIcon = submitBtn.querySelector('.btn-icon');
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Frontend Validation
        if (!email || !password) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: 'Please fill in all fields',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1a1a1a',
                color: '#fff',
                iconColor: '#c6a664'
            });
            return;
        }

        // Loading State
        submitBtn.disabled = true;
        btnText.innerText = 'Signing In...';
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
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    background: '#1a1a1a',
                    color: '#fff',
                    iconColor: '#c6a664'
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#1a1a1a',
                    color: '#fff',
                    iconColor: '#ef4444'
                });
                // Reset Button
                submitBtn.disabled = false;
                btnText.innerText = 'Sign In';
                btnIcon.classList.remove('animate-spin');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Something went wrong. Please try again.',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1a1a1a',
                color: '#fff',
                iconColor: '#ef4444'
            });
            submitBtn.disabled = false;
            btnText.innerText = 'Sign In';
            btnIcon.classList.remove('animate-spin');
        });
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


