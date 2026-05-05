@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between py-4">
        {{-- Deskripsi Pagination (Desktop) --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-medium" style="color: var(--text-secondary);">
                    Menampilkan <span class="font-bold" style="color: var(--text-primary);">{{ $paginator->firstItem() }}</span> 
                    sampai <span class="font-bold" style="color: var(--text-primary);">{{ $paginator->lastItem() }}</span> 
                    dari <span class="font-bold" style="color: var(--text-primary);">{{ $paginator->total() }}</span> data
                </p>
            </div>

            <div>
                <ul class="inline-flex items-center -space-x-px shadow-sm rounded-xl overflow-hidden" 
                    style="border: 1px solid var(--border-color); background: var(--bg-tertiary);">
                    
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="px-3 py-2 cursor-not-allowed opacity-50" style="color: var(--text-secondary);">
                            <i class="bi bi-chevron-left text-xs"></i>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->previousPageUrl() }}" 
                               class="px-3 py-2 transition-colors flex items-center hover:bg-black/5 dark:hover:bg-white/5"
                               style="color: var(--text-primary);">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="px-3 py-2" style="color: var(--text-secondary); border-left: 1px solid var(--border-color);">
                                <span class="text-xs">{{ $element }}</span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="px-4 py-2 text-white font-bold z-10" style="background: #2b5fbe;">
                                        <span class="text-xs">{{ $page }}</span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $url }}" 
                                           class="px-4 py-2 transition-colors font-semibold text-xs hover:bg-black/5 dark:hover:bg-white/5"
                                           style="color: var(--text-primary); border-left: 1px solid var(--border-color);">
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
                            <a href="{{ $paginator->nextPageUrl() }}" 
                               class="px-3 py-2 transition-colors flex items-center hover:bg-black/5 dark:hover:bg-white/5"
                               style="color: var(--text-primary); border-left: 1px solid var(--border-color);">
                                <i class="bi bi-chevron-right text-xs"></i>
                            </a>
                        </li>
                    @else
                        <li class="px-3 py-2 cursor-not-allowed opacity-50" 
                            style="color: var(--text-secondary); border-left: 1px solid var(--border-color);">
                            <i class="bi bi-chevron-right text-xs"></i>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Mobile pagination --}}
        <div class="flex flex-1 justify-between sm:hidden items-center px-2">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 rounded-xl opacity-50 cursor-not-allowed font-semibold text-xs" 
                      style="border: 1px solid var(--border-color); color: var(--text-secondary); background: var(--bg-tertiary);">Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="px-4 py-2 rounded-xl font-semibold text-xs transition-colors hover:bg-black/5 dark:hover:bg-white/5"
                   style="border: 1px solid var(--border-color); color: var(--text-primary); background: var(--bg-tertiary);">Prev</a>
            @endif

            <span class="text-xs font-bold" style="color: var(--text-secondary);">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="px-4 py-2 rounded-xl font-semibold text-xs transition-colors hover:bg-black/5 dark:hover:bg-white/5"
                   style="border: 1px solid var(--border-color); color: var(--text-primary); background: var(--bg-tertiary);">Next</a>
            @else
                <span class="px-4 py-2 rounded-xl opacity-50 cursor-not-allowed font-semibold text-xs" 
                      style="border: 1px solid var(--border-color); color: var(--text-secondary); background: var(--bg-tertiary);">Next</span>
            @endif
        </div>
    </nav>
@endif
