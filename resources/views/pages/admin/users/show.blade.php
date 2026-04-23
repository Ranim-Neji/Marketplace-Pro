@extends('layouts.admin')
@section('title', 'Identity Analysis')

@section('content')
<div class="space-y-10">
    {{-- Header Card --}}
    <div class="bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium">
        <div class="flex flex-col md:flex-row items-center gap-8">
            <img src="{{ $user->avatar_url }}" class="h-32 w-32 rounded-[2rem] border-4 border-card shadow-premium object-cover">
            <div class="text-center md:text-left flex-1">
                <div class="text-[9px] font-black text-primary uppercase tracking-[0.3em] mb-2 italic">Personnel Identity Profile</div>
                <h1 class="text-3xl font-black text-foreground uppercase tracking-tighter italic font-serif">{{ $user->name }}</h1>
                <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-4">
                    @foreach($user->roles as $role)
                        <span class="px-4 py-1.5 rounded-xl bg-primary/10 text-primary border border-primary/20 text-[10px] font-black uppercase tracking-widest italic">
                            {{ $role->name }}
                        </span>
                    @endforeach
                    <span class="px-4 py-1.5 rounded-xl {{ $user->is_active ? 'bg-emerald-50 text-primary border-primary' : 'bg-rose-50 text-warning border-warning' }} border text-[10px] font-black uppercase tracking-widest italic">
                        {{ $user->is_active ? 'Status: Active' : 'Status: Locked' }}
                    </span>
                </div>
            </div>
            <div class="flex gap-3">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline">
                    @csrf @method('PATCH')
                    <input type="hidden" name="is_active" value="{{ $user->is_active ? '0' : '1' }}">
                    <input type="hidden" name="role" value="{{ $user->getRoleNames()->first() ?? 'buyer' }}">
                    <button class="px-8 py-4 bg-dark text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all italic shadow-premium flex items-center gap-2">
                        <i class="fa-solid {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} text-xs"></i>
                        {{ $user->is_active ? 'Deactivate' : 'Authorize' }}
                    </button>
                </form>
                <a href="{{ route('admin.users.edit', $user) }}" class="px-8 py-4 bg-card border border-border text-muted-foreground hover:text-primary rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all italic shadow-premium flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                    Edit Profile
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        {{-- Personnel Data --}}
        <div class="bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium space-y-8">
            <h3 class="text-xs font-black text-foreground uppercase tracking-[0.2em] italic font-serif">Core Credentials</h3>
            <div class="space-y-6">
                <div>
                    <div class="text-[8px] font-black text-muted-foreground uppercase tracking-widest mb-1 italic">Email Protocol</div>
                    <div class="text-[11px] font-bold text-foreground italic">{{ $user->email }}</div>
                </div>
                <div>
                    <div class="text-[8px] font-black text-muted-foreground uppercase tracking-widest mb-1 italic">Intake Timestamp</div>
                    <div class="text-[11px] font-bold text-foreground italic">{{ $user->created_at->format('M d, Y • H:i') }}</div>
                </div>
                <div>
                    <div class="text-[8px] font-black text-muted-foreground uppercase tracking-widest mb-1 italic">Vendor Status</div>
                    <div class="text-[11px] font-bold text-{{ $user->is_vendor ? 'emerald' : 'rose' }}-500 italic">{{ $user->is_vendor ? 'CERTIFIED VENDOR' : 'STANDARD BUYER' }}</div>
                </div>
            </div>
        </div>

        {{-- Performance Metrics --}}
        <div class="lg:col-span-2 bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium">
            <h3 class="text-xs font-black text-foreground uppercase tracking-[0.2em] mb-10 italic font-serif">Activity Analysis</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 rounded-3xl bg-accent/30 border border-border">
                    <div class="text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-2 italic">Total Orders</div>
                    <div class="text-3xl font-black text-foreground font-mono italic">{{ $user->orders_count ?? $user->orders()->count() }}</div>
                </div>
                <div class="p-6 rounded-3xl bg-accent/30 border border-border">
                    <div class="text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-2 italic">Active Assets</div>
                    <div class="text-3xl font-black text-foreground font-mono italic">{{ $user->products_count ?? $user->products()->count() }}</div>
                </div>
                <div class="p-6 rounded-3xl bg-accent/30 border border-border">
                    <div class="text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-2 italic">Reviews Logged</div>
                    <div class="text-3xl font-black text-foreground font-mono italic">{{ $user->reviews_count ?? $user->reviews()->count() }}</div>
                </div>
            </div>

            <div class="mt-10 p-8 rounded-3xl bg-primary/5 border border-primary/10">
                <div class="text-[9px] font-black text-primary uppercase tracking-widest mb-4 italic">Recent Order History</div>
                <div class="space-y-4">
                    @forelse($user->orders()->latest()->take(3)->get() as $order)
                        <div class="flex justify-between items-center text-[10px] font-bold text-muted-foreground italic">
                            <span class="text-primary">#{{ $order->order_number }}</span>
                            <span class="text-foreground font-mono italic">${{ number_format($order->total, 2) }}</span>
                            <span class="uppercase tracking-widest text-[8px] bg-accent px-2 py-0.5 rounded border border-border">{{ $order->status }}</span>
                        </div>
                    @empty
                        <div class="text-[10px] text-muted-foreground italic">No historical data found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
