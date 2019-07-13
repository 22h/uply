<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * GuzzleClientFactory
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class GuzzleClientFactory
{
    /**
     * @var UserAgentService
     */
    private $userAgentService;

    /**
     * @param UserAgentService $userAgentService
     */
    public function __construct(UserAgentService $userAgentService)
    {
        $this->userAgentService = $userAgentService;
    }

    /**
     * @param array|null $config
     *
     * @return Client
     */
    public function getNewGuzzleClient(?array $config = null)
    {
        if (is_null($config)) {
            $config = $this->getDefaultConfig();
        }

        return $this->createNewInstance($config);
    }

    /**
     * @return array
     */
    private function getDefaultConfig()
    {
        return [
            RequestOptions::HEADERS => [
                'User-Agent' => $this->userAgentService->getUserAgent(),
            ],
        ];
    }

    /**
     * @param array $config
     *
     * @return Client
     */
    private function createNewInstance(array $config): Client
    {
        return new Client($config);
    }
}
