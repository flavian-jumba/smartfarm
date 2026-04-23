<?php

namespace App\Filament\Agents\Widgets;

use App\Models\Field;
use App\Models\FieldUpdate;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AgentsStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $agentId = Auth::id();

        $totalFields = Field::where('agent_id', $agentId)->count();
        $totalUpdates = FieldUpdate::where('agent_id', $agentId)->count();
        $todaysUpdates = FieldUpdate::where('agent_id', $agentId)
            ->whereDate('created_at', today())
            ->count();
        $weekUpdates = FieldUpdate::where('agent_id', $agentId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        return [
            Stat::make('My Fields', $totalFields)
                ->description('Assigned to you')
                ->descriptionIcon('heroicon-m-map')
                ->color('primary'),

            Stat::make('Total Updates', $totalUpdates)
                ->description('All time submissions')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Today\'s Updates', $todaysUpdates)
                ->description('Submitted today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('This Week', $weekUpdates)
                ->description('Weekly submissions')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
}
