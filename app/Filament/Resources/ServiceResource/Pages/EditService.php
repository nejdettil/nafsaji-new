<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('حذف الخدمة'),
            Actions\Action::make('view')
                ->label('عرض في الموقع')
                ->url(fn ($record) => route('services.show', $record))
                ->openUrlInNewTab()
                ->icon('heroicon-o-eye'),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if(empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
