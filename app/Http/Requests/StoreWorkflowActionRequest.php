<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Requests;

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
            'service_action_id' => 'sometimes|filled',
            'execution_order' => 'required|gte:0',
            'parameters' => 'array',
        ];
    }
}
