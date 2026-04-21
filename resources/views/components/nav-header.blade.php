<div x-data="{
        activeTab: '{{ request()->route() ? request()->route()->getName() : '' }}',
        hoveredTab: null,
        indicatorWidth: 0,
        indicatorLeft: 0,
        showIndicator: false,
        updateIndicator(el) {
            if (!el) {
                this.showIndicator = false;
                return;
            }
            this.indicatorWidth = el.offsetWidth;
            this.indicatorLeft = el.offsetLeft;
            this.showIndicator = true;
        },
        resetIndicator() {
            this.hoveredTab = null;
            const activeEl = this.$refs.list.querySelector('[data-active=true]');
            this.updateIndicator(activeEl);
        },
        init() {
            $nextTick(() => {
                this.resetIndicator();
            });
        }
    }"
    class="relative inline-flex items-center bg-card/60 backdrop-blur-md rounded-full border border-primary/20 p-1.5 shadow-sm"
>
    <!-- Background Pill Indicator -->
    <div 
        class="absolute top-1.5 bottom-1.5 bg-primary rounded-full transition-all duration-300 ease-out shadow-sm"
        :style="`width: ${indicatorWidth}px; transform: translateX(${indicatorLeft}px); opacity: ${showIndicator ? '1' : '0'}`"
        x-cloak
    ></div>

    <ul x-ref="list" class="relative flex items-center z-10" @mouseleave="resetIndicator()">
        @php
            $navItems = [
                ['name' => 'Home', 'route' => 'home'],
                ['name' => 'Shop', 'route' => 'catalog.index'],
            ];

            if (Auth::check()) {
                if (Auth::user()->isAdmin()) {
                    $navItems[] = ['name' => 'Admin Dashboard', 'route' => 'admin.dashboard'];
                    $navItems[] = ['name' => 'Users', 'route' => 'admin.users.index'];
                    $navItems[] = ['name' => 'Products', 'route' => 'admin.products.index'];
                } elseif (Auth::user()->isVendor()) {
                    $navItems[] = ['name' => 'Dashboard', 'route' => 'vendor.dashboard'];
                    $navItems[] = ['name' => 'My Products', 'route' => 'vendor.products.index'];
                } else {
                    $navItems[] = ['name' => 'Orders', 'route' => 'orders.index'];
                }
            } else {
                $navItems[] = ['name' => 'Orders', 'route' => 'orders.index'];
            }
        @endphp

        @foreach($navItems as $item)
            @php
                $isActive = (request()->route() && request()->route()->getName() === $item['route']);
            @endphp
            <li>
                <a href="{{ $item['route'] !== '#' && Route::has($item['route']) ? route($item['route']) : '#' }}" 
                   @mouseenter="hoveredTab = '{{ $item['name'] }}'; updateIndicator($event.currentTarget)"
                   data-active="{{ $isActive ? 'true' : 'false' }}"
                   class="relative block px-5 py-2 text-sm font-semibold transition-colors duration-300 z-20 rounded-full"
                   :class="(hoveredTab === '{{ $item['name'] }}') || (!hoveredTab && '{{ $isActive ? 'true' : 'false' }}' === 'true') ? 'text-primary-foreground' : 'text-[#91185c] hover:text-primary'"
                >
                    {{ $item['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
