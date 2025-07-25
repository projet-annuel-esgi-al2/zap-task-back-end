<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Models;

use App\Enums\Service\Identifier;
use App\Enums\ServiceAction\Type;
use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property Identifier $identifier
 * @property string $socialite_driver_identifier
 * @property array $oauth_token_options
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSocialiteDriverIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceAction> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceScope> $scopes
 * @property-read int|null $scopes_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceAction> $actions
 * @property-read int|null $actions_count
 *
 * @method static \Database\Factories\ServiceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereOauthTokenOptions($value)
 *
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceAction> $serviceActions
 * @property-read int|null $service_actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceAction> $triggers
 * @property-read int|null $triggers_count
 *
 * @mixin \Eloquent
 */
class Service extends Model
{
    use HasFactory;
    use HasUUID;

    protected $fillable = [
        'id',
        'identifier',
        'name',
        'socialite_driver_identifier',
        'oauth_token_options',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'identifier' => Identifier::class,
            'oauth_token_options' => 'array',
        ];
    }

    public function serviceActions(): HasMany
    {
        return $this->hasMany(ServiceAction::class);
    }

    public function triggers(): HasMany
    {
        return $this->hasMany(ServiceAction::class)
            ->where('type', Type::Trigger);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(ServiceAction::class)
            ->where('type', Type::Action);
    }

    public function scopes(): HasMany
    {
        return $this->hasMany(ServiceScope::class);
    }
}
