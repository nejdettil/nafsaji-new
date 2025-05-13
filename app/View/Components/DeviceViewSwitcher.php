<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Services\DeviceService;
use Illuminate\Support\Facades\URL;

class DeviceViewSwitcher extends Component
{
    /**
     * نوع الجهاز الحالي
     *
     * @var string
     */
    public $deviceType;
    
    /**
     * هل الجهاز الحالي هو جوال
     *
     * @var bool
     */
    public $isMobile;
    
    /**
     * عنوان URL للتبديل إلى عرض سطح المكتب
     *
     * @var string
     */
    public $desktopViewUrl;
    
    /**
     * عنوان URL للتبديل إلى عرض الجوال
     *
     * @var string
     */
    public $mobileViewUrl;

    /**
     * إنشاء مثيل جديد للمكون
     */
    public function __construct()
    {
        // تحديد نوع الجهاز باستخدام خدمة DeviceService
        $this->deviceType = DeviceService::getDeviceType();
        $this->isMobile = DeviceService::isMobile();
        
        // إنشاء روابط التبديل بين عرض سطح المكتب والجوال
        $currentUrl = url()->current();
        $currentRoute = request()->route()->getName();
        
        // إذا كان المسار مسار جوال، فقم بإعادة توجيه إلى الصفحة الرئيسية
        if (strpos($currentRoute, 'mobile.') === 0) {
            $desktopRoute = str_replace('mobile.', '', $currentRoute);
            $this->desktopViewUrl = route($desktopRoute) . '?desktop_view=1';
        } else {
            // إذا لم يكن مسار جوال، فقم بإضافة معلمة desktop_view
            $this->desktopViewUrl = $currentUrl . (parse_url($currentUrl, PHP_URL_QUERY) ? '&' : '?') . 'desktop_view=1';
        }
        
        // رابط عرض الجوال - إزالة معلمة desktop_view إذا وجدت
        // إذا كان هناك مسار جوال مقابل، استخدمه
        if (!$this->isMobile || DeviceService::isForceDesktopView()) {
            if (substr($currentRoute, 0, 7) !== 'mobile.') {
                try {
                    $mobileRoute = 'mobile.' . $currentRoute;
                    $this->mobileViewUrl = route($mobileRoute);
                } catch (\Exception $e) {
                    // إذا لم يوجد مسار جوال مقابل، استخدم الصفحة الرئيسية للجوال
                    $this->mobileViewUrl = route('mobile.app');
                }
            } else {
                // إذا كنا في مسار جوال بالفعل، استخدم نفس العنوان ولكن احذف desktop_view
                $currentUrlParts = parse_url($currentUrl);
                $query = [];
                if (isset($currentUrlParts['query'])) {
                    parse_str($currentUrlParts['query'], $query);
                    unset($query['desktop_view']);
                }
                
                $newQuery = http_build_query($query);
                $this->mobileViewUrl = $currentUrlParts['path'] . ($newQuery ? '?' . $newQuery : '');
            }
        }
    }

    /**
     * الحصول على العرض / المحتويات التي تمثل المكون
     */
    public function render(): View|Closure|string
    {
        return view('components.device-view-switcher');
    }
}
