<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    use ResponseTrait;

    public function __construct(private ReportService $reportService)
    {
    }

    public function history($type)
    {
        try {
            $report = $this->reportService->history($type);
            return $this->responseSuccess($report, 'Report Financial Assets History List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function portfolioDistribution()
    {
        try {
            $report = $this->reportService->portfolioDistribution();
            return $this->responseSuccess($report, 'Report Financial Assets Portfolio Distribution List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function portfolioAssetDistribution()
    {
        try {
            $report = $this->reportService->portfolioAssetDistribution();
            return $this->responseSuccess($report, 'Report Financial Assets Portfolio Distribution List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
