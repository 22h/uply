<?php

namespace App\Service;

/**
 * StatusCode
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class HttpHeader
{
    /**
     * @var int
     */
    protected $timeout = 10;

    /**
     * @var string|null
     */
    protected $userAgent = 'Uplybot/0.2 (+http://github.com)';

    /**
     * @var bool
     */
    protected $disableSSLCheck = true;

    /**
     * @param string $url
     *
     * @return int
     * @throws \Exception
     */
    public function requestStatusCode(string $url): int
    {
        $options = [];
        if ($this->disableSSLCheck) {
            $options['ssl'] = [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ];
        }

        $statusCode = null;

        try {
            $curl = curl_init();
            curl_setopt_array(
                $curl,
                [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_URL            => $url,
                    CURLOPT_TIMEOUT        => $this->timeout,
                    CURLOPT_USERAGENT      => $this->userAgent,
                ]
            );
            curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
        } catch (\Exception $exception) {
            // do nothing
        }

        if (is_null($statusCode)) {
            throw new \Exception('can not read http headers from '.$url);
        }

        return $statusCode;
    }

    /**
     * @param int $timeout
     *
     * @return HttpHeader
     */
    public function setTimeout(int $timeout): HttpHeader
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @param string $userAgent
     *
     * @return HttpHeader
     */
    public function setUserAgent(string $userAgent): HttpHeader
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @param bool $disableSSLCheck
     *
     * @return HttpHeader
     */
    public function setDisableSSLCheck(bool $disableSSLCheck): HttpHeader
    {
        $this->disableSSLCheck = $disableSSLCheck;

        return $this;
    }
}
