<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Models;

use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read OAuthToken|null $childToken
 * @property-read OAuthToken|null $parentToken
 * @property-read \App\Models\ServiceSubscription|null $serviceSubscription
 * @property-read \App\Models\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken query()
 *
 * @property string $id
 * @property string $user_id
 * @property string $value
 * @property string|null $refresh_token
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property string|null $parent_token_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\OAuthTokenFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken whereParentTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OAuthToken whereValue($value)
 *
 * @property-read mixed $expires_in
 *
 * @mixin \Eloquent
 */
class OAuthToken extends Model
{
    use HasFactory;
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

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function expired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function expiresIn(): Attribute
    {
        return Attribute::make(
            get: fn () => now()->diffInSeconds($this->expires_at),
        );
    }

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
