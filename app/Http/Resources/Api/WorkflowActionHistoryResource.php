<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

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
            'action_name' => $this->whenLoaded('workflowAction', fn () => optional($this->workflowAction->serviceAction)->name),
            'service_name' => $this->whenLoaded('workflowAction', fn () => optional(optional($this->workflowAction->serviceAction)->service)->name),
            'type' => $this->whenLoaded('workflowAction', fn () => optional($this->workflowAction->serviceAction)->type),
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
