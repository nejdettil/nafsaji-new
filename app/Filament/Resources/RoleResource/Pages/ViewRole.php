<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('تعديل'),
            Actions\Action::make('clone')
                ->label('نسخ الدور')
                ->icon('heroicon-o-document-duplicate')
                ->action(function () {
                    $role = $this->record;
                    $newRole = $role->replicate();
                    $newRole->name = $role->name . ' (نسخة)';
                    $newRole->save();
                    
                    // نسخ الصلاحيات
                    $permissions = $role->permissions->pluck('id')->toArray();
                    $newRole->permissions()->sync($permissions);
                    
                    $this->redirect(RoleResource::getUrl('edit', ['record' => $newRole]));
                })
                ->requiresConfirmation()
                ->modalHeading('نسخ الدور')
                ->modalDescription('هل أنت متأكد من نسخ هذا الدور مع جميع صلاحياته؟')
                ->modalSubmitActionLabel('نعم، نسخ الدور'),
            Actions\DeleteAction::make()
                ->label('حذف')
                ->modalHeading('حذف الدور')
                ->modalDescription('هل أنت متأكد من حذف هذا الدور؟ سيتم إزالة الصلاحيات من المستخدمين المرتبطين به.')
                ->modalSubmitActionLabel('نعم، حذف الدور'),
        ];
    }
}
