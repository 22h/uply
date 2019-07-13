<?php

namespace App\Service;

/**
 * UserAgentService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class UserAgentService
{

    private const PLACEHOLDER_VERSION = '%version%';
    private const PLACEHOLDER_URL     = '%url%';
    private const REPOSITORY_URL      = 'https://github.com/22h/uply';

    /**
     * @var string
     */
    private $version;

    /**
     * @var string|null
     */
    private $url;

    /**
     * @param string      $uplyVersion
     * @param string|null $uplyUseragentUrl
     */
    public function __construct(string $uplyVersion, ?string $uplyUseragentUrl = null)
    {
        $this->url = $uplyUseragentUrl;
        $this->version = $uplyVersion;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->replaceVersion($this->replaceUrl($this->getPattern()));
    }

    /**
     * @param string $useragent
     *
     * @return string
     */
    private function replaceVersion(string $useragent): string
    {
        return str_replace(self::PLACEHOLDER_VERSION, $this->version, $useragent);
    }

    /**
     * @param string $useragent
     *
     * @return string
     */
    private function replaceUrl(string $useragent): string
    {
        return str_replace(self::PLACEHOLDER_URL, $this->url, $useragent);
    }

    /**
     * @return string
     */
    private function getPattern(): string
    {
        $pattern = 'Uplybot/'.self::PLACEHOLDER_VERSION;
        if (!empty($this->url)) {
            $pattern .= ' (+'.self::PLACEHOLDER_URL.')';
        }

        $pattern .= ' (+'.self::REPOSITORY_URL.')';

        return $pattern;
    }
}
