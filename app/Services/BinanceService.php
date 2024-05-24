<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BinanceService
{
    protected $baseUrl = 'https://testnet.binance.vision/api/v3/';
    protected $apiKey;
    protected $secretKey;
    protected $httpClient;

    public function __construct()
    {
        $this->apiKey = 'MvGrJzFDSYLnV21qtorU1TP7NgknzIw6eC1tfFJ0uQSN7z2iqRXUElE9e7WPYq2S';
        $this->secretKey = 'nB4zCcUe7OzFSPdb2Nq5z636RbHqaOgbftaQdLnxyxsLda9lAmrmbFdo8MJ7h3XT';
        $this->httpClient = new Client(['base_uri' => $this->baseUrl]);
    }

    public function getExchangeInfo($symbol)
    {
        try {
            $response = $this->httpClient->get('exchangeInfo', [
                'headers' => [
                    'X-MBX-APIKEY' => $this->apiKey,
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            foreach ($responseData['symbols'] as $s) {
                if ($s['symbol'] === $symbol) {
                    return $s;
                }
            }

            return ['error' => 'Symbol not found in exchange info.'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getCurrentPrice($symbol)
    {
        try {
            $response = $this->httpClient->get('ticker/price', [
                'query' => [
                    'symbol' => $symbol,
                ],
                'headers' => [
                    'X-MBX-APIKEY' => $this->apiKey,
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData['price'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    public function createOrder($symbol, $side, $type, $quantity, $price = null)
    {
        $timestamp = round(microtime(true) * 1000);
        $recvWindow = 5000;

        $currentPrice = $this->getCurrentPrice($symbol);
        if (isset($currentPrice['error'])) {
            return $currentPrice;
        }

        $symbolInfo = $this->getExchangeInfo($symbol);
        if (isset($symbolInfo['error'])) {
            return $symbolInfo;
        }

        $percentPriceBySideFilter = array_filter($symbolInfo['filters'], function($filter) {
            return $filter['filterType'] === 'PERCENT_PRICE_BY_SIDE';
        });
        $percentPriceBySideFilter = array_shift($percentPriceBySideFilter);

        $multiplierUp = $percentPriceBySideFilter['askMultiplierUp'] ?? $percentPriceBySideFilter['bidMultiplierUp'];
        $multiplierDown = $percentPriceBySideFilter['askMultiplierDown'] ?? $percentPriceBySideFilter['bidMultiplierDown'];

        $minValidPrice = bcmul($currentPrice, $multiplierDown, 8);
        $maxValidPrice = bcmul($currentPrice, $multiplierUp, 8);

        if ($price !== null && ($price < $minValidPrice || $price > $maxValidPrice)) {
            $price = $currentPrice;
        }


        $recvWindow = 5000;

    $serverTime = $this->getServerTime();
    if (isset($serverTime['error'])) {
        return $serverTime;
    }
    $timestamp = $serverTime['serverTime'] + 1000;

    $params = [
        'symbol' => $symbol,
        'side' => strtoupper($side),
        'type' => strtoupper($type),
        'quantity' => $quantity,
        'timestamp' => $timestamp,
        'recvWindow' => $recvWindow,
    ];

    if ($type === 'LIMIT') {
        $params['timeInForce'] = 'GTC';
        $params['price'] = $price;
    }

    $queryString = http_build_query($params);
    $signature = hash_hmac('sha256', $queryString, $this->secretKey);

    $params['signature'] = $signature;

    try {
        $response = $this->httpClient->post('order', [
            'form_params' => $params,
            'headers' => [
                'X-MBX-APIKEY' => $this->apiKey,
            ],
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);

        $orderId = $responseData['orderId'] ?? null;

        return ['order_id' => $orderId];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
}
    private function getServerTime()
{
    try {
        $response = $this->httpClient->get('time');
        $responseData = json_decode($response->getBody()->getContents(), true);
        return $responseData;
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
}








}
