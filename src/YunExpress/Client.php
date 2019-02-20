<?php

namespace Dakalab\YunExpress;

class Client
{
    /**
     * Guzzle http client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Http headers
     *
     * @var array
     */
    protected $headers;

    /**
     * API user
     *
     * @var string
     */
    protected $account;

    /**
     * API user secret
     *
     * @var string
     */
    protected $secret;

    /**
     * Default API server address
     *
     * @var string
     */
    protected $host = 'http://api.yunexpress.com/LMS.API/api/';

    /**
     * Constructor
     *
     * @param string $account API user
     * @param string $secret  API user secret
     * @param string $lang    en-us / zh-cn
     */
    public function __construct($account, $secret, $lang = 'en-us')
    {
        $this->account = $account;
        $this->secret = $secret;

        $this->headers = [
            'Content-Type'    => 'application/json; charset=utf8',
            'Authorization'   => ' basic ' . $this->buildToken(),
            'Accept-Language' => $lang,
            'Accept'          => 'text/json',
        ];

        $this->initClient();
    }

    /**
     * Initialize client
     *
     * @return Client
     */
    protected function initClient()
    {
        $this->client = new \GuzzleHttp\Client([
            'headers' => $this->headers,
        ]);

        return $this;
    }

    /**
     * Build authorization token
     *
     * @return string
     */
    protected function buildToken()
    {
        return base64_encode($this->account . '&' . $this->secret);
    }

    /**
     * Set host
     *
     * @param  string   $host
     * @return Client
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Set language
     *
     * @param  string   $lang en-us / zh-cn
     * @return Client
     */
    public function setLang($lang)
    {
        $this->headers['Accept-Language'] = $lang;

        return $this->initClient();
    }

    /**
     * Parse result
     *
     * @param  string      $result
     * @throws Exception
     * @return array
     */
    public function parseResult($result)
    {
        $arr = json_decode($result, true);
        if (empty($arr) || !isset($arr['ResultCode'])) {
            throw new \Exception('Invalid response: ' . $result, 400);
        }
        if (!in_array($arr['ResultCode'], ['0000', '5001'])) {
            if (!is_numeric($arr['ResultCode'])) {
                $arr['ResultCode'] = '1001';
            }
            throw new \Exception($arr['ResultDesc'], $arr['ResultCode']);
        }

        return $arr['Item'];
    }

    /**
     * Get countries
     *
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\ClientException
     * @return array
     */
    public function getCountry()
    {
        $api = 'lms/GetCountry';
        $response = $this->client->get($this->host . $api);

        return $this->parseResult($response->getBody());
    }

    /**
     * Get available transports in given country,
     * if country code is not specified, then will list all transports
     *
     * @param  string  $countryCode country code, e.g. CN
     * @return array
     */
    public function getTransport($countryCode = '')
    {
        $api = 'lms/Get';
        $query = [];
        if (!empty($countryCode)) {
            $query = [
                'query' => ['countryCode' => $countryCode],
            ];
        }
        $response = $this->client->get($this->host . $api, $query);

        return $this->parseResult($response->getBody());
    }

    /**
     * Get goods type
     *
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\ClientException
     * @return array
     */
    public function getGoodsType()
    {
        $api = 'lms/GetGoodstype';
        $response = $this->client->get($this->host . $api);

        return $this->parseResult($response->getBody());
    }

    /**
     * Get price
     *
     * @param  string  $countryCode country code, e.g. CN
     * @param  string  $weight      weight of package, unit: kg, 3 decimals
     * @param  int     $length      length of package, unit: cm, default 1
     * @param  int     $width       width of package, unit: cm, default 1
     * @param  int     $height      height of package, unit: cm, default 1
     * @param  int     $type        1: package, 2: document, 3: waterproof bag, default 1
     * @return array
     */
    public function getPrice(
        $countryCode,
        $weight,
        $length = 1,
        $width = 1,
        $height = 1,
        $type = 1
    ) {
        $api = 'lms/GetPrice';
        $query = [
            'query' => [
                'countryCode'    => $countryCode,
                'weight'         => $weight,
                'length'         => $length,
                'width'          => $width,
                'height'         => $height,
                'shippingTypeId' => $type,
            ],
        ];
        $response = $this->client->get($this->host . $api, $query);

        return $this->parseResult($response->getBody());
    }

    /**
     * Get tracking number by order id
     *
     * @param  string  $orderID order id, multiple order ids must be separated by comma
     * @return array
     */
    public function getTrackingNumberByOrderID($orderID)
    {
        $api = 'WayBill/GetTrackNumber';
        $query = [
            'query' => [
                'orderId' => $orderID,
            ],
        ];
        $response = $this->client->get($this->host . $api, $query);

        return $this->parseResult($response->getBody());
    }

