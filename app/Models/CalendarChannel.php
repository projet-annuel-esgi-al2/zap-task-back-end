<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CalendarChannel extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'user_service_id', 'calendar_id', 'resource_id',
        'channel_id', 'channel_token', 'expiration'
    ];

    protected $dates = ['expiration'];

    public function userService()
    {
        return $this->belongsTo(UserService::class);
    }
}
