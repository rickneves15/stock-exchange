<?php

namespace App\Console\Commands;

use App\Models\FinancialAssets;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class SynchronizeFinancialAssets extends Command
{
    protected $signature = 'financial-assets:synchronize';
    protected $description = 'Synchronize financial quotes from external API';

    private $alphaVantageAppKey;

    public function handle()
    {
        $this->alphaVantageAppKey = env('ALPHA_VANTAGE_APP_KEY');

        if ($this->getFinancialAssets()) {
            $this->info('Assets added successfully.');
        } else {
            $this->error('Failed to get asset list.');
        }
    }

    public function getFinancialAssets()
    {
        $client = new Client();
        $response = $client->get("https://www.alphavantage.co/query?function=LISTING_STATUS&market=FII&apikey={$this->alphaVantageAppKey}", [
            'headers' => [
                'Content-Type' => 'text/csv'
            ]
        ]);

        $csv = $response->getBody();
        $assets = array_slice(explode(PHP_EOL, $csv), 0, 100);

        if (count($assets) > 0) {
            foreach ($assets as $key => $item) {
                if ($key != 0) {
                    $data = str_getcsv($item);
                    $symbol = $data[0];
                    $name = $data[1];
                    $type = strtolower($data[3] == "Stock" ? $data[3] : "fii");
                    $price = $this->getPriceFinancialAssetsBySymbol($data[0]);

                    $financialAsset = FinancialAssets::where('symbol', $symbol)->first();

                    if (!$financialAsset) {
                        FinancialAssets::create([
                            'symbol' => $symbol,
                            "name" => $name,
                            'type' => $type,
                            "price" => $price
                        ]);
                    } else {
                        $financialAsset->update([
                            "name" => $name,
                            'type' => $type,
                            "price" => $price
                        ]);
                    }

                    $this->info("Added asset {$data[0]}");
                }
            }

            return true;
        }

        return false;
    }

    public function getPriceFinancialAssetsBySymbol($symbol)
    {
        $client = new Client();
        $response = $client->get("https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol={$symbol}&apikey={$this->alphaVantageAppKey}", [
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $asset = json_decode($response->getBody(), true);

        if (count($asset) > 0) {
            if (array_key_exists('Global Quote', $asset)) {
                if (array_key_exists('08. previous close', $asset["Global Quote"])) {
                    return floatval($asset["Global Quote"]["08. previous close"]);
                }
            }
        }

        return 0;
    }
}
