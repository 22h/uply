<?php

namespace App\Service;

/**
 * Curl
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class CurlRequest
{

    /**
     * @var array
     */
    private $header = [];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var string|null
     */
    private $response = null;

    /**
     * @var int|null
     */
    private $statusCode = null;

    /**
     * @var int|null
     */
    private $errorCode = null;

    /**
     * Curl constructor.
     */
    public function __construct()
    {
        $this->addOption(CURLOPT_RETURNTRANSFER, true);     // return web page
        $this->addOption(CURLOPT_HEADER, false);    // don't return headers
        $this->addOption(CURLOPT_FOLLOWLOCATION, true);     // follow redirects
        $this->addOption(CURLOPT_ENCODING, '');       // handle all encodings
        $this->addOption(CURLOPT_AUTOREFERER, true);     // set referer on redirect
        $this->addOption(CURLOPT_CONNECTTIMEOUT, 10);      // timeout on connect
        $this->addOption(CURLOPT_TIMEOUT, 10);      // timeout on response
        $this->addOption(CURLOPT_MAXREDIRS, 10);       // stop after 10 redirects
        $this->addOption(CURLOPT_SSL_VERIFYPEER, false);     // Disabled SSL Cert checks
    }

    /**
     * @param string $header
     *
     * @return CurlRequest
     */
    public function addHeader(string $header): self
    {
        $this->header[] = $header;

        return $this;
    }

    /**
     * @param $optionKey
     * @param $optionValue
     *
     * @return CurlRequest
     */
    public function addOption(string $optionKey, $optionValue): self
    {
        $this->options[$optionKey] = $optionValue;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return void
     */
    public function request(string $url): void
    {
        if (count($this->header) > 0) {
            $this->addOption(CURLOPT_HTTPHEADER, $this->header);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, $this->options);
        $this->response = curl_exec($ch);
        $this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorCode = curl_errno($ch);

        if ($errorCode !== 0) {
            $this->errorCode = $errorCode;
        }

        curl_close($ch);
    }

    /**
     * @return int|null
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return null|string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return int|null
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}