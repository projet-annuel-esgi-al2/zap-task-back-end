<?php

namespace App\Models;

use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * 
 *
 * @property string $id
 * @property string $service_id
 * @property string $scope_value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceScope newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceScope newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceScope query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceScope whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceScope whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceScope whereScopeValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceScope whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceScope whereUpdatedAt($value)
 * @property-read \App\Models\Service $service
 * @mixin \Eloquent
 */
class ServiceScope extends Model
{
    use HasUUID;

    protected $fillable = [
        'id',
        'service_id',
        'scope_value',
        'created_at',
        'updated_at',
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
