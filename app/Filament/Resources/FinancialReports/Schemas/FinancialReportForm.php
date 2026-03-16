<?php

namespace App\Filament\Resources\FinancialReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class FinancialReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Загальна інформація')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('report_date')
                                    ->label('Дата звіту')
                                    ->required()
                                    ->native(false),
                                TextInput::make('company_name')
                                    ->label('Назва підприємства')
                                    ->required(),
                            ]),
                    ]),

                Tabs::make('Звітність')
                    ->tabs([
                        Tab::make('Форма №1: Баланс')
                            ->schema([
                                Placeholder::make('form_1_hint')
                                    ->content('Звіт про фінансовий стан (Активи, Пасиви)'),
                                Repeater::make('form_1_data')
                                    ->label('Статті балансу')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Назва статті')
                                            ->required(),
                                        TextInput::make('code')
                                            ->label('Код рядка')
                                            ->numeric()
                                            ->required(),
                                        TextInput::make('start')
                                            ->label('На початок року')
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('end')
                                            ->label('На кінець періоду')
                                            ->numeric()
                                            ->default(0),
                                    ])
                                    ->columns(4)
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                            ]),

                       Tab::make('Форма №2: Фінансові результати')
                            ->schema([
                                Placeholder::make('form_2_hint')
                                    ->content('Звіт про фінансові результати (прибутки та збитки)'),
                                Repeater::make('form_2_data')
                                    ->label('Статті звіту')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Назва статті')
                                            ->required(),
                                        TextInput::make('code')
                                            ->label('Код рядка')
                                            ->numeric()
                                            ->required(),
                                        TextInput::make('current')
                                            ->label('За звітний період')
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('previous')
                                            ->label('За аналогічний період')
                                            ->numeric()
                                            ->default(0),
                                    ])
                                    ->columns(4)
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                            ]),

                        Tab::make('Додаткова інформація')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('v_n1')
                                            ->label('V_n1: Термін існування (років)')
                                            ->numeric(),
                                        TextInput::make('v_n2')
                                            ->label('V_n2: Градація аналізу (0-5)')
                                            ->numeric()
                                            ->step(0.1)
                                            ->minValue(0)
                                            ->maxValue(5),
                                        TextInput::make('v_n3')
                                            ->label('V_n3: Найбільша сума кредиту (Sk)')
                                            ->numeric(),
                                        TextInput::make('v_o1')
                                            ->label('V_o1: Сума запитуваного кредиту (S)')
                                            ->numeric(),
                                        TextInput::make('v_o2')
                                            ->label('V_o2: Власні кошти в інвестицію (К)')
                                            ->numeric(),
                                        TextInput::make('v_o3')
                                            ->label('V_o3: Вартість ліквідного майна (М)')
                                            ->numeric(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
