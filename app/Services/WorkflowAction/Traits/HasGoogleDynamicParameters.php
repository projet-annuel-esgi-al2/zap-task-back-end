<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Services\WorkflowAction\Traits;

trait HasGoogleDynamicParameters
{
    use HasWorkflowAction;

    /*
     * Used when webhook subscription is done via a "channel" that the service requires to be identified
     * */
    public function channelId(): string
    {
        return $this->workflowAction()->workflow->deployment_id;
    }

    /*
     * Used when service sends an ID with each webhook call
     * This token will be used to identify the workflow to be executed upon webhook call reception
     * */
    public function webhookToken(): string
    {
        return $this->workflowAction()->workflow->id;
    }
}
