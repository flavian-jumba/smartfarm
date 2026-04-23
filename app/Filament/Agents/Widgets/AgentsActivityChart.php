<?php

namespace App\Filament\Agents\Widgets;

use App\Models\FieldUpdate;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AgentsActivityChart extends ChartWidget
{
    protected ?string $heading = 'My Activity (Last 7 Days)';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $agentId = Auth::id();
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M j');
            $data[] = FieldUpdate::where('agent_id', $agentId)
                ->whereDate('created_at', $date)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Updates',
                    'data' => $data,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
