<x-guest-layout>
    <div class="text-center mb-10 animate-fade-in" style="animation-delay: 0.1s;">
        <h2 class="text-3xl font-serif italic font-bold text-foreground mb-2">Create an account</h2>
        <p class="text-sm text-muted-foreground">Join the ultimate digital marketplace today</p>
    </div>

    <form method="POST" action="{{ route('register', absolute: false) }}" class="space-y-6" 
          x-data="{ 
            loading: false,
            formData: {
                name: '{{ old('name', '') }}',
                email: '{{ old('email', '') }}',
                password: '',
                password_confirmation: ''
            },
            touched: {
                name: false,
                email: false,
                password: false,
                password_confirmation: false
            },
            errors: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            get isFormInvalid() {
                this.validateAll();
                return !!(this.errors.name || this.errors.email || this.errors.password || this.errors.password_confirmation || 
                       !this.formData.name || !this.formData.email || !this.formData.password);
            },
            validateAll() {
                this.validateName(false);
                this.validateEmail(false);
                this.validatePassword(false);
                this.validatePasswordConfirmation(false);
            },
            validateName(markTouched = true) {
                if (markTouched) this.touched.name = true;
                if (!this.formData.name) this.errors.name = 'Full name is required';
                else if (this.formData.name.length < 2) this.errors.name = 'Name is too short';
                else this.errors.name = '';
            },
            validateEmail(markTouched = true) {
                if (markTouched) this.touched.email = true;
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.formData.email) this.errors.email = 'Please enter a valid email address';
                else if (!re.test(this.formData.email)) this.errors.email = 'Please enter a valid email address';
                else this.errors.email = '';
            },
            validatePassword(markTouched = true) {
                if (markTouched) this.touched.password = true;
                if (!this.formData.password) this.errors.password = 'Password is required';
                else if (this.formData.password.length < 8) this.errors.password = 'Password must be at least 8 characters';
                else this.errors.password = '';
                this.validatePasswordConfirmation(false);
            },
            validatePasswordConfirmation(markTouched = true) {
                if (markTouched) this.touched.password_confirmation = true;
                if (this.formData.password_confirmation !== this.formData.password) {
                    this.errors.password_confirmation = 'Passwords do not match';
                } else {
                    this.errors.password_confirmation = '';
                }
            }
          }" 
          @submit="if(isFormInvalid) { $event.preventDefault(); return; } loading = true">
        @csrf

        <!-- Name -->
        <div class="animate-fade-in" style="animation-delay: 0.2s;">
            <label for="name" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Full Name</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors text-muted-foreground"
                     :class="(touched.name && errors.name) ? 'text-warning' : (touched.name && !errors.name ? 'text-green-500' : 'group-focus-within:text-primary')">
                    <i class="fa-solid fa-user text-sm"></i>
                </div>
                <input id="name" type="text" name="name" x-model="formData.name" @input="validateName(true)" @blur="touched.name = true" required autofocus autocomplete="name" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm"
                    :class="(touched.name && errors.name) ? 'border-warning/50 focus:ring-warning/10 focus:border-warning' : (touched.name && !errors.name ? 'border-green-500/50 focus:ring-green-500/10 focus:border-green-500' : 'border-border focus:ring-primary/10 focus:border-primary hover:border-muted-foreground/30')"
                    placeholder="John Doe">
                
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none" x-show="touched.name && !errors.name" x-cloak>
                    <i class="fa-solid fa-circle-check text-green-500 text-xs"></i>
                </div>
            </div>
            <p x-show="touched.name && errors.name" x-text="errors.name" x-cloak class="mt-2 text-[10px] font-bold text-warning ml-1 uppercase tracking-wider animate-shake"></p>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs font-medium text-warning ml-1" />
        </div>

        <!-- Email Address -->
        <div class="animate-fade-in" style="animation-delay: 0.3s;">
            <label for="email" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Email address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors text-muted-foreground"
                     :class="(touched.email && errors.email) ? 'text-warning' : (touched.email && !errors.email ? 'text-green-500' : 'group-focus-within:text-primary')">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </div>
                <input id="email" type="email" name="email" x-model="formData.email" @input="validateEmail(true)" @blur="touched.email = true" required autocomplete="username" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm"
                    :class="(touched.email && errors.email) ? 'border-warning/50 focus:ring-warning/10 focus:border-warning' : (touched.email && !errors.email ? 'border-green-500/50 focus:ring-green-500/10 focus:border-green-500' : 'border-border focus:ring-primary/10 focus:border-primary hover:border-muted-foreground/30')"
                    placeholder="you@example.com">
                
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none" x-show="touched.email && !errors.email" x-cloak>
                    <i class="fa-solid fa-circle-check text-green-500 text-xs"></i>
                </div>
            </div>
            <p x-show="touched.email && errors.email" x-text="errors.email" x-cloak class="mt-2 text-[10px] font-bold text-warning ml-1 uppercase tracking-wider animate-shake"></p>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-medium text-warning ml-1" />
        </div>

        <!-- Password -->
        <div class="animate-fade-in" style="animation-delay: 0.4s;">
            <label for="password" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Password</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors text-muted-foreground"
                     :class="(touched.password && errors.password) ? 'text-warning' : (touched.password && !errors.password ? 'text-green-500' : 'group-focus-within:text-primary')">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <input id="password" type="password" name="password" x-model="formData.password" @input="validatePassword(true)" @blur="touched.password = true" required autocomplete="new-password" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm"
                    :class="(touched.password && errors.password) ? 'border-warning/50 focus:ring-warning/10 focus:border-warning' : (touched.password && !errors.password ? 'border-green-500/50 focus:ring-green-500/10 focus:border-green-500' : 'border-border focus:ring-primary/10 focus:border-primary hover:border-muted-foreground/30')"
                    placeholder="••••••••">
                
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none" x-show="touched.password && !errors.password" x-cloak>
                    <i class="fa-solid fa-circle-check text-green-500 text-xs"></i>
                </div>
            </div>
            <p x-show="touched.password && errors.password" x-text="errors.password" x-cloak class="mt-2 text-[10px] font-bold text-warning ml-1 uppercase tracking-wider animate-shake"></p>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-medium text-warning ml-1" />
        </div>

        <!-- Confirm Password -->
        <div class="animate-fade-in" style="animation-delay: 0.5s;">
            <label for="password_confirmation" class="block text-sm font-medium text-foreground/80 mb-2 ml-1">Confirm Password</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors text-muted-foreground"
                     :class="(touched.password_confirmation && errors.password_confirmation) ? 'text-warning' : (touched.password_confirmation && !errors.password_confirmation ? 'text-green-500' : 'group-focus-within:text-primary')">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation" x-model="formData.password_confirmation" @input="validatePasswordConfirmation(true)" @blur="touched.password_confirmation = true" required autocomplete="new-password" 
                    class="pl-11 w-full bg-input/50 backdrop-blur-sm border rounded-2xl px-4 py-3.5 text-sm text-foreground focus:ring-4 outline-none transition-all placeholder:text-muted-foreground/50 shadow-sm"
                    :class="(touched.password_confirmation && errors.password_confirmation) ? 'border-warning/50 focus:ring-warning/10 focus:border-warning' : (touched.password_confirmation && !errors.password_confirmation ? 'border-green-500/50 focus:ring-green-500/10 focus:border-green-500' : 'border-border focus:ring-primary/10 focus:border-primary hover:border-muted-foreground/30')"
                    placeholder="••••••••">
                
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none" x-show="touched.password_confirmation && !errors.password_confirmation" x-cloak>
                    <i class="fa-solid fa-circle-check text-green-500 text-xs"></i>
                </div>
            </div>
            <p x-show="touched.password_confirmation && errors.password_confirmation" x-text="errors.password_confirmation" x-cloak class="mt-2 text-[10px] font-bold text-warning ml-1 uppercase tracking-wider animate-shake"></p>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs font-medium text-warning ml-1" />
        </div>

        <div class="pt-4 animate-fade-in" style="animation-delay: 0.6s;">
            <button type="submit" 
                class="w-full bg-primary text-primary-foreground py-4 rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed disabled:grayscale"
                :disabled="loading || isFormInvalid">
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

    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-2px); }
            75% { transform: translateX(2px); }
        }
        .animate-shake {
            animation: shake 0.2s ease-in-out 0s 2;
        }
    </style>
</x-guest-layout>
