<?php

namespace App\Filament\Widgets;

use App\Models\Field;
use Filament\Widgets\ChartWidget;

class FieldStagesChart extends ChartWidget
{
    protected ?string $heading = 'Fields by Stage';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'half';

    protected function getData(): array
    {
        $stages = Field::selectRaw('current_stage, COUNT(*) as count')
            ->groupBy('current_stage')
            ->pluck('count', 'current_stage')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Fields',
                    'data' => [
                        $stages['planted'] ?? 0,
                        $stages['growing'] ?? 0,
                        $stages['ready'] ?? 0,
                        $stages['harvested'] ?? 0,
                    ],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',  // blue - planted
                        'rgba(245, 158, 11, 0.8)', // amber - growing
                        'rgba(34, 197, 94, 0.8)',  // green - ready
                        'rgba(156, 163, 175, 0.8)', // gray - harvested
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(245, 158, 11)',
                        'rgb(34, 197, 94)',
                        'rgb(156, 163, 175)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Planted', 'Growing', 'Ready', 'Harvested'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
