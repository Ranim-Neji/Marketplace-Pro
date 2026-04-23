@extends('layouts.admin')
@section('title', 'Category Architecture')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
    {{-- Creation Node --}}
    <div class="lg:col-span-5 space-y-8">
        <div class="bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium sticky top-32">
            <h3 class="text-sm font-black text-foreground uppercase tracking-[0.2em] mb-10 italic font-serif">Initialize Category</h3>
            
            <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                        <i class="fa-solid fa-signature text-[10px]"></i>
                        Designation
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground/50 focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground" 
                           placeholder="Category Name" required>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                        <i class="fa-solid fa-diagram-project text-[10px]"></i>
                        Root Node
                    </label>
                    <select name="parent_id" class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic">
                        <option value="">Top Level (Primary)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" @selected((int) old('parent_id') === $parent->id)>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                        <i class="fa-solid fa-align-left text-[10px]"></i>
                        Data Summary
                    </label>
                    <textarea name="description" rows="3" 
                              class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground/50 focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground" 
                              placeholder="Describe category scope...">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                            <i class="fa-solid fa-arrow-down-1-9 text-[10px]"></i>
                            Sort Index
                        </label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" 
                               class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic flex items-center gap-2">
                            <i class="fa-solid fa-image text-[10px]"></i>
                            Visual Asset
                        </label>
                        <input type="file" name="image" 
                               class="w-full bg-accent/30 border-none rounded-2xl py-3.5 px-6 text-[8px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="hidden peer">
                    <div class="h-6 w-11 bg-accent rounded-full p-1 transition-colors peer-checked:bg-primary">
                        <div class="h-4 w-4 bg-card rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></div>
                    </div>
                    <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest italic group-hover:text-foreground transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-power-off text-[10px]"></i>
                        Deployment Status
                    </span>
                </label>

                <button type="submit" class="w-full py-5 bg-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-opacity italic shadow-premium flex items-center justify-center gap-3">
                    <i class="fa-solid fa-plus-circle text-xs"></i>
                    Register Category
                </button>
            </form>
        </div>
    </div>

    {{-- Tree Registry --}}
    <div class="lg:col-span-7 space-y-8">
        {{-- Search Registry --}}
        <div class="bg-card/90 backdrop-blur-md p-6 rounded-[2.5rem] border border-border shadow-premium flex items-center gap-4">
            <form method="GET" class="flex flex-1 gap-4">
                <div class="relative flex-1 group">
                    <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-muted-foreground group-focus-within:text-primary transition-colors">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full bg-accent/30 border-none rounded-2xl py-3.5 pl-12 pr-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground/50 focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground" 
                           placeholder="Filter registry...">
                </div>
                <button class="bg-dark dark:bg-white text-white dark:text-black px-6 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-opacity italic">Filter</button>
                @if(request('search'))
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center justify-center px-4 rounded-2xl bg-accent text-muted-foreground text-[10px] font-black uppercase tracking-widest hover:text-warning transition-all italic">Clear</a>
                @endif
            </form>
        </div>

        <div class="bg-card/90 backdrop-blur-md rounded-[3.5rem] border border-border overflow-hidden shadow-premium">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-accent/30">
                            <th class="px-10 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Identity</th>
                            <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Hierarchy</th>
                            <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-center">Status</th>
                            <th class="px-10 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($categories as $category)
                            <tr class="hover:bg-accent/30 transition-all group">
                                <td class="px-10 py-8">
                                    <div>
                                        <div class="text-[11px] font-black text-foreground uppercase tracking-tighter italic">{{ $category->name }}</div>
                                        <div class="text-[8px] font-bold text-muted-foreground uppercase tracking-widest mt-0.5">/{{ $category->slug }}</div>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="text-[10px] font-black text-muted-foreground uppercase tracking-widest italic">{{ $category->parent?->name ?: 'Root Node' }}</div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <span class="px-3 py-1 rounded-lg {{ $category->is_active ? 'bg-emerald-50 text-primary border-primary' : 'bg-accent text-muted-foreground border-border' }} border text-[8px] font-black uppercase tracking-widest italic">
                                        {{ $category->is_active ? 'Active' : 'Standby' }}
                                    </span>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="flex h-9 px-4 items-center gap-2 rounded-xl bg-card border border-border text-muted-foreground hover:text-primary transition-all shadow-premium group/btn">
                                            <i class="fa-solid fa-pen-to-square text-[10px] transition-transform group-hover/btn:scale-110"></i>
                                            <span class="text-[9px] font-black uppercase tracking-widest italic">Edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Purge node?');">
                                            @csrf @method('DELETE')
                                            <button class="flex h-9 px-4 items-center gap-2 rounded-xl bg-rose-50 text-warning border border-warning hover:bg-warning hover:text-white transition-all shadow-premium group/btn">
                                                <i class="fa-solid fa-trash-can text-[10px] transition-transform group-hover/btn:scale-110"></i>
                                                <span class="text-[9px] font-black uppercase tracking-widest italic">Purge</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
                <div class="px-10 py-8 bg-accent/30 border-t border-border">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
