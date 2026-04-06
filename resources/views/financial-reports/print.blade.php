<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аналіз платоспроможності та кредитоспроможності — {{ $report->company_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            padding: 10mm 15mm;
        }

        /* ── Buttons ── */
        .no-print {
            position: fixed;
            top: 15px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .btn {
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            box-shadow: 0 2px 6px rgba(0,0,0,.25);
            text-decoration: none;
            display: inline-block;
            color: #fff;
        }
        .btn-blue   { background: #1a56db; }
        .btn-blue:hover { background: #1440a8; }
        .btn-gray   { background: #6b7280; }
        .btn-gray:hover { background: #4b5563; }

        /* ── Typography ── */
        h1.report-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        h2.section-title {
            font-size: 12pt;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 8px;
            border-bottom: 2px solid #333;
            padding-bottom: 4px;
        }

        .header-block {
            text-align: center;
            margin-bottom: 16px;
        }
        .header-block p { font-size: 11pt; margin: 2px 0; }

        /* ── Tables ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 18px;
        }

        th {
            background: #dce6f1;
            border: 1px solid #333;
            padding: 5px 6px;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
        }

        td {
            border: 1px solid #555;
            padding: 3px 6px;
            vertical-align: middle;
        }

        td.row-code { text-align: center; width: 10%; }
        td.row-val  { text-align: center; width: 18%; }
        td.row-name { width: 45%; }

        tr.section-header td { background: #e8e8e8; font-weight: bold; }
        tr.total-row td      { background: #d9e1f2; font-weight: bold; }

        /* ── Indicators table ── */
        .ind-table th { background: #344779; color: #fff; }
        .ind-table td { vertical-align: top; }
        .ind-table .cell-formula { font-style: italic; font-size: 9.5pt; color: #333; }
        .ind-table .cell-norm    { font-size: 9.5pt; color: #444; }

        .status-ok   { color: #166534; font-weight: bold; }
        .status-warn { color: #92400e; font-weight: bold; }
        .status-bad  { color: #991b1b; font-weight: bold; }

        /* ── Group headers ── */
        .group-header {
            width: 100%;
            padding: 6px 10px;
            font-size: 12pt;
            font-weight: bold;
            color: #fff;
            margin-top: 18px;
            margin-bottom: 6px;
        }
        .group-g1 { background: #1e3a6e; }
        .group-g2 { background: #14532d; }
        .group-g3 { background: #4c1d95; }
        .group-credit { background: #1e3a5f; }

        /* ── Additional info ── */
        .add-table { width: 80%; margin: 0 auto 18px; }
        .add-table th { width: 5%; }

        /* ── Credit assessment ── */
        .credit-result-box {
            border: 2px solid #1e3a5f;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 16px;
            background: #f0f4ff;
        }
        .credit-result-box .mp-value {
            font-size: 22pt;
            font-weight: bold;
            color: #1e3a5f;
        }
        .credit-result-box .mp-label {
            font-size: 9pt;
            color: #555;
        }
        .credit-cat-table th { background: #1e3a5f; color: #fff; font-size: 9.5pt; }
        .credit-cat-table td { font-size: 9.5pt; }
        .credit-cat-active td { background: #d1fae5; font-weight: bold; }
        .mu-high { color: #166534; font-weight: bold; }
        .mu-mid  { color: #92400e; }
        .mu-low  { color: #991b1b; }

        /* ── Signature ── */
        .signature-block {
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            font-size: 10pt;
            padding-top: 2px;
        }

        @media print {
            .no-print { display: none !important; }
            body { padding: 5mm 10mm; }
            .page-break { page-break-before: always; }
            h2.section-title { page-break-before: auto; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button class="btn btn-blue" onclick="window.print()">Друкувати</button>
</div>


<div class="header-block">
    <h1 class="report-title">Аналіз платоспроможності та кредитоспроможності підприємства</h1>
    <p><strong>Підприємство:</strong> {{ $report->company_name }}</p>
    <p><strong>Дата звіту:</strong> {{ $report->report_date?->format('d.m.Y') ?? '—' }}</p>
    <p><strong>Дата складання:</strong> {{ now()->format('d.m.Y') }}</p>
</div>


<h2 class="section-title">ФОРМА №1 — БАЛАНС (Звіт про фінансовий стан)</h2>

<table>
    <thead>
        <tr>
            <th style="width:45%">Стаття</th>
            <th style="width:10%">Код рядка</th>
            <th style="width:22%">На початок звітного року (тис. грн.)</th>
            <th style="width:23%">На кінець звітного періоду (тис. грн.)</th>
        </tr>
    </thead>
    <tbody>
        @php $form1 = $report->form_1_data ?? []; @endphp
        @if(count($form1) > 0)
            @foreach($form1 as $row)
                <tr class="{{ in_array(($row['code'] ?? 0), [1000,1095,1195,1200,1300,1400,1495,1500,1595,1600,1695,1700,1900]) ? 'section-header' : '' }}
                            {{ in_array(($row['code'] ?? 0), [1300,1900]) ? 'total-row' : '' }}">
                    <td class="row-name">{{ $row['name'] ?? '' }}</td>
                    <td class="row-code">{{ $row['code'] ?? '' }}</td>
                    <td class="row-val">{{ number_format($row['start'] ?? 0, 1, '.', ' ') }}</td>
                    <td class="row-val">{{ number_format($row['end'] ?? 0, 1, '.', ' ') }}</td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="4" style="text-align:center;font-style:italic;padding:8px;">Дані форми №1 відсутні</td></tr>
        @endif
    </tbody>
</table>


<div class="page-break"></div>
<h2 class="section-title">ФОРМА №2 — ЗВІТ ПРО ФІНАНСОВІ РЕЗУЛЬТАТИ</h2>

<table>
    <thead>
        <tr>
            <th style="width:45%">Стаття</th>
            <th style="width:10%">Код рядка</th>
            <th style="width:22%">За звітний період (тис. грн.)</th>
            <th style="width:23%">За аналогічний попередній період (тис. грн.)</th>
        </tr>
    </thead>
    <tbody>
        @php $form2 = $report->form_2_data ?? []; @endphp
        @if(count($form2) > 0)
            @foreach($form2 as $row)
                <tr class="{{ in_array(($row['code'] ?? 0), [2000,2090,2190,2290,2350]) ? 'section-header' : '' }}
                            {{ in_array(($row['code'] ?? 0), [2350]) ? 'total-row' : '' }}">
                    <td class="row-name">{{ $row['name'] ?? '' }}</td>
                    <td class="row-code">{{ $row['code'] ?? '' }}</td>
                    <td class="row-val">{{ number_format($row['current'] ?? 0, 1, '.', ' ') }}</td>
                    <td class="row-val">{{ number_format($row['previous'] ?? 0, 1, '.', ' ') }}</td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="4" style="text-align:center;font-style:italic;padding:8px;">Дані форми №2 відсутні</td></tr>
        @endif
    </tbody>
</table>


<h2 class="section-title">ВХІДНІ ДАНІ ДЛЯ РОЗРАХУНКУ</h2>
<table class="add-table">
    <thead>
        <tr>
            <th style="width:5%">№</th>
            <th>Показник</th>
            <th style="width:12%">Позначення</th>
            <th style="width:22%">Значення</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="row-code">1</td>
            <td>Термін існування підприємства</td>
            <td class="row-code"><em>V<sub>n1</sub></em></td>
            <td class="row-val">{{ $report->v_n1 !== null ? $report->v_n1 . ' р.' : '—' }}</td>
        </tr>
        <tr>
            <td class="row-code">2</td>
            <td>Градація аналізу прибутків та збитків (0; 5]</td>
            <td class="row-code"><em>V<sub>n2</sub></em></td>
            <td class="row-val">{{ $report->v_n2 !== null ? $report->v_n2 : '—' }}</td>
        </tr>
        <tr>
            <td class="row-code">3</td>
            <td>Найбільша сума отриманого і повернутого кредиту (S<sub>k</sub>)</td>
            <td class="row-code"><em>V<sub>n3</sub></em></td>
            <td class="row-val">{{ $report->v_n3 !== null ? number_format($report->v_n3, 2, '.', ' ') . ' грн.' : '—' }}</td>
        </tr>
        <tr>
            <td class="row-code">4</td>
            <td>Сума запитуваного кредиту (S)</td>
            <td class="row-code"><em>V<sub>o1</sub></em></td>
            <td class="row-val">{{ $report->v_o1 !== null ? number_format($report->v_o1, 2, '.', ' ') . ' грн.' : '—' }}</td>
        </tr>
        <tr>
            <td class="row-code">5</td>
            <td>Кількість власних коштів в інвестицію (К)</td>
            <td class="row-code"><em>V<sub>o2</sub></em></td>
            <td class="row-val">{{ $report->v_o2 !== null ? number_format($report->v_o2, 2, '.', ' ') . ' грн.' : '—' }}</td>
        </tr>
        <tr>
            <td class="row-code">6</td>
            <td>Вартість власного ліквідного майна (М)</td>
            <td class="row-code"><em>V<sub>o3</sub></em></td>
            <td class="row-val">{{ $report->v_o3 !== null ? number_format($report->v_o3, 2, '.', ' ') . ' грн.' : '—' }}</td>
        </tr>
    </tbody>
</table>


<div class="page-break"></div>
<h2 class="section-title">РЕЗУЛЬТАТИ АНАЛІЗУ ПОКАЗНИКІВ ПЛАТОСПРОМОЖНОСТІ</h2>

@php
    $indicators = $report->getIndicators();

    // Helper
    $statusG = function($val, $norm, $higher = true) {
        if ($higher) {
            if ($val >= $norm)          return ['label' => 'Норма',      'css' => 'status-ok'];
            if ($val >= $norm * 0.5)    return ['label' => 'Допустимо',  'css' => 'status-warn'];
            return                              ['label' => 'Критично',   'css' => 'status-bad'];
        } else {
            if ($val <= $norm)          return ['label' => 'Норма',      'css' => 'status-ok'];
            if ($val <= $norm * 1.5)    return ['label' => 'Допустимо',  'css' => 'status-warn'];
            return                              ['label' => 'Критично',   'css' => 'status-bad'];
        }
    };
@endphp

<div class="group-header group-g1">Група G₁ — Показники фінансової стійкості</div>
<table class="ind-table">
    <thead>
        <tr>
            <th style="width:5%">№</th>
            <th style="width:30%">Назва показника</th>
            <th style="width:10%">Позначення</th>
            <th style="width:32%">Формула (за рядками звіту)</th>
            <th style="width:10%">Норма</th>
            <th style="width:8%">Значення</th>
            <th style="width:5%">Оцінка</th>
        </tr>
    </thead>
    <tbody>
        @php $s = $statusG($indicators['g1']['k_m'], 0.2); @endphp
        <tr>
            <td class="row-code">1</td>
            <td>Коефіцієнт миттєвої ліквідності</td>
            <td class="row-code"><em>K<sub>м</sub></em></td>
            <td class="cell-formula">K<sub>м</sub> = (ряд 1160 + ряд 1165) / ряд 1695</td>
            <td class="cell-norm">&gt; 0,2</td>
            <td class="row-val">{{ number_format($indicators['g1']['k_m'], 4) }}</td>
            <td class="row-val {{ $s['css'] }}">{{ $s['label'] }}</td>
        </tr>
        @php $s = $statusG($indicators['g1']['k_c'], 0.5); @endphp
        <tr>
            <td class="row-code">2</td>
            <td>Коефіцієнт поточної ліквідності</td>
            <td class="row-code"><em>K<sub>п</sub></em></td>
            <td class="cell-formula">K<sub>п</sub> = (ряд 1125+1155+1160+1165) / ряд 1695</td>
            <td class="cell-norm">&gt; 0,5</td>
            <td class="row-val">{{ number_format($indicators['g1']['k_c'], 4) }}</td>
            <td class="row-val {{ $s['css'] }}">{{ $s['label'] }}</td>
        </tr>
        @php $s = $statusG($indicators['g1']['k_g'], 1.0); @endphp
        <tr>
            <td class="row-code">3</td>
            <td>Коефіцієнт загальної ліквідності</td>
            <td class="row-code"><em>K<sub>з</sub></em></td>
            <td class="cell-formula">K<sub>з</sub> = ряд 1195 / ряд 1695</td>
            <td class="cell-norm">&gt; 1,0</td>
            <td class="row-val">{{ number_format($indicators['g1']['k_g'], 4) }}</td>
            <td class="row-val {{ $s['css'] }}">{{ $s['label'] }}</td>
        </tr>
        @php $s = $statusG($indicators['g1']['k_i'], 1.0, false); @endphp
        <tr>
            <td class="row-code">4</td>
            <td>Коефіцієнт фінансової незалежності</td>
            <td class="row-code"><em>K<sub>н</sub></em></td>
            <td class="cell-formula">K<sub>н</sub> = (ряд 1525+1595+1695) / ряд 1495</td>
            <td class="cell-norm">&lt; 1,0</td>
            <td class="row-val">{{ number_format($indicators['g1']['k_i'], 4) }}</td>
            <td class="row-val {{ $s['css'] }}">{{ $s['label'] }}</td>
        </tr>
        @php $s = $statusG($indicators['g1']['k_e'], 0.1); @endphp
        <tr>
            <td class="row-code">5</td>
            <td>Коефіцієнт маневреності власних коштів</td>
            <td class="row-code"><em>K<sub>м</sub></em></td>
            <td class="cell-formula">K<sub>м</sub> = (ряд 1495 &minus; ряд 1095) / ряд 1495</td>
            <td class="cell-norm">&gt; 0,1</td>
            <td class="row-val">{{ number_format($indicators['g1']['k_e'], 4) }}</td>
            <td class="row-val {{ $s['css'] }}">{{ $s['label'] }}</td>
        </tr>
    </tbody>
</table>


<div class="group-header group-g2">Група G₂ — Аналіз прибутків та збитків</div>
<table class="ind-table">
    <thead>
        <tr>
            <th style="width:5%">№</th>
            <th style="width:30%">Назва показника</th>
            <th style="width:10%">Позначення</th>
            <th style="width:32%">Формула / Опис</th>
            <th style="width:10%">Норма</th>
            <th style="width:8%">Значення</th>
            <th style="width:5%">Оцінка</th>
        </tr>
    </thead>
    <tbody>
        @php $s = $statusG($indicators['g2']['k_p'], 0.05); @endphp
        <tr>
            <td class="row-code">1</td>
            <td>Коефіцієнт рентабельності виробництва</td>
            <td class="row-code"><em>K<sub>р</sub></em></td>
            <td class="cell-formula">K<sub>р</sub> = Ф2 ряд 2350 / (ряд 2500+2505+2510+2515+2520)</td>
            <td class="cell-norm">&gt; 0,05</td>
            <td class="row-val">{{ number_format($indicators['g2']['k_p'], 4) }}</td>
            <td class="row-val {{ $s['css'] }}">{{ $s['label'] }}</td>
        </tr>
        @php
            $k9 = $indicators['g2']['k_9_val'];
            $s9css = $k9 <= 2 ? 'status-bad' : ($k9 <= 3 ? 'status-warn' : 'status-ok');
            $s9lbl = $k9 <= 2 ? 'Критично' : ($k9 <= 3 ? 'Допустимо' : 'Норма');
        @endphp
        <tr>
            <td class="row-code">2</td>
            <td>Коефіцієнт діяльності минулих років</td>
            <td class="row-code"><em>K<sub>9</sub></em></td>
            <td class="cell-formula">
                (0;1]&nbsp;збитки 2р &bull; (1;2]&nbsp;збитки 1р<br>
                (2;3]&nbsp;нейтрально &bull; (3;4]&nbsp;прибуток 1р &bull; (4;5]&nbsp;прибуток 2р
            </td>
            <td class="cell-norm">&gt; 3</td>
            <td class="row-val">
                {{ $indicators['g2']['k_9_val'] }}<br>
                <small style="font-size:8.5pt;">{{ $indicators['g2']['k_9_desc'] }}</small>
            </td>
            <td class="row-val {{ $s9css }}">{{ $s9lbl }}</td>
        </tr>
        @php $s = $statusG($indicators['g2']['k_k'], 1.0); @endphp
        <tr>
            <td class="row-code">3</td>
            <td>Коефіцієнт найбільшої суми раніше повернутого кредиту</td>
            <td class="row-code"><em>K<sub>к</sub></em></td>
            <td class="cell-formula">K<sub>к</sub> = S<sub>k</sub> / S</td>
            <td class="cell-norm">&ge; 1,0</td>
            <td class="row-val">{{ number_format($indicators['g2']['k_k'], 4) }}</td>
            <td class="row-val {{ $s['css'] }}">{{ $s['label'] }}</td>
        </tr>
    </tbody>
</table>


<div class="group-header group-g3">Група G₃ — Ефективність управління підприємством</div>
<table class="ind-table">
    <thead>
        <tr>
            <th style="width:5%">№</th>
            <th style="width:30%">Назва показника</th>
            <th style="width:10%">Позначення</th>
            <th style="width:32%">Формула / Опис</th>
            <th style="width:10%">Норма</th>
            <th style="width:8%">Значення</th>
            <th style="width:5%">Оцінка</th>
        </tr>
    </thead>
    <tbody>
        @php
            $k3 = $indicators['g3']['k_3'];
            $s3css = $k3 >= 3 ? 'status-ok' : ($k3 >= 1 ? 'status-warn' : 'status-bad');
            $s3lbl = $k3 >= 3 ? 'Норма' : ($k3 >= 1 ? 'Допустимо' : 'Критично');
        @endphp
        <tr>
            <td class="row-code">1</td>
            <td>Термін існування підприємства</td>
            <td class="row-code"><em>K<sub>3</sub></em></td>
            <td class="cell-formula">Вказується у роках функціонування</td>
            <td class="cell-norm">&ge; 3 р.</td>
            <td class="row-val">{{ $indicators['g3']['k_3'] }} р.</td>
            <td class="row-val {{ $s3css }}">{{ $s3lbl }}</td>
        </tr>
        @php $s = $statusG($indicators['g3']['k_kp'], 0.3); @endphp
        <tr>
            <td class="row-code">2</td>
            <td>Коефіцієнт питомої ваги коштів у кредитному проекті</td>
            <td class="row-code"><em>K<sub>10</sub></em></td>
            <td class="cell-formula">K<sub>10</sub> = К / S<br><small>(К — власні кошти, S — сума кредиту)</small></td>
            <td class="cell-norm">&ge; 0,3</td>
            <td class="row-val">{{ number_format($indicators['g3']['k_kp'], 4) }}</td>
            <td class="row-val {{ $s['css'] }}">{{ $s['label'] }}</td>
        </tr>
        @php $s = $statusG($indicators['g3']['k_lp'], 1.0); @endphp
        <tr>
            <td class="row-code">3</td>
            <td>Коефіцієнт наявності власного ліквідного майна</td>
            <td class="row-code"><em>K<sub>11</sub></em></td>
            <td class="cell-formula">K<sub>11</sub> = М / S<br><small>(М — вартість ліквідного майна)</small></td>
            <td class="cell-norm">&ge; 1,0</td>
            <td class="row-val">{{ number_format($indicators['g3']['k_lp'], 4) }}</td>
            <td class="row-val {{ $s['css'] }}">{{ $s['label'] }}</td>
        </tr>
    </tbody>
</table>


@php
    $credit = $report->getCreditAssessment();
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
        'K₁₀ — Питома вага коштів у проекті',
        'K₁₁ — Ліквідне майно (М/S)',
    ];
@endphp

<div class="page-break"></div>
<h2 class="section-title">ОЦІНКА КРЕДИТОСПРОМОЖНОСТІ ПІДПРИЄМСТВА (НЕЧІТКА МОДЕЛЬ)</h2>


<div class="group-header group-credit">Критерії та функції належності μ(Kᵢ)</div>
<table class="ind-table credit-cat-table">
    <thead>
        <tr>
            <th style="width:5%">№</th>
            <th style="width:35%">Критерій</th>
            <th style="width:12%">K<sub>i</sub></th>
            <th style="width:12%">μ(K<sub>i</sub>)</th>
            <th style="width:9%">v<sub>i</sub></th>
            <th style="width:12%">w<sub>i</sub></th>
            <th style="width:15%">w<sub>i</sub> · μ(K<sub>i</sub>)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($criteriaLabels as $idx => $label)
            @php
                $ki      = $credit['criteria'][$idx] ?? 0;
                $mui     = $credit['mu'][$idx] ?? 0;
                $vi      = $credit['raw_w'][$idx] ?? 5;
                $wi      = $credit['w'][$idx] ?? 0;
                $contrib = round($wi * $mui, 4);
                $muCss   = $mui >= 0.7 ? 'mu-high' : ($mui >= 0.3 ? 'mu-mid' : 'mu-low');
            @endphp
            <tr>
                <td class="row-code">{{ $idx + 1 }}</td>
                <td>{{ $label }}</td>
                <td class="row-val" style="font-family:monospace;">{{ number_format($ki, 4) }}</td>
                <td class="row-val {{ $muCss }}" style="font-family:monospace;">{{ number_format($mui, 4) }}</td>
                <td class="row-val">{{ $vi }}</td>
                <td class="row-val" style="font-family:monospace;">{{ number_format($wi, 4) }}</td>
                <td class="row-val" style="font-family:monospace; font-weight:bold;">{{ number_format($contrib, 4) }}</td>
            </tr>
        @endforeach
        <tr style="background:#1e3a5f; color:#fff; font-weight:bold;">
            <td colspan="6" style="padding:5px 6px;">Агрегована оцінка m(P) = Σ wᵢ · μ(Kᵢ)</td>
            <td class="row-val" style="font-size:13pt;">{{ number_format($credit['m_p'], 4) }}</td>
        </tr>
    </tbody>
</table>


<div class="group-header group-credit">Рейтинг кредитоспроможності</div>

<div class="credit-result-box">
    <table style="margin:0; border:none;">
        <tr>
            <td style="border:none; padding:4px 20px 4px 0; width:160px;">
                <div class="mp-label">Агрегована оцінка m(P)</div>
                <div class="mp-value">{{ number_format($credit['m_p'], 4) }}</div>
            </td>
            <td style="border:none; padding:4px 20px 4px 0; width:120px;">
                <div class="mp-label">Категорія якості</div>
                <div style="font-size:18pt; font-weight:bold;">{{ $credit['category'] }}</div>
            </td>
            <td style="border:none; padding:4px 20px 4px 0; width:140px;">
                <div class="mp-label">Рейтинг</div>
                <div style="font-size:14pt; font-weight:bold;">{{ $credit['rating'] }}</div>
            </td>
            <td style="border:none; padding:4px; font-style:italic; font-size:10pt; color:#333;">
                {{ $credit['description'] }}
            </td>
        </tr>
    </table>
</div>

<table class="credit-cat-table" style="width:85%; margin:0 auto 18px;">
    <thead>
        <tr>
            <th style="width:8%">Категорія</th>
            <th style="width:12%">Рейтинг</th>
            <th style="width:18%">m(P)</th>
            <th>Опис</th>
        </tr>
    </thead>
    <tbody>
        @foreach([
            ['I',   'ААА / АА', '(0,57; 1]',   'Найвищий рівень кредитоспроможності'],
            ['II',  'А / ВВВ',  '(0,37; 0,57]', 'Висока кредитоспроможність'],
            ['III', 'ВВ / В',   '(0,19; 0,37]', 'Спекулятивний рейтинг'],
            ['IV',  'ССС',      '(0,11; 0,19]', 'Можливий дефолт'],
            ['V',   'С / RD / D','[0; 0,11]',   'Дефолт неминучий'],
        ] as [$cat, $rat, $range, $desc])
            <tr class="{{ $credit['category'] === $cat ? 'credit-cat-active' : '' }}">
                <td class="row-code">{{ $cat }}</td>
                <td class="row-code">{{ $rat }}</td>
                <td class="row-code" style="font-family:monospace;">{{ $range }}</td>
                <td>{{ $desc }}{{ $credit['category'] === $cat ? ' ← Ваш результат' : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="signature-block">
    <div><div class="signature-line">Керівник підприємства</div></div>
    <div><div class="signature-line">Головний бухгалтер</div></div>
</div>

</body>
</html>
