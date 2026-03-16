<?php

use App\Http\Controllers\FinancialReportPrintController;
use Illuminate\Support\Facades\Route;

Route::get('/financial-reports/{financialReport}/print', [FinancialReportPrintController::class, 'show'])
    ->name('financial-reports.print');
