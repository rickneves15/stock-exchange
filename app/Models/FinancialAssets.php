<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialAssets extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'description',
        'type',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
