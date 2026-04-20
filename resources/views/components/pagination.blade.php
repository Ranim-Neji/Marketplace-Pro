@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between sm:justify-end w-full">
        <ul class="flex items-center gap-1.5">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="relative inline-flex items-center justify-center h-9 px-3.5 text-sm font-medium text-muted-foreground/50 bg-card border border-border cursor-not-allowed rounded-md">
                        <i class="fa-solid fa-chevron-left text-[10px] mr-2"></i> Previous
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" class="relative inline-flex items-center justify-center h-9 px-3.5 text-sm font-medium text-foreground bg-card border border-border rounded-md hover:bg-accent hover:text-accent-foreground transition-all duration-200 hover:scale-[1.03] shadow-sm">
                        <i class="fa-solid fa-chevron-left text-[10px] mr-2"></i> Previous
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="hidden sm:block">
                        <span aria-disabled="true" class="relative inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-muted-foreground cursor-default">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="hidden sm:block">
                                <span aria-current="page" class="relative inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-primary-foreground bg-primary rounded-md shadow-sm border border-primary cursor-default ring-2 ring-primary/20 ring-offset-background">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li class="hidden sm:block">
                                <a href="{{ $url }}" aria-label="Go to page {{ $page }}" class="relative inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-muted-foreground bg-card border border-border rounded-md hover:bg-accent hover:text-accent-foreground hover:border-border transition-all duration-200 hover:scale-[1.03] shadow-sm">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" class="relative inline-flex items-center justify-center h-9 px-3.5 text-sm font-medium text-foreground bg-card border border-border rounded-md hover:bg-accent hover:text-accent-foreground transition-all duration-200 hover:scale-[1.03] shadow-sm">
                        Next <i class="fa-solid fa-chevron-right text-[10px] ml-2"></i>
                    </a>
                </li>
            @else
                <li>
                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="relative inline-flex items-center justify-center h-9 px-3.5 text-sm font-medium text-muted-foreground/50 bg-card border border-border cursor-not-allowed rounded-md">
                        Next <i class="fa-solid fa-chevron-right text-[10px] ml-2"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
