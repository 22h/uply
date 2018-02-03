<?php

namespace App\Service\Curl;

/**
 * CurlRequestFactory
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class CurlRequestFactory
{

    /**
     * @return CurlRequest
     */
    public function createCurlRequest(): CurlRequest
    {
        return $this->getCurlRequestInstance();
    }

    /**
     * @return CurlRequest
     */
    private function getCurlRequestInstance(): CurlRequest
    {
        return new CurlRequest();
    }
}