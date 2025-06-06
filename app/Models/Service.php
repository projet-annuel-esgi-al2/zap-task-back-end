<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function triggers()
    {
        return $this->hasMany(Trigger::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function userServices()
    {
        return $this->hasMany(UserService::class);
    }
}
