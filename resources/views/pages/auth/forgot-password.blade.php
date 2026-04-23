<x-guest-layout>
    <div class="text-center mb-10 animate-fade-in" style="animation-delay: 0.1s;">
        <h2 class="text-3xl font-serif italic font-bold text-foreground mb-2">Reset Password</h2>
        <p class="text-sm text-muted-foreground">Forgot your password? No problem. Just let us know your email address and we'll send you a reset link.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700" :status="session('status')" />

    <form method="POST" action="{{ route('password.email', absolute: false) }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="animate-fade-in" style="animation-delay: 0.2s;">
            <label for="email" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Email address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-primary text-muted-foreground">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border border-border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm hover:border-muted-foreground/30" 
                    placeholder="you@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-medium text-warning ml-1" />
        </div>

        <div class="pt-2 animate-fade-in" style="animation-delay: 0.3s;">
            <button type="submit" class="w-full bg-primary text-primary-foreground py-4 rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 group">
                <span>Send Reset Link</span>
                <i class="fa-solid fa-paper-plane text-xs group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>

    <div class="mt-10 text-center text-sm text-muted-foreground animate-fade-in" style="animation-delay: 0.4s;">
        Remembered your password? 
        <a href="{{ route('login') }}" class="font-bold text-primary hover:text-primary/80 transition-colors">Sign in</a>
    </div>
</x-guest-layout>
