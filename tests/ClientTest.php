<?php

namespace Dakalab\YunExpress\Tests;

use Dakalab\YunExpress\Client;

/**
 * Test class for Dakalab\YunExpress\Client
 *
 * @coversDefaultClass \Dakalab\YunExpress\Client
 */
class ClientTest extends TestCase
{
    /**
     * Account for testing
     *
     * @var string
     */
    protected $account = 'C88888';

    /**
     * Secret for testing account
     *
     * @var string
     */
    protected $secret = 'NMt9f54gz9M=';

    /**
     * Testing host
     *
     * @var string
     */
    protected $host = 'http://120.76.102.19:8034/LMS.API/api/';

    protected $client;

    protected function setUp()
    {
        parent::setUp();

        $this->client = new Client($this->account, $this->secret);
        $this->client->setHost($this->host);
    }

    /**
     * @dataProvider parseResultProvider
     * @expectedException \Exception
     */
    public function testParseResult($result)
    {
        $this->client->parseResult($result);
    }

    public function parseResultProvider()
    {
        return [
            [''],
            ['{"ResultCode": "1006", "ResultDesc": "未找到数据查无数据", "Item": null}'],
        ];
    }

    public function testGetCountry()
    {
        $res = $this->client->setLang('zh-cn')->getCountry();
        $this->assertGreaterThan(0, count($res));
        $this->assertEquals([
            'CountryCode', 'EName', 'CName',
        ], array_keys($res[0]));
    }

    public function testGetTransport()
    {
        $res = $this->client->getTransport('HK');
        $this->assertGreaterThan(0, count($res));
        $this->assertEquals([
            'Code',
            'FullName',
            'EnglishName',
            'HaveTrackingNum',
            'DisplayName',
        ], array_keys($res[0]));
    }

    public function testGetGoodsType()
    {
        $res = $this->client->getGoodsType();
        $this->assertGreaterThan(0, count($res));
        $this->assertEquals([
            'GoodsTypeID',
            'GoodsTypeName',
        ], array_keys($res[0]));
    }

    public function testGetPrice()
    {
        $res = $this->client->getPrice('HK', 2);
        $this->assertGreaterThan(0, count($res));
        $this->assertEquals([
            'Code',
            'ShippingMethodName',
            'ShippingMethodEName',
            'ShippingFee',
            'RegistrationFee',
            'FuelFee',
            'TariffPrepayFee',
            'SundryFee',
            'TotalFee',
            'Remarks',
            'DeliveryTime',
        ], array_keys($res[0]));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 1006
     * @expectedExceptionMessage 未找到跟踪号
     */
    public function testGetTrackingNumberByOrderID()
    {
        $this->client->getTrackingNumberByOrderID('fake');
    }
}
