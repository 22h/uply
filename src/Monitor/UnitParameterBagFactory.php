<?php

namespace App\Monitor;

use App\Entity\Unit\UnitInterface;

/**
 * UnitParameterBagFactory
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class UnitParameterBagFactory
{

    /**
     * @param UnitInterface $monitor
     * @param string        $message
     * @param string        $title
     * @param null|string   $alert
     *
     * @return UnitParameterBag
     */
    public function createParameterBag(
        UnitInterface $monitor,
        string $message,
        string $title,
        ?string $alert = null
    ) {
        return new UnitParameterBag($monitor, $message, $title, $alert);
    }
}