<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'is_read'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get a truncated version of the message for preview.
     *
     * @param int $length
     * @return string
     */
    public function getPreviewAttribute(int $length = 100): string
    {
        if (strlen($this->message) <= $length) {
            return $this->message;
        }
        
        return substr($this->message, 0, $length) . '...';
    }
}
