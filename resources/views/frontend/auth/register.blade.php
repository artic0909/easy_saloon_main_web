@extends('frontend.layout.app')

@section('content')
<div class="min-h-screen relative flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Premium Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/images/auth-bg.png') }}" class="w-full h-full object-cover" alt="Background" onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&q=80'">
        <div class="absolute inset-0 bg-black/70 lg:bg-black/50 backdrop-blur-[2px] lg:backdrop-blur-[1px]"></div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[10%] -right-[10%] w-[50%] h-[50%] bg-[#c6a664]/10 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -left-[10%] w-[50%] h-[50%] bg-[#3d2b1f]/20 rounded-full blur-[150px] animate-pulse" style="animation-delay: 3s;"></div>
    </div>

    <!-- Auth Card -->
    <div class="relative z-10 w-full max-w-2xl transform transition-all duration-700">
        <div class="bg-[#111111]/90 lg:bg-[#111111]/80 backdrop-blur-md border border-white/5 rounded-[3rem] shadow-[0_40px_80px_-15px_rgba(0,0,0,0.7)] overflow-hidden">
            
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
                <div class="lg:w-2/3 p-10 lg:p-14 relative">
                    <div class="mb-10 text-center lg:text-left">
                        <h1 class="text-4xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">Create Account</h1>
                        <p id="stepIndicator" class="text-[#c6a664] text-xs font-bold uppercase tracking-[0.2em]">Step 1: Verify Mobile Number</p>
                    </div>

                    <form id="registerForm" action="{{ route('register.post') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Step 1: Phone -->
                        <div id="step1" class="space-y-6 transition-all duration-500">
                            <div class="space-y-2 group">
                                <label class="block text-[10px] font-black text-white uppercase tracking-[0.2em] ml-2 transition-colors group-focus-within:text-[#c6a664]">Mobile Number</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-5 rounded-l-2xl border border-r-0 border-white/10 bg-white/10 text-white/50 font-bold">+91</span>
                                    <input type="tel" name="phone" id="phone" required placeholder="9876543210" maxlength="10"
                                        class="block w-full bg-white/5 border border-white/10 rounded-r-2xl py-4 px-6 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:bg-white/10 transition-all duration-300">
                                </div>
                            </div>
                            <button type="button" id="sendOtpBtn" class="group relative w-full overflow-hidden rounded-2xl bg-[#c6a664] py-5 px-8 text-[#3d2b1f] shadow-xl transition-all duration-300 hover:shadow-[#c6a664]/30 hover:scale-[1.02] active:scale-[0.98]">
                                <div class="absolute inset-0 bg-white/20 translate-y-[100%] group-hover:translate-y-0 transition-transform duration-300"></div>
                                <span class="relative flex items-center justify-center gap-3 font-bold text-lg tracking-widest uppercase">
                                    <span class="btn-text">Send OTP</span>
                                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>

                        <!-- Step 2: OTP -->
                        <div id="step2" class="space-y-6 hidden transition-all duration-500">
                            <div class="space-y-2 group">
                                <label class="block text-[10px] font-black text-white uppercase tracking-[0.2em] ml-2 transition-colors group-focus-within:text-[#c6a664]">Enter 6-Digit OTP</label>
                                <div class="relative">
                                    <input type="text" id="otp" placeholder="••••••" maxlength="6"
                                        class="block w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-center text-white tracking-widest placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:bg-white/10 transition-all duration-300">
                                </div>
                                <p class="text-right mt-2 text-[10px] text-white/50 font-bold uppercase cursor-pointer hover:text-[#c6a664] transition-colors" onclick="resendOtp()">Resend OTP</p>
                            </div>
                            <div class="flex gap-4">
                                <button type="button" onclick="goToStep(1)" class="w-1/3 rounded-2xl border border-white/20 bg-transparent py-5 px-4 text-white font-bold text-sm tracking-widest uppercase hover:bg-white/5 transition-all">
                                    Back
                                </button>
                                <button type="button" id="verifyOtpBtn" class="group relative w-2/3 overflow-hidden rounded-2xl bg-[#c6a664] py-5 px-8 text-[#3d2b1f] shadow-xl transition-all duration-300 hover:shadow-[#c6a664]/30 hover:scale-[1.02] active:scale-[0.98]">
                                    <div class="absolute inset-0 bg-white/20 translate-y-[100%] group-hover:translate-y-0 transition-transform duration-300"></div>
                                    <span class="relative flex items-center justify-center gap-3 font-bold text-sm tracking-widest uppercase">
                                        <span class="btn-text">Verify OTP</span>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Password -->
                        <div id="step3" class="space-y-6 hidden transition-all duration-500">
                            <!-- Password Input Group -->
                            <div class="space-y-2 group">
                                <label class="block text-[10px] font-black text-white uppercase tracking-[0.2em] ml-2 transition-colors group-focus-within:text-[#c6a664]">Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" placeholder="••••••••" 
                                        class="block w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-6 pr-12 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:bg-white/10 transition-all duration-300">
                                    <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-5 flex items-center text-white/30 hover:text-[#c6a664] transition-colors">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            <path class="eye-closed hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password Input Group -->
                            <div class="space-y-2 group">
                                <label class="block text-[10px] font-black text-white uppercase tracking-[0.2em] ml-2 transition-colors group-focus-within:text-[#c6a664]">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" 
                                        class="block w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-6 pr-12 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-[#c6a664]/50 focus:bg-white/10 transition-all duration-300">
                                    <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-5 flex items-center text-white/30 hover:text-[#c6a664] transition-colors">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            <path class="eye-closed hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-start ml-2 py-2">
                                <label class="flex items-center gap-3 cursor-pointer group/check">
                                    <div class="relative">
                                        <input type="checkbox" id="terms" class="peer hidden">
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
                        </div>
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
    let currentStep = 1;

    function goToStep(step) {
        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.add('hidden');
        document.getElementById('step3').classList.add('hidden');
        
        document.getElementById(`step${step}`).classList.remove('hidden');
        currentStep = step;

        const indicator = document.getElementById('stepIndicator');
        if (step === 1) indicator.innerText = 'Step 1: Verify Mobile Number';
        if (step === 2) indicator.innerText = 'Step 2: Enter OTP';
        if (step === 3) indicator.innerText = 'Step 3: Secure Account';
    }

    // Step 1: Send OTP
    document.getElementById('sendOtpBtn').addEventListener('click', function() {
        const phone = document.getElementById('phone').value;
        if (!phone || phone.length < 10) {
            toast('Please enter a valid 10-digit mobile number', 'warning');
            return;
        }

        const btn = this;
        const btnText = btn.querySelector('.btn-text');
        const btnIcon = btn.querySelector('.btn-icon');
        
        btn.disabled = true;
        btnText.innerText = 'Sending...';
        btnIcon.classList.add('animate-spin');

        fetch("{{ route('register.send-otp') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone: '+91' + phone })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btnText.innerText = 'Send OTP';
            btnIcon.classList.remove('animate-spin');

            if (data.success) {
                toast(data.message, 'success');
                goToStep(2);
            } else {
                toast(data.message, 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btnText.innerText = 'Send OTP';
            btnIcon.classList.remove('animate-spin');
            toast('Failed to send OTP', 'error');
        });
    });

    // Resend OTP
    function resendOtp() {
        document.getElementById('sendOtpBtn').click();
    }

    // Step 2: Verify OTP
    document.getElementById('verifyOtpBtn').addEventListener('click', function() {
        const phone = document.getElementById('phone').value;
        const otp = document.getElementById('otp').value;
        if (!otp || otp.length < 6) {
            toast('Please enter the 6-digit OTP', 'warning');
            return;
        }

        const btn = this;
        const btnText = btn.querySelector('.btn-text');
        
        btn.disabled = true;
        btnText.innerText = 'Verifying...';

        fetch("{{ route('register.verify-otp') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone: '+91' + phone, otp: otp })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btnText.innerText = 'Verify OTP';

            if (data.success) {
                toast(data.message, 'success');
                goToStep(3);
            } else {
                toast(data.message, 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btnText.innerText = 'Verify OTP';
            toast('Failed to verify OTP', 'error');
        });
    });

    // Step 3: Create Account
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (currentStep !== 3) return;
        
        const form = this;
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnIcon = submitBtn.querySelector('.btn-icon');
        
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;
        const terms = document.getElementById('terms').checked;

        if (!password || !password_confirmation) {
            toast('Please fill in passwords', 'warning');
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

        submitBtn.disabled = true;
        btnText.innerText = 'Creating...';
        btnIcon.classList.add('animate-spin');

        const formData = new FormData();
        formData.append('phone', '+91' + phone);
        formData.append('password', password);
        formData.append('password_confirmation', password_confirmation);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
            toast('Something went wrong. Please try again.', 'error');
            resetBtn();
        });

        function resetBtn() {
            submitBtn.disabled = false;
            btnText.innerText = 'Create Account';
            btnIcon.classList.remove('animate-spin');
        }
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

    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const openIcons = btn.querySelectorAll('.eye-open');
        const closedIcon = btn.querySelector('.eye-closed');
        
        if (input.type === 'password') {
            input.type = 'text';
            openIcons.forEach(i => i.classList.add('hidden'));
            closedIcon.classList.remove('hidden');
        } else {
            input.type = 'password';
            openIcons.forEach(i => i.classList.remove('hidden'));
            closedIcon.classList.add('hidden');
        }
    }
</script>

<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>
@endsection
