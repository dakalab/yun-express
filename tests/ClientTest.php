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
     */
    public function testParseResult($result)
    {
        $this->expectException(\Exception::class);
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
        $this->assertContains([
            'CountryCode' => 'CN', 'EName' => 'CHINA', 'CName' => '中国',
        ], $res);
    }

    public function testGetTransport()
    {
        $res = $this->client->getTransport('HK');
        $this->assertGreaterThan(0, count($res));
        $this->assertContains([
            'Code'            => 'ZGZX',
            'FullName'        => '中港专线',
            'EnglishName'     => 'ZGZX',
            'HaveTrackingNum' => true,
            'DisplayName'     => '中港专线',
        ], $res);
    }
}
