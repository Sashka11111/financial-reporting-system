<?php

namespace App\Http\Controllers;

use App\Models\FinancialReport;
use Illuminate\Http\Request;

class FinancialReportPrintController extends Controller
{
    public function show(FinancialReport $financialReport)
    {
        return view('financial-reports.print', [
            'report' => $financialReport,
        ]);
    }
}
