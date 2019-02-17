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
            throw new \Exception('Invalid response: ' . $result);
        }
        if ($arr['ResultCode'] != '0000') {
            throw new \Exception(
                sprintf('code: %s, desc: %s', $arr['ResultCode'], $arr['ResultDesc'])
            );
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
        $response = $this->client->request('GET', $this->host . $api);

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
        $response = $this->client->request('GET', $this->host . $api, $query);

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
        $response = $this->client->request('GET', $this->host . $api);

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
        $query = [];
        if (!empty($countryCode)) {
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
        }
        $response = $this->client->request('GET', $this->host . $api, $query);

        return $this->parseResult($response->getBody());
    }
}
