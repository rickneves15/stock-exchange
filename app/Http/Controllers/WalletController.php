<?php

namespace App\Http\Controllers;

use App\Services\WalletService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;

class WalletController extends Controller
{
    use ResponseTrait;

    public function __construct(private WalletService $walletService)
    {
    }

    public function index()
    {
        try {
            $wallet = $this->walletService->fetchAll();
            return $this->responseSuccess($wallet, 'Wallet List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
