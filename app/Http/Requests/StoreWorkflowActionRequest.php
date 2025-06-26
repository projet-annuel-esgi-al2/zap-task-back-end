<?php

namespace App\Http\Requests;

use App\Enums\ServiceAction\Identifier;
use App\Traits\Makeable;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkflowActionRequest extends FormRequest
{
    use Makeable;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'identifier' => 'in:'.Identifier::implodedValues(),
            'execution_order' => 'gte:0',
            'parameters' => ['array:parameter_key,parameter_value'],
            'parameters.parameter_key' => 'required',
            'parameters.parameter_value' => 'required',
        ];
    }
}
