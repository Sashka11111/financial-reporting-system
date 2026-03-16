<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Звіт про фінансовий стан — {{ $report->company_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            padding: 10mm 15mm;
        }

        .print-btn {
            position: fixed;
            top: 15px;
            right: 20px;
            background: #1a56db;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .print-btn:hover { background: #1440a8; }

        .back-btn {
            position: fixed;
            top: 15px;
            right: 130px;
            background: #6b7280;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            z-index: 1000;
            text-decoration: none;
            display: inline-block;
        }
        .back-btn:hover { background: #4b5563; }

        h1.report-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
        }

        h2.form-title {
            font-size: 12pt;
            font-weight: bold;
            text-align: center;
            margin-top: 18px;
            margin-bottom: 6px;
        }

        .header-block {
            text-align: center;
            margin-bottom: 14px;
        }
        .header-block p {
            font-size: 11pt;
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 20px;
        }

        th {
            background: #f0f0f0;
            border: 1px solid #333;
            padding: 4px 6px;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
        }

        td {
            border: 1px solid #555;
            padding: 3px 6px;
            vertical-align: middle;
        }

        td.row-name {
            width: 45%;
        }

        td.row-code,
        td.row-val {
            text-align: center;
            width: 18%;
        }

        tr.section-header td {
            background: #e8e8e8;
            font-weight: bold;
        }

        tr.total-row td {
            background: #f5f5f5;
            font-weight: bold;
        }

        .additional-info {
            margin-top: 10px;
        }

        .additional-info table {
            width: 80%;
            margin: 0 auto 20px;
        }

        .additional-info th {
            width: 5%;
        }

        .signature-block {
            margin-top: 20px;
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
            .print-btn, .back-btn { display: none !important; }
            body { padding: 5mm 10mm; }
            h2.form-title { page-break-before: auto; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">Друкувати</button>

<div class="header-block">
    <h1 class="report-title">
        №1 та №2. БАЛАНС. ЗВІТ ПРО ФІНАНСОВИЙ СТАН
    </h1>
    <p><strong>Підприємство:</strong> {{ $report->company_name }}</p>
    <p><strong>Дата звіту:</strong> {{ $report->report_date?->format('d.m.Y') ?? '—' }}</p>
    <p><strong>Дата складання:</strong> {{ now()->format('d.m.Y') }}</p>
</div>

{{-- ===================== ФОРМА №1: БАЛАНС ===================== --}}
<h2 class="form-title">ФОРМА №1 — БАЛАНС (Звіт про фінансовий стан)</h2>

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
        @php
            $form1 = $report->form_1_data ?? [];
        @endphp

        @if(count($form1) > 0)
            @foreach($form1 as $row)
                <tr class="{{ in_array(($row['code'] ?? 0), [1000, 1095, 1195, 1200, 1300, 1400, 1495, 1500, 1595, 1600, 1695, 1700, 1900]) ? 'section-header' : '' }}
                            {{ in_array(($row['code'] ?? 0), [1300, 1900]) ? 'total-row' : '' }}">
                    <td class="row-name">{{ $row['name'] ?? '' }}</td>
                    <td class="row-code">{{ $row['code'] ?? '' }}</td>
                    <td class="row-val">{{ number_format($row['start'] ?? 0, 1, '.', ' ') }}</td>
                    <td class="row-val">{{ number_format($row['end'] ?? 0, 1, '.', ' ') }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" style="text-align:center;font-style:italic;padding:8px;">
                    Дані форми №1 відсутні
                </td>
            </tr>
        @endif
    </tbody>
</table>

{{-- ===================== ФОРМА №2: ФІНАНСОВІ РЕЗУЛЬТАТИ ===================== --}}
<div class="page-break"></div>
<h2 class="form-title">ФОРМА №2 — ЗВІТ ПРО ФІНАНСОВІ РЕЗУЛЬТАТИ</h2>

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
        @php
            $form2 = $report->form_2_data ?? [];
        @endphp

        @if(count($form2) > 0)
            @foreach($form2 as $row)
                <tr class="{{ in_array(($row['code'] ?? 0), [2000, 2090, 2190, 2290, 2350]) ? 'section-header' : '' }}
                            {{ in_array(($row['code'] ?? 0), [2350]) ? 'total-row' : '' }}">
                    <td class="row-name">{{ $row['name'] ?? '' }}</td>
                    <td class="row-code">{{ $row['code'] ?? '' }}</td>
                    <td class="row-val">{{ number_format($row['current'] ?? 0, 1, '.', ' ') }}</td>
                    <td class="row-val">{{ number_format($row['previous'] ?? 0, 1, '.', ' ') }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" style="text-align:center;font-style:italic;padding:8px;">
                    Дані форми №2 відсутні
                </td>
            </tr>
        @endif
    </tbody>
</table>

{{-- ===================== ДОДАТКОВА ІНФОРМАЦІЯ ===================== --}}
<h2 class="form-title">ДОДАТКОВА ІНФОРМАЦІЯ</h2>

<div class="additional-info">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Показник</th>
                <th>Позначення</th>
                <th>Значення</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="row-code">1</td>
                <td class="row-name">Термін існування підприємства</td>
                <td class="row-code"><em>V<sub>n1</sub></em></td>
                <td class="row-val">{{ $report->v_n1 !== null ? $report->v_n1 . ' р.' : '—' }}</td>
            </tr>
            <tr>
                <td class="row-code">2</td>
                <td class="row-name">Градація аналізу прибутків та збитків (0; 5]</td>
                <td class="row-code"><em>V<sub>n2</sub></em></td>
                <td class="row-val">{{ $report->v_n2 !== null ? $report->v_n2 : '—' }}</td>
            </tr>
            <tr>
                <td class="row-code">3</td>
                <td class="row-name">Найбільша сума отриманого і повернутого кредиту (Sk)</td>
                <td class="row-code"><em>V<sub>n3</sub></em></td>
                <td class="row-val">{{ $report->v_n3 !== null ? number_format($report->v_n3, 2, '.', ' ') . ' грн.' : '—' }}</td>
            </tr>
            <tr>
                <td class="row-code">4</td>
                <td class="row-name">Сума запитуваного кредиту (S)</td>
                <td class="row-code"><em>V<sub>o1</sub></em></td>
                <td class="row-val">{{ $report->v_o1 !== null ? number_format($report->v_o1, 2, '.', ' ') . ' грн.' : '—' }}</td>
            </tr>
            <tr>
                <td class="row-code">5</td>
                <td class="row-name">Кількість власних коштів в інвестицію (К)</td>
                <td class="row-code"><em>V<sub>o2</sub></em></td>
                <td class="row-val">{{ $report->v_o2 !== null ? number_format($report->v_o2, 2, '.', ' ') . ' грн.' : '—' }}</td>
            </tr>
            <tr>
                <td class="row-code">6</td>
                <td class="row-name">Вартість власного ліквідного майна (М)</td>
                <td class="row-code"><em>V<sub>o3</sub></em></td>
                <td class="row-val">{{ $report->v_o3 !== null ? number_format($report->v_o3, 2, '.', ' ') . ' грн.' : '—' }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="signature-block">
    <div>
        <div class="signature-line">Керівник підприємства</div>
    </div>
    <div>
        <div class="signature-line">Головний бухгалтер</div>
    </div>
</div>

</body>
</html>
