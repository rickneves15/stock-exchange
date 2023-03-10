<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinancialAssetsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'type' => 'nullable|in:stock,fii,firf',
            'price' => 'nullable|numeric|min:0.01',
        ];
    }

    public function messages()
    {
        return [
            'type.in' => "The :attribute accepts only 'stock', 'fii' or 'firf'"
        ];
    }
}
