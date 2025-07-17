<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Services\GoogleCalendar\Exceptions;

use App\Models\WorkflowAction;
use RuntimeException;

class NullCalendarIdException extends RuntimeException
{
    public static function make(WorkflowAction $action): self
    {
        return new self('Null calendarId on WorkflowAction id = '.$action->id);
    }
}
