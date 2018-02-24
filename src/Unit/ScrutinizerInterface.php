<?php

namespace App\Unit;

use App\Entity\Unit\UnitInterface;

/**
 * ScrutinizerInterface
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
interface ScrutinizerInterface
{

    /**
     * @param UnitInterface $unit
     */
    public function scrutinize($unit): void;

}