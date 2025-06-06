<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExecutionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'execution_id', 'step_name', 'step_type',
        'input_data', 'output_data', 'error_message', 'executed_at'
    ];

    protected $casts = [
        'input_data' => 'array',
        'output_data' => 'array',
    ];

    protected $dates = ['executed_at'];

    public function execution()
    {
        return $this->belongsTo(Execution::class);
    }
}
