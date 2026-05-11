@extends('frontend.layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center pt-32 pb-20 bg-[#fdfbf7] relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-[#c6a664]/5 rounded-full -mr-64 -mt-64 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-[#3d2b1f]/5 rounded-full -ml-64 -mb-64 blur-3xl"></div>

    <div class="max-w-xl w-full px-6 relative z-10">
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-[#3d2b1f]/10 overflow-hidden border border-gray-100">
            <div class="flex flex-col md:flex-row">
                <!-- Sidebar Branding -->
                <div class="md:w-1/3 bg-[#3d2b1f] p-12 text-center flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#c6a664]/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-[#c6a664] rounded-3xl mx-auto mb-6 flex items-center justify-center shadow-lg transform -rotate-6">
                            <svg class="w-10 h-10 text-[#3d2b1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-4" style="font-family: 'Playfair Display', serif;">Join the Elite</h2>
                        <p class="text-[#c6a664]/80 text-[10px] font-black uppercase tracking-widest leading-relaxed">Experience salon expertise at your doorstep.</p>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="md:w-2/3 p-12">
                    <div class="mb-10">
                        <h1 class="text-3xl font-black text-[#3d2b1f] mb-2" style="font-family: 'Playfair Display', serif;">Create Account</h1>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Start your journey with us</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-8 p-4 bg-red-50 rounded-2xl border border-red-100">
                            @foreach ($errors->all() as $error)
                                <p class="text-xs font-bold text-red-500 mb-1">✕ {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Full Name</label>
                            <input type="text" name="name" required value="{{ old('name') }}" placeholder="John Doe" 
                                class="w-full bg-[#fdfbf7] border-none rounded-2xl py-3.5 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664] transition-all placeholder:text-gray-200">
                        </div>
                        
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Email Address</label>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="name@example.com" 
                                class="w-full bg-[#fdfbf7] border-none rounded-2xl py-3.5 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664] transition-all placeholder:text-gray-200">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Password</label>
                                <input type="password" name="password" required placeholder="••••••••" 
                                    class="w-full bg-[#fdfbf7] border-none rounded-2xl py-3.5 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664] transition-all placeholder:text-gray-200">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Confirm</label>
                                <input type="password" name="password_confirmation" required placeholder="••••••••" 
                                    class="w-full bg-[#fdfbf7] border-none rounded-2xl py-3.5 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664] transition-all placeholder:text-gray-200">
                            </div>
                        </div>

                        <div class="flex items-start px-2 py-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" required class="w-5 h-5 rounded-lg border-gray-200 text-[#3d2b1f] focus:ring-[#c6a664] transition-all">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">I agree to the <a href="#" class="text-[#c6a664]">Terms of Service</a></span>
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-[#c6a664] text-white py-5 rounded-[2rem] font-bold text-lg shadow-xl shadow-[#c6a664]/20 hover:scale-[1.02] transition-all active:scale-[0.98] mt-4">
                            Create Account
                        </button>
                    </form>

                    <div class="mt-10 text-center">
                        <p class="text-sm text-gray-500 font-medium">Already have an account? 
                            <a href="{{ route('login') }}" class="text-[#3d2b1f] font-black uppercase tracking-widest text-[10px] border-b-2 border-[#3d2b1f] pb-1 hover:text-[#c6a664] hover:border-[#c6a664] transition-all">
                                Sign In Instead
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
