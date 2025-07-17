<?php

namespace App\Models;

use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id
 * @property string $user_id
 * @property string $calendar_id
 * @property string|null $sync_token
 * @property \Illuminate\Support\Carbon|null $last_time_min_parameter
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleCalendarSyncToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleCalendarSyncToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleCalendarSyncToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleCalendarSyncToken whereCalendarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleCalendarSyncToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleCalendarSyncToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleCalendarSyncToken whereLastTimeMinParameter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleCalendarSyncToken whereSyncToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleCalendarSyncToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleCalendarSyncToken whereUserId($value)
 *
 * @property-read \App\Models\User|null $user
 *
 * @mixin \Eloquent
 */
class GoogleCalendarSyncToken extends Model
{
    use HasUUID;

    protected $fillable = [
        'id',
        'user_id',
        'calendar_id',
        'sync_token',
        'last_time_min_parameter',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'last_time_min_parameter' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
