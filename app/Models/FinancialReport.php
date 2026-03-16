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
}
