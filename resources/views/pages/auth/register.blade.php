<x-guest-layout>
    <div class="text-center mb-10 animate-fade-in" style="animation-delay: 0.1s;">
        <h2 class="text-3xl font-serif italic font-bold text-foreground mb-2">Create an account</h2>
        <p class="text-sm text-muted-foreground">Join the ultimate digital marketplace today</p>
    </div>

    <form method="POST" action="{{ route('register', absolute: false) }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div class="animate-fade-in" style="animation-delay: 0.2s;">
            <label for="name" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Full Name</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-primary text-muted-foreground">
                    <i class="fa-solid fa-user text-sm"></i>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border border-border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm hover:border-muted-foreground/30" 
                    placeholder="John Doe">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs font-medium text-red-500 ml-1" />
        </div>

        <!-- Email Address -->
        <div class="animate-fade-in" style="animation-delay: 0.3s;">
            <label for="email" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Email address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-primary text-muted-foreground">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border border-border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm hover:border-muted-foreground/30" 
                    placeholder="you@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-medium text-red-500 ml-1" />
        </div>

        <!-- Password -->
        <div class="animate-fade-in" style="animation-delay: 0.4s;">
            <label for="password" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Password</label>
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
        <div class="animate-fade-in" style="animation-delay: 0.5s;">
            <label for="password_confirmation" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Confirm Password</label>
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

        <div class="pt-4 animate-fade-in" style="animation-delay: 0.6s;" x-data="{ loading: false }">
            <button type="submit" 
                @click="loading = true"
                class="w-full bg-primary text-primary-foreground py-4 rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 group disabled:opacity-70 disabled:cursor-not-allowed"
                :disabled="loading">
                <template x-if="!loading">
                    <div class="flex items-center gap-2">
                        <span>Create Account</span>
                        <i class="fa-solid fa-user-plus text-xs group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </template>
                <template x-if="loading">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-notch animate-spin"></i>
                        <span>Joining...</span>
                    </div>
                </template>
            </button>
        </div>
    </form>

    <div class="mt-10 text-center text-sm text-muted-foreground animate-fade-in" style="animation-delay: 0.7s;">
        Already registered? 
        <a href="{{ route('login') }}" class="font-bold text-primary hover:text-primary/80 transition-colors">Sign in</a>
    </div>
</x-guest-layout>
