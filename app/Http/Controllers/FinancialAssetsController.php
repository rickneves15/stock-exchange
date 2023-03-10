<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinancialAssetsRequest;
use App\Http\Requests\UpdateFinancialAssetsRequest;
use App\Models\FinancialAssets;
use App\Services\FinancialAssetsService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FinancialAssetsController extends Controller
{
    use ResponseTrait;

    public function __construct(private FinancialAssetsService $financialAssetsService)
    {
    }

    public function index()
    {
        try {
            $financialAssets = $this->financialAssetsService->fetchAll();
            return $this->responseSuccess($financialAssets, 'Financial Assets List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreFinancialAssetsRequest $request)
    {
        try {
            $validation = $request->validated();
            $financialAsset = $this->financialAssetsService->create($request->all());
            return $this->responseSuccess($financialAsset, 'Financial Assets Created Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $symbol)
    {
        try {
            $financialAsset = $this->financialAssetsService->fetchOne($symbol);
            if (is_null($financialAsset)) {
                return $this->responseError(null, 'Financial Asset Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($financialAsset, 'Financial Asset Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateFinancialAssetsRequest $request, string $symbol)
    {
        try {
            $validation = $request->validated();
            $data = $request->all();

            if (count($data) == 0) {
                return $this->responseError(null, 'Necessary To Inform Data To Be Updated', Response::HTTP_NOT_FOUND);
            }

            $financialAsset = $this->financialAssetsService->update($symbol, $data);
            if (is_null($financialAsset)) {
                return $this->responseError(null, 'Financial Asset Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($financialAsset, 'Financial Asset Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(string $symbol)
    {
        try {
            $financialAsset = $this->financialAssetsService->delete($symbol);
            if (is_null($financialAsset)) {
                return $this->responseError(null, 'Financial Asset Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($financialAsset, 'Financial Asset Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
