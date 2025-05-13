<?php

namespace App\Filament\Resources\MentalHealthAssessmentResource\Pages;

use App\Filament\Resources\MentalHealthAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMentalHealthAssessment extends EditRecord
{
    protected static string $resource = MentalHealthAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
