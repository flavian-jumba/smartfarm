<?php

namespace App\Filament\Widgets;

use App\Models\Field;
use App\Models\FieldUpdate;
use App\Models\Tenant;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalFields = Field::count();
        $fieldsThisWeek = Field::where('created_at', '>=', now()->subWeek())->count();
        
        $totalUpdates = FieldUpdate::count();
        $updatesThisWeek = FieldUpdate::where('created_at', '>=', now()->subWeek())->count();

        return [
            Stat::make('Total Tenants', Tenant::count())
                ->description('Active organizations')
                ->descriptionIcon(Heroicon::OutlinedBuildingOffice2)
                ->color('primary'),

            Stat::make('Total Users', User::count())
                ->description(User::where('role', 'agent')->count() . ' agents, ' . User::where('role', 'admin')->count() . ' admins')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('success'),

            Stat::make('Total Fields', $totalFields)
                ->description($fieldsThisWeek . ' added this week')
                ->descriptionIcon(Heroicon::OutlinedMap)
                ->color('info'),

            Stat::make('Field Updates', $totalUpdates)
                ->description($updatesThisWeek . ' submitted this week')
                ->descriptionIcon(Heroicon::OutlinedClipboardDocumentList)
                ->color('warning'),
        ];
    }
}
