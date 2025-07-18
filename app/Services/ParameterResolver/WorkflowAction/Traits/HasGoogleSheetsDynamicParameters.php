<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Services\ParameterResolver\WorkflowAction\Traits;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

trait HasGoogleSheetsDynamicParameters
{
    use HasWorkflowAction;

    public function getSheetIdFromUrl($url): string
    {
        return Str::of($url)
            ->after('/spreadsheets/d/')
            ->before('/')
            ->value();
    }

    public function getDocumentIdFromUrl($url): string
    {
        return Str::of($url)
            ->after('/document/d/')
            ->before('/')
            ->value();
    }

    public function googleSheetsValues(): string
    {
        return new HtmlString(json_encode([[1, 2, 3], [4, 5, 6]]));
    }
}
