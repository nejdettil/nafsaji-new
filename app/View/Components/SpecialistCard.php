<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SpecialistCard extends Component
{
    /**
     * بيانات المتخصص
     *
     * @var array|متخصص|object
     */
    public $specialist;
    
    /**
     * هل البطاقة للجوال
     *
     * @var bool
     */
    public $mobile;
    
    /**
     * النمط - يمكن أن يكون 'compact', 'full', 'horizontal'
     * 
     * @var string
     */
    public $mode;

    /**
     * إنشاء مثيل جديد للمكون.
     * 
     * @param array|object $specialist بيانات المتخصص
     * @param boolean $mobile هل البطاقة للجوال
     * @param string $mode نمط البطاقة ('compact', 'full', 'horizontal')
     */
    public function __construct($specialist, $mobile = false, $mode = 'full')
    {
        $this->specialist = $specialist;
        $this->mobile = $mobile;
        $this->mode = $mode;
    }
    
    /**
     * الحصول على اسم المتخصص
     * 
     * @return string
     */
    public function getName(): string
    {
        if (is_array($this->specialist)) {
            return $this->specialist['name'] ?? '';
        }
        
        return $this->specialist->name ?? '';
    }
    
    /**
     * الحصول على التخصص
     * 
     * @return string
     */
    public function getSpecialty(): string
    {
        if (is_array($this->specialist)) {
            return $this->specialist['specialty'] ?? '';
        }
        
        return $this->specialist->specialty ?? '';
    }
    
    /**
     * الحصول على التقييم
     * 
     * @return int|float
     */
    public function getRating()
    {
        if (is_array($this->specialist)) {
            return $this->specialist['rating'] ?? 0;
        }
        
        return $this->specialist->rating ?? 0;
    }
    
    /**
     * الحصول على صورة المتخصص
     * 
     * @return string
     */
    public function getImage(): string
    {
        $defaultImage = config('nafsaji.specialists.default_image', 'images/default-specialist.png');
        
        if (is_array($this->specialist)) {
            return $this->specialist['image'] ?? $defaultImage;
        }
        
        return $this->specialist->image ?? $defaultImage;
    }
    
    /**
     * الحصول على رابط صفحة المتخصص
     * 
     * @return string
     */
    public function getProfileUrl(): string
    {
        $id = 0;
        
        if (is_array($this->specialist)) {
            $id = $this->specialist['id'] ?? 0;
        } else {
            $id = $this->specialist->id ?? 0;
        }
        
        if ($id) {
            return $this->mobile 
                ? route('mobile.specialist.profile', $id) 
                : route('specialists.show', $id);
        }
        
        return '#';
    }

    /**
     * الحصول على العرض / المحتويات التي تمثل المكون.
     */
    public function render(): View|Closure|string
    {
        return view('components.specialist-card');
    }
}