    /**
     * Batch add waybills
     *
     * @param  array   $waybills array of Waybill
     * @return array
     */
    public function batchAddWaybill(array $waybills)
    {
        $api = 'WayBill/BatchAdd';
        $data = [];
        foreach ($waybills as $waybill) {
            $data[] = $waybill->toArray();
        }
        $body = ['body' => json_encode($data)];

        $response = $this->client->post($this->host . $api, $body);

        return $this->parseResult($response->getBody());
    }

    /**
     * Get sender info
     *
     * @param  string  $number can be waybill number, order number or tracking number
     * @return array
     */
    public function getSenderInfo($number)
    {
        $api = 'WayBill/GetSendMessage';
        $query = [
            'query' => [
                'number' => $number,
            ],
        ];
        $response = $this->client->get($this->host . $api, $query);

        return $this->parseResult($response->getBody());
    }

    /**
     * Get agent numbers by order id
     *
     * @param  string  $orderIDs order id, multiple order ids must be separated by comma
     * @return array
     */
    public function getAgentNumbers($orderIDs)
    {
        $api = 'WayBill/GetAgentNumbers';
        $query = [
            'query' => [
                'orderIds' => $orderIDs,
            ],
        ];
        $response = $this->client->get($this->host . $api, $query);

        return $this->parseResult($response->getBody());
    }

    /**
     * Get waybill info by waybill number, order number or tracking number
     *
     * @param  string  $number waybill number, order number or tracking number
     * @return array
     */
    public function getWayBill($number)
    {
        $api = 'WayBill/GetWayBill';
        $query = [
            'query' => [
                'wayBillNumber' => $number,
            ],
        ];
        $response = $this->client->get($this->host . $api, $query);

        return $this->parseResult($response->getBody());
    }

    /**
     * Update weight of order
     *
     * @param  string $orderNumber order number
     * @param  float  $weight      weight, decimal(18,3)
     * @return void
     */
    public function updateWeight($orderNumber, $weight)
    {
        $api = 'WayBill/UpdateWeight';
        $data = [
            'OrderNumber' => $orderNumber,
            'Weight'      => $weight,
        ];
        $body = ['body' => json_encode($data)];

        $response = $this->client->post($this->host . $api, $body);

        return $this->parseResult($response->getBody());
    }

    /**
     * Delete order
     *
     * @param  string  $number waybill number, order number or tracking number
     * @param  int     $type   1: waybill number, 2: order number, 3: tracking number
     * @return array
     */
    public function deleteOrder($number, $type = 2)
    {
        $api = 'WayBill/DeleteCoustomerOrderInfo';
        $data = [
            'OrderNumber' => $number,
            'Type'        => $type,
        ];
        $body = ['body' => json_encode($data)];

        $response = $this->client->post($this->host . $api, $body);

        return $this->parseResult($response->getBody());
    }

    /**
     * Hold order
     *
     * @param  string  $number waybill number, order number or tracking number
     * @param  string  remark  can not be empty
     * @param  int     $type   1: waybill number, 2: order number, 3: tracking number
     * @return array
     */
    public function holdOrder($number, $remark = '', $type = 2)
    {
        $api = 'WayBill/HoldOnCoustomerOrderInfo';
        $data = [
            'OrderNumber'  => $number,
            'Type'         => $type,
            'HoldOnRemark' => $remark,
        ];
        $body = ['body' => json_encode($data)];

        $response = $this->client->post($this->host . $api, $body);

        return $this->parseResult($response->getBody());
    }

    /**
     * Get tracking info by waybill number, order number or tracking number
     *
     * @param  string  $number waybill number, order number or tracking number
     * @return array
     */
    public function getTrackingInfo($number)
    {
        $api = 'WayBill/GetTrackingNumber';
        $query = [
            'query' => [
                'trackingNumber' => $number,
            ],
        ];
        $response = $this->client->get($this->host . $api, $query);

        return $this->parseResult($response->getBody());
    }

    /**
     * Get shipping fee detail by waybill number
     *
     * @param  string  $number waybill number
     * @return array
     */
    public function getShippingFeeDetail($number)
    {
        $api = 'WayBill/GetShippingFeeDetail';
        $query = [
            'query' => [
                'wayBillNumber' => $number,
            ],
        ];
        $response = $this->client->get($this->host . $api, $query);

        return $this->parseResult($response->getBody());
    }
}
