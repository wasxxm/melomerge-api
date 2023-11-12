<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JoinJamSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'role_id' => 'required|exists:roles,id',
            'instrument_id' => [
                'required',
                // Apply the exists rule conditionally
                Rule::when(
                    $this->instrument_id > 0,
                    Rule::exists('instruments', 'id')
                ),
            ],
            'message' => 'nullable|string|max:500',
        ];
    }
}
