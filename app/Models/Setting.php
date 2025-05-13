<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
    ];
    
    /**
     * تحويل القيم المخزنة من JSON إلى قيمتها الحقيقية عند استرجاعها
     *
     * @param string $value
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        // محاولة فك تشفير JSON إذا كان النص يبدأ وينتهي بالعلامات المناسبة
        if (is_string($value) && (
            str_starts_with($value, '{') && str_ends_with($value, '}') || 
            str_starts_with($value, '[') && str_ends_with($value, ']')
        )) {
            $decoded = json_decode($value, true);
            
            // إذا كان التحويل ناجحًا، استخدام القيمة المحولة
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        
        // إذا كانت القيمة منطقية
        if ($value === 'true' || $value === 'false') {
            return $value === 'true';
        }
        
        // إذا كانت القيمة رقمية
        if (is_numeric($value)) {
            // إعادة القيمة كعدد صحيح إذا كانت مكتملة
            if ((int) $value == $value) {
                return (int) $value;
            }
            
            // إعادة القيمة كعدد عشري
            return (float) $value;
        }
        
        // إعادة القيمة كما هي
        return $value;
    }
}
