<?php

namespace App\Services;

use App\Models\Transactions;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function __construct(private WalletService $walletService, private FinancialAssetsService $financialAssetsService)
    {
    }

    public function history($type)
    {
        $report = [];
        if ($type == "category") {
            $report = Transactions::select('financial_assets.type', DB::raw('SUM(transactions.quantity) as total_quantity'), DB::raw('SUM(financial_assets.price) as total'))
                ->groupBy('financial_assets.type')
                ->join('financial_assets', 'transactions.financial_asset_id', '=', 'financial_assets.id')
                ->orderBy('financial_assets.type')
                ->get();
        } else if ($type == "ticker") {
            $report = Transactions::select('financial_assets.symbol', 'financial_assets.name', DB::raw('SUM(transactions.quantity) as total_quantity'), DB::raw('SUM(financial_assets.price) as total'))
                ->groupBy(['financial_assets.symbol', 'financial_assets.name'])
                ->join('financial_assets', 'transactions.financial_asset_id', '=', 'financial_assets.id')
                ->orderBy('financial_assets.symbol')
                ->get();
        } else if ($type == "general") {
            $report = Transactions::select('financial_assets.symbol', 'financial_assets.name', 'financial_assets.type', 'transactions.quantity', 'financial_assets.price', 'transactions.date')
                ->join('financial_assets', 'transactions.financial_asset_id', '=', 'financial_assets.id')
                ->orderBy('transactions.date', 'desc')
                ->get();
        }

        if (!$report) {
            return null;
        }

        return $report;
    }

    public function portfolioDistribution()
    {
        $distribution = DB::table('transactions')
            ->select('financial_assets.type', DB::raw('SUM(transactions.quantity * financial_assets.price) as total_value'))
            ->leftJoin('financial_assets', 'transactions.financial_asset_id', '=', 'financial_assets.id')
            ->groupBy('financial_assets.type')
            ->get();

        $total_value = $distribution->sum('total_value');
        foreach ($distribution as $asset) {
            $asset->total_value = floatval($asset->total_value);
            $asset->percentage = round(($asset->total_value / $total_value) * 100);
        }

        if (!$distribution) {
            return null;
        }

        return $distribution;
    }

    public function portfolioAssetDistribution()
    {

        $distribution = DB::table('transactions')
            ->select('financial_assets.symbol', 'financial_assets.name', DB::raw('SUM(transactions.quantity * financial_assets.price) as total_value'))
            ->leftJoin('financial_assets', 'transactions.financial_asset_id', '=', 'financial_assets.id')
            ->groupBy('financial_assets.symbol', 'financial_assets.name')
            ->get();

        $total_value = $distribution->sum('total_value');
        foreach ($distribution as $asset) {
            $asset->total_value = floatval($asset->total_value);
            $asset->percentage = round(($asset->total_value / $total_value) * 100);
        }

        if (!$distribution) {
            return null;
        }

        return $distribution;
    }
}
