<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Models;

use App\Enums\ServiceAction\Type;
use App\Enums\Workflow\Status;
use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property Status $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkflowAction> $actions
 * @property-read int|null $actions_count
 * @property-read \App\Models\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workflow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workflow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workflow query()
 *
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $saved_at
 * @property \Illuminate\Support\Carbon|null $deployed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workflow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workflow whereDeployedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workflow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workflow whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workflow whereSavedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workflow whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workflow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workflow whereUserId($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkflowAction> $executableActions
 * @property-read int|null $executable_actions_count
 * @property-read \App\Models\WorkflowAction|null $trigger
 *
 * @method static \Database\Factories\WorkflowFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class Workflow extends Model
{
    use HasFactory;
    use HasUUID;

    protected $fillable = [
        'user_id',
        'name',
        'status',
        'saved_at',
        'deployed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'saved_at' => 'datetime',
            'deployed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(WorkflowAction::class)
            ->orderBy('execution_order');
    }

    public function trigger(): HasOne
    {
        return $this->hasMany(WorkflowAction::class)
            ->whereHas('serviceAction', fn ($q) => $q->where('type', Type::Trigger))
            ->one();
    }

    public function executableActions(): HasMany
    {
        return $this->hasMany(WorkflowAction::class)
            ->whereHas('serviceAction', fn ($q) => $q->where('type', Type::Action));
    }
}
