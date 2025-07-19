<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Integrations\Workflow\Requests;

use App\Http\Integrations\Workflow\Exceptions\OAuthTokenExpiredException;
use App\Models\WorkflowAction;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class WorkflowActionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method;

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

    /**
     * @throws OAuthTokenExpiredException
     * */
    public static function fromWorkflowAction(WorkflowAction $action): self
    {
        /** @var \App\Models\User $user */
        $user = $action->workflow->user;
        /** @var \App\Models\ServiceSubscription $serviceSubscription */
        $serviceSubscription = $user->serviceSubscriptions()
            ->where('service_id', $action->serviceAction->service->id)
            ->first();
        $oauthToken = $serviceSubscription->oauthToken;

        if ($oauthToken->expired()) {
            throw new OAuthTokenExpiredException;
        }

        $oauthTokenValue = $oauthToken->value;

        $request = self::make();
        $request->method = $action->http_method;
        $request->headers()->add('Authorization', 'Bearer '.$oauthTokenValue);
        $request->url = $action->url;
        $request->body()->merge($action->resolved_body);
        $request->query()->merge($action->query_parameters ?? []);
        $request->headers()->merge($action->headers ?? []);

        return $request;
    }
}
