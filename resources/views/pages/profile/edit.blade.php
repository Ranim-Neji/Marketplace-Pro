@extends('layouts.app')
@section('title', 'Profile Protocol | MarketPlace Pro')

@section('content')
<div class="container-layout py-16" x-data="{ vendorModalOpen: false }">
    <div class="flex items-baseline gap-6 mb-12 border-b border-slate-100 dark:border-slate-800 pb-10">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Identity Protocol</h1>
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Personal Data Management</div>
    </div>

    <form method="POST" action="{{ route('profile.update', absolute: false) }}" enctype="multipart/form-data">
        @csrf @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            {{-- Avatar & Core --}}
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white dark:bg-slate-950 rounded-[3rem] border border-slate-100 dark:border-slate-900 p-10 shadow-sm text-center">
                    <div class="relative inline-block mb-8">
                        <img src="{{ $user->avatar_url }}"
                             id="avatarPreview"
                             class="h-40 w-40 rounded-full border-4 border-white dark:border-slate-800 shadow-2xl object-cover">
                        <label for="avatarInput"
                               class="absolute bottom-0 right-0 h-10 w-10 bg-indigo-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-xl hover:scale-110 transition-all">
                            <i class="fa-solid fa-camera text-sm"></i>
                        </label>
                        <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                    </div>
                    
                    <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">{{ $user->name }}</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2 mb-8">{{ $user->email }}</p>
                    
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach($user->getRoleNames() as $role)
                            <span class="px-3 py-1 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-[8px] font-black uppercase tracking-widest text-slate-500">{{ $role }}</span>
                        @endforeach
                        @if($user->isVendor())
                            <span class="px-3 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 text-[8px] font-black uppercase tracking-widest text-amber-600">
                                <i class="fa-solid fa-shop mr-1"></i>{{ $user->shop_name }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Security Protocol --}}
                <div class="bg-white dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-slate-900 p-10 shadow-sm">
                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <i class="fa-solid fa-shield-halved text-amber-500"></i> Security Protocol
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Current Password</label>
                            <input type="password" name="current_password" class="input-premium" placeholder="Verification Required">
                            @error('current_password') <div class="text-rose-500 text-[8px] font-black uppercase mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">New Cipher</label>
                            <input type="password" name="new_password" class="input-premium" placeholder="Min 8 characters">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Confirm Cipher</label>
                            <input type="password" name="new_password_confirmation" class="input-premium" placeholder="Repeat Protocol">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Personal Data --}}
            <div class="lg:col-span-8 space-y-12">
                <div class="bg-white dark:bg-slate-950 rounded-[3rem] border border-slate-100 dark:border-slate-900 p-10 lg:p-12 shadow-sm">
                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <i class="fa-solid fa-id-card text-indigo-600"></i> Personnel Data
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Full Identity Name</label>
                                <input type="text" name="name" class="input-premium" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Communication Email</label>
                                <input type="email" name="email" class="input-premium" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Signal Contact (Phone)</label>
                                <input type="text" name="phone" class="input-premium" value="{{ old('phone', $user->phone) }}" placeholder="+1 000 000 000">
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Logistic Base (Address)</label>
                                <textarea name="address" class="input-premium h-28 py-4" placeholder="Deployment Base">{{ old('address', $user->address) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Identity Bio</label>
                                <textarea name="bio" class="input-premium h-28 py-4" placeholder="Personnel History...">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Merchant Protocol --}}
                @if($user->isVendor())
                    <div class="bg-white dark:bg-slate-950 rounded-[3rem] border border-slate-100 dark:border-slate-900 p-10 lg:p-12 shadow-sm">
                        <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                            <i class="fa-solid fa-shop text-emerald-500"></i> Merchant Protocol
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Shop Designation</label>
                                <input type="text" name="shop_name" class="input-premium" value="{{ old('shop_name', $user->shop_name) }}">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Commercial Description</label>
                                <textarea name="shop_description" class="input-premium h-32 py-4">{{ old('shop_description', $user->shop_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-10 rounded-[3rem] bg-indigo-600 shadow-2xl shadow-indigo-500/20 text-white flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="max-w-md">
                            <h3 class="text-xl font-black uppercase tracking-tighter mb-2 italic">Upgrade to Merchant</h3>
                            <p class="text-[10px] font-bold text-indigo-100 uppercase tracking-widest leading-relaxed italic">
                                Initialize your commercial node and start deploying assets to the global vault.
                            </p>
                        </div>
                        <button type="button" @click.prevent="vendorModalOpen = true" class="px-10 py-5 bg-white text-indigo-600 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:scale-105 active:scale-95 transition-all">
                            Initialize Shop
                        </button>
                    </div>
                @endif

                <div class="flex gap-6">
                    <button type="submit" class="flex-1 btn-primary py-6 text-[11px] uppercase tracking-[0.4em] font-black shadow-2xl shadow-indigo-500/20 italic group">
                        Confirm Data Mutation
                        <i class="fa-solid fa-save ml-3 group-hover:scale-110 transition-transform"></i>
                    </button>
                    <a href="{{ route('home') }}" class="px-12 py-6 rounded-2xl border border-slate-100 dark:border-slate-800 text-[11px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all italic">
                        Abort
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
                        <h5 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Initialize Merchant Node</h5>
                        <button type="button" @click="vendorModalOpen = false" class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-rose-500 transition-colors">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <form method="POST" action="{{ route('profile.become-vendor', absolute: false) }}" class="space-y-8 text-left">
                        @csrf
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Shop Designation <span class="text-rose-500">*</span></label>
                            <input type="text" name="shop_name" class="input-premium" placeholder="e.g. CyberTech Node 01" required>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Commercial Description</label>
                            <textarea name="shop_description" class="input-premium h-32 py-4" placeholder="Describe your asset portfolio..."></textarea>
                        </div>
                        <button type="submit" class="w-full btn-primary py-6 text-[10px] font-black uppercase tracking-[0.4em] italic">
                            Activate Merchant Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .input-premium {
        @apply w-full px-6 py-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none dark:text-white placeholder-slate-400;
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
