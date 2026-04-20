<x-guest-layout>
    <div class="text-center mb-10 animate-fade-in" style="animation-delay: 0.1s;">
        <h2 class="text-3xl font-serif italic font-bold text-foreground mb-2">Security Check</h2>
        <p class="text-sm text-muted-foreground">This is a secure area of the application. Please confirm your password before continuing.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm', absolute: false) }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div class="animate-fade-in" style="animation-delay: 0.2s;">
            <label for="password" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Password</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-primary text-muted-foreground">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border border-border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm hover:border-muted-foreground/30" 
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-medium text-red-500 ml-1" />
        </div>

        <div class="pt-2 animate-fade-in" style="animation-delay: 0.3s;">
            <button type="submit" class="w-full bg-primary text-primary-foreground py-4 rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 group">
                <span>Confirm Password</span>
                <i class="fa-solid fa-shield-check text-xs group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>
</x-guest-layout>
