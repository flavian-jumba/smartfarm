<?php

namespace App\Filament\Resources\Messages\Tables;

use App\Models\Message;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->rootMessages())
            ->columns([
                IconColumn::make('is_read')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('gray')
                    ->falseColor('info'),

                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'urgent' => 'danger',
                        'high' => 'warning',
                        'normal' => 'info',
                        'low' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('sender.name')
                    ->label('From')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('receiver.name')
                    ->label('To')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject')
                    ->searchable()
                    ->limit(40)
                    ->weight(fn (Message $record): string => $record->is_read ? 'normal' : 'bold'),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'alert' => 'danger',
                        'report' => 'info',
                        'request' => 'warning',
                        'broadcast' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('replies_count')
                    ->label('Replies')
                    ->counts('replies')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Sent')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'text' => 'Text',
                        'alert' => 'Alert',
                        'report' => 'Report',
                        'request' => 'Request',
                        'broadcast' => 'Broadcast',
                    ]),

                SelectFilter::make('priority')
                    ->options([
                        'urgent' => 'Urgent',
                        'high' => 'High',
                        'normal' => 'Normal',
                        'low' => 'Low',
                    ]),

                Filter::make('unread')
                    ->query(fn (Builder $query) => $query->unread())
                    ->label('Unread Only'),

                Filter::make('received')
                    ->query(fn (Builder $query) => $query->where('receiver_id', auth()->id()))
                    ->label('Received'),

                Filter::make('sent')
                    ->query(fn (Builder $query) => $query->where('sender_id', auth()->id()))
                    ->label('Sent'),
            ])
            ->recordActions([
                Action::make('mark_read')
                    ->icon('heroicon-o-envelope-open')
                    ->color('gray')
                    ->visible(fn (Message $record): bool => !$record->is_read && $record->receiver_id === auth()->id())
                    ->action(fn (Message $record) => $record->markAsRead()),

                Action::make('reply')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('info')
                    ->url(fn (Message $record): string => route('filament.admin.resources.messages.messages.create', [
                        'receiver_id' => $record->sender_id,
                        'parent_id' => $record->id,
                        'subject' => 'Re: ' . $record->subject,
                    ])),

                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('mark_read')
                        ->label('Mark as Read')
                        ->icon('heroicon-o-envelope-open')
                        ->action(fn (Collection $records) => $records->each(fn (Message $record) => $record->markAsRead())),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
