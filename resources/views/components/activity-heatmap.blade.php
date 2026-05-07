@props(['data' => [], 'title' => 'Aktivitas', 'selectedYear' => null])

@php
    $year = $selectedYear ?? date('Y');
    $endDate = ($year == date('Y')) ? now() : \Carbon\Carbon::create($year, 12, 31);
    $startDate = \Carbon\Carbon::create($year, 1, 1)->startOfWeek();
    $days = [];
    $currentDate = $startDate->copy();
    
    while ($currentDate <= $endDate) {
        $dateStr = $currentDate->format('Y-m-d');
        $count = $data[$dateStr] ?? 0;
        
        $colorClass = match(true) {
            $count == 0 => 'bg-slate-100 dark:bg-slate-800/40',
            $count <= 2 => 'bg-emerald-200 dark:bg-emerald-900/40',
            $count <= 5 => 'bg-emerald-400 dark:bg-emerald-700',
            $count <= 10 => 'bg-emerald-600 dark:bg-emerald-500',
            default => 'bg-emerald-800 dark:bg-emerald-400',
        };

        $days[] = [
            'date' => $dateStr,
            'count' => $count,
            'color' => $colorClass,
        ];
        $currentDate->addDay();
    }

    // Hitung label bulan berdasarkan posisi minggu
    $monthLabels = [];
    $tempDate = $startDate->copy();
    $weekCount = 0;
    while ($tempDate <= $endDate) {
        if ($tempDate->day <= 7) {
            $monthLabels[$weekCount] = $tempDate->translatedFormat('M');
        }
        $tempDate->addWeek();
        $weekCount++;
    }
@endphp

{{-- Gunakan inline style var agar sinkron dengan layout --}}
<div class="p-5 rounded-2xl border transition-all duration-300 shadow-sm mb-6" 
     style="background: var(--bg-secondary); border-color: var(--border-color);">
    
    <div class="flex flex-wrap justify-between items-start mb-6 gap-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                <i class="bi bi-fire text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-base font-bold" style="color: var(--text-primary);">{{ $title }}</span>
                <div class="flex items-center gap-2 mt-1">
                    @foreach(range(date('Y'), 2026) as $y)
                        <a href="{{ request()->fullUrlWithQuery(['heatmap_year' => $y]) }}" 
                           class="px-2 py-0.5 rounded text-[9px] font-bold transition-all {{ $year == $y ? 'bg-emerald-500 text-white' : 'bg-slate-800/40 text-slate-500 hover:bg-slate-700' }}">
                            {{ $y }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider opacity-60" style="color: var(--text-secondary);">
            <span>Less</span>
            <div class="flex gap-[3px] items-center px-1.5 py-1 rounded-md" style="background: var(--bg-tertiary);">
                <div class="w-[10px] h-[10px] rounded-[2px] bg-slate-100 dark:bg-slate-800/40"></div>
                <div class="w-[10px] h-[10px] rounded-[2px] bg-emerald-200 dark:bg-emerald-900/40"></div>
                <div class="w-[10px] h-[10px] rounded-[2px] bg-emerald-400 dark:bg-emerald-700"></div>
                <div class="w-[10px] h-[10px] rounded-[2px] bg-emerald-600 dark:bg-emerald-500"></div>
                <div class="w-[10px] h-[10px] rounded-[2px] bg-emerald-800 dark:bg-emerald-400"></div>
            </div>
            <span>More</span>
        </div>
    </div>
    
    <div class="flex gap-2">
        {{-- Label Hari --}}
        <div class="grid grid-rows-7 gap-[3px] pr-1 mt-6 text-[9px] font-bold opacity-40 uppercase tracking-tighter shrink-0" style="color: var(--text-secondary);">
            <div class="h-[11px]"></div>
            <div class="h-[11px] flex items-center">Mon</div>
            <div class="h-[11px]"></div>
            <div class="h-[11px] flex items-center">Wed</div>
            <div class="h-[11px]"></div>
            <div class="h-[11px] flex items-center">Fri</div>
            <div class="h-[11px]"></div>
        </div>

        <div class="overflow-x-auto pb-2 pt-8 scrollbar-hide flex-1">
            <div class="min-w-max">
                {{-- Label Bulan --}}
                <div class="flex gap-[3px] mb-1 ml-[1px]">
                    @for($i = 0; $i < $weekCount; $i++)
                        <div class="w-[11px] text-[9px] font-bold opacity-50 uppercase tracking-tighter" style="color: var(--text-secondary);">
                            {{ $monthLabels[$i] ?? '' }}
                        </div>
                    @endfor
                </div>

                {{-- Grid Kontribusi --}}
                <div class="inline-grid grid-rows-7 grid-flow-col gap-[3px]">
                    @foreach($days as $day)
                        <div 
                            x-data="{ tooltip: false }"
                            @mouseenter="tooltip = true"
                            @mouseleave="tooltip = false"
                            class="w-[11px] h-[11px] rounded-[2px] {{ $day['color'] }} relative cursor-pointer hover:ring-1 hover:ring-emerald-500 transition-all"
                        >
                            {{-- Tooltip Premium --}}
                            <template x-if="tooltip">
                                <div 
                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2.5 py-1.5 text-[9px] font-bold rounded-md whitespace-nowrap z-[100] shadow-2xl pointer-events-none animate__animated animate__fadeInUp animate__faster"
                                    style="background: var(--text-primary); color: var(--bg-secondary); border: 1px solid var(--border-color);"
                                >
                                    <div class="flex flex-col items-center">
                                        <span>{{ $day['count'] }} Aktivitas</span>
                                        <span class="opacity-70 font-normal">{{ \Carbon\Carbon::parse($day['date'])->translatedFormat('d M Y') }}</span>
                                    </div>
                                    {{-- Arrow --}}
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-[4px] border-transparent" 
                                         style="border-top-color: var(--text-primary);"></div>
                                </div>
                            </template>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-4 pt-4 border-t" style="border-color: var(--border-color);">
        <div class="flex items-center gap-5">
            <div class="flex flex-col text-center sm:text-left">
                <span class="text-lg font-black leading-none" style="color: var(--text-primary);">{{ array_sum($data) }}</span>
                <span class="text-[9px] uppercase font-bold tracking-widest opacity-40 mt-1" style="color: var(--text-secondary);">Total Kontribusi</span>
            </div>
            <div class="w-px h-6 opacity-10 bg-current"></div>
            <div class="flex flex-col text-center sm:text-left">
                <span class="text-lg font-black text-emerald-500 leading-none">{{ $data[now()->format('Y-m-d')] ?? 0 }}</span>
                <span class="text-[9px] uppercase font-bold tracking-widest opacity-40 mt-1" style="color: var(--text-secondary);">Hari Ini</span>
            </div>
        </div>
        <div class="flex items-center gap-2 opacity-40">
            <span class="text-[9px] font-bold uppercase tracking-widest" style="color: var(--text-secondary);">Realtime Updates</span>
            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
        </div>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
