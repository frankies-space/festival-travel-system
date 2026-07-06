<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RedeemDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'points' => [
                'required',
                'integer',
                'min:'.config('fts.min_redeem_discount_points'),
                'max:'.$this->user()->points_balance,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'points.min' => 'Je moet minimaal '.config('fts.min_redeem_discount_points').' punten inwisselen.',
            'points.max' => 'Je hebt niet genoeg punten.',
        ];
    }
}
