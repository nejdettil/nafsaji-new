<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:set-admin {email? : البريد الإلكتروني للمستخدم المراد تعيينه كمسؤول}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تعيين مستخدم معين أو أول مستخدم كمسؤول في النظام';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("لم يتم العثور على مستخدم بالبريد الإلكتروني: {$email}");
                return 1;
            }
        } else {
            $user = User::first();
            
            if (!$user) {
                $this->error('لا يوجد مستخدمين في النظام');
                return 1;
            }
        }
        
        $user->is_admin = true;
        $user->save();
        
        $this->info("تم تعيين المستخدم {$user->name} ({$user->email}) كمسؤول في النظام بنجاح");
        
        return 0;
    }
}
