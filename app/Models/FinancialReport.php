<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    protected $fillable = [
        'report_date',
        'company_name',
        'form_1_data',
        'form_2_data',
        'v_n1',
        'v_n2',
        'v_n3',
        'v_o1',
        'v_o2',
        'v_o3',
        'credit_weights',
    ];

    protected $casts = [
        'report_date'    => 'date',
        'form_1_data'    => 'array',
        'form_2_data'    => 'array',
        'v_n2'           => 'float',
        'v_n3'           => 'float',
        'v_o1'           => 'float',
        'v_o2'           => 'float',
        'v_o3'           => 'float',
        'credit_weights' => 'array',
    ];

    public function getIndicators(): array
    {
        $f1 = $this->form_1_data ?? [];
        $f2 = $this->form_2_data ?? [];

        $v = fn($code, $data, $field = 'end') => collect($data)->firstWhere('code', $code)[$field] ?? 0;

        $k_m = ($v(1160, $f1) + $v(1165, $f1)) / max(1, $v(1695, $f1));
        $k_c = ($v(1155, $f1) + $v(1160, $f1) + $v(1165, $f1)) / max(1, $v(1695, $f1));
        $k_g = $v(1195, $f1) / max(1, $v(1695, $f1));
        $k_i = ($v(1525, $f1) + $v(1595, $f1) + $v(1695, $f1)) / max(1, $v(1495, $f1));
        $k_e = ($v(1495, $f1) - $v(1095, $f1)) / max(1, $v(1495, $f1));

        $denom_g2 = $v(2500, $f2, 'current') + $v(2505, $f2, 'current') + $v(2510, $f2, 'current') + $v(2515, $f2, 'current') + $v(2520, $f2, 'current');
        $k_p = $v(2350, $f2, 'current') / max(1, $denom_g2);

        $k_9_val = (float) $this->v_n2;
        $k_9_desc = match(true) {
            $k_9_val <= 1 => 'Збиткова діяльність (2 роки)',
            $k_9_val <= 2 => 'Збиткова діяльність (1 рік)',
            $k_9_val <= 3 => 'Без прибутків і збитків',
            $k_9_val <= 4 => 'Прибуткова (1 рік)',
            default       => 'Прибуткова (2 роки)',
        };

        $k_k = $this->v_o1 > 0 ? ($this->v_n3 / $this->v_o1) : 0;

        $k_3  = $this->v_n1;
        $k_kp = $this->v_o1 > 0 ? ($this->v_o2 / $this->v_o1) : 0;
        $k_lp = $this->v_o1 > 0 ? ($this->v_o3 / $this->v_o1) : 0;

        return [
            'g1' => [
                'k_m' => round($k_m, 4),
                'k_c' => round($k_c, 4),
                'k_g' => round($k_g, 4),
                'k_i' => round($k_i, 4),
                'k_e' => round($k_e, 4),
            ],
            'g2' => [
                'k_p'      => round($k_p, 4),
                'k_9_val'  => $k_9_val,
                'k_9_desc' => $k_9_desc,
                'k_k'      => round($k_k, 4),
            ],
            'g3' => [
                'k_3'  => $k_3,
                'k_kp' => round($k_kp, 4),
                'k_lp' => round($k_lp, 4),
            ],
        ];
    }

    private static function mu1(float $k): float
    {
        if ($k <= 0.2)  return 0.0;
        if ($k <= 0.225) return 32 * pow(5 * $k - 1, 2);
        if ($k < 0.25)  return 1 - 50 * pow(1 - 4 * $k, 2);
        return 1.0;
    }

    private static function mu2(float $k): float
    {
        if ($k <= 0.5)  return 0.0;
        if ($k <= 0.75) return 2 * pow(2 * $k - 1, 2);
        if ($k < 1.0)   return 1 - 8 * pow(1 - $k, 2);
        return 1.0;
    }

    private static function mu3(float $k): float
    {
        if ($k <= 1.0)  return 0.0;
        if ($k <= 1.75) return (4 * ($k - 1)) / 3;
        if ($k < 2.5)   return (10 - 4 * $k) / 3;
        return 0.0;
    }

    private static function mu4(float $k): float
    {
        if ($k <= 0.0)  return 0.0;
        if ($k <= 1.0)  return $k;
        if ($k < 2.0)   return 2 - $k;
        return 0.0;
    }

    private static function mu5(float $k): float
    {
        if ($k <= 0.0)  return 0.0;
        if ($k <= 0.5)  return 2 * $k;
        if ($k < 1.0)   return 2 - 2 * $k;
        return 0.0;
    }

    private static function mu6(float $k): float
    {
        if ($k <= 0.05)   return 0.0;
        if ($k <= 0.075)  return 2 * pow(20 * $k - 1, 2);
        if ($k < 0.1)     return 1 - 8 * pow(1 - 10 * $k, 2);
        return 1.0;
    }

    private static function mu7(float $k): float
    {
        if ($k <= 1.0)  return 0.0;
        if ($k <= 3.0)  return pow($k - 1, 2) / 8;
        if ($k < 5.0)   return 1 - pow(5 - $k, 2) / 8;
        return 1.0;
    }

    private static function mu8(float $k): float
    {
        if ($k <= 0.8)  return 0.0;
        if ($k <= 0.9)  return 2 * pow(5 * $k - 4, 2);
        if ($k < 1.0)   return 1 - 2 * pow(5 - 5 * $k, 2);
        return 1.0;
    }

    private static function mu9(float $k): float
    {
        if ($k <= 1.0)  return 0.0;
        if ($k <= 3.0)  return pow($k - 1, 2) / 8;
        if ($k < 5.0)   return 1 - pow(5 - $k, 2) / 8;
        return 1.0;
    }

    private static function mu10(float $k): float
    {
        if ($k <= 0.2)  return 0.0;
        if ($k < 0.4)   return 5 * $k - 1;
        return 1.0;
    }

    private static function mu11(float $k): float
    {
        if ($k <= 0.25)  return 0.0;
        if ($k <= 0.625) return (2 * pow(4 * $k - 1, 2)) / 9;
        if ($k < 1.0)    return 1 - (32 * pow(1 - $k, 2)) / 9;
        return 1.0;
    }

    public function getCreditAssessment(?array $weights = null): array
    {
        $f1 = $this->form_1_data ?? [];
        $f2 = $this->form_2_data ?? [];
        $v  = fn($code, $data, $field = 'end') => (float)(collect($data)->firstWhere('code', $code)[$field] ?? 0);

        $S  = (float)($this->v_o1 ?? 0);

        $k1  = ($v(1160, $f1) + $v(1165, $f1)) / max(1, $v(1695, $f1));
        $k2  = ($v(1125, $f1) + $v(1155, $f1) + $v(1160, $f1) + $v(1165, $f1)) / max(1, $v(1695, $f1));
        $k3  = $v(1195, $f1) / max(1, $v(1695, $f1));
        $k4  = ($v(1525, $f1) + $v(1595, $f1) + $v(1695, $f1)) / max(1, $v(1495, $f1));
        $k5  = ($v(1495, $f1) - $v(1095, $f1)) / max(1, $v(1495, $f1));

        $denom6 = $v(2500, $f2, 'current') + $v(2505, $f2, 'current')
                + $v(2510, $f2, 'current') + $v(2515, $f2, 'current')
                + $v(2520, $f2, 'current');
        $k6  = $v(2350, $f2, 'current') / max(1, $denom6);

        $k7  = (float)($this->v_n2 ?? 0);
        $k8  = $S > 0 ? (float)($this->v_n3 ?? 0) / $S : 0;
        $k9  = (float)($this->v_n1 ?? 0);
        $k10 = $S > 0 ? (float)($this->v_o2 ?? 0) / $S : 0;
        $k11 = $S > 0 ? (float)($this->v_o3 ?? 0) / $S : 0;

        $criteria = [$k1, $k2, $k3, $k4, $k5, $k6, $k7, $k8, $k9, $k10, $k11];

        $mu = [
            self::mu1($k1),
            self::mu2($k2),
            self::mu3($k3),
            self::mu4($k4),
            self::mu5($k5),
            self::mu6($k6),
            self::mu7($k7),
            self::mu8($k8),
            self::mu9($k9),
            self::mu10($k10),
            self::mu11($k11),
        ];
        $mu = array_map(fn($m) => max(0.0, min(1.0, $m)), $mu);

        $saved = $this->credit_weights ?? [];
        $raw   = [];
        for ($i = 1; $i <= 11; $i++) {
            $key     = "w_k{$i}";
            $fromArg = $weights[$key] ?? null;
            $fromDb  = $saved[$key]   ?? null;
            $raw[]   = (float)($fromArg ?? $fromDb ?? 5);
        }

        $sum = array_sum($raw);
        $w   = $sum > 0 ? array_map(fn($r) => $r / $sum, $raw) : array_fill(0, 11, 1 / 11);

        $mP = 0.0;
        foreach ($mu as $i => $m) {
            $mP += $w[$i] * $m;
        }
        $mP = round($mP, 6);

        [$category, $rating, $description, $color] = match(true) {
            $mP > 0.57 => [
                'I',
                'ААА / АА',
                'Найвищий рівень кредитоспроможності. Дуже низькі очікування по кредитних ризиках та дуже висока здатність своєчасно погашати фінансові зобовязання.',
                'green',
            ],
            $mP > 0.37 => [
                'II',
                'А / ВВВ',
                'Висока кредитоспроможність. Низькі очікування по кредитним ризикам. Здатність вчасно погашати фінансові зобов\'язання оцінюється як адекватна.',
                'blue',
            ],
            $mP > 0.19 => [
                'III',
                'ВВ / В',
                'Спекулятивний рейтинг. Існує можливість розвитку кредитних ризиків, особливо в результаті негативних економічних змін.',
                'yellow',
            ],
            $mP > 0.11 => [
                'IV',
                'ССС',
                'Можливий дефолт. Здатність виконувати фінансові зобов\'язання цілком залежить від стійкої та сприятливої ділової або економічної кон\'юнктури.',
                'orange',
            ],
            default => [
                'V',
                'С / RD / D',
                'Дефолт неминучий.',
                'red',
            ],
        };

        return [
            'criteria' => array_map(fn($k) => round($k, 4), $criteria),
            'mu'       => array_map(fn($m) => round($m, 4), $mu),
            'raw_w'    => $raw,
            'w'        => array_map(fn($wi) => round($wi, 4), $w),
            'm_p'      => $mP,
            'category' => $category,
            'rating'   => $rating,
            'description' => $description,
            'color'    => $color,
        ];
    }
}
