@php
    $indicators = $getRecord() ? $getRecord()->getIndicators() : null;

    if (!$indicators && isset($component)) {
        $state = $getLivewire()->data;
        $report = new \App\Models\FinancialReport($state);
        $indicators = $report->getIndicators();
    }

    // Credit assessment — uses saved record when available
    $creditRecord = $getRecord() ?? (isset($component) ? new \App\Models\FinancialReport($getLivewire()->data) : null);
    $credit = $creditRecord ? $creditRecord->getCreditAssessment() : null;

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

    // Labels for each criterion
    $criteriaLabels = [
        'K₁ — Миттєва ліквідність',
        'K₂ — Поточна ліквідність',
        'K₃ — Загальна ліквідність',
        'K₄ — Фінансова незалежність',
        'K₅ — Маневреність власних коштів',
        'K₆ — Рентабельність виробництва',
        'K₇ — Діяльність минулих років',
        'K₈ — Повернення кредиту (Sk/S)',
        'K₉ — Термін існування',
        'K₁₀ — Питома вага коштів',
        'K₁₁ — Ліквідне майно (М/S)',
    ];

    $creditBg = match($credit['color'] ?? 'gray') {
        'green'  => 'bg-green-700',
        'blue'   => 'bg-blue-700',
        'yellow' => 'bg-yellow-600',
        'orange' => 'bg-orange-600',
        'red'    => 'bg-red-700',
        default  => 'bg-gray-700',
    };
    $creditBorder = match($credit['color'] ?? 'gray') {
        'green'  => 'border-green-300',
        'blue'   => 'border-blue-300',
        'yellow' => 'border-yellow-300',
        'orange' => 'border-orange-300',
        'red'    => 'border-red-300',
        default  => 'border-gray-300',
    };
    $creditBadge = match($credit['color'] ?? 'gray') {
        'green'  => 'bg-green-100 text-green-800',
        'blue'   => 'bg-blue-100 text-blue-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'orange' => 'bg-orange-100 text-orange-800',
        'red'    => 'bg-red-100 text-red-800',
        default  => 'bg-gray-100 text-gray-800',
    };
@endphp

