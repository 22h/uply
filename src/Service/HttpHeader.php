<?php

namespace App\Service;


use GuzzleHttp\RequestOptions;

/**
 * StatusCode
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class HttpHeader
{
    /**
     * @var GuzzleClientFactory
     */
    private $guzzleClientFactory;

    /**
     * @param GuzzleClientFactory $guzzleClientFactory
     */
    public function __construct(GuzzleClientFactory $guzzleClientFactory)
    {
        $this->guzzleClientFactory = $guzzleClientFactory;
    }

    /**
     * @param string $url
     *
     * @return int
     * @throws \Exception
     */
    public function requestStatusCode(string $url): int
    {
        $client = $this->guzzleClientFactory->getNewGuzzleClient();
        $statusCode = null;
        try {
            $response = $client->get($url, [RequestOptions::CONNECT_TIMEOUT => 10]);
            $statusCode = $response->getStatusCode();
        } catch (\Exception $exception) {
            // do nothing
        }

        if (is_null($statusCode)) {
            throw new \Exception('can not read http headers from '.$url);
        }

        return $statusCode;
    }
}
