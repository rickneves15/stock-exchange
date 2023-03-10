<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class BuyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'symbol' => 'required|string',
            'quantity' => 'required|numeric|min:1',
        ];
    }
}
