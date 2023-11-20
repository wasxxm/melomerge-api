<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateJamSessionRequest extends FormRequest
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
        // cast is_private to boolean
        $this->merge([
            'is_private' => $this->is_private === 'true',
        ]);
        return [
            'name' => 'required|string',
            'start_date' => 'required|date',
//            'end_date' => 'required|date',
            'genre_id' => 'required|exists:genres,id',
            'jam_type_id' => 'required|exists:jam_types,id',
            'is_private' => 'required|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'location' => 'required|string',
        ];
    }
}
