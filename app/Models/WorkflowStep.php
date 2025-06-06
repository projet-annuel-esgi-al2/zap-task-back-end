<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowStep extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'workflow_id', 'type', 'ref_id', 'config', 'order'
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function trigger()
    {
        return $this->belongsTo(Trigger::class, 'ref_id')->where('type', 'trigger');
    }

    public function action()
    {
        return $this->belongsTo(Action::class, 'ref_id')->where('type', 'action');
    }
}
