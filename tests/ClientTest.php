<?php

namespace Dakalab\YunExpress\Tests;

use Dakalab\YunExpress\ApplicationInfo;
use Dakalab\YunExpress\Client;
use Dakalab\YunExpress\SenderInfo;
use Dakalab\YunExpress\ShippingInfo;
use Dakalab\YunExpress\Waybill;

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
            ['{"ResultCode": "提交失败", "ResultDesc": "订单不存在", "Item": null}'],
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

    /**
     * @expectedException \Exception
     */
    public function testBatchAddWaybill()
    {
        $waybills = [];

        $waybill = new Waybill;

        $waybill->OrderNumber = 'H' . date('YmdHis') . mt_rand(100, 999);
        $waybill->TrackingNumber = 'T' . date('YmdHis') . mt_rand(100, 999);
        $waybill->ShippingMethodCode = 'UPS';
        $waybill->PackageNumber = 1;
        $waybill->Weight = 10;
        $waybill->SourceCode = 'API';
        $waybill->IsReturn = true;
        $waybill->ApplicationType = 1;
        $waybill->InsuranceType = 1;
        $waybill->InsureAmount = 1000;
        $waybill->SensitiveTypeID = 1;

        $r = new ShippingInfo;
        $r->ShippingTaxId = 'cn';
        $r->CountryCode = 'AD';
        $r->ShippingFirstName = 'xing';
        $r->ShippingLastName = 'ming';
        $r->ShippingCompany = 'company';
        $r->ShippingAddress = 'adress';
        $r->ShippingCity = 'city';
        $r->ShippingState = 'guangdong';
        $r->ShippingZip = '123456';
        $r->ShippingPhone = '12345678456';

        $waybill->ShippingInfo = $r;

        $s = new SenderInfo;
        $s->CountryCode = 'CN';
        $s->SenderFirstName = 'xing';
        $s->SenderLastName = 'ming';
        $s->SenderCompany = 'company';
        $s->SenderAddress = 'adress';
        $s->SenderCity = 'city';
        $s->SenderState = 'guangdong';
        $s->SenderZip = '123456';
        $s->SenderPhone = '12345678456';

        // $waybill->SenderInfo = $s;

        $a = new ApplicationInfo;
        $a->ApplicationName = 'Awesome product';
        $a->HSCode = '12585';
        $a->Qty = 1;
        $a->UnitPrice = 10;
        $a->UnitWeight = 1;
        $a->PickingName = 'test';
        $a->Remark = 'test';

        $waybill->ApplicationInfos[] = $a;

        $waybills[] = $waybill;

        $this->client->batchAddWaybill($waybills);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 1001
     * @expectedExceptionMessage 订单不存在
     */
    public function testGetSenderInfo()
    {
        $this->client->getSenderInfo('fake');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 1006
     * @expectedExceptionMessage 未找到数据
     */
    public function testGetAgentNumbers()
    {
        $this->client->getAgentNumbers('fake');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 1006
     * @expectedExceptionMessage 未找到数据
     */
    public function testGetWayBill()
    {
        $this->client->getWayBill('fake');
    }

    public function testUpdateWeight()
    {
        $res = $this->client->updateWeight('fake', '2.88');
        $this->assertEquals('failure', $res['Rueslt']);
    }

    public function testDeleteOrder()
    {
        $res = $this->client->deleteOrder('fake', 2);
        $this->assertEquals('5004', $res['Rueslt']);
    }

    public function testHoldOrder()
    {
        $res = $this->client->holdOrder('fake', '客户要求拦截', 1);
        $this->assertEquals('5004', $res['Rueslt']);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 1006
     * @expectedExceptionMessage 未找到数据
     */
    public function testGetTrackingInfo()
    {
        $res = $this->client->getTrackingInfo('fake');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 1006
     * @expectedExceptionMessage 未找到数据
     */
    public function testGetShippingFeeDetail()
    {
        $res = $this->client->getShippingFeeDetail('fake');
    }
}
