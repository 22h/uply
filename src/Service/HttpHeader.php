<?php

namespace App\Service;

/**
 * HttpHeader
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
    protected $userAgent = 'Uplybot/0.1 (+http://github.com)';

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var bool
     */
    protected $disableSSLCheck = true;

    /**
     * @param string $url
     *
     * @return int
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
        $options['http'] = [
            'method'  => $this->method,
            'timeout' => $this->timeout,
        ];
        if (!is_null($this->userAgent)) {
            $options['http']['user_agent'] = $this->userAgent;
        }
        stream_context_set_default($options);

        $statusCode = 0;
        try {
            $headers = @get_headers($url);
            if(is_array($headers) && array_key_exists(0, $headers)) {
                $statusCode = (int)substr($headers[0], 9, 3);
            }
        }catch (\Exception $exception) {
            // do nothing
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
     * @param string $method
     *
     * @return HttpHeader
     */
    public function setMethod(string $method): HttpHeader
    {
        $this->method = $method;

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