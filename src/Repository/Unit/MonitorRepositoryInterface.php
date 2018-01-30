<?php

namespace App\Repository\Unit;

/**
 * MonitorRepositoryInterface
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
interface MonitorRepositoryInterface
{

    /**
     * @param int $id
     */
    public function setAsTriggered(int $id): void;

    /**
     * @param int $id
     */
    public function removeTriggered(int $id): void;
}