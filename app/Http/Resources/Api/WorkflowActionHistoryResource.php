<?php

namespace App\Http\Resources\Api;

use App\Models\WorkflowActionHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin WorkflowActionHistory
 * */
class WorkflowActionHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'action_id' => $this->whenLoaded('workflowAction', fn () => $this->workflowAction->id),
            'execution_status' => $this->execution_status->value,
            'response_http_code' => $this->response_http_code,
            'execution_order' => $this->execution_order,
            'exception' => $this->exception,
            'url' => $this->url,
            'parameters' => $this->parameters,
            'executed_at' => $this->executed_at,
        ];
    }
}
