<?php

namespace App\Unit;

use App\Entity\Unit\StatusCode;
use App\Repository\Unit\StatusCodeRepository;
use App\Unit\StatusCode\Scrutinizer;

/**
 * StatusCodeService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StatusCodeService extends AbstractUnitService
{

    /**
     * StatusCodeService constructor.
     *
     * @param StatusCodeRepository $repository
     * @param Scrutinizer          $scrutinizer
     */
    public function __construct(StatusCodeRepository $repository, Scrutinizer $scrutinizer)
    {
        $this->repository = $repository;
        $this->scrutinizer = $scrutinizer;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return StatusCode::class;
    }
}
