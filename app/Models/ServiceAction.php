<?php

namespace App\Models;

use App\Enums\ServiceAction\Identifier;
use App\Enums\ServiceAction\TriggerNotificationType;
use App\Enums\ServiceAction\Type;
use App\Models\Traits\HasUUID;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 * @property Identifier $identifier
 * @property string $service_id
 * @property Type $type
 * @property array $parameters
 * @property TriggerNotificationType $trigger_notification_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereTriggerNotificationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereUpdatedAt($value)
 *
 * @mixin Eloquent
 *
 * @property-read \App\Models\Service $service
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkflowAction> $workflowActions
 * @property-read int|null $workflow_actions_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereParameters($value)
 *
 * @mixin \Eloquent
 */
class ServiceAction extends Model
{
    use HasUUID;

    protected $fillable = [
        'id',
        'name',
        'identifier',
        'service_id',
        'type',
        'parameters',
        'trigger_notification_type',
    ];

    protected function casts(): array
    {
        return [
            'identifier' => Identifier::class,
            'type' => Type::class,
            'parameters' => 'array',
            'trigger_notification_type' => TriggerNotificationType::class,
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function workflowActions(): HasMany
    {
        return $this->hasMany(WorkflowAction::class);
    }
}
