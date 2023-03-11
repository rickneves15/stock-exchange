<?php

namespace App\Services;

use App\Models\Transactions;

class TransactionService
{
    public function __construct(private WalletService $walletService, private FinancialAssetsService $financialAssetsService)
    {
    }

    public function findOne($id)
    {

        $transaction = Transactions::with(['financialAsset', 'user'])->where('id', $id)->get();

        if (!$transaction) {
            return null;
        }

        return $transaction;
    }

    public function buy($userId, $data)
    {
        $financialAsset = $this->financialAssetsService->fetchOne($data['symbol']);

        if (is_null($financialAsset)) {
            return [
                "message" => "Financial Asset Not Found"
            ];
        }

        $buyAsset = new Transactions();

        $buyAsset->financial_asset_id = $financialAsset['id'];
        $buyAsset->user_id = $userId;
        $buyAsset->type = 'buy';
        $buyAsset->quantity = $data['quantity'];
        $buyAsset->save();

        if (!$buyAsset) {
            return null;
        }

        $wallet = $this->walletService->buy([
            'symbol' => $financialAsset['symbol'],
            'type' => $financialAsset['type'],
            'total' => round($financialAsset['price'] * $data['quantity']),
        ]);

        if (is_array($wallet)) {
            if (key_exists('message', $wallet)) {
                return [
                    "message" => $wallet['message']
                ];
            }
        }

        $transaction = $this->findOne($buyAsset->id);

        return $transaction;
    }

    public function sell($userId, $data)
    {
        $financialAsset = $this->financialAssetsService->fetchOne($data['symbol']);

        if (is_null($financialAsset)) {
            return [
                "message" => "Financial Asset Not Found"
            ];
        }

        $wallet = $this->walletService->sell([
            'symbol' => $financialAsset['symbol'],
            'type' => $financialAsset['type'],
            'total' => round($financialAsset['price'] * $data['quantity']),
        ]);

        if (is_array($wallet)) {
            if (key_exists('message', $wallet)) {
                return [
                    "message" => $wallet['message']
                ];
            }
        }

        $sellAsset = new Transactions();

        $sellAsset->financial_asset_id = $financialAsset['id'];
        $sellAsset->user_id = $userId;
        $sellAsset->type = 'sell';
        $sellAsset->quantity = $data['quantity'];
        $sellAsset->save();

        if (!$sellAsset) {
            return null;
        }

        $transaction = $this->findOne($sellAsset->id);

        return $transaction;
    }
}
