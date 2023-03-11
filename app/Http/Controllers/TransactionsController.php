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

            $buyAsset = $this->transactionService->buy($userId, $data);

            if (is_array($buyAsset)) {
                if (key_exists('message', $buyAsset)) {
                    return $this->responseSuccess(null, $buyAsset['message']);
                }
            }

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

            $sellAsset = $this->transactionService->sell($userId, $data);

            if (is_array($sellAsset)) {
                if (key_exists('message', $sellAsset)) {
                    return $this->responseSuccess(null, $sellAsset['message']);
                }
            }

            return $this->responseSuccess($sellAsset, 'Successful Sell Of Financial Assets !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
