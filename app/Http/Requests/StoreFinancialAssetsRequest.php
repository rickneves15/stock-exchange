<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinancialAssetsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'symbol' => 'required|string|unique:financial_assets',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:stock,fii,firf',
            'price' => 'required|numeric|min:0.01',
        ];
    }

    public function messages()
    {
        return [
            'type.in' => "The :attribute accepts only 'stock', 'fii' or 'firf'"
        ];
    }
}
