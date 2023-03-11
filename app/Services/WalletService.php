<?php

namespace App\Services;

use App\Models\Wallet;

class WalletService
{
    private $typeFinancialAssets = [
        'stock' => "Stock",
        'fii' => "Real Estate Funds",
        'firf' => "Fixed Income",
    ];

    public function fetchAll()
    {

        $wallet = Wallet::all();

        if (!$wallet) {
            return null;
        }

        return $wallet;
    }

    public function buy($data)
    {
        $symbol = $data['symbol'];
        unset($data['symbol']);

        $wallet = Wallet::where('symbol', $symbol)->first();
        if ($wallet) {
            $wallet->update([
                'total' => round($data['total'] + $wallet['total']),
            ]);
        } else {
            $wallet = Wallet::create([
                'symbol' => $symbol,
                'type' => $data['type'],
                'total' => $data['total']
            ]);
        }

        if (!$wallet) {
            return null;
        }

        return $wallet;
    }

    public function sell($data)
    {
        if (!$this->hasValueIsAvailable($data['symbol'], $data['total'])) {
            return [
                "message" => "You Don't Have Enough Value For {$this->typeFinancialAssets[$data['type']]} {$data['symbol']}"
            ];
        }

        $symbol = $data['symbol'];
        unset($data['symbol']);

        $wallet = Wallet::where('symbol', $symbol)->first();
        $wallet->update([
            'total' => round($wallet['total'] - $data['total']),
        ]);

        if (!$wallet) {
            return null;
        }

        return $wallet;
    }

    public function hasValueIsAvailable($symbol, $value)
    {
        $wallet = Wallet::where('symbol', $symbol)->first();

        if ($wallet) {
            if (round($value) <= round($wallet['total'])) {
                return true;
            }
        }

        return false;
    }
}
