@extends('layouts.app')
@section('title', 'My Profile | MarketPlace Pro')

@section('content')
<div class="container-layout py-16" x-data="{ vendorModalOpen: {{ request()->query('open_vendor_modal') === 'true' ? 'true' : 'false' }} }">
    <div class="flex items-baseline gap-6 mb-12 border-b border-border dark:border-border pb-10">
        <h1 class="text-3xl font-black text-foreground dark:text-white uppercase tracking-tighter italic">Settings</h1>
        <div class="text-[10px] font-black text-muted-foreground uppercase tracking-widest italic">Manage your account and preferences</div>
    </div>

    <form method="POST" action="{{ route('profile.update', absolute: false) }}" enctype="multipart/form-data" x-data="{ updating: false }" @submit="updating = true">
        @csrf @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            {{-- Avatar & Core --}}
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white dark:bg-dark rounded-[3rem] border border-border dark:border-border p-10 shadow-sm text-center">
                    <div class="relative inline-block mb-8">
                        <img src="{{ $user->avatar_url }}"
                             id="avatarPreview"
                             class="h-40 w-40 rounded-full border-4 border-white dark:border-dark shadow-2xl object-cover">
                        <label for="avatarInput"
                               class="absolute bottom-0 right-0 h-10 w-10 bg-primary text-white rounded-full flex items-center justify-center cursor-pointer shadow-xl hover:scale-110 transition-all">
                            <i class="fa-solid fa-camera text-sm"></i>
                        </label>
                        <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)" :disabled="updating">
                    </div>
                    
                    <h2 class="text-xl font-black text-foreground dark:text-white uppercase tracking-tighter italic">{{ $user->name }}</h2>
                    <p class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mt-2 mb-8">{{ $user->email }}</p>
                    
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach($user->getRoleNames() as $role)
                            <span class="px-3 py-1 rounded-lg bg-muted dark:bg-dark border border-border dark:border-border text-[8px] font-black uppercase tracking-widest text-muted-foreground">{{ $role }}</span>
                        @endforeach
                        @if($user->isVendor())
                            <span class="px-3 py-1 rounded-lg bg-amber-50 dark:bg-accent/10 border border-accent dark:border-accent/30 text-[8px] font-black uppercase tracking-widest text-accent">
                                <i class="fa-solid fa-shop mr-1"></i>{{ $user->shop_name }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Security & Password --}}
                <div class="bg-white dark:bg-dark rounded-[2.5rem] border border-border dark:border-border p-10 shadow-sm">
                    <h3 class="text-xs font-black text-foreground dark:text-white uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <i class="fa-solid fa-shield-halved text-accent"></i> Password & Security
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-3">Current Password</label>
                            <input type="password" name="current_password" class="input-premium" placeholder="Enter current password" :disabled="updating">
                            @error('current_password') <div class="text-warning text-[8px] font-black uppercase mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-3">New Password</label>
                            <input type="password" name="new_password" class="input-premium" placeholder="At least 8 characters" :disabled="updating">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-3">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="input-premium" placeholder="Repeat new password" :disabled="updating">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Personal Details --}}
            <div class="lg:col-span-8 space-y-12">
                <div class="bg-white dark:bg-dark rounded-[3rem] border border-border dark:border-border p-10 lg:p-12 shadow-sm">
                    <h3 class="text-xs font-black text-foreground dark:text-white uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <i class="fa-solid fa-id-card text-primary"></i> Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-3">Full Name</label>
                                <input type="text" name="name" class="input-premium" value="{{ old('name', $user->name) }}" required :disabled="updating">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-3">Email Address</label>
                                <input type="email" name="email" class="input-premium" value="{{ old('email', $user->email) }}" required :disabled="updating">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-3">Phone Number</label>
                                <input type="text" name="phone" class="input-premium" value="{{ old('phone', $user->phone) }}" placeholder="+1 000 000 000" :disabled="updating">
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-3">Shipping Address</label>
                                <textarea name="address" class="input-premium h-28 py-4" placeholder="Your default delivery address" :disabled="updating">{{ old('address', $user->address) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-3">Short Bio</label>
                                <textarea name="bio" class="input-premium h-28 py-4" placeholder="Tell us a bit about yourself..." :disabled="updating">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Store Settings --}}
                @if($user->isVendor())
                    <div class="bg-white dark:bg-dark rounded-[3rem] border border-border dark:border-border p-10 lg:p-12 shadow-sm">
                        <h3 class="text-xs font-black text-foreground dark:text-white uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                            <i class="fa-solid fa-shop text-primary"></i> Store Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div>
                                <label class="block text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-3">Shop Name</label>
                                <input type="text" name="shop_name" class="input-premium" value="{{ old('shop_name', $user->shop_name) }}" :disabled="updating">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Shop Description</label>
                                <textarea name="shop_description" class="input-premium h-32 py-4" :disabled="updating">{{ old('shop_description', $user->shop_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-10 rounded-[3rem] bg-primary shadow-2xl shadow-indigo-500/20 text-white flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="max-w-md">
                            <h3 class="text-xl font-black uppercase tracking-tighter mb-2 italic">Sell on MarketPlace</h3>
                            <p class="text-[10px] font-bold text-primary uppercase tracking-widest leading-relaxed italic">
                                Start your business today. Open your shop and reach thousands of potential buyers instantly.
                            </p>
                        </div>
                        <button type="button" @click.prevent="vendorModalOpen = true" class="px-10 py-5 bg-white text-primary rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:scale-105 active:scale-95 transition-all" :disabled="updating">
                            Open My Shop
                        </button>
                    </div>
                @endif

                <div class="flex gap-6">
                    <button type="submit" class="flex-1 btn-primary py-6 text-[11px] uppercase tracking-[0.4em] font-black shadow-2xl shadow-indigo-500/20 italic group flex items-center justify-center gap-3 disabled:opacity-70" :disabled="updating">
                        <template x-if="!updating">
                            <div class="flex items-center gap-2">
                                <span>Save Changes</span>
                                <i class="fa-solid fa-save ml-3 group-hover:scale-110 transition-transform"></i>
                            </div>
                        </template>
                        <template x-if="updating" x-cloak>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-notch animate-spin"></i>
                                <span>Updating profile...</span>
                            </div>
                        </template>
                    </button>
                    <a href="{{ route('home') }}" class="px-12 py-6 rounded-2xl border border-slate-100 dark:border-slate-800 text-[11px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all italic">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Vendor Initialization Modal (Alpine) --}}
    <div x-show="vendorModalOpen" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="vendorModalOpen = false"></div>

            <div class="relative bg-white dark:bg-slate-950 rounded-[3rem] shadow-2xl w-full max-w-md overflow-hidden transform transition-all sm:my-8"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                
                <div class="p-10">
                    <div class="flex justify-between items-center mb-10 text-left">
                        <h5 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Open Your Shop</h5>
                        <button type="button" @click="vendorModalOpen = false" class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-dark border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-warning transition-colors">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <form method="POST" action="{{ route('profile.become-vendor', absolute: false) }}" class="space-y-8 text-left" x-data="{ onboarding: false }" @submit="onboarding = true">
                        @csrf
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Shop Name <span class="text-warning">*</span></label>
                            <input type="text" name="shop_name" class="input-premium" placeholder="e.g. My Awesome Shop" required :disabled="onboarding">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Shop Description</label>
                            <textarea name="shop_description" class="input-premium h-32 py-4" placeholder="What do you plan to sell?" :disabled="onboarding"></textarea>
                        </div>
                        <button type="submit" class="w-full btn-primary py-6 text-[10px] font-black uppercase tracking-[0.4em] italic flex items-center justify-center gap-3 group disabled:opacity-70 disabled:cursor-not-allowed" :disabled="onboarding">
                            <template x-if="!onboarding">
                                <div class="flex items-center gap-2">
                                    <span>Launch My Shop</span>
                                    <i class="fa-solid fa-rocket text-xs group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                                </div>
                            </template>
                            <template x-if="onboarding" x-cloak>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-circle-notch animate-spin"></i>
                                    <span>Creating your seller account...</span>
                                </div>
                            </template>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .input-premium {
        @apply w-full px-6 py-4 rounded-xl bg-slate-50 dark:bg-dark/50 border border-slate-100 dark:border-slate-800 text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none dark:text-white placeholder-slate-400;
    }
</style>
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
