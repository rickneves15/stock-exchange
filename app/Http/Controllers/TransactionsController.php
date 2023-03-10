<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transactions\BuyRequest;
use App\Http\Requests\Transactions\SellRequest;
use App\Services\FinancialAssetsService;
use App\Services\TransactionService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;

class TransactionsController extends Controller
{

    use ResponseTrait;

    public function __construct(private TransactionService $transactionService, private FinancialAssetsService $financialAssetsService)
    {
    }

    public function buy(BuyRequest $request)
    {
        try {
            $validation = $request->validated();
            $data = $request->all();
            $userId = auth()->user()->id;

            $financialAsset = $this->financialAssetsService->fetchOne($data['symbol']);
            $financialAssetId = $financialAsset['id'];

            if (is_null($financialAsset)) {
                return $this->responseError(null, 'Financial Asset Not Found', Response::HTTP_NOT_FOUND);
            }

            $buyAsset = $this->transactionService->buy($financialAssetId, $userId, $data);

            return $this->responseSuccess($buyAsset, 'Successful Buy Of Financial Assets !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sell(SellRequest $request)
    {
        try {
            $validation = $request->validated();
            $data = $request->all();
            $userId = auth()->user()->id;

            $financialAsset = $this->financialAssetsService->fetchOne($data['symbol']);
            $financialAssetId = $financialAsset['id'];

            if (is_null($financialAsset)) {
                return $this->responseError(null, 'Financial Asset Not Found', Response::HTTP_NOT_FOUND);
            }

            $sellAsset = $this->transactionService->buy($financialAssetId, $userId, $data);

            return $this->responseSuccess($sellAsset, 'Successful Sell Of Financial Assets !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
