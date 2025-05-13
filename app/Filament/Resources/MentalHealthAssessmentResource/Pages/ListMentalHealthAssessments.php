<?php

namespace App\Filament\Resources\MentalHealthAssessmentResource\Pages;

use App\Filament\Resources\MentalHealthAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMentalHealthAssessments extends ListRecords
{
    protected static string $resource = MentalHealthAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
