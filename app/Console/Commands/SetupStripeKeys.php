<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupStripeKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:stripe-keys 
                            {--public_key= : Stripe public key (starts with pk_)}
                            {--secret_key= : Stripe secret key (starts with sk_)}
                            {--webhook_secret= : Stripe webhook secret (starts with whsec_)}
                            {--sandbox=true : Use sandbox mode for testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Stripe API keys in the .env file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('إعداد مفاتيح Stripe...');

        // قراءة مفاتيح من المعاملات أو طلبها من المستخدم
        $publicKey = $this->option('public_key') ?: $this->ask('أدخل مفتاح Stripe العام (يبدأ بـ pk_)');
        $secretKey = $this->option('secret_key') ?: $this->secret('أدخل مفتاح Stripe السري (يبدأ بـ sk_)');
        $webhookSecret = $this->option('webhook_secret') ?: $this->ask('أدخل مفتاح webhook (اختياري، يبدأ بـ whsec_)');
        $sandbox = $this->option('sandbox');

        // التحقق من صحة المفاتيح
        if (!$this->validateKeys($publicKey, $secretKey)) {
            return 1;
        }

        // تحديث ملف .env
        $this->updateEnvFile($publicKey, $secretKey, $webhookSecret, $sandbox);

        $this->info('تم تحديث مفاتيح Stripe بنجاح!');
        $this->info('لا تنس تنظيف ذاكرة التخزين المؤقت للتكوين:');
        $this->line('php artisan config:clear');

        return 0;
    }

    /**
     * التحقق من صحة مفاتيح Stripe
     */
    private function validateKeys($publicKey, $secretKey)
    {
        $valid = true;

        if (!$publicKey || !str_starts_with($publicKey, 'pk_')) {
            $this->error('المفتاح العام غير صالح. يجب أن يبدأ بـ pk_');
            $valid = false;
        }

        if (!$secretKey || !str_starts_with($secretKey, 'sk_')) {
            $this->error('المفتاح السري غير صالح. يجب أن يبدأ بـ sk_');
            $valid = false;
        }

        return $valid;
    }

    /**
     * تحديث ملف .env بمفاتيح Stripe
     */
    private function updateEnvFile($publicKey, $secretKey, $webhookSecret, $sandbox)
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            $this->error('ملف .env غير موجود!');
            return;
        }

        $envContent = File::get($envPath);

        // تحديث أو إضافة مفاتيح Stripe
        $envContent = $this->updateEnvVariable($envContent, 'STRIPE_KEY', $publicKey);
        $envContent = $this->updateEnvVariable($envContent, 'STRIPE_SECRET', $secretKey);
        
        if ($webhookSecret) {
            $envContent = $this->updateEnvVariable($envContent, 'STRIPE_WEBHOOK_SECRET', $webhookSecret);
        }
        
        $envContent = $this->updateEnvVariable($envContent, 'STRIPE_SANDBOX', $sandbox ? 'true' : 'false');

        // حفظ التغييرات
        File::put($envPath, $envContent);
    }

    /**
     * تحديث متغير محدد في محتوى ملف .env
     */
    private function updateEnvVariable($content, $key, $value)
    {
        // التحقق مما إذا كان المتغير موجود بالفعل
        if (preg_match("/^{$key}=.*/m", $content)) {
            // تحديث القيمة الموجودة
            return preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            // إضافة متغير جديد في نهاية الملف
            return $content . PHP_EOL . "{$key}={$value}";
        }
    }
}
