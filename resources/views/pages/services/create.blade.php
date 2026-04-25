@extends('layouts.app')
@section('title', 'Register New Service')

@section('content')
<div class="container-layout py-16">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="flex items-center gap-6 mb-12 border-b border-slate-100 dark:border-slate-800 pb-10">
                <a href="{{ route('vendor.services.index') }}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-primary transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Register Service</h1>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Introduce your expertise to the marketplace</div>
                </div>
            </div>

            <form method="POST"
                  action="{{ route('vendor.services.store') }}"
                  enctype="multipart/form-data"
                  id="serviceForm">
                @csrf

                @if($errors->any())
                    <div class="bg-warning/10 border border-warning/20 rounded-2xl p-6 mb-8">
                        <div class="flex items-center gap-3 text-warning mb-4">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span class="text-xs font-black uppercase tracking-widest">Validation Error</span>
                        </div>
                        <ul class="space-y-2">
                            @foreach($errors->all() as $error)
                                <li class="text-[10px] font-bold text-warning/80 uppercase tracking-wide flex items-center gap-2">
                                    <div class="h-1 w-1 rounded-full bg-warning"></div>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    {{-- LEFT COLUMN --}}
                    <div class="lg:col-span-8 space-y-8">
                        {{-- Basic Info --}}
                        <div class="bg-white dark:bg-slate-950 rounded-[2rem] border border-slate-100 dark:border-slate-900 p-8 lg:p-10 shadow-sm">
                            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                                <i class="fa-solid fa-info-circle text-primary"></i> Service Details
                            </h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Service Name <span class="text-warning">*</span></label>
                                    <input type="text"
                                           name="name"
                                           class="input-premium @error('name') border-warning @enderror"
                                           value="{{ old('name') }}"
                                           placeholder="e.g. Professional Web Development"
                                           required>
                                    @error('name')
                                        <div class="text-warning text-[10px] font-bold mt-2 uppercase tracking-tight">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Detailed Description <span class="text-warning">*</span></label>
                                    <textarea name="description"
                                              class="input-premium h-64 py-4 @error('description') border-warning @enderror"
                                              placeholder="What does this service include? Be specific about deliverables...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-warning text-[10px] font-bold mt-2 uppercase tracking-tight">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Pricing --}}
                        <div class="bg-white dark:bg-slate-950 rounded-[2rem] border border-slate-100 dark:border-slate-900 p-8 lg:p-10 shadow-sm">
                            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                                <i class="fa-solid fa-tag text-emerald-500"></i> Investment
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Starting Price ($) <span class="text-warning">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-mono">$</div>
                                        <input type="number"
                                               name="price"
                                               class="input-premium pl-10 @error('price') border-warning @enderror"
                                               value="{{ old('price') }}"
                                               step="0.01"
                                               min="0"
                                               placeholder="0.00"
                                               required>
                                    </div>
                                    @error('price')
                                        <div class="text-warning text-[10px] font-bold mt-2 uppercase tracking-tight">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Availability Status</label>
                                    <select name="availability" class="input-premium">
                                        <option value="1" {{ old('availability', '1') == '1' ? 'selected' : '' }}>Available Now</option>
                                        <option value="0" {{ old('availability') == '0' ? 'selected' : '' }}>Busy / Waitlist</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN --}}
                    <div class="lg:col-span-4 space-y-8">
                        {{-- Service Image --}}
                        <div class="bg-white dark:bg-slate-950 rounded-[2rem] border border-slate-100 dark:border-slate-900 p-8 shadow-sm">
                            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                                <i class="fa-solid fa-image text-primary"></i> Service Banner
                            </h3>
                            
                            <div id="imagePreviewContainer"
                                 class="relative aspect-[4/3] rounded-2xl border-2 border-dashed border-slate-100 dark:border-slate-800 flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-900 transition-all cursor-pointer group overflow-hidden"
                                 onclick="document.getElementById('imageInput').click()">
                                <img id="imagePreview"
                                     src="#"
                                     class="hidden absolute inset-0 w-full h-full object-cover">
                                <div id="imagePlaceholder" class="text-center p-6">
                                    <i class="fa-solid fa-camera text-3xl text-slate-300 dark:text-slate-700 group-hover:text-primary transition-colors mb-4"></i>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Upload Service Image</p>
                                    <p class="text-[7px] font-bold text-slate-400 uppercase mt-2 tracking-widest">Optional but Recommended</p>
                                </div>
                            </div>
                            <input type="file"
                                   name="image"
                                   id="imageInput"
                                   accept="image/*"
                                   class="hidden"
                                   onchange="previewImage(this)">
                        </div>

                        {{-- Category --}}
                        <div class="bg-white dark:bg-slate-950 rounded-[2rem] border border-slate-100 dark:border-slate-900 p-8 shadow-sm">
                            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                                <i class="fa-solid fa-folder-tree text-accent"></i> Classification
                            </h3>
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Primary Category</label>
                                <select name="category_id" class="input-premium">
                                    <option value="">No Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="w-full btn-primary py-6 text-sm uppercase tracking-[0.4em] font-black shadow-2xl shadow-primary/20 group">
                            Register Service
                            <i class="fa-solid fa-handshake ml-3 group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .input-premium {
        @apply w-full px-6 py-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none dark:text-white placeholder-slate-400;
    }
</style>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('imagePreview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            document.getElementById('imagePlaceholder').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
