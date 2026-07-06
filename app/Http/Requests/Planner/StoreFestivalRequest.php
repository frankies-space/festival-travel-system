<?php

namespace App\Http\Requests\Planner;

use Illuminate\Foundation\Http\FormRequest;

class StoreFestivalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPlanner() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'max_capacity' => ['required', 'integer', 'min:1'],
            'ticket_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
