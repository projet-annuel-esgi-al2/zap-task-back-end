<?php

namespace App\Http\Integrations\Workflow\Requests;

use App\Models\WorkflowAction;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class WorkflowActionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    protected ?string $url;

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function resolveEndpoint(): string
    {
        return $this->url;
    }

    protected function defaultBody(): array
    {
        return [];
    }

    public static function fromWorkflowAction(WorkflowAction $action): self
    {
        /** @var \App\Models\User $user */
        $user = $action->workflow->user;
        /** @var \App\Models\ServiceSubscription $serviceSubscription */
        $serviceSubscription = $user->serviceSubscriptions()
            ->where('service_id', $action->serviceAction->service->id)
            ->first();
        $oauthTokenValue = $serviceSubscription->oauthToken->value;

        $request = self::make();
        $request->headers()->add('Authorization', 'Bearer '.$oauthTokenValue);
        $request->url = $action->url;
        $request->body()->merge($action->resolved_body);

        return $request;
    }
}
