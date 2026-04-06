@php
    $indicators = $getRecord() ? $getRecord()->getIndicators() : null;

    if (!$indicators && isset($component)) {
        $state = $getLivewire()->data;
        $report = new \App\Models\FinancialReport($state);
        $indicators = $report->getIndicators();
    }

    function indicatorColor($value, $norm, $higher = true) {
        if ($higher) {
            if ($value >= $norm) return 'green';
            if ($value >= $norm * 0.5) return 'yellow';
            return 'red';
        } else {
            if ($value <= $norm) return 'green';
            if ($value <= $norm * 1.5) return 'yellow';
            return 'red';
        }
    }

    $colorClass = [
        'green'  => 'bg-green-50 border-green-300 text-green-700',
        'yellow' => 'bg-yellow-50 border-yellow-300 text-yellow-700',
        'red'    => 'bg-red-50 border-red-300 text-red-700',
    ];
    $badgeClass = [
        'green'  => 'bg-green-100 text-green-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'red'    => 'bg-red-100 text-red-800',
    ];
    $statusLabel = [
        'green'  => '✓ Норма',
        'yellow' => '~ Допустимо',
        'red'    => '✗ Критично',
    ];
@endphp

@if($indicators)
<div class="space-y-8 py-2">

    {{-- ===== GROUP G1 ===== --}}
    <div class="rounded-xl border border-blue-200 overflow-hidden shadow-sm">
        <div class="bg-blue-700 px-5 py-3">
            <h3 class="text-white font-bold text-base tracking-wide">
                Група G₁ — Показники фінансової стійкості
            </h3>
        </div>
        <div class="divide-y divide-gray-100">

            {{-- K_m --}}
            @php $color = indicatorColor($indicators['g1']['k_m'], 0.2); @endphp
            <div class="flex items-start gap-4 px-5 py-4 {{ $colorClass[$color] }} border-l-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-blue-700 shadow text-sm border border-blue-200">K<sub>м</sub></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="font-semibold text-gray-800">Коефіцієнт миттєвої ліквідності</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass[$color] }}">{{ $statusLabel[$color] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5 font-mono">
                        K<sub>м</sub> = (ряд 1160 + ряд 1165) / ряд 1695 &nbsp;|&nbsp; Норма: &gt; 0,2
                    </div>
                    <div class="text-2xl font-bold mt-1">{{ number_format($indicators['g1']['k_m'], 4) }}</div>
                </div>
            </div>

            {{-- K_c --}}
            @php $color = indicatorColor($indicators['g1']['k_c'], 0.5); @endphp
            <div class="flex items-start gap-4 px-5 py-4 {{ $colorClass[$color] }} border-l-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-blue-700 shadow text-sm border border-blue-200">K<sub>п</sub></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="font-semibold text-gray-800">Коефіцієнт поточної ліквідності</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass[$color] }}">{{ $statusLabel[$color] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5 font-mono">
                        K<sub>п</sub> = (ряд 1125 + 1155 + 1160 + 1165) / ряд 1695 &nbsp;|&nbsp; Норма: &gt; 0,5
                    </div>
                    <div class="text-2xl font-bold mt-1">{{ number_format($indicators['g1']['k_c'], 4) }}</div>
                </div>
            </div>

            {{-- K_g --}}
            @php $color = indicatorColor($indicators['g1']['k_g'], 1.0); @endphp
            <div class="flex items-start gap-4 px-5 py-4 {{ $colorClass[$color] }} border-l-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-blue-700 shadow text-sm border border-blue-200">K<sub>з</sub></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="font-semibold text-gray-800">Коефіцієнт загальної ліквідності</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass[$color] }}">{{ $statusLabel[$color] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5 font-mono">
                        K<sub>з</sub> = ряд 1195 / ряд 1695 &nbsp;|&nbsp; Норма: &gt; 1,0
                    </div>
                    <div class="text-2xl font-bold mt-1">{{ number_format($indicators['g1']['k_g'], 4) }}</div>
                </div>
            </div>

            {{-- K_i --}}
            @php $color = indicatorColor($indicators['g1']['k_i'], 1.0, false); @endphp
            <div class="flex items-start gap-4 px-5 py-4 {{ $colorClass[$color] }} border-l-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-blue-700 shadow text-sm border border-blue-200">K<sub>н</sub></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="font-semibold text-gray-800">Коефіцієнт фінансової незалежності</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass[$color] }}">{{ $statusLabel[$color] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5 font-mono">
                        K<sub>н</sub> = (ряд 1525 + 1595 + 1695) / ряд 1495 &nbsp;|&nbsp; Норма: &lt; 1,0
                    </div>
                    <div class="text-2xl font-bold mt-1">{{ number_format($indicators['g1']['k_i'], 4) }}</div>
                </div>
            </div>

            {{-- K_e --}}
            @php $color = indicatorColor($indicators['g1']['k_e'], 0.1); @endphp
            <div class="flex items-start gap-4 px-5 py-4 {{ $colorClass[$color] }} border-l-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-blue-700 shadow text-sm border border-blue-200">K<sub>м</sub></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="font-semibold text-gray-800">Коефіцієнт маневреності власних коштів</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass[$color] }}">{{ $statusLabel[$color] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5 font-mono">
                        K<sub>м</sub> = (ряд 1495 − ряд 1095) / ряд 1495 &nbsp;|&nbsp; Норма: &gt; 0,1
                    </div>
                    <div class="text-2xl font-bold mt-1">{{ number_format($indicators['g1']['k_e'], 4) }}</div>
                </div>
            </div>

        </div>
    </div>

    {{-- ===== GROUP G2 ===== --}}
    <div class="rounded-xl border border-emerald-200 overflow-hidden shadow-sm">
        <div class="bg-emerald-700 px-5 py-3">
            <h3 class="text-white font-bold text-base tracking-wide">
                Група G₂ — Аналіз прибутків та збитків
            </h3>
        </div>
        <div class="divide-y divide-gray-100">

            {{-- K_p --}}
            @php $color = indicatorColor($indicators['g2']['k_p'], 0.05); @endphp
            <div class="flex items-start gap-4 px-5 py-4 {{ $colorClass[$color] }} border-l-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-emerald-700 shadow text-sm border border-emerald-200">K<sub>р</sub></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="font-semibold text-gray-800">Коефіцієнт рентабельності виробництва</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass[$color] }}">{{ $statusLabel[$color] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5 font-mono">
                        K<sub>р</sub> = Ф2 ряд 2350 / (ряд 2500+2505+2510+2515+2520) &nbsp;|&nbsp; Норма: &gt; 0,05
                    </div>
                    <div class="text-2xl font-bold mt-1">{{ number_format($indicators['g2']['k_p'], 4) }}</div>
                </div>
            </div>

            {{-- K9 --}}
            @php
                $k9 = $indicators['g2']['k_9_val'];
                $color = $k9 <= 2 ? 'red' : ($k9 <= 3 ? 'yellow' : 'green');
            @endphp
            <div class="flex items-start gap-4 px-5 py-4 {{ $colorClass[$color] }} border-l-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-emerald-700 shadow text-sm border border-emerald-200">K<sub>9</sub></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="font-semibold text-gray-800">Коефіцієнт діяльності минулих років</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass[$color] }}">{{ $statusLabel[$color] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5">
                        Шкала: (0;1] збитки 2р &bull; (1;2] збитки 1р &bull; (2;3] нейтрально &bull; (3;4] прибуток 1р &bull; (4;5] прибуток 2р
                    </div>
                    <div class="text-2xl font-bold mt-1">{{ $indicators['g2']['k_9_val'] }}</div>
                    <div class="text-sm italic text-gray-600">{{ $indicators['g2']['k_9_desc'] }}</div>
                </div>
            </div>

            {{-- K_k --}}
            @php $color = indicatorColor($indicators['g2']['k_k'], 1.0); @endphp
            <div class="flex items-start gap-4 px-5 py-4 {{ $colorClass[$color] }} border-l-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-emerald-700 shadow text-sm border border-emerald-200">K<sub>к</sub></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="font-semibold text-gray-800">Коефіцієнт найбільшої суми раніше повернутого кредиту</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass[$color] }}">{{ $statusLabel[$color] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5 font-mono">
                        K<sub>к</sub> = S<sub>k</sub> / S &nbsp;|&nbsp; Норма: &geq; 1,0
                    </div>
                    <div class="text-2xl font-bold mt-1">{{ number_format($indicators['g2']['k_k'], 4) }}</div>
                </div>
            </div>

        </div>
    </div>

    {{-- ===== GROUP G3 ===== --}}
    <div class="rounded-xl border border-purple-200 overflow-hidden shadow-sm">
        <div class="bg-purple-700 px-5 py-3">
            <h3 class="text-white font-bold text-base tracking-wide">
                Група G₃ — Ефективність управління підприємством
            </h3>
        </div>
        <div class="divide-y divide-gray-100">

            {{-- K3 --}}
            @php $color = $indicators['g3']['k_3'] >= 3 ? 'green' : ($indicators['g3']['k_3'] >= 1 ? 'yellow' : 'red'); @endphp
            <div class="flex items-start gap-4 px-5 py-4 {{ $colorClass[$color] }} border-l-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-purple-700 shadow text-sm border border-purple-200">K<sub>3</sub></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="font-semibold text-gray-800">Термін існування підприємства</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass[$color] }}">{{ $statusLabel[$color] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5">Вказується у роках функціонування &nbsp;|&nbsp; Норма: &geq; 3 роки</div>
                    <div class="text-2xl font-bold mt-1">{{ $indicators['g3']['k_3'] }} рік(-ків)</div>
                </div>
            </div>

            {{-- K_kp --}}
            @php $color = indicatorColor($indicators['g3']['k_kp'], 0.3); @endphp
            <div class="flex items-start gap-4 px-5 py-4 {{ $colorClass[$color] }} border-l-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-purple-700 shadow text-sm border border-purple-200">K<sub>10</sub></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="font-semibold text-gray-800">Коефіцієнт питомої ваги коштів у проекті</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass[$color] }}">{{ $statusLabel[$color] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5 font-mono">
                        K<sub>10</sub> = К / S &nbsp;|&nbsp; Норма: &geq; 0,3
                    </div>
                    <div class="text-2xl font-bold mt-1">{{ number_format($indicators['g3']['k_kp'], 4) }}</div>
                </div>
            </div>

            {{-- K_lp --}}
            @php $color = indicatorColor($indicators['g3']['k_lp'], 1.0); @endphp
            <div class="flex items-start gap-4 px-5 py-4 {{ $colorClass[$color] }} border-l-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-purple-700 shadow text-sm border border-purple-200">K<sub>11</sub></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="font-semibold text-gray-800">Коефіцієнт наявності власного ліквідного майна</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass[$color] }}">{{ $statusLabel[$color] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5 font-mono">
                        K<sub>11</sub> = М / S &nbsp;|&nbsp; Норма: &geq; 1,0
                    </div>
                    <div class="text-2xl font-bold mt-1">{{ number_format($indicators['g3']['k_lp'], 4) }}</div>
                </div>
            </div>

        </div>
    </div>

</div>
@else
<div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-lg text-amber-700">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Збережіть звіт, щоб побачити результати аналізу фінансових показників.</span>
</div>
@endif
