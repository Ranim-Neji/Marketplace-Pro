<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="app-authenticated" content="{{ auth()->check() ? '1' : '0' }}">
        <meta name="notifications-fetch-url" content="{{ auth()->check() ? url('/notifications') : '' }}">
        <meta name="notifications-poll-ms" content="5000">

        <title>{{ config('app.name', 'MarketPlace') }} | Authentication</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    </head>
    <body class="h-full font-sans text-foreground bg-background antialiased overflow-hidden flex items-center justify-center relative">
        <x-background-gradient />

        <div x-data="{
            toasts: [],
            add(toast) {
                this.toasts.push({
                    id: Date.now() + Math.random(),
                    type: toast.type || 'info',
                    message: toast.message || 'Notification',
                    show: true
                });
                setTimeout(() => {
                    this.toasts = this.toasts.filter((item) => item.id !== this.toasts[0]?.id);
                }, 5000);
            }
        }"
        @toast.window="add($event.detail)"
        class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="toast.show" x-transition class="pointer-events-auto p-4 rounded-lg shadow-premium border flex items-start gap-3 bg-card border-border animate-fade-in">
                    <p class="text-sm font-medium text-foreground flex-1" x-text="toast.message"></p>
                    <button @click="toast.show = false" class="text-muted-foreground hover:text-foreground transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <div class="h-full w-full flex flex-col items-center justify-center p-4 relative z-10 overflow-y-auto custom-scrollbar">
            <div class="w-full sm:max-w-md relative z-10 my-8">
                <div class="flex flex-col items-center mb-8 text-center">
                    <a href="/" class="group flex flex-col items-center gap-4">
                        <div class="h-16 w-16 rounded-2xl shadow-premium flex items-center justify-center group-hover:scale-105 transition-transform duration-300 overflow-hidden">
                            <img src="{{ asset('images/logo.png') }}" alt="MarketPlace Logo" class="w-full h-full object-cover">
                        </div>
                        <h1 class="text-3xl font-semibold tracking-tight text-foreground font-serif italic">MarketPlace Pro</h1>
                    </a>
                </div>

                <div class="bg-card/90 backdrop-blur-md p-8 sm:p-10 rounded-[2rem] border border-border shadow-premium w-full animate-fade-in relative overflow-hidden">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
