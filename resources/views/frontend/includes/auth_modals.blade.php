<!-- Auth Modals Container -->
<div x-data="{ 
    authModal: false, 
    authMode: 'login',
    openLogin() { this.authMode = 'login'; this.authModal = true; },
    openRegister() { this.authMode = 'register'; this.authModal = true; }
}" 
@open-login.window="openLogin()" 
@open-register.window="openRegister()"
class="relative">

    <!-- Modal Backdrop -->
    <div x-show="authModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] bg-[#3d2b1f]/60 backdrop-blur-sm flex items-center justify-center p-4"
         x-cloak>
        
        <!-- Modal Content -->
        <div @click.away="authModal = false"
             x-show="authModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="bg-white w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl relative">
            
            <!-- Close Button -->
            <button @click="authModal = false" class="absolute top-6 right-6 text-gray-400 hover:text-[#3d2b1f] transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Modal Header -->
            <div class="bg-[#3d2b1f] p-10 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#c6a664]/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative z-10">
                    <h3 x-text="authMode === 'login' ? 'Welcome Back' : 'Join Easy Saloon'" class="text-3xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;"></h3>
                    <p class="text-[#c6a664] text-xs font-medium uppercase tracking-widest" x-text="authMode === 'login' ? 'Login to your account' : 'Start your luxury journey'"></p>
                </div>
            </div>

            <!-- Form Container -->
            <div class="p-10">
                <!-- Login Mode -->
                <div x-show="authMode === 'login'">
                    <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Email Address</label>
                            <input type="email" name="email" required class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Password</label>
                            <input type="password" name="password" required class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <div class="flex items-center justify-between px-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-200 text-[#3d2b1f] focus:ring-[#c6a664]">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Remember Me</span>
                            </label>
                            <a href="#" class="text-xs font-bold text-[#c6a664] uppercase tracking-widest hover:text-[#3d2b1f]">Forgot?</a>
                        </div>
                        <button type="submit" class="w-full bg-[#3d2b1f] text-white py-5 rounded-[2rem] font-bold text-lg shadow-xl hover:scale-[1.02] transition-all active:scale-[0.98]">
                            Sign In
                        </button>
                    </form>
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-500">Don't have an account? 
                            <button @click="authMode = 'register'" class="text-[#3d2b1f] font-bold hover:text-[#c6a664] transition-colors">Create Account</button>
                        </p>
                    </div>
                </div>

                <!-- Register Mode -->
                <div x-show="authMode === 'register'">
                    <form action="{{ route('register.post') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Full Name</label>
                            <input type="text" name="name" required class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Email Address</label>
                            <input type="email" name="email" required class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Password</label>
                            <input type="password" name="password" required class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664]">
                        </div>
                        <button type="submit" class="w-full bg-[#c6a664] text-white py-5 rounded-[2rem] font-bold text-lg shadow-xl hover:scale-[1.02] transition-all active:scale-[0.98]">
                            Join Now
                        </button>
                    </form>
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-500">Already have an account? 
                            <button @click="authMode = 'login'" class="text-[#3d2b1f] font-bold hover:text-[#c6a664] transition-colors">Sign In</button>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Footer Branding -->
            <div class="bg-[#fdfbf7] p-6 text-center border-t border-gray-100">
                <div class="flex items-center justify-center gap-2">
                    <div class="w-5 h-5 bg-[#3d2b1f] rounded flex items-center justify-center">
                        <svg class="w-3 h-3 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758L6.243 17.757M12 12L15.758 15.758M12 12l-2.879-2.879"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-[#3d2b1f] tracking-tight" style="font-family: 'Playfair Display', serif;">Easy Saloon</span>
                </div>
            </div>
        </div>
    </div>
</div>
