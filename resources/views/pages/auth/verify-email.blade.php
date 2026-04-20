<x-guest-layout>
    <div class="text-center mb-10 animate-fade-in" style="animation-delay: 0.1s;">
        <h2 class="text-3xl font-serif italic font-bold text-foreground mb-2">Verify Email</h2>
        <p class="text-sm text-muted-foreground">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm font-medium animate-fade-in">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="space-y-4 animate-fade-in" style="animation-delay: 0.2s;">
        <form method="POST" action="{{ route('verification.send', absolute: false) }}">
            @csrf
            <button type="submit" class="w-full bg-primary text-primary-foreground py-4 rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 group">
                <span>Resend Verification Email</span>
                <i class="fa-solid fa-paper-plane text-xs group-hover:translate-x-1 transition-transform"></i>
            </button>
        </form>

        <form method="POST" action="{{ route('logout', absolute: false) }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm font-semibold text-muted-foreground hover:text-primary transition-colors flex items-center justify-center gap-2 mx-auto group">
                <i class="fa-solid fa-arrow-right-from-bracket text-xs group-hover:-translate-x-0.5 transition-transform"></i>
                <span>Log Out</span>
            </button>
        </form>
    </div>
</x-guest-layout>
