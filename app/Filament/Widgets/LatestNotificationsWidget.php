<?php

namespace App\Filament\Widgets;

use App\Models\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestNotificationsWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'آخر الإشعارات';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Notification::where('read_at', null)
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->formatStateUsing(function ($state) {
                        return str_replace('App\\Notifications\\', '', $state);
                    }),
                Tables\Columns\TextColumn::make('data.title')
                    ->label('العنوان')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notifiable_type')
                    ->label('المستلم')
                    ->formatStateUsing(function ($state) {
                        return str_replace('App\\Models\\', '', $state);
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('عرض')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Notification $record): string => route('filament.admin.resources.notifications.view', $record)),
                Tables\Actions\Action::make('mark_as_read')
                    ->label('تعيين كمقروء')
                    ->icon('heroicon-o-check')
                    ->action(function (Notification $record): void {
                        $record->markAsRead();
                        $this->resetTableFiltersForm();
                    }),
            ])
            ->emptyStateIcon('heroicon-o-bell-slash')
            ->emptyStateHeading('لا توجد إشعارات جديدة')
            ->emptyStateDescription('سيتم عرض الإشعارات الجديدة هنا عند وصولها.');
    }
}
