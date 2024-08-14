<?php

namespace App\Http\Requests\Ticket;

use App\Http\Requests\ApiBaseRequest;
use Illuminate\Validation\Rule;

class TicketRequest extends ApiBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255|unique:tickets',
            'description' => 'required|string',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['title'] = [
                'required',
                'string',
                'max:255',
                // Ensure the title is unique except for the current ticket being updated
                Rule::unique('tickets')->ignore($this->route('ticket'))
            ];
            $rules['description'] = 'required|string';
        }

        return $rules;
    }
}
