<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Models\User;
use App\Models\Specialist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Model;

class NotificationResource extends Resource
{
    protected static ?string $model = \App\Models\Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $navigationGroup = 'إدارة المحتوى';
    protected static ?string $navigationLabel = 'الإشعارات والرسائل';
    protected static ?string $modelLabel = 'إشعار';
    protected static ?string $pluralModelLabel = 'الإشعارات والرسائل';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('بيانات الإشعار')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('عنوان الإشعار/الرسالة')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label('نوع الإشعار')
                            ->options([
                                'general' => 'عام لجميع المستخدمين',
                                'specialists' => 'للمختصين فقط',
                                'users' => 'للمستخدمين العاديين فقط',
                                'selected' => 'لمستخدمين محددين',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('recipients', null)),
                        Forms\Components\Select::make('recipients')
                            ->label('المستلمون')
                            ->options(function (callable $get) {
                                if ($get('type') === 'specialists') {
                                    return Specialist::all()->pluck('name', 'id');
                                } elseif ($get('type') === 'users' || $get('type') === 'selected') {
                                    return User::where('role', $get('type') === 'users' ? 'user' : '!=', 'admin')
                                        ->pluck('name', 'id');
                                }
                                return [];
                            })
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->visible(fn (callable $get) => $get('type') === 'selected'),
                        Forms\Components\RichEditor::make('content')
                            ->label('محتوى الإشعار')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('إعدادات الإرسال')
                    ->schema([
                        Forms\Components\Toggle::make('is_email')
                            ->label('إرسال بريد إلكتروني')
                            ->helperText('سيتم إرسال الإشعار عبر البريد الإلكتروني أيضًا'),
                        Forms\Components\Toggle::make('is_push')
                            ->label('إرسال إشعار فوري')
                            ->helperText('سيتم إرسال إشعار فوري للمستخدمين المستهدفين'),
                        Forms\Components\Toggle::make('is_sms')
                            ->label('إرسال رسالة نصية')
                            ->helperText('سيتم إرسال رسالة نصية (SMS) للمستخدمين المستهدفين'),
                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label('موعد الإرسال')
                            ->helperText('تركه فارغًا سيرسل الإشعار فورًا')
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('النوع')
                    ->colors([
                        'primary' => 'general',
                        'warning' => 'specialists',
                        'success' => 'users',
                        'info' => 'selected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'general' => 'عام',
                        'specialists' => 'المختصين',
                        'users' => 'المستخدمين',
                        'selected' => 'محددين',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('content')
                    ->label('المحتوى')
                    ->html()
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_email')
                    ->label('بريد')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_push')
                    ->label('إشعار')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_sms')
                    ->label('SMS')
                    ->boolean(),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('موعد الإرسال')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('النوع')
                    ->options([
                        'general' => 'عام',
                        'specialists' => 'المختصين',
                        'users' => 'المستخدمين',
                        'selected' => 'محددين',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('send')
                    ->label('إرسال الآن')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->action(function (Model $record) {
                        // منطق إرسال الإشعارات هنا
                        // مثال: Notification::send($users, new \App\Notifications\GeneralNotification($record));
                        
                        // تحديث سجل الإشعار
                        $record->update(['sent_at' => now()]);
                    })
                    ->requiresConfirmation()
                    ->hidden(fn (Model $record) => $record->sent_at !== null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'view' => Pages\ViewNotification::route('/{record}'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
