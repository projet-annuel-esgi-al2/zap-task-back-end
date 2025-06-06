<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trigger extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'service_id', 'config_schema'];

    protected $casts = [
        'config_schema' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function workflowSteps()
    {
        return $this->hasMany(WorkflowStep::class, 'ref_id');
    }
}
