<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContact extends EditRecord
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('reply')
                ->label('الرد بالبريد')
                ->icon('heroicon-o-paper-airplane')
                ->url(fn () => "mailto:{$this->record->email}?subject=رد على استفسارك: {$this->record->subject}")
                ->openUrlInNewTab(),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // تحديث حالة القراءة تلقائيًا عند تحرير الرسالة
        $data['is_read'] = true;
        
        return $data;
    }
    
    protected function afterSave(): void
    {
        // إعادة تحميل التنقل لتحديث شارة العدد
        $this->redirectRoute('filament.admin.resources.contacts.index');
    }
}
