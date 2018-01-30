<?php

namespace App\Monitor;

use App\Entity\Unit\AbstractUnit;

/**
 * UnitParameterBagFactory
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class UnitParameterBagFactory
{

    /**
     * @param AbstractUnit $monitor
     * @param string       $message
     * @param string       $title
     * @param null|string  $alert
     *
     * @return UnitParameterBag
     */
    public function createParameterBag(
        AbstractUnit $monitor,
        string $message,
        string $title,
        ?string $alert = null
    ) {
        return new UnitParameterBag($monitor, $message, $title, $alert);
    }
}