<?php

namespace App\Filament\Resources\FinancialReports\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FinancialReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('report_date')
                    ->label('Дата звіту')
                    ->date()
                    ->sortable(),
                TextColumn::make('company_name')
                    ->label('Підприємство')
                    ->searchable(),
                TextColumn::make('v_n1')
                    ->label('Термін існування (років)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('v_n3')
                    ->label('Max сума кредиту (Sk)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('v_o1')
                    ->label('Запитуваний кредит (S)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('print')
                    ->label('Друк')
                    ->icon(Heroicon::OutlinedPrinter)
                    ->color('info')
                    ->url(fn ($record) => route('financial-reports.print', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
            ]);
    }
}
