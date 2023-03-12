<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\FinancialAssets;
use App\Services\FinancialAssetsService;

class FinancialAssetServiceTest extends TestCase
{
    use RefreshDatabase;

    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FinancialAssetsService::class);
        $this->createTestFinancialAssets();
    }

    protected function createTestFinancialAssets()
    {
        // Utilizei o factory para criar um objeto de FinancialAssets para testes
        FinancialAssets::factory()->create([
            'symbol' => 'AAPL',
            'name' => 'Apple Inc.',
            'description' => 'A multinational technology company.',
            'type' => 'stock',
            'price' => 100.50,
        ]);
    }

    /** @test */
    public function test_fetch_all_financial_assets()
    {
        $financialAssets = $this->service->fetchAll();
        $this->assertCount(1, $financialAssets);
    }

    /** @test */
    public function test_fetch_one_financial_asset()
    {
        $financialAsset = $this->service->fetchOne('AAPL');
        $this->assertEquals('Apple Inc.', $financialAsset->name);
    }

    /** @test */
    public function test_create_financial_asset()
    {
        $data = [
            'symbol' => 'AMZN',
            'name' => 'Amazon.com, Inc.',
            'description' => 'An American multinational technology company.',
            'type' => 'stock',
            'price' => 200.50,
        ];
        $createdFinancialAsset = $this->service->create($data);
        $this->assertInstanceOf(FinancialAssets::class, $createdFinancialAsset);
        $this->assertEquals($data['symbol'], $createdFinancialAsset->symbol);
    }

    /** @test */
    public function test_update_financial_asset()
    {
        $data = [
            'name' => 'New Apple Inc.',
            'price' => 150.00,
        ];
        $updatedFinancialAsset = $this->service->update('AAPL', $data);
        $this->assertInstanceOf(FinancialAssets::class, $updatedFinancialAsset);
        $this->assertEquals($data['name'], $updatedFinancialAsset->name);
        $this->assertEquals($data['price'], $updatedFinancialAsset->price);
    }

    /** @test */
    public function test_delete_financial_asset()
    {
        $result = $this->service->delete('AAPL');
        $this->assertTrue($result);
        $this->assertNull($this->service->fetchOne('AAPL'));
    }
}
