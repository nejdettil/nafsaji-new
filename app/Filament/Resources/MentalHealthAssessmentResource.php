<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MentalHealthAssessmentResource\Pages;
use App\Filament\Resources\MentalHealthAssessmentResource\RelationManagers;
use App\Models\MentalHealthAssessment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MentalHealthAssessmentResource extends Resource
{
    protected static ?string $model = MentalHealthAssessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    // Ocultar de la navegación ya que la tabla no existe
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMentalHealthAssessments::route('/'),
            'create' => Pages\CreateMentalHealthAssessment::route('/create'),
            'edit' => Pages\EditMentalHealthAssessment::route('/{record}/edit'),
        ];
    }
}
