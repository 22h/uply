<?php

namespace App\Scrutinizer;

use App\Entity\Unit\UnitInterface;
use App\Notification\NotificationData;
use App\Repository\Unit\AbstractUnitRepository;

interface ScrutinizerInterface
{

    /**
     * @param UnitInterface $unit
     *
     * @return NotificationData
     */
    public function scrutinize(UnitInterface $unit): NotificationData;

    /**
     * @return string
     */
    public function getIdent(): string;

    /**
     * @return string
     */
    public function getEntityClass(): string;

    /**
     * @return AbstractUnitRepository
     */
    public function getRepository(): AbstractUnitRepository;
}