@extends('layouts.app')
@section('title', 'Add New Product')

@section('content')
<div class="container-layout py-16">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center gap-6 mb-12 border-b border-slate-100 dark:border-slate-800 pb-10">
                <a href="{{ route('vendor.products.index') }}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-primary transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Deploy Asset</h1>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">New Entry into the Vault</div>
                </div>
            </div>

            <form method="POST"
                  action="{{ route('vendor.products.store') }}"
                  enctype="multipart/form-data"
                  id="productForm">
                @csrf

                @if($errors->any())
                    <div class="bg-warning/10 border border-warning/20 rounded-2xl p-6 mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fa-solid fa-triangle-exclamation text-warning"></i>
                            <h4 class="text-xs font-black text-warning uppercase tracking-widest">Initialization Failed</h4>
                        </div>
                        <ul class="space-y-2">
                            @foreach($errors->all() as $error)
                                <li class="text-[10px] font-bold text-warning uppercase tracking-tight">• {{ $error }}</li>
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
                                <i class="fa-solid fa-info-circle text-primary"></i> Basic Specification
                            </h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Asset Title <span class="text-warning">*</span></label>
                                    <input type="text"
                                           name="title"
                                           class="input-premium @error('title') border-warning @enderror"
                                           value="{{ old('title') }}"
                                           placeholder="e.g. Premium Wireless Headphones"
                                           required>
                                    @error('title')
                                        <div class="text-warning text-[10px] font-bold mt-2 uppercase tracking-tight">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Short Protocol Summary</label>
                                    <input type="text"
                                           name="short_description"
                                           class="input-premium @error('short_description') border-warning @enderror"
                                           value="{{ old('short_description') }}"
                                           placeholder="One-line summary (shown on cards)">
                                    @error('short_description')
                                        <div class="text-warning text-[10px] font-bold mt-2 uppercase tracking-tight">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Technical Description <span class="text-warning">*</span></label>
                                    <textarea name="description"
                                              class="input-premium h-48 py-4 @error('description') border-warning @enderror"
                                              placeholder="Detailed product specification...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-warning text-[10px] font-bold mt-2 uppercase tracking-tight">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Pricing & Inventory --}}
                        <div class="bg-white dark:bg-slate-950 rounded-[2rem] border border-slate-100 dark:border-slate-900 p-8 lg:p-10 shadow-sm">
                            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                                <i class="fa-solid fa-currency-dollar text-emerald-500"></i> Liquidity & Volume
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Base Value ($) <span class="text-warning">*</span></label>
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
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Liquidation Price ($)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-mono">$</div>
                                        <input type="number"
                                               name="sale_price"
                                               class="input-premium pl-10 @error('sale_price') border-rose-500 @enderror"
                                               value="{{ old('sale_price') }}"
                                               step="0.01"
                                               min="0"
                                               placeholder="None">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Unit Volume <span class="text-warning">*</span></label>
                                    <input type="number"
                                           name="stock"
                                           class="input-premium @error('stock') border-warning @enderror"
                                           value="{{ old('stock', 0) }}"
                                           min="0"
                                           required>
                                    @error('stock')
                                        <div class="text-warning text-[10px] font-bold mt-2 uppercase tracking-tight">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">SKU Identifier</label>
                                    <input type="text"
                                           name="sku"
                                           class="input-premium @error('sku') border-rose-500 @enderror"
                                           value="{{ old('sku') }}"
                                           placeholder="e.g. PROD-001">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Protocol Status <span class="text-warning">*</span></label>
                                    <select name="status" class="input-premium @error('status') border-warning @enderror" required>
                                        <option value="active"   {{ old('status') == 'active'   ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="draft"    {{ old('status') == 'draft'    ? 'selected' : '' }}>Draft</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN --}}
                    <div class="lg:col-span-4 space-y-8">
                        {{-- Main Image --}}
                        <div class="bg-white dark:bg-slate-950 rounded-[2rem] border border-slate-100 dark:border-slate-900 p-8 shadow-sm">
                            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                                <i class="fa-solid fa-image text-primary"></i> Visual Identity <span class="text-warning">*</span>
                            </h3>
                            
                            <div id="imagePreviewContainer"
                                 class="relative aspect-square rounded-2xl border-2 border-dashed border-slate-100 dark:border-slate-800 flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-900 transition-all cursor-pointer group overflow-hidden"
                                 onclick="document.getElementById('imageInput').click()">
                                <img id="imagePreview"
                                     src="#"
                                     class="hidden absolute inset-0 w-full h-full object-cover">
                                <div id="imagePlaceholder" class="text-center p-6">
                                    <i class="fa-solid fa-cloud-upload text-3xl text-slate-300 dark:text-slate-700 group-hover:text-primary transition-colors mb-4"></i>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Deploy Visual Asset</p>
                                    <p class="text-[7px] font-bold text-slate-400 uppercase mt-2 tracking-widest">JPG, PNG, WebP • Max 2MB</p>
                                </div>
                                <div class="absolute inset-0 bg-black/40 items-center justify-center hidden group-hover:flex">
                                    <p class="text-[9px] font-black text-white uppercase tracking-widest">Change Asset</p>
                                </div>
                            </div>
                            <input type="file"
                                   name="image"
                                   id="imageInput"
                                   accept="image/*"
                                   class="hidden"
                                   onchange="previewImage(this)">
                            @error('image')
                                <div class="text-rose-500 text-[10px] font-bold mt-4 uppercase tracking-tight">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Categories --}}
                        <div class="bg-white dark:bg-slate-950 rounded-[2rem] border border-slate-100 dark:border-slate-900 p-8 shadow-sm">
                            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                                <i class="fa-solid fa-tags text-accent"></i> Classification <span class="text-warning">*</span>
                            </h3>
                            
                            <div class="space-y-4 max-h-72 overflow-y-auto pr-4 custom-scrollbar">
                                @error('categories')
                                    <div class="text-rose-500 text-[10px] font-bold mb-4 uppercase tracking-tight">{{ $message }}</div>
                                @enderror
                                @foreach($categories as $category)
                                    <div class="space-y-3">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox"
                                                   name="categories[]"
                                                   value="{{ $category->id }}"
                                                   class="h-4 w-4 rounded border-slate-200 text-primary focus:ring-primary/20 transition-all"
                                                   {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                            <span class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $category->name }}</span>
                                        </label>
                                        @foreach($category->children as $child)
                                            <label class="flex items-center gap-3 cursor-pointer group ml-6">
                                                <input type="checkbox"
                                                       name="categories[]"
                                                       value="{{ $child->id }}"
                                                       class="h-4 w-4 rounded border-slate-200 text-primary focus:ring-primary/20 transition-all"
                                                       {{ in_array($child->id, old('categories', [])) ? 'checked' : '' }}>
                                                <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $child->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Features --}}
                        <div class="bg-white dark:bg-slate-950 rounded-[2rem] border border-slate-100 dark:border-slate-900 p-8 shadow-sm">
                            <label class="flex items-center justify-between cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-accent/10 dark:bg-accent/20 flex items-center justify-center text-accent">
                                        <i class="fa-solid fa-star text-xs"></i>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Featured Status</span>
                                </div>
                                <input type="checkbox"
                                       name="is_featured" value="1"
                                       id="isFeatured"
                                       class="h-5 w-10 rounded-full border-slate-200 dark:border-slate-800 text-primary focus:ring-primary/20 transition-all appearance-none bg-slate-100 dark:bg-slate-800 checked:bg-primary relative after:absolute after:h-4 after:w-4 after:bg-white after:rounded-full after:left-0.5 after:top-0.5 after:transition-all checked:after:translate-x-5"
                                       {{ old('is_featured') ? 'checked' : '' }}>
                            </label>
                        </div>

                        {{-- Deploy Button --}}
                        <button type="submit" class="w-full btn-primary py-6 text-sm uppercase tracking-[0.4em] font-black shadow-2xl shadow-primary/20 group">
                            Initialize Deployment
                            <i class="fa-solid fa-bolt ml-3 group-hover:animate-pulse"></i>
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
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; }
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
