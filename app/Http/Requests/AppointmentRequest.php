<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'      => ['string', 'max:50'],
            'last_name'       => ['string', 'max:50'],
            'email'           => ['email', 'max:100'],
            'contact_number'  => ['nullable', 'string', 'min:9', 'max:30'],
            'product_id'      => ['integer', 'exists:products,id'],
            'date'            => ['required', 'date'],
            'start_time'      => ['required', 'date_format:H:i'],
            'end_time'        => ['required', 'date_format:H:i', 'after:start_time'],
            'status'          => ['required', 'in:pending,confirmed,cancelled'],
            'notes'           => ['nullable', 'string', 'max:500'],
            'cancel_token'    => ['nullable'],
            'created_by'      => ['nullable', 'integer', 'exists:users,id'],
            'updated_by'      => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
