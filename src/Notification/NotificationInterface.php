<?php

namespace App\Notification;

use App\Monitor\UnitParameterBag;

/**
 * NotificationInterface
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
interface NotificationInterface
{

    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @param UnitParameterBag $monitorParameterBag
     */
    public function send(UnitParameterBag $monitorParameterBag): void;
}