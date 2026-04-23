@extends('layouts.admin')
@section('title', 'Refine Asset Configuration')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-foreground uppercase tracking-tighter italic font-serif">Edit Asset <span class="text-primary">#{{ $product->id }}</span></h1>
            <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-[0.2em] mt-1 italic">Product Identification: {{ $product->sku ?: 'PENDING SKU' }}</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="px-8 py-3 bg-accent text-muted-foreground rounded-xl text-[9px] font-black uppercase tracking-widest hover:text-primary transition-all italic border border-border">Back to Registry</a>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-10">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            {{-- Main Data --}}
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium space-y-8">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                            <i class="fa-solid fa-signature text-[10px]"></i>
                            Product Designation
                        </label>
                        <input type="text" name="title" value="{{ old('title', $product->title) }}" 
                               class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground/50 focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground" 
                               placeholder="Asset Title" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                            <i class="fa-solid fa-align-left text-[10px]"></i>
                            Description Payload
                        </label>
                        <textarea name="description" rows="6" 
                                  class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground/50 focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground" 
                                  placeholder="Full technical specifications...">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                                <i class="fa-solid fa-tag text-[10px]"></i>
                                Valuation (USD)
                            </label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" 
                                   class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-primary/20 transition-all font-mono italic text-foreground" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                                <i class="fa-solid fa-percent text-[10px]"></i>
                                Discount Value (USD)
                            </label>
                            <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" 
                                   class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-primary/20 transition-all font-mono italic text-foreground">
                        </div>
                    </div>
                </div>

                <div class="bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium space-y-8">
                    <h3 class="text-xs font-black text-foreground uppercase tracking-[0.2em] italic font-serif">Category Classification</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @php $selected = old('categories', $product->categories->pluck('id')->all()); @endphp
                        @foreach($categories as $category)
                            <label class="relative group cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, $selected)) class="hidden peer">
                                <div class="px-4 py-3 rounded-xl border border-border text-[9px] font-black uppercase tracking-widest text-muted-foreground text-center transition-all peer-checked:bg-primary/10 peer-checked:text-primary peer-checked:border-primary/30 hover:border-primary/30 italic">
                                    {{ $category->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium space-y-8 sticky top-32">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                            <i class="fa-solid fa-user-tie text-[10px]"></i>
                            Provider Attribution
                        </label>
                        <select name="user_id" class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic">
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" @selected((int) old('user_id', $product->user_id) === $vendor->id)>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                            <i class="fa-solid fa-boxes-stacked text-[10px]"></i>
                            Inventory Units
                        </label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" 
                               class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-primary/20 transition-all font-mono italic text-foreground" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                            <i class="fa-solid fa-power-off text-[10px]"></i>
                            Operational Status
                        </label>
                        <select name="status" class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic">
                            @foreach(['active', 'inactive', 'draft'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $product->status) === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_featured" value="1" @checked((bool) old('is_featured', $product->is_featured)) class="hidden peer">
                        <div class="h-6 w-11 bg-accent rounded-full p-1 transition-colors peer-checked:bg-accent border border-border">
                            <div class="h-4 w-4 bg-card rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></div>
                        </div>
                        <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest italic group-hover:text-foreground transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            Promote as Featured
                        </span>
                    </label>

                    <button type="submit" class="w-full py-5 bg-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-opacity italic shadow-premium flex items-center justify-center gap-3">
                        <i class="fa-solid fa-save text-xs"></i>
                        Commit Configurations
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
