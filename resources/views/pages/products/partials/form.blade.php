@php
    $isEdit = isset($product);
    $selectedCategoryIds = old('categories', $selectedCategories ?? ($isEdit ? $product->categories->pluck('id')->all() : []));
@endphp

<div class="space-y-10">
    {{-- Basic Intelligence Section --}}
    <div class="space-y-6">
        <h3 class="text-xs font-black text-primary uppercase tracking-widest flex items-center gap-2">
            <span class="h-2 w-2 bg-primary rounded-full"></span> Basic Intelligence
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-8">
                <label for="title" class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mb-2 block ml-1">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $product->title ?? '') }}" class="input-premium @error('title') border-warning @enderror" placeholder="Ex: Premium Leather Modular Gear" required>
                @error('title') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-4">
                <label for="sku" class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mb-2 block ml-1">SKU / Serial</label>
                <input id="sku" name="sku" type="text" value="{{ old('sku', $product->sku ?? '') }}" class="input-premium @error('sku') border-warning @enderror" placeholder="PRM-001">
                @error('sku') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
            </div>
        </div>

        <div>
            <label for="short_description" class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mb-2 block ml-1">Brief Pitch</label>
            <textarea id="short_description" name="short_description" class="input-premium !h-24 @error('short_description') border-warning @enderror" placeholder="Describe the core value proposition in 2 sentences...">{{ old('short_description', $product->short_description ?? '') }}</textarea>
            @error('short_description') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="description" class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mb-2 block ml-1">Full Technical Description</label>
            <textarea id="description" name="description" class="input-premium !h-48 @error('description') border-warning @enderror" placeholder="Detail the features, specifications, and benefits..." required>{{ old('description', $product->description ?? '') }}</textarea>
            @error('description') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
        </div>
    </div>

    {{-- Logistics & Economics Section --}}
    <div class="space-y-6">
        <h3 class="text-xs font-black text-primary uppercase tracking-widest flex items-center gap-2">
            <span class="h-2 w-2 bg-primary rounded-full animate-pulse"></span> Economics & Inventory
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label for="price" class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mb-2 block ml-1">Price ($)</label>
                <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $product->price ?? '') }}" class="input-premium @error('price') border-warning @enderror" placeholder="99.99" required>
                @error('price') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="sale_price" class="text[10px] font-black text-muted-foreground uppercase tracking-widest mb-2 block ml-1">Sale Price ($)</label>
                <input id="sale_price" name="sale_price" type="number" min="0" step="0.01" value="{{ old('sale_price', $product->sale_price ?? '') }}" class="input-premium @error('sale_price') border-warning @enderror" placeholder="Optional">
                @error('sale_price') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="stock" class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mb-2 block ml-1">Stock Level</label>
                <input id="stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', $product->stock ?? 0) }}" class="input-premium @error('stock') border-warning @enderror" placeholder="0" required>
                @error('stock') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="status" class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mb-2 block ml-1">Visibility Status</label>
                <select id="status" name="status" class="input-premium !py-[15px] @error('status') border-warning @enderror selection:bg-primary" required>
                    @foreach(['active' => 'Market Active', 'inactive' => 'Temporarily Offline', 'draft' => 'Draft In Review'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $product->status ?? 'active') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 p-4 bg-muted dark:bg-dark rounded-2xl border-2 border-border dark:border-border focus-within:border-primary transition-all group">
            <input type="checkbox" value="1" id="is_featured" name="is_featured" @checked((bool) old('is_featured', $product->is_featured ?? false)) class="h-5 w-5 rounded text-primary focus:ring-primary/20 border-border dark:border-border dark:bg-dark">
            <label for="is_featured" class="text-sm font-black text-foreground dark:text-foreground uppercase tracking-widest cursor-pointer select-none">
                Promote as Featured <span class="text-[10px] text-primary ml-2 font-bold">(Will appear in primary billboards)</span>
            </label>
        </div>
    </div>

    {{-- Taxonomy Section --}}
    <div class="space-y-6">
        <h3 class="text-xs font-black text-accent uppercase tracking-widest flex items-center gap-2">
            <span class="h-2 w-2 bg-accent rounded-full"></span> Tagging & Taxonomy
        </h3>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <label class="group relative cursor-pointer">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, $selectedCategoryIds)) class="sr-only peer">
                    <div class="flex items-center justify-center p-4 rounded-2xl border-2 border-border dark:border-border bg-white dark:bg-dark peer-checked:border-primary peer-checked:bg-primary/10 dark:peer-checked:bg-primary/10 transition-all hover:border-border dark:hover:border-border">
                        <span class="text-xs font-black text-muted-foreground dark:text-muted-foreground group-hover:text-foreground dark:group-hover:text-white peer-checked:text-primary dark:peer-checked:text-primary uppercase tracking-widest">
                            {{ $category->name }}
                        </span>
                        <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                            <i class="fa-solid fa-circle-check text-primary text-[10px]"></i>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
        @error('categories') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
    </div>

    {{-- Visual Assets Section --}}
    <div class="space-y-6">
        <h3 class="text-xs font-black text-warning uppercase tracking-widest flex items-center gap-2">
            <span class="h-2 w-2 bg-warning rounded-full"></span> Media Vault
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Main Hero Image --}}
            <div class="space-y-4">
                <label for="image" class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mb-2 block ml-1">Primary Hero Asset {{ $isEdit ? '(Optional Update)' : '*' }}</label>
                <div class="relative group">
                    <input id="image" name="image" type="file" accept="image/*" class="sr-only" {{ $isEdit ? '' : 'required' }} onchange="previewMain(event)">
                    <label for="image" class="flex flex-col items-center justify-center h-48 rounded-[2.5rem] border-2 border-dashed border-slate-200 dark:border-slate-800 hover:border-primary transition-all bg-slate-50/50 dark:bg-slate-950/50 cursor-pointer overflow-hidden p-4 text-center">
                        <div id="main-preview-container" class="{{ $isEdit ? '' : 'hidden' }} absolute inset-0">
                            <img id="main-preview" src="{{ $isEdit ? $product->image_url : '' }}" class="h-full w-full object-cover">
                        </div>
                        <div id="main-placeholder" class="{{ $isEdit ? 'hidden' : '' }} flex flex-col items-center gap-3">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-300 group-hover:text-primary transition-colors"></i>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Drop Primary High-Res PNG/JPG</span>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 py-2 bg-black/60 text-white text-[8px] font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fa-solid fa-camera"></i> Replace Intelligence
                        </div>
                    </label>
                </div>
                @error('image') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
            </div>

            {{-- Gallery Images --}}
            <div class="space-y-4">
                <label for="additional_images" class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mb-2 block ml-1">Supporting Product Views (Up to 5)</label>
                <div class="relative group">
                    <input id="additional_images" name="additional_images[]" type="file" accept="image/*" multiple class="sr-only">
                    <label for="additional_images" class="flex flex-col items-center justify-center h-48 rounded-[2.5rem] border-2 border-dashed border-slate-200 dark:border-slate-800 hover:border-primary transition-all bg-slate-50/50 dark:bg-slate-950/50 cursor-pointer p-4 text-center group">
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex gap-2">
                                <i class="fa-solid fa-image text-slate-200 text-lg"></i>
                                <i class="fa-solid fa-image text-slate-200 text-lg translate-y-[-4px]"></i>
                                <i class="fa-solid fa-image text-slate-200 text-lg"></i>
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Upload Gallery Perspective Array</span>
                        </div>
                    </label>
                </div>
                @error('additional_images') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
                @error('additional_images.*') <div class="text-[10px] font-bold text-warning mt-2 ml-1 uppercase">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
</div>

<script>
function previewMain(event) {
    const input = event.target;
    const preview = document.getElementById('main-preview');
    const container = document.getElementById('main-preview-container');
    const placeholder = document.getElementById('main-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

