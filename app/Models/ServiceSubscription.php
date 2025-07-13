<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Models;

use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $service_id
 * @property string $oauth_token_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OAuthToken $oauthToken
 * @property-read \App\Models\Service $service
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSubscription whereOauthTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSubscription whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSubscription whereUpdatedAt($value)
 * @method static \Database\Factories\ServiceSubscriptionFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class ServiceSubscription extends Model
{
    use HasFactory;
    use HasUUID;

    protected $fillable = [
        'id',
        'service_id',
        'oauth_token_id',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function oauthToken(): BelongsTo
    {
        return $this->belongsTo(OAuthToken::class);
    }
}
