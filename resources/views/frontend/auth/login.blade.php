@extends('frontend.layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center pt-32 pb-20 bg-[#fdfbf7] relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#c6a664]/5 rounded-full -mr-64 -mt-64 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#3d2b1f]/5 rounded-full -ml-64 -mb-64 blur-3xl"></div>

    <div class="max-w-md w-full px-6 relative z-10">
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-[#3d2b1f]/10 overflow-hidden border border-gray-100">
            <!-- Header Section -->
            <div class="bg-[#3d2b1f] p-12 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#c6a664]/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative z-10">
                    <h1 class="text-4xl font-bold text-white mb-3" style="font-family: 'Playfair Display', serif;">Welcome Back</h1>
                    <p class="text-[#c6a664] text-[10px] font-black uppercase tracking-[0.2em]">Luxury Grooming Awaits</p>
                </div>
            </div>

            <!-- Form Section -->
            <div class="p-12">
                @if ($errors->any())
                    <div class="mb-8 p-4 bg-red-50 rounded-2xl border border-red-100">
                        @foreach ($errors->all() as $error)
                            <p class="text-xs font-bold text-red-500 mb-1">✕ {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-4">Email Address</label>
                        <input type="email" name="email" required value="{{ old('email') }}" placeholder="name@example.com" 
                            class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664] transition-all placeholder:text-gray-200">
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-4">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Password</label>
                            <a href="#" class="text-[10px] font-black text-[#c6a664] uppercase tracking-widest hover:text-[#3d2b1f] transition-colors">Forgot?</a>
                        </div>
                        <input type="password" name="password" required placeholder="••••••••" 
                            class="w-full bg-[#fdfbf7] border-none rounded-2xl py-4 px-6 text-sm font-bold text-[#3d2b1f] focus:ring-2 focus:ring-[#c6a664] transition-all placeholder:text-gray-200">
                    </div>

                    <div class="flex items-center px-4 py-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-5 h-5 rounded-lg border-gray-200 text-[#3d2b1f] focus:ring-[#c6a664] transition-all">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest group-hover:text-[#3d2b1f] transition-colors">Remember Me</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-[#3d2b1f] text-white py-5 rounded-[2rem] font-bold text-lg shadow-xl shadow-[#3d2b1f]/20 hover:scale-[1.02] transition-all active:scale-[0.98]">
                        Sign In
                    </button>
                </form>

                <div class="mt-12 text-center">
                    <p class="text-sm text-gray-500 font-medium italic">New to Easy Saloon?</p>
                    <a href="{{ route('register') }}" class="inline-block mt-2 text-[#3d2b1f] font-black uppercase tracking-widest text-[10px] border-b-2 border-[#c6a664] pb-1 hover:text-[#c6a664] transition-all">
                        Create Your Account
                    </a>
                </div>
            </div>

            <!-- Footer Branding -->
            <div class="bg-[#fdfbf7] p-8 text-center border-t border-gray-100">
                <div class="flex items-center justify-center gap-3">
                    <div class="w-6 h-6 bg-[#3d2b1f] rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4 text-[#c6a664]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758L6.243 17.757M12 12L15.758 15.758M12 12l-2.879-2.879"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-[#3d2b1f] tracking-tight" style="font-family: 'Playfair Display', serif;">Easy Saloon</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
