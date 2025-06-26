<?php

namespace App\Http\Requests;

use App\Traits\Makeable;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkflowRequest extends FormRequest
{
    use Makeable;

    public function authorize(): bool
    {
        return true;
    }

    public static function getActionRules(): array
    {
        return collect(StoreWorkflowActionRequest::make()->rules())
            ->mapWithKeys(fn ($value, $key) => ['actions.*.'.$key => $value])
            ->toArray();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            ...self::getActionRules(),
        ];
    }
}
