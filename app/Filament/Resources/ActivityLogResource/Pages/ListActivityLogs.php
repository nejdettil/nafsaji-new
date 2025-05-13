<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('purge')
                ->label('مسح السجل')
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('هل أنت متأكد من مسح سجل الأنشطة؟')
                ->modalDescription('سيتم حذف جميع سجلات الأنشطة القديمة. هذا الإجراء لا يمكن التراجع عنه.')
                ->modalSubmitActionLabel('نعم، مسح السجل')
                ->action(function () {
                    // مسح السجلات القديمة (أكثر من شهر)
                    $deleted = \App\Models\ActivityLog::where('created_at', '<', now()->subMonth())->delete();
                    
                    $this->notification()->title('تم مسح السجل')
                        ->body("تم حذف {$deleted} من سجلات الأنشطة القديمة.")
                        ->success()
                        ->send();
                }),
        ];
    }
}
