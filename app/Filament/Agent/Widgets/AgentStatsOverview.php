<?php

namespace App\Filament\Agent\Widgets;

use App\Models\Field;
use App\Models\FieldUpdate;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AgentStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        // Fields in my tenant
        $myFields = Field::where('tenant_id', $tenantId)->count();
        $myActiveFields = Field::where('tenant_id', $tenantId)
            ->whereIn('current_stage', ['planted', 'growing', 'ready'])
            ->count();

        // My submitted updates
        $myUpdates = FieldUpdate::where('agent_id', $user->id)->count();
        $myUpdatesThisWeek = FieldUpdate::where('agent_id', $user->id)
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        // Fields ready for harvest
        $readyForHarvest = Field::where('tenant_id', $tenantId)
            ->where('current_stage', 'ready')
            ->count();

        // Fields needing attention (no update in 7 days)
        $needsAttention = Field::where('tenant_id', $tenantId)
            ->whereIn('current_stage', ['planted', 'growing'])
            ->whereDoesntHave('updates', function ($query) {
                $query->where('created_at', '>=', now()->subWeek());
            })
            ->count();

        return [
            Stat::make('My Fields', $myFields)
                ->description($myActiveFields . ' active')
                ->descriptionIcon(Heroicon::OutlinedMap)
                ->color('primary'),

            Stat::make('My Updates', $myUpdates)
                ->description($myUpdatesThisWeek . ' this week')
                ->descriptionIcon(Heroicon::OutlinedClipboardDocumentList)
                ->color('success'),

            Stat::make('Ready for Harvest', $readyForHarvest)
                ->description('Fields ready to harvest')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->color($readyForHarvest > 0 ? 'success' : 'gray'),

            Stat::make('Needs Attention', $needsAttention)
                ->description('No update in 7 days')
                ->descriptionIcon(Heroicon::OutlinedExclamationTriangle)
                ->color($needsAttention > 0 ? 'danger' : 'success'),
        ];
    }
}
