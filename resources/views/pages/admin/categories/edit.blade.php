@extends('layouts.admin')
@section('title', 'Refine Category Node')

@section('content')
<div class="max-w-3xl mx-auto space-y-10">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-foreground uppercase tracking-tighter italic font-serif">Edit Category <span class="text-primary">#{{ $category->id }}</span></h1>
            <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-[0.2em] mt-1 italic">Node Path: /{{ $category->slug }}</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="px-8 py-3 bg-accent text-muted-foreground rounded-xl text-[9px] font-black uppercase tracking-widest hover:text-primary transition-all italic border border-border">Back to Registry</a>
    </div>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium space-y-8">
        @csrf
        @method('PUT')

        <div class="space-y-2">
            <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                <i class="fa-solid fa-signature text-[10px]"></i>
                Designation
            </label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" 
                   class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground/50 focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground" 
                   placeholder="Category Name" required>
        </div>

        <div class="space-y-2">
            <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                <i class="fa-solid fa-sitemap text-[10px]"></i>
                Root Node
            </label>
            <select name="parent_id" class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic">
                <option value="">Top Level (Primary)</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" @selected((int) old('parent_id', $category->parent_id) === $parent->id)>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="space-y-2">
            <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                <i class="fa-solid fa-align-left text-[10px]"></i>
                Data Summary
            </label>
            <textarea name="description" rows="4" 
                      class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground/50 focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground" 
                      placeholder="Describe category scope...">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                    <i class="fa-solid fa-arrow-down-9-1 text-[10px]"></i>
                    Sort Index
                </label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" 
                       class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground">
            </div>
            <div class="space-y-2">
                <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                    <i class="fa-solid fa-image text-[10px]"></i>
                    Visual Asset Override
                </label>
                <input type="file" name="image" 
                       class="w-full bg-accent/30 border-none rounded-2xl py-3.5 px-6 text-[8px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic">
            </div>
        </div>

        <label class="flex items-center gap-3 cursor-pointer group">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active)) class="hidden peer">
            <div class="h-6 w-11 bg-accent rounded-full p-1 transition-colors peer-checked:bg-emerald-500 border border-border">
                <div class="h-4 w-4 bg-card rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></div>
            </div>
            <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest italic group-hover:text-foreground transition-colors flex items-center gap-2">
                <i class="fa-solid fa-power-off text-[10px]"></i>
                Deployment Status
            </span>
        </label>

        <button type="submit" class="w-full py-5 bg-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-opacity italic shadow-premium flex items-center justify-center gap-3">
            <i class="fa-solid fa-save text-xs"></i>
            Commence Update
        </button>
    </form>
</div>
@endsection
