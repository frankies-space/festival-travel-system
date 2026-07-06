<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $festival = $this->route('festival');

        if ($festival->isFull()) {
            return false;
        }

        if ($festival->hasBookingFrom($this->user())) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [];
    }

    protected function failedAuthorization(): void
    {
        $festival = $this->route('festival');

        if ($festival->hasBookingFrom($this->user())) {
            abort(403, 'Je hebt dit festival al geboekt.');
        }

        if ($festival->isFull()) {
            abort(403, 'Dit festival is vol.');
        }

        parent::failedAuthorization();
    }
}
