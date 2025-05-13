<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    /**
     * boot الميثود - يتم استدعاؤها تلقائياً عند تهيئة النموذج
     */
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            static::logActivity(
                $model,
                'تم إنشاء ' . static::getModelDisplayName() . ' جديد',
                ActivityLog::ACTION_TYPE_CREATE
            );
        });

        static::updated(function (Model $model) {
            if ($model->wasChanged()) {
                static::logActivity(
                    $model,
                    'تم تحديث ' . static::getModelDisplayName(),
                    ActivityLog::ACTION_TYPE_UPDATE,
                    $model->getChanges()
                );
            }
        });

        static::deleted(function (Model $model) {
            static::logActivity(
                $model,
                'تم حذف ' . static::getModelDisplayName(),
                ActivityLog::ACTION_TYPE_DELETE
            );
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function (Model $model) {
                static::logActivity(
                    $model,
                    'تم استعادة ' . static::getModelDisplayName(),
                    ActivityLog::ACTION_TYPE_RESTORE
                );
            });
        }
    }

    /**
     * تسجيل نشاط للنموذج
     */
    public static function logActivity(Model $model, string $description, string $actionType, array $properties = [])
    {
        if (method_exists($model, 'shouldLogActivity') && !$model->shouldLogActivity($actionType)) {
            return;
        }

        $modelName = static::getModelDisplayName();

        ActivityLog::log(
            "تم {$actionType} {$modelName}",
            $actionType,
            $description,
            $model,
            array_merge($properties, ['model' => [
                'id' => $model->getKey(),
                'type' => get_class($model),
                'name' => $model->getDisplayName(),
            ]])
        );
    }

    /**
     * للتحقق مما إذا كان يجب تسجيل النشاط
     */
    public function shouldLogActivity(string $actionType): bool
    {
        return true;
    }

    /**
     * الحصول على الاسم المعروض للنموذج
     */
    public static function getModelDisplayName(): string
    {
        return strtolower(class_basename(get_called_class()));
    }

    /**
     * الحصول على الاسم المعروض للكائن
     */
    public function getDisplayName(): string
    {
        if (property_exists($this, 'displayNameAttribute')) {
            return $this->{$this->displayNameAttribute};
        }

        if (method_exists($this, 'getDisplayNameAttribute')) {
            return $this->getDisplayNameAttribute();
        }

        foreach (['name', 'title', 'label', 'subject', 'full_name', 'display_name'] as $attribute) {
            if ($this->{$attribute} ?? false) {
                return $this->{$attribute};
            }
        }

        return "#{$this->getKey()}";
    }
}
