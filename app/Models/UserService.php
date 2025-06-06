<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class UserService extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'service_id',
        'access_token',
        'refresh_token',
        'token_expires_at'
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
    ];

    // Automatically generate UUID if not set
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function calendarChannels()
    {
        return $this->hasMany(CalendarChannel::class);
    }
}
