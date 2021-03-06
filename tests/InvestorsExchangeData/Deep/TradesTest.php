<?php

namespace Digitonic\IexCloudSdk\Tests\InvestorsExchangeData\Deep;

use Digitonic\IexCloudSdk\Exceptions\WrongData;
use Digitonic\IexCloudSdk\Facades\InvestorsExchangeData\Deep\Trades;
use Digitonic\IexCloudSdk\Tests\BaseTestCase;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;

class TradesTest extends BaseTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new Response(200, [], '{"AAPL": [{"price": 227.3,"size": 72,"tradeId": 869507736,"isISO": false,"isOddLot": true,"isOutsideRegularHours": false,"isSinglePriceCross": false,"isTradeThroughExempt": false,"timestamp": 1591657188819},{"price": 229.23,"size": 30,"tradeId": 844915880,"isISO": false,"isOddLot": true,"isOutsideRegularHours": false,"isSinglePriceCross": false,"isTradeThroughExempt": false,"timestamp": 1619690090714},{"price": 229.478,"size": 52,"tradeId": 850469976,"isISO": false,"isOddLot": true,"isOutsideRegularHours": false,"isSinglePriceCross": false,"isTradeThroughExempt": false,"timestamp": 1628633187464},{"price": 224.328,"size": 21,"tradeId": 859904609,"isISO": false,"isOddLot": true,"isOutsideRegularHours": false,"isSinglePriceCross": false,"isTradeThroughExempt": false,"timestamp": 1619035871226},{"price": 230.48,"size": 117,"tradeId": 827610906, "isISO": false,"isOddLot": false,"isOutsideRegularHours": false,"isSinglePriceCross": false,"isTradeThroughExempt": false,"timestamp": 1579640030232}]}');

        $this->client = $this->setupMockedClient($this->response);
    }

    /** @test */
    public function it_should_fail_without_a_symbol()
    {
        $trades = new \Digitonic\IexCloudSdk\InvestorsExchangeData\Deep\Trades($this->client);

        $this->expectException(WrongData::class);

        $trades->send();
    }

    /** @test */
    public function it_can_query_the_deep_trades_endpoint()
    {
        $trades = new \Digitonic\IexCloudSdk\InvestorsExchangeData\Deep\Trades($this->client);

        $response = $trades->setSymbols('aapl')->get();

        $this->assertInstanceOf(Collection::class, $response);

        $response = $response->toArray();
        $this->assertCount(1, $response);
        $this->assertCount(5, (array) $response['AAPL']);
    }

    /** @test */
    public function it_can_call_the_facade()
    {
        $this->setConfig();

        Trades::shouldReceive('setSymbol')
            ->once()
            ->andReturnSelf();

        Trades::setSymbol('aapl');
    }
}
