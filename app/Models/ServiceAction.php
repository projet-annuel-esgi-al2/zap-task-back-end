<?php

namespace App\Models;

use App\Enums\ServiceAction\Identifier;
use App\Enums\ServiceAction\TriggerNotificationType;
use App\Enums\ServiceAction\Type;
use App\Models\Traits\HasHttpParameters;
use App\Models\Traits\HasUUID;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property string|null $url
 * @property array $body_parameters
 * @property array $url_parameters
 * @property array $query_parameters
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereBodyParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereQueryParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereUrlParameters($value)
 *
 * @property-read array $body_parameters_for_api
 * @property-read array $query_parameters_for_api
 * @property-read array $url_parameters_for_api
 * @property-read array $headers_for_api
 * @property-read array $parameters_for_api
 *
 * @method static \Database\Factories\ServiceActionFactory factory($count = null, $state = [])
 *
 * @property array $headers
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereHeaders($value)
 *
 * @mixin \Eloquent
 */
class ServiceAction extends Model
{
    use HasFactory;
    use HasHttpParameters;
    use HasUUID;

    protected $fillable = [
        'id',
        'name',
        'identifier',
        'service_id',
        'type',
        'url',
        'body_parameters',
        'url_parameters',
        'query_parameters',
        'trigger_notification_type',
    ];

    protected function casts(): array
    {
        return [
            'identifier' => Identifier::class,
            'type' => Type::class,
            'body_parameters' => 'array',
            'url_parameters' => 'array',
            'query_parameters' => 'array',
            'headers' => 'array',
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
