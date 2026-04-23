@extends('layouts.admin')
@section('title', 'Global Dashboard')

@section('content')
<div class="space-y-12">
    {{-- High-Level Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6">
        <div class="bg-card/90 backdrop-blur-md p-6 rounded-[2rem] border border-border shadow-premium transition-all hover:border-primary/30 group">
            <div class="flex justify-between items-start mb-4">
                <div class="h-8 w-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-users text-xs"></i>
                </div>
            </div>
            <div class="text-[8px] font-black text-muted-foreground uppercase tracking-widest mb-1 italic">Total Users</div>
            <div class="text-2xl font-black text-foreground font-mono italic">{{ number_format($totalUsers) }}</div>
        </div>

        <div class="bg-card/90 backdrop-blur-md p-6 rounded-[2rem] border border-border shadow-premium transition-all hover:border-primary/30 group">
            <div class="flex justify-between items-start mb-4">
                <div class="h-8 w-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-shop text-xs"></i>
                </div>
            </div>
            <div class="text-[8px] font-black text-muted-foreground uppercase tracking-widest mb-1 italic">Vendors</div>
            <div class="text-2xl font-black text-foreground font-mono italic">{{ number_format($totalVendors) }}</div>
        </div>

        <div class="bg-card/90 backdrop-blur-md p-6 rounded-[2rem] border border-border shadow-premium transition-all hover:border-accent/30 group">
            <div class="flex justify-between items-start mb-4">
                <div class="h-8 w-8 bg-accent/10 rounded-lg flex items-center justify-center text-accent transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-cart-shopping text-xs"></i>
                </div>
            </div>
            <div class="text-[8px] font-black text-muted-foreground uppercase tracking-widest mb-1 italic">Buyers</div>
            <div class="text-2xl font-black text-foreground font-mono italic">{{ number_format($totalBuyers) }}</div>
        </div>

        <div class="bg-card/90 backdrop-blur-md p-6 rounded-[2rem] border border-border shadow-premium transition-all hover:border-accent/30 group">
            <div class="flex justify-between items-start mb-4">
                <div class="h-8 w-8 bg-accent/10 rounded-lg flex items-center justify-center text-accent transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-money-bill-trend-up text-xs"></i>
                </div>
            </div>
            <div class="text-[8px] font-black text-muted-foreground uppercase tracking-widest mb-1 italic">Revenue</div>
            <div class="text-2xl font-black text-foreground font-mono italic">${{ number_format($totalRevenue, 0) }}</div>
        </div>

        <div class="bg-card/90 backdrop-blur-md p-6 rounded-[2rem] border border-border shadow-premium transition-all hover:border-warning/30 group">
            <div class="flex justify-between items-start mb-4">
                <div class="h-8 w-8 bg-warning/10 rounded-lg flex items-center justify-center text-warning transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-box text-xs"></i>
                </div>
            </div>
            <div class="text-[8px] font-black text-muted-foreground uppercase tracking-widest mb-1 italic">Orders</div>
            <div class="text-2xl font-black text-foreground font-mono italic">{{ number_format($totalOrders) }}</div>
        </div>

        <div class="bg-card/90 backdrop-blur-md p-6 rounded-[2rem] border border-border shadow-premium transition-all hover:border-primaryHover/30 group">
            <div class="flex justify-between items-start mb-4">
                <div class="h-8 w-8 bg-primaryHover/10 rounded-lg flex items-center justify-center text-primaryHover transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-tags text-xs"></i>
                </div>
            </div>
            <div class="text-[8px] font-black text-muted-foreground uppercase tracking-widest mb-1 italic">Products</div>
            <div class="text-2xl font-black text-foreground font-mono italic">{{ number_format($totalProducts) }}</div>
        </div>
    </div>

    {{-- Analytics Matrix --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Revenue Chart --}}
        <div class="lg:col-span-8 bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium">
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-sm font-black text-foreground uppercase tracking-[0.2em] italic font-serif">Revenue Analytics</h3>
            </div>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Dispatch Status --}}
        <div class="lg:col-span-4 bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium">
            <h3 class="text-sm font-black text-foreground uppercase tracking-[0.2em] mb-10 italic font-serif">Dispatch Status</h3>
            <div class="space-y-6">
                @foreach($orderStats as $stat)
                    <div class="flex justify-between items-center group">
                        <div class="flex items-center gap-4">
                            <div class="h-2 w-2 rounded-full bg-{{ $stat->color ?? 'indigo' }}-500"></div>
                            <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest italic group-hover:text-foreground transition-colors">{{ $stat->status }}</span>
                        </div>
                        <span class="text-xs font-black text-foreground font-mono italic">{{ $stat->count }}</span>
                    </div>
                    <div class="w-full h-1 bg-accent rounded-full overflow-hidden">
                        <div class="h-full bg-{{ $stat->color ?? 'indigo' }}-500" style="width: {{ ($stat->count / max($totalOrders, 1)) * 100 }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Mid-Level Lists --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Bestsellers --}}
        <div class="bg-card/90 backdrop-blur-md rounded-[2.5rem] border border-border overflow-hidden shadow-premium">
            <div class="px-10 py-8 border-b border-border flex justify-between items-center">
                <h3 class="text-sm font-black text-foreground uppercase tracking-[0.2em] italic font-serif">Bestseller Node</h3>
                <i class="fa-solid fa-fire-flame-curved text-primary animate-pulse"></i>
            </div>
            <div class="divide-y divide-border">
                @foreach($bestsellers as $product)
                    <div class="px-10 py-6 flex items-center justify-between group hover:bg-accent/30 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <img src="{{ $product->image_url }}" class="h-10 w-10 rounded-lg object-cover">
                                <div class="absolute -top-2 -right-2 h-5 w-5 bg-primary rounded-full border-2 border-card flex items-center justify-center text-[8px] text-white font-bold">
                                    <i class="fa-solid fa-trophy"></i>
                                </div>
                            </div>
                            <div>
                                <div class="text-[10px] font-black text-foreground uppercase tracking-tighter italic">{{ $product->title }}</div>
                                <div class="text-[9px] text-muted-foreground uppercase font-mono italic flex items-center gap-1">
                                    <i class="fa-solid fa-tag text-[8px] opacity-50"></i>
                                    ${{ number_format($product->price, 2) }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-eye text-[9px] text-muted-foreground/30"></i>
                            <span class="text-[10px] font-black text-primary uppercase font-mono italic">{{ $product->views_count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="bg-card/90 backdrop-blur-md rounded-[2.5rem] border border-border overflow-hidden shadow-premium">
            <div class="px-10 py-8 border-b border-border flex justify-between items-center">
                <h3 class="text-sm font-black text-foreground uppercase tracking-[0.2em] italic font-serif">Personnel Intake</h3>
                <i class="fa-solid fa-user-plus text-muted-foreground/30"></i>
            </div>
            <div class="divide-y divide-border">
                @foreach($recentUsers as $user)
                    <div class="px-10 py-6 flex items-center justify-between group hover:bg-accent/30 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <img src="{{ $user->avatar_url }}" class="h-10 w-10 rounded-lg object-cover">
                                @if($user->is_active)
                                    <div class="absolute -bottom-1 -right-1 h-3 w-3 bg-emerald-500 rounded-full border-2 border-card"></div>
                                @endif
                            </div>
                            <div>
                                <div class="text-[10px] font-black text-foreground uppercase tracking-tighter italic">{{ $user->name }}</div>
                                <div class="text-[9px] text-muted-foreground uppercase font-mono italic flex items-center gap-1">
                                    <i class="fa-solid fa-envelope text-[8px] opacity-50"></i>
                                    {{ $user->email }}
                                </div>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-lg bg-accent text-muted-foreground text-[8px] font-black uppercase tracking-widest italic border border-border flex items-center gap-1.5">
                            <i class="fa-solid fa-id-badge text-[9px] opacity-50"></i>
                            {{ $user->getRoleNames()->first() ?? 'user' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Protocols --}}
    <div class="bg-card/90 backdrop-blur-md rounded-[3.5rem] border border-border overflow-hidden shadow-premium">
        <div class="px-12 py-10 border-b border-border flex justify-between items-center">
            <div>
                <h3 class="text-sm font-black text-foreground uppercase tracking-[0.2em] italic font-serif">Recent Orders</h3>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn-secondary py-3 px-8 text-[9px] font-black uppercase tracking-[0.3em] italic">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-accent/30">
                        <th class="px-12 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Node ID</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Identity</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-center">Value</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-center">Status</th>
                        <th class="px-12 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-right">Directive</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($recentOrders as $order)
                        <tr class="hover:bg-accent/30 transition-all group">
                            <td class="px-12 py-8">
                                <div class="text-[10px] font-black text-primary font-mono italic">#{{ $order->order_number }}</div>
                            </td>
                            <td class="px-8 py-8">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $order->user->avatar_url }}" class="h-8 w-8 rounded-lg border border-border object-cover">
                                    <div class="text-[10px] font-black text-foreground uppercase tracking-tighter italic">{{ $order->user->name }}</div>
                                </div>
                            </td>
                            <td class="px-8 py-8 text-center">
                                <div class="text-xs font-black text-foreground font-mono italic">${{ number_format($order->total, 2) }}</div>
                            </td>
                            <td class="px-8 py-8 text-center">
                                @php
                                    $colors = [
                                        'pending' => 'primary', 'processing' => 'accent', 'validated' => 'primary',
                                        'shipped' => 'primaryHover', 'delivered' => 'primary', 'cancelled' => 'warning', 'completed' => 'primary'
                                    ];
                                    $color = $colors[$order->status] ?? 'primary';
                                @endphp
                                <span class="px-3 py-1 rounded-lg bg-[var(--{{ $color }})]/10 text-[var(--{{ $color }})] border border-[var(--{{ $color }})]/20 text-[8px] font-black uppercase tracking-widest italic">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-12 py-8 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex h-9 px-5 items-center justify-center gap-2 rounded-xl bg-card border border-border text-[8px] font-black uppercase tracking-widest text-muted-foreground hover:text-primary transition-all shadow-premium italic group/btn">
                                    <i class="fa-solid fa-microchip text-[10px] transition-transform group-hover/btn:rotate-12"></i>
                                    Analyze
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($revenueLabels) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode($revenueValues) !!},
            borderColor: '#C91C7A',
            borderWidth: 4,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#C91C7A',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8,
            tension: 0.4,
            fill: true,
            backgroundColor: (context) => {
                const chart = context.chart;
                const {ctx, chartArea} = chart;
                if (!chartArea) return null;
                const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                gradient.addColorStop(0, 'rgba(201, 28, 122, 0.1)');
                gradient.addColorStop(1, 'rgba(201, 28, 122, 0)');
                return gradient;
            }
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#000',
                titleFont: { family: 'Outfit', size: 11, weight: 'bold' },
                bodyFont: { family: 'JetBrains Mono', size: 10 },
                padding: 12,
                displayColors: false,
                callbacks: {
                    label: (context) => `$${context.parsed.y.toLocaleString()}`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.03)', borderDash: [5, 5] },
                ticks: {
                    font: { family: 'JetBrains Mono', size: 10 },
                    color: '#64748b',
                    callback: (value) => `$${value}`
                }
            },
            x: {
                grid: { display: false },
                ticks: { font: { family: 'Outfit', size: 10, weight: 'bold' }, color: '#64748b' }
            }
        }
    }
});
</script>
@endpush
