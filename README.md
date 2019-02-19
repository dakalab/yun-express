# YunTu Express SDK

Unofficial PHP SDK for YunTu express (https://www.yunexpress.com/)


[![Build Status](https://travis-ci.com/dakalab/yun-express.svg?branch=master)](https://travis-ci.com/dakalab/yun-express)
[![codecov](https://codecov.io/gh/dakalab/yun-express/branch/master/graph/badge.svg)](https://codecov.io/gh/dakalab/yun-express)
[![Latest Stable Version](https://poser.pugx.org/dakalab/yun-express/v/stable)](https://packagist.org/packages/dakalab/yun-express)
[![Total Downloads](https://poser.pugx.org/dakalab/yun-express/downloads)](https://packagist.org/packages/dakalab/yun-express)
[![PHP Version](https://img.shields.io/php-eye/dakalab/yun-express.svg)](https://packagist.org/packages/dakalab/yun-express)
[![License](https://poser.pugx.org/dakalab/yun-express/license.svg)](https://packagist.org/packages/dakalab/yun-express)

## Install

```
composer require dakalab/yun-express
```

## Usage

```
$client = new Dakalab\YunExpress\Client($account, $secret);

try {
    $tracking = $client->getTrackingInfo($trackingNumber);
    print_r($tracking);
} catch (\Exception $e) {
    echo $e->getMessage();
}
```
