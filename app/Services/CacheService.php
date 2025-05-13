<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CacheService
{
    /**
     * مدة صلاحية التخزين المؤقت الافتراضية بالدقائق
     */
    const DEFAULT_CACHE_TIME = 60; // 60 دقيقة

    /**
     * الحصول على كائنات المتخصصين مع تخزين مؤقت
     *
     * @param array $conditions شروط الاستعلام
     * @param array $orderBy ترتيب النتائج
     * @param int $limit عدد النتائج
     * @param int $minutes مدة صلاحية التخزين المؤقت بالدقائق
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCachedSpecialists(array $conditions = [], array $orderBy = [], int $limit = 10, int $minutes = self::DEFAULT_CACHE_TIME): Collection
    {
        $key = 'specialists_' . md5(json_encode($conditions) . json_encode($orderBy) . $limit);
        
        return Cache::remember($key, $minutes * 60, function () use ($conditions, $orderBy, $limit) {
            $query = \App\Models\Specialist::query();
            
            // إضافة الشروط
            foreach ($conditions as $column => $value) {
                $query->where($column, $value);
            }
            
            // إضافة الترتيب
            foreach ($orderBy as $column => $direction) {
                $query->orderBy($column, $direction);
            }
            
            // تحديد العدد
            if ($limit > 0) {
                $query->limit($limit);
            }
            
            return $query->get();
        });
    }

    /**
     * الحصول على متخصص واحد مع تخزين مؤقت
     *
     * @param int $id معرف المتخصص
     * @param int $minutes مدة صلاحية التخزين المؤقت بالدقائق
     * @return \App\Models\Specialist|null
     */
    public static function getCachedSpecialist(int $id, int $minutes = self::DEFAULT_CACHE_TIME): ?Model
    {
        $key = 'specialist_' . $id;
        
        return Cache::remember($key, $minutes * 60, function () use ($id) {
            return \App\Models\Specialist::find($id);
        });
    }

    /**
     * الحصول على كائنات الخدمات مع تخزين مؤقت
     *
     * @param array $conditions شروط الاستعلام
     * @param array $orderBy ترتيب النتائج
     * @param int $limit عدد النتائج
     * @param int $minutes مدة صلاحية التخزين المؤقت بالدقائق
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCachedServices(array $conditions = [], array $orderBy = [], int $limit = 10, int $minutes = self::DEFAULT_CACHE_TIME): Collection
    {
        $key = 'services_' . md5(json_encode($conditions) . json_encode($orderBy) . $limit);
        
        return Cache::remember($key, $minutes * 60, function () use ($conditions, $orderBy, $limit) {
            $query = \App\Models\Service::query();
            
            // إضافة الشروط
            foreach ($conditions as $column => $value) {
                $query->where($column, $value);
            }
            
            // إضافة الترتيب
            foreach ($orderBy as $column => $direction) {
                $query->orderBy($column, $direction);
            }
            
            // تحديد العدد
            if ($limit > 0) {
                $query->limit($limit);
            }
            
            return $query->get();
        });
    }

    /**
     * إزالة كل بيانات التخزين المؤقت المتعلقة بالمتخصصين
     */
    public static function clearSpecialistsCache(): void
    {
        Cache::flush();
        // يمكننا تحسينها لاحقًا لإزالة فقط مفاتيح التخزين المؤقت المتعلقة بالمتخصصين
    }

    /**
     * إزالة كل بيانات التخزين المؤقت المتعلقة بالخدمات
     */
    public static function clearServicesCache(): void
    {
        Cache::flush();
        // يمكننا تحسينها لاحقًا لإزالة فقط مفاتيح التخزين المؤقت المتعلقة بالخدمات
    }
}
