<?php

namespace App\Filament\Widgets;

use App\Models\Contact;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestContactsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;
    protected static ?string $heading = 'أحدث رسائل الاتصال';
    protected static ?string $pollingInterval = '30s';
    protected static bool $deferLoading = false;
    
    protected function getTableContentFooter(): ?\Illuminate\Contracts\View\View
    {
        $totalMessages = Contact::count();
        $unreadCount = Contact::where('is_read', false)->count();
        
        $viewAllUrl = route('filament.admin.resources.contacts.index');
        
        return view('filament.widgets.latest-contacts-footer', [
            'totalMessages' => $totalMessages,
            'unreadCount' => $unreadCount,
            'viewAllUrl' => $viewAllUrl,
        ]);
    }

    protected function getTableQuery(): Builder
    {
        // التحقق من وجود جدول الاتصالات
        if (!\Illuminate\Support\Facades\Schema::hasTable('contacts')) {
            return Contact::query();
        }
        
        // إظهار الرسائل غير المقروءة أولاً ثم الرسائل الأحدث
        return Contact::query()
            ->orderBy('is_read', 'asc')
            ->latest('created_at')
            ->limit(7);
    }

    protected function getTableColumns(): array
    {
        return [
            IconColumn::make('is_read')
                ->label('')
                ->boolean()
                ->size(Tables\Columns\IconColumn\IconColumnSize::Medium)
                ->trueIcon('heroicon-o-check-circle')
                ->trueColor('success')
                ->falseIcon('heroicon-o-envelope')
                ->falseColor('danger'),
                
            TextColumn::make('name')
                ->label('المرسل')
                ->searchable()
                ->weight('bold')
                ->formatStateUsing(fn (string $state, Contact $record): string => 
                    $record->is_read ? $state : "<span class='font-bold'>{$state}</span>"),

            TextColumn::make('subject')
                ->label('الموضوع')
                ->limit(20)
                ->searchable()
                ->wrap()
                ->color(fn (Contact $record): string => $record->is_read ? 'gray' : 'primary'),
                
            TextColumn::make('message')
                ->label('الرسالة')
                ->limit(30)
                ->wrap()
                ->color('gray'),
                
            TextColumn::make('created_at')
                ->label('منذ')
                ->date('d/m/Y')
                ->sortable()
                ->color('gray')
                ->size('sm'),
            
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->icon('heroicon-o-eye')
                ->iconButton()
                ->tooltip('عرض الرسالة')
                ->url(fn (Contact $record): string => route('filament.admin.resources.contacts.edit', ['record' => $record]))
                ->openUrlInNewTab()
                ->extraAttributes([
                    'class' => 'text-primary-600 hover:text-primary-500',
                ]),
                
            Action::make('mark_read')
                ->icon('heroicon-o-check-circle')
                ->iconButton()
                ->tooltip('تمت القراءة')
                ->action(function (Contact $record) {
                    $record->update(['is_read' => true]);
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => 'تم تحديث حالة الرسالة',
                    ]);
                })
                ->hidden(fn (Contact $record) => $record->is_read)
                ->color('success')
                ->extraAttributes([
                    'class' => 'hover:scale-110 transition-transform',
                ]),
                
            Action::make('reply')
                ->icon('heroicon-o-paper-airplane')
                ->iconButton()
                ->tooltip('الرد على الرسالة')
                ->url(fn (Contact $record): string => "mailto:{$record->email}?subject=رد على استفسارك: {$record->subject}")
                ->openUrlInNewTab()
                ->color('warning')
                ->extraAttributes([
                    'class' => 'hover:scale-110 transition-transform',
                ]),
                
            Action::make('mark_unread')
                ->icon('heroicon-o-envelope')
                ->iconButton()
                ->tooltip('وضع كغير مقروءة')
                ->action(function (Contact $record) {
                    $record->update(['is_read' => false]);
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => 'تم وضع الرسالة كغير مقروءة',
                    ]);
                })
                ->hidden(fn (Contact $record) => !$record->is_read)
                ->color('gray')
                ->extraAttributes([
                    'class' => 'hover:scale-110 transition-transform',
                ]),
        ];
    }
    
    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
    
    protected function getTableHeading(): string
    {
        $unreadCount = Contact::where('is_read', false)->count();
        $badge = $unreadCount > 0 ? " <span class='text-warning-500'>($unreadCount جديدة)</span>" : '';
        
        return 'أحدث رسائل الاتصال' . $badge;
    }
}
