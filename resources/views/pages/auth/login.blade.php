<x-guest-layout>
    <div class="text-center mb-10 animate-fade-in" style="animation-delay: 0.1s;">
        <h2 class="text-3xl font-serif italic font-bold text-foreground mb-2">Welcome back</h2>
        <p class="text-sm text-muted-foreground">Enter your details to access your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 p-4 rounded-xl bg-primary/10 border border-primary/20 text-primary" :status="session('status')" />

    <form method="POST" action="{{ route('login', absolute: false) }}" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <!-- Email Address -->
        <div class="animate-fade-in" style="animation-delay: 0.2s;">
            <label for="email" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Email address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-primary text-muted-foreground">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email', 'admin@marketplace.com') }}" required autofocus autocomplete="username" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border border-border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm hover:border-muted-foreground/30" 
                    placeholder="you@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-medium text-warning ml-1" />
        </div>

        <!-- Password -->
        <div class="animate-fade-in" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between mb-2 ml-1">
                <label for="password" class="block text-sm font-medium text-foreground/80">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-primary hover:text-primary/80 transition-colors uppercase tracking-wider">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-primary text-muted-foreground">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border border-border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm hover:border-muted-foreground/30" 
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-medium text-warning ml-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between animate-fade-in" style="animation-delay: 0.4s;">
            <label for="remember_me" class="flex items-center cursor-pointer group">
                <div class="relative flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="peer h-5 w-5 rounded-lg border-border text-primary focus:ring-primary/20 bg-input cursor-pointer transition-all">
                </div>
                <span class="ml-3 text-sm text-muted-foreground group-hover:text-foreground transition-colors font-medium">
                    Remember me for 30 days
                </span>
            </label>
        </div>

        <div class="pt-2 animate-fade-in" style="animation-delay: 0.5s;">
            <button type="submit" 
                class="w-full bg-primary text-primary-foreground py-4 rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 group disabled:opacity-70 disabled:cursor-not-allowed"
                :disabled="loading">
                <template x-if="!loading">
                    <div class="flex items-center gap-2">
                        <span>Sign In</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </template>
                <template x-if="loading" x-cloak>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-notch animate-spin"></i>
                        <span>Processing...</span>
                    </div>
                </template>
            </button>
        </div>
    </form>

    <div class="mt-10 text-center text-sm text-muted-foreground animate-fade-in" style="animation-delay: 0.6s;">
        Don't have an account? 
        <a href="{{ route('register') }}" class="font-bold text-primary hover:text-primary/80 transition-colors">Sign up</a>
    </div>
</x-guest-layout>
