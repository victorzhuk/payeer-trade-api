# payeer-trade-api

## Installation

```bash
$ composer require zhuk/payeer-trade-api
```

## Usage

Get all orders using http client using [guzzle](https://docs.guzzlephp.org) PSR implementation:

```php
<?php

require_once 'vendor/autoload.php';

$appId = getenv('API_ID') ?: 'test';
$apiSecret = getenv('API_SECRET') ?: 'test';

$httpClient = new GuzzleHttp\Client();
$httpFactory = new GuzzleHttp\Psr7\HttpFactory();
$signer = new Zhuk\Payeer\TradeApi\HmacHeaderSigner((string) $apiSecret);

$api = new Zhuk\Payeer\TradeApi\HttpTradeClient(
    $httpClient,
    $httpFactory,
    $httpFactory,
    $httpFactory,
    $signer,
    (string) $appId
);

$response = $api->getAllOrders();
var_dump($response);
```

Run tests:

```sh
$ composer test
```

Run linter:

```sh
$ composer lint
```
