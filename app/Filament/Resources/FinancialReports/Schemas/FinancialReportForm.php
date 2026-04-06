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
                                            ->label('Термін існування (років)')
                                            ->numeric(),
                                        TextInput::make('v_n2')
                                            ->label('Градація аналізу (0-5)')
                                            ->numeric()
                                            ->step(0.1)
                                            ->minValue(0)
                                            ->maxValue(5),
                                        TextInput::make('v_n3')
                                            ->label('Найбільша сума кредиту (Sk)')
                                            ->numeric(),
                                        TextInput::make('v_o1')
                                            ->label('Сума запитуваного кредиту (S)')
                                            ->numeric(),
                                        TextInput::make('v_o2')
                                            ->label('Власні кошти в інвестицію (К)')
                                            ->numeric(),
                                        TextInput::make('v_o3')
                                            ->label('Вартість ліквідного майна (М)')
                                            ->numeric(),
                                    ]),
                            ]),

                        Tab::make('Ваги критеріїв (v₁..v₁₁)')
                            ->schema([
                                Placeholder::make('Примітка')
                                    ->content('Вкажіть вагові коефіцієнти від 1 до 10 для кожного критерію кредитоспроможності. Нормалізація виконується автоматично. За замовчуванням усі ваги рівні (5).'),
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('credit_weights.w_k1')
                                            ->label('v₁ — K₁ Миттєва ліквідність')
                                            ->numeric()->default(5)->minValue(1)->maxValue(10)->step(1),
                                        TextInput::make('credit_weights.w_k2')
                                            ->label('v₂ — K₂ Поточна ліквідність')
                                            ->numeric()->default(5)->minValue(1)->maxValue(10)->step(1),
                                        TextInput::make('credit_weights.w_k3')
                                            ->label('v₃ — K₃ Загальна ліквідність')
                                            ->numeric()->default(5)->minValue(1)->maxValue(10)->step(1),
                                        TextInput::make('credit_weights.w_k4')
                                            ->label('v₄ — K₄ Фінансова незалежність')
                                            ->numeric()->default(5)->minValue(1)->maxValue(10)->step(1),
                                        TextInput::make('credit_weights.w_k5')
                                            ->label('v₅ — K₅ Маневреність коштів')
                                            ->numeric()->default(5)->minValue(1)->maxValue(10)->step(1),
                                        TextInput::make('credit_weights.w_k6')
                                            ->label('v₆ — K₆ Рентабельність виробництва')
                                            ->numeric()->default(5)->minValue(1)->maxValue(10)->step(1),
                                        TextInput::make('credit_weights.w_k7')
                                            ->label('v₇ — K₇ Діяльність минулих років')
                                            ->numeric()->default(5)->minValue(1)->maxValue(10)->step(1),
                                        TextInput::make('credit_weights.w_k8')
                                            ->label('v₈ — K₈ Повернення кредиту (Sk/S)')
                                            ->numeric()->default(5)->minValue(1)->maxValue(10)->step(1),
                                        TextInput::make('credit_weights.w_k9')
                                            ->label('v₉ — K₉ Термін існування')
                                            ->numeric()->default(5)->minValue(1)->maxValue(10)->step(1),
                                        TextInput::make('credit_weights.w_k10')
                                            ->label('v₁₀ — K₁₀ Питома вага коштів у проекті')
                                            ->numeric()->default(5)->minValue(1)->maxValue(10)->step(1),
                                        TextInput::make('credit_weights.w_k11')
                                            ->label('v₁₁ — K₁₁ Ліквідне майно (М/S)')
                                            ->numeric()->default(5)->minValue(1)->maxValue(10)->step(1),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
