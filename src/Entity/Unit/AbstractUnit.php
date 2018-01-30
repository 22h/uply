<?php

namespace App\Entity\Unit;

/**
 * AbstractUnit
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
abstract class AbstractUnit implements UnitInterface
{

    const DEFAULT_IDLE_TIME = 5;

    /**
     * @return null|string
     */
    public function getDomain(): ?string
    {
        if (!is_null($this->getUrl())) {
            $parsedUrl = parse_url($this->getUrl(), PHP_URL_HOST);

            return (!is_null($parsedUrl) ? $parsedUrl : $this->getUrl());
        }

        return null;
    }
}