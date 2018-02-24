<?php

namespace App\Unit;

use App\Entity\Unit\GooglePageSpeed;
use App\Repository\Unit\GooglePageSpeedRepository;
use App\Unit\GooglePageSpeed\Scrutinizer;

/**
 * GooglePageSpeedService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class GooglePageSpeedService extends AbstractUnitService
{

    /**
     * StatusCodeService constructor.
     *
     * @param GooglePageSpeedRepository $repository
     * @param Scrutinizer               $scrutinizer
     */
    public function __construct(GooglePageSpeedRepository $repository, Scrutinizer $scrutinizer)
    {
        $this->repository = $repository;
        $this->scrutinizer = $scrutinizer;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return GooglePageSpeed::class;
    }
}
