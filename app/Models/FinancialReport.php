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
    ];

    protected $casts = [
        'report_date' => 'date',
        'form_1_data' => 'array',
        'form_2_data' => 'array',
        'v_n2' => 'float',
        'v_n3' => 'float',
        'v_o1' => 'float',
        'v_o2' => 'float',
        'v_o3' => 'float',
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
            default => 'Прибуткова (2 роки)',
        };

        $k_k = $this->v_o1 > 0 ? ($this->v_n3 / $this->v_o1) : 0;

        $k_3 = $this->v_n1;
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
                'k_p' => round($k_p, 4),
                'k_9_val' => $k_9_val,
                'k_9_desc' => $k_9_desc,
                'k_k' => round($k_k, 4),
            ],
            'g3' => [
                'k_3' => $k_3,
                'k_kp' => round($k_kp, 4),
                'k_lp' => round($k_lp, 4),
            ],
        ];
    }
}
