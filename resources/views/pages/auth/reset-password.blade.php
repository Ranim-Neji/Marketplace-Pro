<x-guest-layout>
    <div class="text-center mb-10 animate-fade-in" style="animation-delay: 0.1s;">
        <h2 class="text-3xl font-serif italic font-bold text-foreground mb-2">Set New Password</h2>
        <p class="text-sm text-muted-foreground">Please enter your new password below to regain access to your account.</p>
    </div>

    <form method="POST" action="{{ route('password.store', absolute: false) }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="animate-fade-in" style="animation-delay: 0.2s;">
            <label for="email" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Email address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-primary text-muted-foreground">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border border-border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm hover:border-muted-foreground/30" 
                    placeholder="you@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-medium text-red-500 ml-1" />
        </div>

        <!-- Password -->
        <div class="animate-fade-in" style="animation-delay: 0.3s;">
            <label for="password" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">New Password</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-primary text-muted-foreground">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="new-password" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border border-border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm hover:border-muted-foreground/30" 
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-medium text-red-500 ml-1" />
        </div>

        <!-- Confirm Password -->
        <div class="animate-fade-in" style="animation-delay: 0.4s;">
            <label for="password_confirmation" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Confirm New Password</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-primary text-muted-foreground">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border border-border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm hover:border-muted-foreground/30" 
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs font-medium text-red-500 ml-1" />
        </div>

        <div class="pt-2 animate-fade-in" style="animation-delay: 0.5s;">
            <button type="submit" class="w-full bg-primary text-primary-foreground py-4 rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 group">
                <span>Reset Password</span>
                <i class="fa-solid fa-key text-xs group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>
</x-guest-layout>
