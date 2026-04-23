@extends('layouts.admin')
@section('title', 'Register New Asset')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black dark:text-white uppercase tracking-tighter italic">Initialize <span class="text-primary">New Product</span></h1>
            <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-[0.2em] mt-1 italic">Protocol: PRODUCT_REGISTRATION</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="px-8 py-3 bg-muted dark:bg-dark text-muted-foreground rounded-xl text-[9px] font-black uppercase tracking-widest hover:text-primary transition-all italic">Back to Registry</a>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-10">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            {{-- Main Data --}}
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white dark:bg-dark p-10 rounded-[3rem] border border-border dark:border-border shadow-sm space-y-8">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic">Product Designation</label>
                        <input type="text" name="title" value="{{ old('title') }}" 
                               class="w-full bg-muted dark:bg-dark border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic" 
                               placeholder="Asset Title" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic">Description Payload</label>
                        <textarea name="description" rows="6" 
                                  class="w-full bg-muted dark:bg-dark border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic" 
                                  placeholder="Full technical specifications..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic">Valuation (USD)</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" 
                                   class="w-full bg-muted dark:bg-dark border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-primary/20 transition-all font-mono italic" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic">Asset Visualization</label>
                            <input type="file" name="image" 
                                   class="w-full bg-muted dark:bg-dark border-none rounded-2xl py-3.5 px-6 text-[8px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all" required>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-dark p-10 rounded-[3rem] border border-border dark:border-border shadow-sm space-y-8">
                    <h3 class="text-xs font-black dark:text-white uppercase tracking-[0.2em] italic">Category Classification</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @php $selected = old('categories', []); @endphp
                        @foreach($categories as $category)
                            <label class="relative group cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, $selected)) class="hidden peer">
                                <div class="px-4 py-3 rounded-xl border border-border dark:border-border text-[9px] font-black uppercase tracking-widest text-muted-foreground text-center transition-all peer-checked:bg-primary/10 peer-checked:text-primary peer-checked:border-primary/30 hover:border-border italic">
                                    {{ $category->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white dark:bg-dark p-10 rounded-[3rem] border border-border dark:border-border shadow-sm space-y-8 sticky top-32">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic">Provider Attribution</label>
                        <select name="user_id" class="w-full bg-muted dark:bg-dark border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic" required>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" @selected((int) old('user_id') === $vendor->id)>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic">Inventory Units</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" 
                               class="w-full bg-muted dark:bg-dark border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-primary/20 transition-all font-mono italic" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic">Operational Status</label>
                        <select name="status" class="w-full bg-muted dark:bg-dark border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic">
                            @foreach(['active', 'inactive', 'draft'] as $status)
                                <option value="{{ $status }}" @selected(old('status') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_featured" value="1" @checked((bool) old('is_featured')) class="hidden peer">
                        <div class="h-6 w-11 bg-slate-100 dark:bg-dark rounded-full p-1 transition-colors peer-checked:bg-accent">
                            <div class="h-4 w-4 bg-white rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></div>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Promote as Featured</span>
                    </label>

                    <button type="submit" class="w-full py-5 bg-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-opacity italic shadow-xl shadow-primary/20">
                        Initiate Registration
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
