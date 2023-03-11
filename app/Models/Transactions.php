<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_asset_id',
        'user_id',
        'type',
        'quantity',
        'price',
        'date'
    ];

    protected $casts = [
        'date' => 'date:d/m/Y H:i:s',
        'price' => 'float',
    ];

    public function financialAsset()
    {
        return $this->belongsTo(FinancialAssets::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
