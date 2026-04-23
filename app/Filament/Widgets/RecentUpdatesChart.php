<?php

namespace App\Filament\Widgets;

use App\Models\FieldUpdate;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RecentUpdatesChart extends ChartWidget
{
    protected ?string $heading = 'Updates (Last 7 Days)';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'half';

    protected function getData(): array
    {
        $data = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            return [
                'date' => $date->format('M j'),
                'count' => FieldUpdate::whereDate('created_at', $date)->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Field Updates',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
