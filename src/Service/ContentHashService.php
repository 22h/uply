<?php

namespace App\Service;

use Exception;
use GuzzleHttp\RequestOptions;

/**
 * ContentHashService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class ContentHashService
{
    /**
     * @var GuzzleClientFactory
     */
    private $guzzleClientFactory;

    /**
     * ContentHashService constructor.
     *
     * @param GuzzleClientFactory $guzzleClientFactory
     */
    public function __construct(GuzzleClientFactory $guzzleClientFactory)
    {
        $this->guzzleClientFactory = $guzzleClientFactory;
    }

    /**
     * @param string $url
     *
     * @return string
     * @throws \Exception
     */
    public function getContentHashFromUrl(string $url): string
    {
        $client = $this->guzzleClientFactory->getNewGuzzleClient();

        $content = null;
        try {
            $response = $client->get($url, [RequestOptions::CONNECT_TIMEOUT => 10]);
            $content = $response->getBody()->getContents();
        } catch (\Exception $exception) {
            // do nothing
        }
        if (is_null($content)) {
            throw new Exception(sprintf('can not connect to %s', $url));
        }

        return md5($content);
    }
}
