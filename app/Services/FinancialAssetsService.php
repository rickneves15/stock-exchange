<?php

namespace App\Services;

use App\Models\FinancialAssets;

class FinancialAssetsService
{
    public function fetchAll()
    {
        return FinancialAssets::all();
    }

    public function fetchOne($symbol)
    {
        $financialAsset = FinancialAssets::where('symbol', $symbol)->first();

        if (!$financialAsset) {
            return null;
        }

        return $financialAsset;
    }

    public function create($data)
    {
        $financialAsset = FinancialAssets::create($data);

        if (!$financialAsset) {
            return null;
        }

        return $financialAsset;
    }

    public function update($symbol, $data)
    {
        $financialAsset = $this->fetchOne($symbol);

        $data = $this->serializeFinancialAssetToUpdate($data);
        if ($financialAsset) {
            $financialAsset->update($data);
        }

        return $financialAsset;
    }

    public function delete($symbol)
    {
        $financialAsset = FinancialAssets::where('symbol', $symbol)->first();

        if (!$financialAsset) {
            return null;
        }

        FinancialAssets::where('symbol', $symbol)->delete();
        return true;
    }

    public function serializeFinancialAssetToUpdate($data)
    {
        $dataSerialized = [];

        if (array_key_exists('name', $data)) {
            $dataSerialized['name'] = $data['name'];
        }

        if (array_key_exists('description', $data)) {
            $dataSerialized['description'] = $data['description'];
        }

        if (array_key_exists('type', $data)) {
            $dataSerialized['type'] = strtolower($data['type']);
        }

        if (array_key_exists('price', $data)) {
            $dataSerialized['price'] = floatval($data['price']);
        }

        return $dataSerialized;
    }
}
