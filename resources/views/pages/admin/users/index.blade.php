@extends('layouts.admin')
@section('title', 'User Management')

@section('content')
<div class="space-y-10">
    {{-- Filtering & Search --}}
    <div class="bg-card/90 backdrop-blur-md p-8 rounded-[2.5rem] border border-border shadow-premium flex flex-col md:flex-row gap-6 items-center justify-between">
        <form method="GET" class="flex flex-1 gap-4 w-full md:max-w-xl">
            <div class="relative flex-1 group">
                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-muted-foreground group-focus-within:text-primary transition-colors">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full bg-accent/30 border-none rounded-2xl py-4 pl-12 pr-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground/50 focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground" 
                       placeholder="Scan identity or credentials...">
            </div>
            <select name="role" class="bg-accent/30 border-none rounded-2xl px-6 text-[10px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic">
                <option value="">All Ranks</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->name }}</option>
                @endforeach
            </select>
            <button class="bg-primary text-white px-8 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-opacity italic shadow-premium">Filter</button>
            @if(request()->anyFilled(['search', 'role']))
                <a href="{{ route('admin.users.index') }}" class="px-6 py-4 rounded-2xl bg-accent text-muted-foreground text-[10px] font-black uppercase tracking-widest hover:text-rose-500 transition-all italic">Clear</a>
            @endif
        </form>
    </div>

    {{-- Personnel Registry --}}
    <div class="bg-card/90 backdrop-blur-md rounded-[3.5rem] border border-border overflow-hidden shadow-premium">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-accent/30">
                        <th class="px-10 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Identity</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Credentials</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Authorization</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-center">Status</th>
                        <th class="px-10 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($users as $user)
                        <tr class="hover:bg-accent/30 transition-all group">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $user->avatar_url }}" class="h-10 w-10 rounded-xl border border-border object-cover shadow-sm">
                                    <div>
                                        <div class="text-[11px] font-black text-foreground uppercase tracking-tighter italic">{{ $user->name }}</div>
                                        <div class="text-[8px] font-bold text-muted-foreground uppercase tracking-widest mt-0.5">UID: #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                <div class="text-[10px] font-black text-muted-foreground lowercase tracking-tight italic">{{ $user->email }}</div>
                            </td>
                            <td class="px-8 py-8">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->roles as $role)
                                        <span class="px-3 py-1 rounded-lg bg-primary/10 text-primary border border-primary/20 text-[8px] font-black uppercase tracking-widest italic">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-8 py-8 text-center">
                                <span class="px-3 py-1 rounded-lg {{ $user->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }} border text-[8px] font-black uppercase tracking-widest italic">
                                    {{ $user->is_active ? 'Active' : 'Locked' }}
                                </span>
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="flex h-9 px-4 items-center gap-2 rounded-xl bg-card border border-border text-muted-foreground hover:text-primary transition-all shadow-premium group/btn">
                                        <i class="fa-solid fa-eye text-[10px] transition-transform group-hover/btn:scale-110"></i>
                                        <span class="text-[9px] font-black uppercase tracking-widest italic">View</span>
                                    </a>

                                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="is_active" value="{{ $user->is_active ? '0' : '1' }}">
                                        <input type="hidden" name="role" value="{{ $user->getRoleNames()->first() ?? 'buyer' }}">
                                        <button class="flex h-9 px-4 items-center gap-2 rounded-xl {{ $user->is_active ? 'bg-amber-50 text-amber-600 border-amber-100 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-100' }} border transition-all shadow-premium group/btn">
                                            <i class="fa-solid {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} text-[10px] transition-transform group-hover/btn:scale-110"></i>
                                            <span class="text-[9px] font-black uppercase tracking-widest italic">{{ $user->is_active ? 'Lock' : 'Unlock' }}</span>
                                        </button>
                                    </form>
                                    
                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Purge this identity?');">
                                            @csrf @method('DELETE')
                                            <button class="flex h-9 px-4 items-center gap-2 rounded-xl bg-rose-50 text-rose-500 border border-rose-100 hover:bg-rose-500 hover:text-white transition-all shadow-premium group/btn">
                                                <i class="fa-solid fa-trash-can text-[10px] transition-transform group-hover/btn:scale-110"></i>
                                                <span class="text-[9px] font-black uppercase tracking-widest italic">Purge</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-10 py-8 bg-accent/30 border-t border-border">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
