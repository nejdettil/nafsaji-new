<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\HtmlString;

class Profile extends Page
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'الملف الشخصي';
    protected static ?string $navigationGroup = 'الحساب';
    protected static ?string $title = 'الملف الشخصي';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.profile';

    public $name;
    public $email;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $avatar;

    public function mount(): void
    {
        $this->form->fill([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'avatar' => auth()->user()->avatar,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('المعلومات الشخصية')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('avatar')
                                    ->label('الصورة الشخصية')
                                    ->image()
                                    ->directory('users/avatars')
                                    ->imagePreviewHeight('250')
                                    ->maxSize(1024)
                                    ->circleCropper()
                                    ->columnSpanFull(),
                                TextInput::make('name')
                                    ->label('الاسم الكامل')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('البريد الإلكتروني')
                                    ->email()
                                    ->unique(ignorable: auth()->user())
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->collapsible(false),
                
                Section::make('تغيير كلمة المرور')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('current_password')
                                    ->label('كلمة المرور الحالية')
                                    ->password()
                                    ->rules([
                                        function () {
                                            return function (string $attribute, $value, \Closure $fail) {
                                                if (! Hash::check($value, auth()->user()->password)) {
                                                    $fail('كلمة المرور الحالية غير صحيحة');
                                                }
                                            };
                                        },
                                    ])
                                    ->dehydrated(false)
                                    ->autocomplete('current-password')
                                    ->revealable(),
                                TextInput::make('new_password')
                                    ->label('كلمة المرور الجديدة')
                                    ->password()
                                    ->rules([
                                        Password::min(8)
                                            ->letters()
                                            ->numbers(),
                                    ])
                                    ->revealable()
                                    ->autocomplete('new-password'),
                                TextInput::make('new_password_confirmation')
                                    ->label('تأكيد كلمة المرور الجديدة')
                                    ->password()
                                    ->same('new_password')
                                    ->dehydrated(false)
                                    ->revealable()
                                    ->autocomplete('new-password'),
                                
                                // إرشادات كلمة المرور
                                \Filament\Forms\Components\View::make('filament.components.password-requirements')
                                    ->label('')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->collapsible(true)
                    ->collapsed(true),
                
                Section::make('معلومات الحساب')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('registered_at')
                            ->label('تاريخ التسجيل')
                            ->content(fn () => auth()->user()->created_at->format('d/m/Y H:i')),
                        \Filament\Forms\Components\Placeholder::make('last_login_at')
                            ->label('آخر تسجيل دخول')
                            ->content(fn () => auth()->user()->last_login_at ? \Carbon\Carbon::parse(auth()->user()->last_login_at)->format('d/m/Y H:i') : 'غير متوفر'),
                        \Filament\Forms\Components\Placeholder::make('account_status')
                            ->label('حالة الحساب')
                            ->content(function () {
                                $user = auth()->user();
                                $status = $user->active ? 'نشط' : 'غير نشط';
                                $verified = $user->email_verified_at ? 'مفعل' : 'غير مفعل';
                                
                                return new HtmlString("
                                    <div class='flex flex-col gap-1'>
                                        <div class='flex items-center gap-2'>
                                            <span class='inline-flex items-center justify-center w-4 h-4 rounded-full " . ($user->active ? 'bg-success-500' : 'bg-danger-500') . "'></span>
                                            <span>{$status}</span>
                                        </div>
                                        <div class='flex items-center gap-2'>
                                            <span class='inline-flex items-center justify-center w-4 h-4 rounded-full " . ($user->email_verified_at ? 'bg-success-500' : 'bg-warning-500') . "'></span>
                                            <span>البريد الإلكتروني: {$verified}</span>
                                        </div>
                                    </div>
                                ");
                            }),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(true),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('حفظ التغييرات')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = auth()->user();
        
        // تحديث البيانات الشخصية
        $user->name = $data['name'];
        $user->email = $data['email'];
        
        if ($data['avatar'] && $data['avatar'] != $user->avatar) {
            $user->avatar = $data['avatar'];
        }
        
        // تحديث كلمة المرور إذا تم توفيرها
        if (filled($data['new_password'])) {
            $user->password = Hash::make($data['new_password']);
        }
        
        $user->save();
        
        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
            
            Notification::make()
                ->title('تم إرسال بريد إلكتروني للتحقق')
                ->body('تم تغيير بريدك الإلكتروني، يرجى التحقق من بريدك الجديد.')
                ->warning()
                ->send();
        }
        
        Notification::make()
            ->title('تم تحديث الملف الشخصي')
            ->success()
            ->send();
            
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }
}
