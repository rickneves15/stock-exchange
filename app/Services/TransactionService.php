<?php

namespace App\Services;

use App\Models\Transactions;

class TransactionService
{

    public function findOne($id)
    {

        $transaction = Transactions::with(['financialAsset', 'user'])->where('id', $id)->get();

        if (!$transaction) {
            return null;
        }

        return $transaction;
    }

    public function buy($financialAssetId, $userId, $data)
    {
        $buyAsset = new Transactions();

        $buyAsset->financial_asset_id = $financialAssetId;
        $buyAsset->user_id = $userId;
        $buyAsset->type = 'buy';
        $buyAsset->quantity = $data['quantity'];
        $buyAsset->save();

        if (!$buyAsset) {
            return null;
        }

        $transaction = $this->findOne($buyAsset->id);

        return $transaction;
    }

    public function sell($financialAssetId, $userId, $data)
    {
        $sellAsset = new Transactions();

        $sellAsset->financial_asset_id = $financialAssetId;
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
