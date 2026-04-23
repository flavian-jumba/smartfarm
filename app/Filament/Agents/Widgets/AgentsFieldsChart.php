<?php

namespace App\Filament\Agents\Widgets;

use App\Models\Field;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AgentsFieldsChart extends ChartWidget
{
    protected ?string $heading = 'My Fields by Stage';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $agentId = Auth::id();

        $stages = Field::where('agent_id', $agentId)
            ->selectRaw('current_stage, count(*) as count')
            ->groupBy('current_stage')
            ->pluck('count', 'current_stage')
            ->toArray();

        $labels = ['Preparation', 'Planting', 'Growing', 'Harvesting', 'Post-Harvest'];
        $data = [
            $stages['preparation'] ?? 0,
            $stages['planting'] ?? 0,
            $stages['growing'] ?? 0,
            $stages['harvesting'] ?? 0,
            $stages['post-harvest'] ?? 0,
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Fields',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(156, 163, 175, 0.8)',  // gray
                        'rgba(59, 130, 246, 0.8)',   // blue
                        'rgba(234, 179, 8, 0.8)',    // yellow
                        'rgba(34, 197, 94, 0.8)',    // green
                        'rgba(99, 102, 241, 0.8)',   // indigo
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
