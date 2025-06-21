<?php

namespace App\Models;

use App\Models\Enums\ServiceEvent\Type;
use App\Models\Traits\HasUUID;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $name
 * @property string $identifier
 * @property string $service_id
 * @property string $type
 * @property string $trigger_notification_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEvent whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEvent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEvent whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEvent whereTriggerNotificationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEvent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEvent whereUpdatedAt($value)
 *
 * @mixin Eloquent
 * @mixin \Eloquent
 */
class ServiceEvent extends Model
{
    use HasUUID;

    protected $fillable = [
        'id',
        'name',
        'identifier',
        'service_id',
        'type',
        'trigger_notification_type',
    ];

    protected function casts(): array
    {
        return [
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
