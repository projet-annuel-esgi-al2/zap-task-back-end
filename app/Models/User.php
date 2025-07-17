<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property ?string $rememberToken
 * @property PersonalAccessToken $latestAccessToken
 * @property Collection $tokens
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $tokens_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OAuthToken> $oauthTokens
 * @property-read int|null $oauth_tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceSubscription> $serviceSubscriptions
 * @property-read int|null $service_subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Workflow> $workflows
 * @property-read int|null $workflows_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GoogleCalendarSyncToken> $googleSyncTokens
 * @property-read int|null $google_sync_tokens_count
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use HasUUID;
    use Notifiable;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function latestAccessToken(): MorphOne
    {
        return $this->tokens()
            ->latest()
            ->one();
    }

    public function oauthTokens(): HasMany
    {
        return $this->hasMany(OAuthToken::class);
    }

    public function serviceSubscriptions(): HasManyThrough
    {
        return $this->hasManyThrough(ServiceSubscription::class, OAuthToken::class, 'user_id', 'oauth_token_id');
    }

    public function subscribedToService(Service $service, bool $excludeExpiredTokens = true): bool
    {
        $query = $this->serviceSubscriptions()
            ->whereHas('service', fn ($q) => $q->where('id', $service->id));

        if ($excludeExpiredTokens) {
            $query->whereDoesntHave(
                'oauthToken',
                fn ($q) => $q->where('expires_at', '<', now()->toDateTimeString())
                    ->where(fn ($query) => $query->whereNull('refresh_token')->orWhere('refresh_token', ''))
            );
        }

        return $query->exists();
    }

    public function workflows(): HasMany
    {
        return $this->hasMany(Workflow::class);
    }

    public function googleSyncTokens(): HasMany
    {
        return $this->hasMany(GoogleCalendarSyncToken::class);
    }
}
