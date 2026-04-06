<?php

namespace App\Filament\Resources\FinancialReports\Pages;

use App\Filament\Resources\FinancialReports\FinancialReportResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditFinancialReport extends EditRecord
{
    protected static string $resource = FinancialReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Перегляд та друк звіту')
                ->icon(Heroicon::OutlinedPrinter)
                ->color('info')
                ->url(fn ($record) => route('financial-reports.print', $record))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}