@if($indicators)
<div class="space-y-8 py-2">


    <div class="rounded-xl border border-blue-200 overflow-hidden shadow-sm">
        <div class="bg-blue-700 px-5 py-3">
            <h3 class="text-white font-bold text-base tracking-wide">
                Група G₁ — Показники фінансової стійкості
            </h3>
        </div>
        <div class="divide-y divide-gray-100">


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


    <div class="rounded-xl border border-emerald-200 overflow-hidden shadow-sm">
        <div class="bg-emerald-700 px-5 py-3">
            <h3 class="text-white font-bold text-base tracking-wide">
                Група G₂ — Аналіз прибутків та збитків
            </h3>
        </div>
        <div class="divide-y divide-gray-100">


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


    <div class="rounded-xl border border-purple-200 overflow-hidden shadow-sm">
        <div class="bg-purple-700 px-5 py-3">
            <h3 class="text-white font-bold text-base tracking-wide">
                Група G₃ — Ефективність управління підприємством
            </h3>
        </div>
        <div class="divide-y divide-gray-100">


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


    @if($credit)
    <div class="rounded-xl border {{ $creditBorder }} overflow-hidden shadow-sm">
        <div class="{{ $creditBg }} px-5 py-3">
            <h3 class="text-white font-bold text-base tracking-wide">
                Оцінка кредитоспроможності підприємства (нечітка модель)
            </h3>
        </div>


        <div class="px-5 py-5 bg-white border-b border-gray-100">
            <div class="flex flex-wrap items-center gap-6">
                <div class="text-center">
                    <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Агрегована оцінка</div>
                    <div class="text-5xl font-black text-gray-800">{{ number_format($credit['m_p'], 4) }}</div>
                    <div class="text-xs text-gray-400 mt-1">m(P) ∈ [0; 1]</div>
                </div>
                <div class="h-16 w-px bg-gray-200 hidden sm:block"></div>
                <div>
                    <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Категорія якості</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $credit['category'] }}</div>
                </div>
                <div class="h-16 w-px bg-gray-200 hidden sm:block"></div>
                <div>
                    <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Кредитний рейтинг</div>
                    <span class="inline-block text-xl font-bold px-4 py-1 rounded-full {{ $creditBadge }}">{{ $credit['rating'] }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-600 italic">{{ $credit['description'] }}</p>
                </div>
            </div>


            <div class="mt-4">
                <div class="text-xs text-gray-500 mb-1 font-semibold">Шкала кредитоспроможності</div>
                <div class="relative h-5 rounded-full overflow-hidden flex">
                    <div class="flex-1 bg-red-500 flex items-center justify-center text-white text-xs font-bold" style="flex:0.11">V</div>
                    <div class="flex-1 bg-orange-400 flex items-center justify-center text-white text-xs font-bold" style="flex:0.08">IV</div>
                    <div class="flex-1 bg-yellow-400 flex items-center justify-center text-white text-xs font-bold" style="flex:0.18">III</div>
                    <div class="flex-1 bg-blue-400 flex items-center justify-center text-white text-xs font-bold" style="flex:0.20">II</div>
                    <div class="flex-1 bg-green-500 flex items-center justify-center text-white text-xs font-bold" style="flex:0.43">I</div>
                </div>
                <div class="relative mt-1">
                    <div style="left: calc({{ min(100, $credit['m_p'] * 100) }}% - 6px)" class="absolute -top-1">
                        <div class="w-3 h-3 bg-gray-800 rotate-45 transform"></div>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-2">
                    <span>0</span><span>0.11</span><span>0.19</span><span>0.37</span><span>0.57</span><span>1</span>
                </div>
            </div>
        </div>


        <div class="px-5 py-4 bg-gray-50">
            <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">Деталізація по критеріям (кроки 2–4)</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700 text-xs uppercase tracking-wide">
                            <th class="text-left px-3 py-2 border border-gray-300">Критерій</th>
                            <th class="text-center px-3 py-2 border border-gray-300">K<sub>i</sub></th>
                            <th class="text-center px-3 py-2 border border-gray-300">μ(K<sub>i</sub>)</th>
                            <th class="text-center px-3 py-2 border border-gray-300">v<sub>i</sub></th>
                            <th class="text-center px-3 py-2 border border-gray-300">w<sub>i</sub></th>
                            <th class="text-center px-3 py-2 border border-gray-300">w<sub>i</sub>·μ(K<sub>i</sub>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criteriaLabels as $idx => $label)
                            @php
                                $ki  = $credit['criteria'][$idx] ?? 0;
                                $mui = $credit['mu'][$idx] ?? 0;
                                $vi  = $credit['raw_w'][$idx] ?? 5;
                                $wi  = $credit['w'][$idx] ?? 0;
                                $contrib = round($wi * $mui, 4);
                                $muColor = $mui >= 0.7 ? 'text-green-700 font-bold'
                                         : ($mui >= 0.3 ? 'text-yellow-700' : 'text-red-600');
                            @endphp
                            <tr class="{{ $idx % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-blue-50 transition-colors">
                                <td class="px-3 py-2 border border-gray-200 font-medium text-gray-700">{{ $label }}</td>
                                <td class="px-3 py-2 border border-gray-200 text-center font-mono text-gray-600">{{ number_format($ki, 4) }}</td>
                                <td class="px-3 py-2 border border-gray-200 text-center font-mono {{ $muColor }}">{{ number_format($mui, 4) }}</td>
                                <td class="px-3 py-2 border border-gray-200 text-center text-gray-600">{{ $vi }}</td>
                                <td class="px-3 py-2 border border-gray-200 text-center font-mono text-gray-600">{{ number_format($wi, 4) }}</td>
                                <td class="px-3 py-2 border border-gray-200 text-center font-mono font-semibold text-gray-800">{{ number_format($contrib, 4) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-800 text-white font-bold">
                            <td class="px-3 py-2 border border-gray-600" colspan="5">m(P) = Σ wᵢ·μ(Kᵢ)</td>
                            <td class="px-3 py-2 border border-gray-600 text-center text-lg">{{ number_format($credit['m_p'], 4) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="px-5 py-4 bg-white">
            <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">Рівні кредитоспроможності (крок 6)</h4>
            <div class="grid grid-cols-1 gap-2 text-xs">
                @foreach([
                    ['I', 'ААА/АА', '(0,57; 1]', 'Найвищий рівень', 'bg-green-100 border-green-400'],
                    ['II', 'А/ВВВ', '(0,37; 0,57]', 'Висока кредитоспроможність', 'bg-blue-100 border-blue-400'],
                    ['III', 'ВВ/В', '(0,19; 0,37]', 'Спекулятивний рейтинг', 'bg-yellow-100 border-yellow-400'],
                    ['IV', 'ССС', '(0,11; 0,19]', 'Можливий дефолт', 'bg-orange-100 border-orange-400'],
                    ['V', 'С/RD/D', '[0; 0,11]', 'Дефолт неминучий', 'bg-red-100 border-red-400'],
                ] as [$cat, $rat, $range, $desc, $cls])
                    <div class="flex items-center gap-3 px-3 py-2 rounded-lg border {{ $cls }} {{ $credit['category'] === $cat ? 'ring-2 ring-offset-1 ring-gray-500' : 'opacity-60' }}">
                        <span class="font-bold w-8">{{ $cat }}</span>
                        <span class="font-semibold w-16">{{ $rat }}</span>
                        <span class="font-mono w-24">{{ $range }}</span>
                        <span>{{ $desc }}</span>
                        @if($credit['category'] === $cat)
                            <span class="ml-auto font-bold text-gray-700">← Ваш результат</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>
@else
<div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-lg text-amber-700">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Збережіть звіт, щоб побачити результати аналізу фінансових показників.</span>
</div>
@endif
