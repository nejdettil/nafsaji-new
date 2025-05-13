<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * أنواع الإجراءات الممكنة
     */
    const ACTION_TYPE_CREATE = 'create';
    const ACTION_TYPE_UPDATE = 'update';
    const ACTION_TYPE_DELETE = 'delete';
    const ACTION_TYPE_RESTORE = 'restore';
    const ACTION_TYPE_LOGIN = 'login';
    const ACTION_TYPE_LOGOUT = 'logout';
    const ACTION_TYPE_VIEW = 'view';
    const ACTION_TYPE_EXPORT = 'export';
    const ACTION_TYPE_IMPORT = 'import';
    const ACTION_TYPE_DOWNLOAD = 'download';
    const ACTION_TYPE_UPLOAD = 'upload';
    const ACTION_TYPE_SEND = 'send';
    const ACTION_TYPE_OTHER = 'other';

    /**
     * الخصائص القابلة للتعبئة الجماعية.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'action_type',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    /**
     * الخصائص التي يجب تحويلها.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'properties' => 'json',
    ];

    /**
     * الحصول على المستخدم الذي نفذ النشاط.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الحصول على الكائن المرتبط بالنشاط.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * تسجيل نشاط جديد.
     *
     * @param string $action
     * @param string $actionType
     * @param string $description
     * @param Model|null $subject
     * @param array|null $properties
     * @return static
     */
    public static function log(
        string $action,
        string $actionType,
        string $description,
        ?Model $subject = null,
        ?array $properties = []
    ): self {
        $user = auth()->user();
        $request = request();

        $log = new static();
        $log->user_id = $user?->id;
        $log->action = $action;
        $log->action_type = $actionType;
        $log->description = $description;
        
        if ($subject) {
            $log->subject()->associate($subject);
        }
        
        $log->properties = $properties;
        $log->ip_address = $request->ip();
        $log->user_agent = $request->userAgent();
        $log->save();

        return $log;
    }
}
