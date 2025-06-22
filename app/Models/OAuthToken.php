<?php

namespace App\Models;

use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read OAuthToken|null $childToken
 * @property-read OAuthToken|null $parentToken
 * @property-read \App\Models\ServiceSubscription|null $serviceSubscription
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken query()
 * @mixin \Eloquent
 */
class OAuthToken extends Model
{
    use HasUUID;

    protected $table = 'oauth_tokens';

    protected $fillable = [
        'id',
        'user_id',
        'value',
        'refresh_token',
        'expires_at',
        'parent_token_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceSubscription(): HasOne
    {
        return $this->hasOne(ServiceSubscription::class, 'oauth_token_id');
    }

    public function parentToken(): BelongsTo
    {
        return $this->belongsTo(OAuthToken::class, 'parent_token_id');
    }

    public function childToken(): HasOne
    {
        return $this->hasOne(OAuthToken::class, 'parent_token_id');
    }
}
