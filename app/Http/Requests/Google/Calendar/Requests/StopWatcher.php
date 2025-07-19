<?php

namespace App\Http\Requests\Google\Calendar\Requests;

use App\Models\Workflow;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Makeable;

class StopWatcher extends Request implements HasBody
{
    use HasJsonBody;
    use Makeable;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    public function __construct(private string $deploymentId, private string $watcherId) {}

    protected function defaultHeaders(): array
    {
        return [];
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/channels/stop';
    }

    public static function fromWorkflow(Workflow $workflow): self
    {
        return self::make($workflow->deployment_id, $workflow->trigger->watcher_id);
    }

    public function defaultBody(): array
    {
        return [
            // channelId = deploymentId
            'id' => $this->deploymentId,
            // watcher_id on workflowaction
            'resourceId' => $this->watcherId,
        ];
    }
}
